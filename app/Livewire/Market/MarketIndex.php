<?php

namespace App\Livewire\Market;

use App\Models\Item;
use App\Models\Inventory;
use App\Models\Npc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MarketIndex extends Component
{
    public $activeMerchant = null;
    public $cart = [];
    public $showReceipt = false;

    // NPC 對話內容
    public $greetingMessage = '';
    // 用來觸發前端動畫的計數器
    public $bubbleShake = 0;

    protected $merchantCatalog = [
        'qing' => ['武夷茶葉', '陶瓷水甕', '木桶水槽'],
        'guo'  => ['稻米', '臘肉', '白酒'],
    ];

    // 初始化：進入商店 (直接顯示商品)
    public function enterShop($merchantKey)
    {
        $this->activeMerchant = $merchantKey;
        $this->cart = [];
        $this->showReceipt = false;

        // 根據 NPC 給出歡迎詞
        if ($merchantKey == 'qing') {
            $this->greetingMessage = '「喲！稀客啊，來看看剛到岸的好貨！」';
        } else {
            $this->greetingMessage = '「年輕人，肚子餓了嗎？來買點糧食吧。」';
        }

        // 初始化購物車
        $items = $this->getShopItemsProperty();
        foreach ($items as $item) {
            $this->cart[$item->id] = 0;
        }
    }

    public function leaveShop()
    {
        $this->activeMerchant = null;
        $this->cart = [];
        $this->showReceipt = false;
        $this->greetingMessage = '';
    }

    // 統一處理 NPC 說話 (包含錯誤訊息)
    private function npcSpeak($type)
    {
        $this->bubbleShake++; // 觸發前端氣泡搖晃動畫

        if ($this->activeMerchant == 'qing') {
            // 水仔阿慶 (市儈、活潑)
            switch ($type) {
                case 'no_stock':
                    $this->greetingMessage = '「哎呀！這貨太搶手已經賣光了，下次請早！」';
                    break;
                case 'no_money':
                    $this->greetingMessage = '「客官，小本生意概不賒帳，您這銀兩不夠啊...」';
                    break;
                case 'no_stamina':
                    $this->greetingMessage = '「我看您臉色發白，還是先去休息吧，別暈在我店裡！」';
                    break;
                case 'empty_cart':
                    $this->greetingMessage = '「您還沒挑東西呢，別跟我開玩笑了。」';
                    break;
                case 'success':
                    $this->greetingMessage = '「謝啦！下次有新貨再通知您，慢走！」';
                    break;
                default:
                    $this->greetingMessage = $type;
            }
        } else {
            // 郭老爹 (穩重、長輩口吻)
            switch ($type) {
                case 'no_stock':
                    $this->greetingMessage = '「地裡還沒長出來呢，這東西暫時沒了。」';
                    break;
                case 'no_money':
                    $this->greetingMessage = '「年輕人，要腳踏實地，沒錢是買不了東西的。」';
                    break;
                case 'no_stamina':
                    $this->greetingMessage = '「身子骨要緊，累了就快去歇息，別硬撐。」';
                    break;
                case 'empty_cart':
                    $this->greetingMessage = '「籃子是空的，想買什麼儘管拿。」';
                    break;
                case 'success':
                    $this->greetingMessage = '「好好吃飯，有力氣才能建設東寧。」';
                    break;
                default:
                    $this->greetingMessage = $type;
            }
        }
    }

    public function getShopItemsProperty()
    {
        if (!$this->activeMerchant) return collect();
        $names = $this->merchantCatalog[$this->activeMerchant] ?? [];
        return Item::whereIn('name', $names)->get();
    }

    public function getCartSummaryProperty()
    {
        $items = $this->getShopItemsProperty();
        $totalGold = 0;
        $totalItems = 0;
        $details = [];

        foreach ($items as $item) {
            $qty = (int) ($this->cart[$item->id] ?? 0);
            if ($qty > 0) {
                $price = $item->current_price;
                $subtotal = $price * $qty;
                $totalGold += $subtotal;
                $totalItems += $qty;
                $details[] = ['item' => $item, 'price' => $price, 'qty' => $qty, 'subtotal' => $subtotal];
            }
        }

        return [
            'details' => $details,
            'totalGold' => $totalGold,
            'totalItems' => $totalItems,
            'staminaCost' => 15,
        ];
    }

    public function increment($itemId)
    {
        $item = Item::find($itemId);
        // [修改] 錯誤直接由 NPC 說出
        if ($this->cart[$itemId] < $item->stock) {
            $this->cart[$itemId]++;
            // 恢復一般對話
            $this->greetingMessage = $this->activeMerchant == 'qing' ? '「眼光真好，這可是搶手貨！」' : '「這東西實在，多買點好。」';
        } else {
            $this->npcSpeak('no_stock');
        }
    }

    public function decrement($itemId)
    {
        if ($this->cart[$itemId] > 0) {
            $this->cart[$itemId]--;
        }
    }

    public function previewCheckout()
    {
        $summary = $this->cart_summary;

        // [修改] 錯誤直接由 NPC 說出，不跳 Toast
        if ($summary['totalItems'] <= 0) {
            $this->npcSpeak('empty_cart');
            return;
        }

        if (Auth::user()->stamina < 15) {
            $this->npcSpeak('no_stamina');
            return;
        }

        if (Auth::user()->gold < $summary['totalGold']) {
            $this->npcSpeak('no_money');
            return;
        }

        $this->showReceipt = true;
    }

    public function confirmCheckout()
    {
        $user = Auth::user();
        $summary = $this->cart_summary;

        // 再次檢查 (防止多視窗操作)
        if ($user->stamina < 15) {
            $this->showReceipt = false;
            $this->npcSpeak('no_stamina');
            return;
        }
        if ($user->gold < $summary['totalGold']) {
            $this->showReceipt = false;
            $this->npcSpeak('no_money');
            return;
        }

        DB::transaction(function () use ($user, $summary) {
            $user->decrement('stamina', 15);
            $user->decrement('gold', $summary['totalGold']);

            foreach ($summary['details'] as $detail) {
                $item = $detail['item'];
                $qty = $detail['qty'];

                $itemLock = Item::where('id', $item->id)->lockForUpdate()->first();
                $itemLock->decrement('stock', $qty);

                $inventory = Inventory::where('user_id', $user->id)->where('item_id', $item->id)->first();
                if ($inventory) {
                    $inventory->increment('quantity', $qty);
                } else {
                    Inventory::create(['user_id' => $user->id, 'item_id' => $item->id, 'quantity' => $qty]);
                }
            }
        });

        $this->showReceipt = false;
        $this->cart = [];
        $items = $this->getShopItemsProperty();
        foreach ($items as $item) {
            $this->cart[$item->id] = 0;
        }

        // [修改] 成功後 NPC 說話
        $this->npcSpeak('success');

        // 還是可以保留一個小的 Toast 讓玩家確認交易完成
        $this->dispatch('operation-success', message: '交易完成！');
    }

    public function render()
    {
        $npcQing = Npc::where('name', 'like', '%阿慶%')->first();
        $npcGuo = Npc::where('name', 'like', '%郭老爹%')->first();

        return view('livewire.market.market-index', [
            'npcQing' => $npcQing,
            'npcGuo' => $npcGuo,
            'summary' => $this->activeMerchant ? $this->cart_summary : null,
        ])->layout('layouts.app');
    }
}
