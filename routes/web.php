<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// --- 玩家區域 (前台) ---

Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// [修正] 商號是給玩家用的，要放在外面，並加上 auth (登入才能用) 的保護
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/shop', \App\Livewire\Shop\ShopIndex::class)->name('shop.index');
    Route::get('/shop/manage', \App\Livewire\Shop\ShopManagement::class)->name('shop.manage');
    Route::get('/shop/ledger', \App\Livewire\Shop\ShopLedger::class)->name('shop.ledger');
    Route::get('/market', \App\Livewire\Market\MarketIndex::class)->name('market');
    Route::get('/government', \App\Livewire\Government\GovernmentIndex::class)->name('government');
});


// --- 獨立遊戲後台 (管理員區域) ---
Route::middleware(['auth', 'admin:GM'])->prefix('admin')->name('admin.')->group(function () {

    // 後台首頁
    Route::get('/', function () {
        return view('admin.index');
    })->name('index');

    Route::get('/users', \App\Livewire\Admin\UserManagement::class)->name('users');
    Route::get('/settings', \App\Livewire\Admin\SystemSettings::class)->name('settings');
    Route::get('/avatars', \App\Livewire\Admin\AvatarManagement::class)->name('avatars');
    Route::get('/npcs', \App\Livewire\Admin\NpcManagement::class)->name('npcs');
    Route::get('/items', \App\Livewire\Admin\ItemManagement::class)->name('items');
    Route::get('/stock-monitor', \App\Livewire\Admin\StockMonitor::class)->name('stock-monitor');
    Route::get('/government', \App\Livewire\Admin\GovernmentManagement::class)->name('government');
});

require __DIR__ . '/auth.php';
