<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('job_applications', 'cover_letter_path')) {
                $table->string('cover_letter_path')->after('cv_path')->nullable();
            }
            if (!Schema::hasColumn('job_applications', 'answers')) {
                $table->json('answers')->after('cover_letter_path')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['cover_letter_path', 'answers']);
        });
    }
};
