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
        Schema::create('system_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // 誰上傳的
            $table->string('type')->default('logo');     // 類型 (logo, banner...)
            $table->string('path');                      // 檔案路徑
            $table->string('original_name')->nullable(); // 原始檔名
            $table->string('mime_type')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_assets');
    }
};
