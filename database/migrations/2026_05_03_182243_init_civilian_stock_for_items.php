<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 初始化所有物資的民間數量（civilian_stock）為目標庫存的 30%
     * 代表遊戲開始時，民間市場上已有一定的流通物資，玩家可以在大井頭市集購買
     */
    public function up(): void
    {
        // 用 RAW SQL 直接更新所有物品，避免 N+1 問題
        DB::statement('UPDATE items SET civilian_stock = FLOOR(target_stock * 0.30) WHERE civilian_stock = 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('UPDATE items SET civilian_stock = 0');
    }
};
