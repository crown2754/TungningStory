<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ShopLedger extends Component
{
    use WithPagination;

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

        // 取得該店鋪的訂單，按時間排序
        $orders = $shop->orders()->with('item')->latest()->paginate(15);

        $totalIncomeToday = $shop->orders()->where('type', 'sale')->whereDate('created_at', today())->sum('total_amount');
        $totalExpenseToday = $shop->orders()->where('type', 'purchase')->whereDate('created_at', today())->sum('total_amount');
        
        $totalIncome = $shop->orders()->where('type', 'sale')->sum('total_amount');
        $totalExpense = $shop->orders()->where('type', 'purchase')->sum('total_amount');

        return view('livewire.shop.shop-ledger', [
            'shop' => $shop,
            'orders' => $orders,
            'totalIncomeToday' => $totalIncomeToday,
            'totalExpenseToday' => $totalExpenseToday,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
        ])->layout('layouts.app');
    }
}
