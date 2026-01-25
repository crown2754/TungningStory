<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');


// --- 遊戲本身 (玩家區域) ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


// --- 獨立遊戲後台 (管理員區域) ---
Route::middleware(['auth', 'admin:GM'])->prefix('admin')->name('admin.')->group(function () {

    // 後台首頁
    Route::get('/', function () {
        return view('admin.index');
    })->name('index');

    // 玩家管理 (僅 OM 可進入)
    Route::middleware('admin:OM')->group(function () {
        Route::get('/users', function () {
            return "玩家管理清單 - 僅限營運管理員(OM)存取";
        })->name('users');
    });
});
require __DIR__ . '/auth.php';
