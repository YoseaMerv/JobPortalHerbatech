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
        Schema::create('psychological_questions', function (Blueprint $table) {
            $table->id();
            // 1. Tambahkan 'disc' di sini
            $table->enum('test_type', ['msdt', 'papi', 'disc']);
            $table->integer('question_number');

            // Kolom untuk MSDT & PAPI (2 pilihan)
            $table->text('option_a')->nullable();
            $table->text('option_b')->nullable();
            $table->string('dimension_a')->nullable();
            $table->string('dimension_b')->nullable();

            // Kolom TAMBAHAN untuk DISC (Mendukung 4 pernyataan per nomor)
            // Simpan pernyataan dalam format JSON agar fleksibel
            $table->json('question_text')->nullable();
            // Simpan mapping dimensi P dan K dalam format JSON
            $table->json('dimension_p')->nullable();
            $table->json('dimension_k')->nullable();

            $table->timestamps();
        });

        Schema::create('psychological_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 2. Tambahkan 'disc' di sini untuk memperbaiki error "Data Truncated"
            $table->enum('test_type', ['msdt', 'papi', 'disc']);

            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->json('answers')->nullable();
            $table->json('final_score')->nullable();
            $table->text('interpretation')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psychological_test_results');
        Schema::dropIfExists('psychological_questions');
    }
};
