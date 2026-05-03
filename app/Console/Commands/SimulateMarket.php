<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Shop;
use App\Models\Item;
use App\Models\ShopProduct;
use App\Models\ShopOrder;
use App\Models\User;
use App\Models\Government;
use Illuminate\Support\Facades\DB;

class SimulateMarket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:simulate-market';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate citizen buying and government purchasing (Tungning economy)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting market simulation...');

        $this->simulateCitizenPurchases();
        $this->simulateGovernmentPurchases();
        $this->simulateGovernmentRelease(); // 官府釋出庫存至民間（確保民間市場有貨）

        $this->info('Market simulation completed.');
    }

    private function simulateCitizenPurchases()
    {
        // 取得所有有庫存的商品
        $products = ShopProduct::where('quantity', '>', 0)
            ->whereHas('shop', function ($query) {
                $query->where('is_open', true);
            })->with(['item', 'shop.user'])->get();

        $firstNames = ['趙', '錢', '孫', '李', '周', '吳', '鄭', '王', '陳', '林', '黃', '張', '劉', '楊'];
        $lastNames = ['大牛', '二狗', '小明', '阿花', '鐵柱', '大山', '翠花', '老四', '富貴', '阿財', '招弟'];

        foreach ($products as $product) {
            $item = $product->item;
            $shop = $product->shop;
            $user = $shop->user;

            $marketPrice = $item->current_price;
            $playerPrice = $product->price;

            // 計算性價比 (CP值)
            $cpRatio = $marketPrice / max(1, $playerPrice);

            // 如果 CP 值太低 (玩家賣太貴，超過市價兩倍)，機率趨近於 0
            if ($cpRatio < 0.5) {
                continue;
            }

            // 決定購買機率與數量
            $buyChance = 0;
            $maxBuyQty = 0;

            if ($cpRatio >= 1.2) {
                $buyChance = 80; // 便宜很多，80% 機率被掃貨
                $maxBuyQty = min(10, $product->quantity);
            } elseif ($cpRatio >= 1.0) {
                $buyChance = 50; // 比市價便宜或一樣
                $maxBuyQty = min(5, $product->quantity);
            } elseif ($cpRatio >= 0.8) {
                $buyChance = 20; // 稍微貴一點
                $maxBuyQty = min(2, $product->quantity);
            } else {
                $buyChance = 5;  // 貴很多
                $maxBuyQty = 1;
            }

            // 套用後台設定的難易度倍率
            $multiplier = (float) \App\Models\GameSetting::get('market_purchase_multiplier', 1.0);
            $buyChance = min(100, $buyChance * $multiplier);
            $maxBuyQty = max(1, (int) ceil($maxBuyQty * $multiplier));
            $maxBuyQty = min($maxBuyQty, $product->quantity); // 確保不超過貨架數量

            if (rand(1, 100) <= $buyChance) {
                $buyQty = rand(1, $maxBuyQty);
                $totalAmount = $buyQty * $playerPrice;

                DB::transaction(function () use ($product, $item, $user, $buyQty, $playerPrice, $totalAmount, $firstNames, $lastNames, $shop) {
                    // 重新上鎖讀取，避免併發問題
                    $lockedProduct = ShopProduct::where('id', $product->id)->lockForUpdate()->first();
                    if (!$lockedProduct || $lockedProduct->quantity < $buyQty) return;

                    // 1. 扣除貨架數量
                    $lockedProduct->quantity -= $buyQty;
                    if ($lockedProduct->quantity <= 0) {
                        $lockedProduct->delete();
                    } else {
                        $lockedProduct->save();
                    }

                    // 2. 玩家獲得金錢 (扣除稅收)
                    $gov = Government::current();
                    $taxAmount = $gov->calculateTaxAmount($totalAmount);
                    $playerNetIncome = $totalAmount - $taxAmount;

                    $lockedUser = User::where('id', $user->id)->lockForUpdate()->first();
                    $lockedUser->increment('gold', $playerNetIncome);

                    // 將稅收加入政府國庫
                    if ($taxAmount > 0) {
                        $lockedGov = Government::where('id', $gov->id)->lockForUpdate()->first();
                        $lockedGov->increment('treasury', $taxAmount);
                    }

                    // 3. 增加民間數量
                    $lockedItem = Item::where('id', $item->id)->lockForUpdate()->first();
                    $lockedItem->increment('civilian_stock', $buyQty);

                    // 4. 記錄帳本
                    $buyerName = $firstNames[array_rand($firstNames)] . $lastNames[array_rand($lastNames)];
                    ShopOrder::create([
                        'shop_id' => $shop->id,
                        'item_id' => $item->id,
                        'type' => 'sale',
                        'buyer_name' => $buyerName,
                        'quantity' => $buyQty,
                        'price' => $playerPrice,
                        'total_amount' => $totalAmount,
                        'tax_rate' => $gov->tax_rate,
                        'tax_amount' => $taxAmount,
                    ]);
                });
            }
        }
    }

    private function simulateGovernmentPurchases()
    {
        $items = Item::where('civilian_stock', '>', 0)
            ->whereRaw('stock < target_stock')
            ->get();

        foreach ($items as $item) {
            $shortage = $item->target_stock - $item->stock;
            // 官府每次最多從民間收購短缺的量，或是民間所有的量
            $purchaseQty = min($shortage, $item->civilian_stock);

            if ($purchaseQty > 0) {
                // 模擬官府收購（每次大約收購市場上流通量的 10% ~ 30%）
                $actualPurchase = (int) ceil($purchaseQty * (rand(10, 30) / 100));
                
                DB::transaction(function () use ($item, $actualPurchase) {
                    $lockedItem = Item::where('id', $item->id)->lockForUpdate()->first();
                    if ($lockedItem->civilian_stock >= $actualPurchase) {
                        $lockedItem->decrement('civilian_stock', $actualPurchase);
                        $lockedItem->increment('stock', $actualPurchase);
                    }
                });
                
                $this->info("Government purchased {$actualPurchase} of {$item->name} from civilians.");
            }
        }
    }
    /**
     * 官府釋出政策：當官府庫存充裕時，定期釋出少量庫存到民間
     * 確保大井頭市集始終有貨可買
     */
    private function simulateGovernmentRelease()
    {
        // 取得官府庫存充裕（超過目標量的 50%）且民間康存不足目標的 20% 的物品
        $items = Item::whereRaw('stock > target_stock * 0.5')
            ->whereRaw('civilian_stock < target_stock * 0.20')
            ->get();

        foreach ($items as $item) {
            // 每次釋出官府庫存的 2% ~ 5%，讓民間市場持續有貨源
            $releaseQty = (int) ceil($item->stock * (rand(2, 5) / 100));

            if ($releaseQty > 0) {
                DB::transaction(function () use ($item, $releaseQty) {
                    $lockedItem = Item::where('id', $item->id)->lockForUpdate()->first();
                    if ($lockedItem->stock >= $releaseQty) {
                        $lockedItem->decrement('stock', $releaseQty);
                        $lockedItem->increment('civilian_stock', $releaseQty);
                    }
                });
                $this->info("Government released {$releaseQty} of {$item->name} to civilian market.");
            }
        }
    }
}
