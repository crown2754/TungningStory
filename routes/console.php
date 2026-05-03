<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// [新增] 遊戲排程設定
Schedule::command('game:restore-stamina')
    ->everyFifteenMinutes() // 設定頻率：每 15 分鐘
    ->runInBackground();    // 背景執行 (避免卡住其他排程)

// [新增] 市場模擬排程 (市民購買與官府採買)
Schedule::command('game:simulate-market')
    ->everyMinute()
    ->runInBackground();