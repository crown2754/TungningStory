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
        Schema::create('npcs', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // 姓名 (例如: 楊英)
            $table->string('title');         // 稱號/職位 (例如: 戶部主事)
            $table->text('description')->nullable(); // 人物介紹
            $table->text('greeting')->nullable();    // 預設問候語
            $table->foreignId('avatar_id')->nullable()->constrained('avatars')->nullOnDelete(); // 綁定頭像
            $table->string('location')->nullable();  // 出沒地點 (例如: dashboard, shop)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('npcs');
    }
};
