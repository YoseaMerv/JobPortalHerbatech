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
            $table->enum('test_type', ['msdt', 'papi']);
            $table->integer('question_number');
            $table->text('option_a');
            $table->text('option_b');
            // Dimension tetap penting untuk mapping PAPI Kostick (G, L, I, dll)
            $table->string('dimension_a')->nullable();
            $table->string('dimension_b')->nullable();
            $table->timestamps();
        });

        Schema::create('psychological_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('test_type', ['msdt', 'papi']);
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');

            $table->json('answers')->nullable();

            // final_score akan menyimpan hasil akhir:
            // PAPI: {"G": 5, "L": 4, ...}
            // MSDT: {"task": 10, "relation": 8, "style": "Executive"}
            $table->json('final_score')->nullable();

            // TAMBAHKAN KOLOM INI:
            // Untuk menyimpan interpretasi teks atau deskripsi gaya kepemimpinan/kepribadian
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
