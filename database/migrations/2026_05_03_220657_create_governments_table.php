<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('governments', function (Blueprint $table) {
            $table->id();
            $table->decimal('tax_rate', 5, 2)->default(5.00)->comment('稅率百分比 0~100');
            $table->unsignedTinyInteger('public_order')->default(70)->comment('治安指數 1~100');
            $table->unsignedTinyInteger('land_development')->default(30)->comment('開荒程度 1~100');
            $table->unsignedBigInteger('treasury')->default(10000000)->comment('政府資金（文）');
            $table->unsignedInteger('population')->default(50000)->comment('人口數（人）');
            $table->unsignedInteger('military_count')->default(5000)->comment('軍隊數量（人）');
            $table->unsignedInteger('military_food')->default(20000)->comment('軍糧數量（石）');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('governments');
    }
};
