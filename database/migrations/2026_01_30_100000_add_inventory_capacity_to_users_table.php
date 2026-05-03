<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 新增倉庫容量上限，預設為 1000 (代表總共可以放 1000 個單位的物資)
            $table->integer('inventory_capacity')->default(1000)->after('max_stamina')->comment('倉庫容量上限');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('inventory_capacity');
        });
    }
};