<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


// --- 遊戲本身 (玩家區域) ---
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });


// --- 獨立遊戲後台 (管理員區域) ---
Route::middleware(['auth', 'admin:GM'])->prefix('admin')->name('admin.')->group(function () {

    // 後台首頁
    Route::get('/', function () {
        return view('admin.index');
    })->name('index');

    // [修改] 這裡原本是 admin:OM，改成 admin:GM，讓一般管理員也能進來改數值
    Route::middleware('admin:GM')->group(function () {
        Route::get('/users', \App\Livewire\Admin\UserManagement::class)->name('users');
    });
});
require __DIR__ . '/auth.php';
