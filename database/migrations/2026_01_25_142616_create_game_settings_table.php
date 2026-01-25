<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 設定鍵名 (如: initial_gold)
            $table->string('value')->nullable(); // 設定值
            $table->string('name'); // 顯示名稱 (如: 初始金幣)
            $table->text('description')->nullable(); // 說明
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_settings');
    }
};
