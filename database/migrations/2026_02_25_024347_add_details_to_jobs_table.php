<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('department')->nullable();
            $table->enum('work_setting', ['on_site', 'hybrid', 'remote'])->default('on_site');
            $table->boolean('is_salary_visible')->default(true);
            $table->string('salary_currency')->default('IDR');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
};
