<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- 記得加入這行

return new class extends Migration
{
    public function up(): void
    {
        // [修正點] 使用 Raw SQL 加上 CASCADE 來強制解除依賴
        // 這會同時刪除 items 表以及其他表(如 inventories) 對 items 的外鍵約束
        DB::statement('DROP TABLE IF EXISTS items CASCADE');

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            
            // --- 基礎資訊 ---
            $table->string('name')->unique()->comment('物資名稱');
            $table->text('description')->nullable()->comment('描述');
            $table->string('type')->default('material')->comment('類型: food, material, luxury, equipment');
            $table->string('image_path')->nullable()->comment('圖片路徑');

            // --- 經濟調控參數 (上帝視角) ---
            $table->integer('base_price')->default(100)->comment('標準基準價格 (平衡時的價格)');
            $table->integer('min_price')->default(10)->comment('價格下限 (崩盤價)');
            $table->integer('max_price')->default(1000)->comment('價格上限 (天價)');
            
            // --- 市場動態參數 ---
            $table->integer('stock')->default(1000)->comment('當前市場實際庫存');
            $table->integer('target_stock')->default(1000)->comment('目標平衡庫存 (庫存少於此數則漲價)');
            $table->decimal('volatility', 5, 2)->default(0.5)->comment('波動率 (0.1=穩定, 1.0=劇烈, 2.0=瘋狂)');

            $table->boolean('is_tradable')->default(true)->comment('是否開放交易');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // down 方法也要加 CASCADE 以防萬一
        DB::statement('DROP TABLE IF EXISTS items CASCADE');
    }
};