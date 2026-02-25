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
            // Menambahkan atau memastikan kolom yang sesuai rencana Anda
            $table->text('summary')->nullable()->after('bio'); // Ringkasan Pribadi
            $table->json('languages')->nullable(); // Bahasa (disimpan sebagai array/json)
            $table->string('resume_filename')->nullable(); // Nama asli file resume
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            //
        });
    }
};
