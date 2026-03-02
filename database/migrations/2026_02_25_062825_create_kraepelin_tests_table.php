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
        Schema::create('kraepelin_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            
            // Data Mentah
            $table->json('questions');
            $table->json('answers')->nullable();
            $table->json('results_chart')->nullable(); // PENTING: Untuk menyimpan tinggi tiap kolom (grafik)

            // Skor Dasar
            $table->integer('total_answered')->default(0);
            $table->integer('total_correct')->default(0);
            $table->integer('total_wrong')->default(0);

            // ANALISIS PSIKOMETRI (Industrial Standard)
            $table->float('panker')->default(0);   // Kecepatan (Speed)
            $table->integer('tianker')->default(0); // Ketelitian (Accuracy)
            $table->float('janker')->default(0);   // Stabilitas (Consistency)
            $table->float('ganker')->default(0);   // Ketahanan (Endurance)

            // Lain-lain
            $table->float('stability_score')->default(0);
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
        Schema::dropIfExists('kraepelin_tests');
    }
};