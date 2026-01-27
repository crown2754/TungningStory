<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// --- 玩家區域 (前台) ---

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// [修正] 商號是給玩家用的，要放在外面，並加上 auth (登入才能用) 的保護
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/shop', \App\Livewire\Shop\ShopIndex::class)->name('shop.index');
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
});

require __DIR__ . '/auth.php';
