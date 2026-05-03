<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 透過關聯撈取資料，並預先載入 item 避免 N+1 問題
        $inventory = auth()->user()->inventories()->with('item')
            ->where('quantity', '>', 0)->get();

        // 計算當前已使用的倉庫容量 (將所有物品的 quantity 加總)
        $currentCapacity = $inventory->sum('quantity');

        return view('dashboard', compact('inventory', 'currentCapacity'));
    }
}