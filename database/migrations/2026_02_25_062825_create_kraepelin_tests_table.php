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
    $table->json('questions');
    $table->json('answers')->nullable();
    $table->integer('total_answered')->default(0); // Kolom ini yang menyebabkan error jika tidak ada
    $table->integer('total_correct')->default(0);
    $table->integer('total_wrong')->default(0);
    $table->float('stability_score')->default(0);
    $table->timestamp('started_at')->nullable();
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('kraepelin_tests');
    }
};
