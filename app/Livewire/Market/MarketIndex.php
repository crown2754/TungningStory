<?php

namespace App\Livewire\Market;

use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MarketIndex extends Component
{
    // 購買功能
    public function buy($itemId)
    {
        $user = Auth::user();

        // 使用資料庫交易，確保「扣錢」跟「給貨」同時成功或同時失敗
        DB::transaction(function () use ($user, $itemId) {
            // 1. 鎖定商品列 (避免高併發時庫存算錯)
            $item = Item::where('id', $itemId)->lockForUpdate()->first();

            // 2. 重新計算當下價格 (防止玩家停在頁面太久價格變動)
            $currentPrice = $item->current_price;

            // 3. 檢查條件
            if ($user->gold < $currentPrice) {
                $this->dispatch('operation-error', message: '客官，您的銀兩不夠啊！');
                return; // 終止交易
            }

            if ($item->stock <= 0) {
                $this->dispatch('operation-error', message: '這貨已經賣光了，下次請早！');
                return;
            }

            // 4. 執行扣款與扣庫存
            $user->decrement('gold', $currentPrice);
            $item->decrement('stock', 1);

            // 5. 玩家背包入帳
            // 檢查背包是否已有此物品
            $inventory = Inventory::where('user_id', $user->id)
                ->where('item_id', $item->id)
                ->first();

            if ($inventory) {
                $inventory->increment('quantity', 1);
            } else {
                Inventory::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                    'quantity' => 1,
                ]);
            }

            // 6. 發送成功通知
            $this->dispatch('operation-success', message: "成功購入 {$item->name}，花費 {$currentPrice} 文！");
        });
    }

    public function render()
    {
        // 抓取所有可交易的商品，並依照名稱排序
        $items = Item::where('is_tradable', true)->orderBy('id')->get();

        return view('livewire.market.market-index', [
            'items' => $items
        ])->layout('layouts.app');
    }
}
