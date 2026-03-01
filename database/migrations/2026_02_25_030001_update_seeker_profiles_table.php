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
        Schema::table('seeker_profiles', function (Blueprint $table) {
            $table->text('summary')->nullable()->after('bio'); 
            $table->json('languages')->nullable(); 
            $table->string('resume_filename')->nullable();
            
            // Tambahan untuk Identitas Pelamar
            $table->string('linkedin_url')->nullable();
            $table->decimal('expected_salary', 15, 2)->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn([
                'summary',
                'languages',
                'resume_filename',
                'linkedin_url',
                'expected_salary'
            ]);
        });
    }
};