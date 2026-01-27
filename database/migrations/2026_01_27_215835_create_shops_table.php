<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 店主
            $table->string('name')->default('未命名商號'); // 店名
            $table->text('description')->nullable(); // 商號介紹/公告
            $table->boolean('is_open')->default(false); // 是否營業中
            $table->unsignedInteger('level')->default(1); // 商號等級 (未來可擴充店面大小)
            $table->timestamp('opened_at')->nullable(); // 開張日期
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
