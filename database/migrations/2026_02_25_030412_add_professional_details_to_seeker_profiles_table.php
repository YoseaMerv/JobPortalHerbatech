<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            // 1. Ringkasan Pribadi (Sesuai Poin 4 rencana Anda)
            if (!Schema::hasColumn('seeker_profiles', 'summary')) {
                $table->text('summary')->nullable()->after('bio');
            }

            // 2. Bahasa (Sesuai Poin 9 rencana Anda - Disimpan sebagai JSON/Array)
            if (!Schema::hasColumn('seeker_profiles', 'languages')) {
                $table->json('languages')->nullable()->after('summary');
            }

            // 3. Nama File Resume Asli (Untuk kebutuhan Step 1 lamaran)
            if (!Schema::hasColumn('seeker_profiles', 'resume_filename')) {
                $table->string('resume_filename')->nullable()->after('resume_path');
            }

            // 4. Domisili/Lokasi Rumah (Sesuai Poin 2 rencana Anda)
            // Model Anda sudah punya 'city', kita pastikan ada kolom untuk koordinat atau detail tambahan jika perlu
            if (!Schema::hasColumn('seeker_profiles', 'home_location_details')) {
                $table->string('home_location_details')->nullable()->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('seeker_profiles', function (Blueprint $table) {
            $table->dropColumn(['summary', 'languages', 'resume_filename', 'home_location_details']);
        });
    }
};
