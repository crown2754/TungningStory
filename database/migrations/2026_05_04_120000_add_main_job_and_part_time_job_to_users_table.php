<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('main_job')->nullable()->after('job');
            $table->string('part_time_job')->nullable()->after('main_job');
        });

        DB::table('users')->whereNull('main_job')->update([
            'main_job' => DB::raw('job'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['main_job', 'part_time_job']);
        });
    }
};
