<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First change validation logic will happen in code, but here we change the column
        // We might want to clear data or just simple altering if DB supports it.
        // MySQL enum to int might be tricky. Let's drop and add or strictly modify.
        // Since it's development phase (implied), we can just modify.
        
        // However, changing ENUM to INT directly might cause issues if values are strings.
        // We'll drop the column and re-add it as integer to be safe and clean since existing data 'junior' etc cannot be auto-converted to int.
        
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('experience_level');
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->string('experience_level')->nullable()->after('job_type')->comment('Experience level description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
             $table->dropColumn('experience_level');
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->enum('experience_level', ['entry', 'junior', 'mid', 'senior', 'lead', 'manager'])->default('junior')->after('job_type');
        });
    }
};
