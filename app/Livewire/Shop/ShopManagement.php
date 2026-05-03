<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Inventory;
use App\Models\ShopProduct;
use App\Models\AuditLog;
use App\Models\Government;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopManagement extends Component
{
    public $isListingModalOpen = false;
    public $selectedInventoryId = null;
    public $listQuantity = 1;
    public $listPrice = 1;

    public $isUnlistingModalOpen = false;
    public $selectedShopProductId = null;
    public $unlistQuantity = 1;

    public function mount()
    {
        $user = Auth::user();
        if (!$user->shop) {
            return redirect()->route('shop.index');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $shop = $user->shop;
        
        // 取得庫房物品 (過濾數量大於 0)
        $inventoryItems = $user->inventories()->with('item')->where('quantity', '>', 0)->get();
        
        // 取得貨架物品
        $shopProducts = $shop->products()->with('item')->get();
        $taxRate = (float) Government::current()->tax_rate;

        return view('livewire.shop.shop-management', [
            'shop' => $shop,
            'inventoryItems' => $inventoryItems,
            'shopProducts' => $shopProducts,
            'taxRate' => $taxRate,
        ])->layout('layouts.app');
    }

    // ========== 上架邏輯 ==========

    public function openListingModal($inventoryId)
    {
        $this->selectedInventoryId = $inventoryId;
        $inventory = Inventory::findOrFail($inventoryId);
        
        if ($inventory->user_id !== Auth::id()) {
            return;
        }
        
        $this->listQuantity = 1;
        // 預設價格給予物品的基礎價格
        $this->listPrice = $inventory->item->base_price ?? 10;
        
        $this->resetValidation();
        $this->isListingModalOpen = true;
    }

    public function closeListingModal()
    {
        $this->isListingModalOpen = false;
        $this->selectedInventoryId = null;
    }

    public function confirmListing()
    {
        $this->validate([
            'listQuantity' => 'required|integer|min:1',
            'listPrice' => 'required|integer|min:1',
        ], [
            'listQuantity.required' => '請輸入上架數量。',
            'listQuantity.integer' => '上架數量必須是整數。',
            'listQuantity.min' => '上架數量至少為 1。',
            'listPrice.required' => '請輸入定價。',
            'listPrice.integer' => '定價必須是整數。',
            'listPrice.min' => '定價至少為 1 文。',
        ]);

        $user = Auth::user();
        $shop = $user->shop;

        DB::transaction(function () use ($user, $shop) {
            $inventory = Inventory::where('id', $this->selectedInventoryId)
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (!$inventory || $inventory->quantity < $this->listQuantity) {
                $this->addError('listQuantity', '庫存不足！');
                return;
            }

            // 1. 扣除庫存
            $inventory->quantity -= $this->listQuantity;
            if ($inventory->quantity <= 0) {
                $inventory->delete();
            } else {
                $inventory->save();
            }

            // 2. 尋找貨架上是否已有該物品
            $shopProduct = ShopProduct::where('shop_id', $shop->id)
                ->where('item_id', $inventory->item_id)
                ->first();

            if ($shopProduct) {
                // 已存在，合併數量並覆蓋價格
                $shopProduct->quantity += $this->listQuantity;
                $shopProduct->price = $this->listPrice;
                $shopProduct->save();
            } else {
                // 不存在，新增貨架商品
                ShopProduct::create([
                    'shop_id' => $shop->id,
                    'item_id' => $inventory->item_id,
                    'price' => $this->listPrice,
                    'quantity' => $this->listQuantity,
                ]);
            }

            // 3. 紀錄日誌
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'SHOP_LIST_ITEM',
                'description' => "將 {$this->listQuantity} 個 {$inventory->item->name} 上架，定價 {$this->listPrice} 文",
                'changes' => [
                    'item_id' => $inventory->item_id,
                    'quantity' => $this->listQuantity,
                    'price' => $this->listPrice,
                ]
            ]);
        });

        $this->closeListingModal();
    }

    // ========== 下架邏輯 ==========

    public function openUnlistingModal($shopProductId)
    {
        $this->selectedShopProductId = $shopProductId;
        $shopProduct = ShopProduct::findOrFail($shopProductId);
        
        if ($shopProduct->shop->user_id !== Auth::id()) {
            return;
        }
        
        // 預設全部下架
        $this->unlistQuantity = $shopProduct->quantity;
        
        $this->resetValidation();
        $this->isUnlistingModalOpen = true;
    }

    public function closeUnlistingModal()
    {
        $this->isUnlistingModalOpen = false;
        $this->selectedShopProductId = null;
    }

    public function confirmUnlisting()
    {
        $this->validate([
            'unlistQuantity' => 'required|integer|min:1',
        ], [
            'unlistQuantity.required' => '請輸入下架數量。',
            'unlistQuantity.integer' => '下架數量必須是整數。',
            'unlistQuantity.min' => '下架數量至少為 1。',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user) {
            $shopProduct = ShopProduct::where('id', $this->selectedShopProductId)
                ->lockForUpdate()
                ->first();

            if (!$shopProduct || $shopProduct->shop->user_id !== $user->id || $shopProduct->quantity < $this->unlistQuantity) {
                $this->addError('unlistQuantity', '貨架數量不足或商品不存在！');
                return;
            }

            // 1. 扣除貨架數量
            $shopProduct->quantity -= $this->unlistQuantity;
            if ($shopProduct->quantity <= 0) {
                $shopProduct->delete();
            } else {
                $shopProduct->save();
            }

            // 2. 加回庫存
            $inventory = Inventory::firstOrCreate(
                ['user_id' => $user->id, 'item_id' => $shopProduct->item_id],
                ['quantity' => 0]
            );
            $inventory->increment('quantity', $this->unlistQuantity);

            // 3. 紀錄日誌
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'SHOP_UNLIST_ITEM',
                'description' => "從貨架下架了 {$this->unlistQuantity} 個 {$shopProduct->item->name}",
                'changes' => [
                    'item_id' => $shopProduct->item_id,
                    'quantity' => $this->unlistQuantity,
                ]
            ]);
        });

        $this->closeUnlistingModal();
    }
}
