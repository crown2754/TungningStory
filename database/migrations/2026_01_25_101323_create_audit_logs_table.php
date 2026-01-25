<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            // 操作者 (哪個 GM/OM 做的)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 動作類型 (例如: UPDATE_USER, GRANT_ROLE)
            $table->string('action');

            // 目標對象 ID (被修改的玩家 ID)
            $table->unsignedBigInteger('target_id')->nullable();

            // 變更內容 (儲存修改前與修改後的數值，使用 JSON 格式)
            $table->json('changes')->nullable();

            // 備註說明
            $table->text('description')->nullable();

            // 來源 IP (安全審計用)
            $table->string('ip_address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
