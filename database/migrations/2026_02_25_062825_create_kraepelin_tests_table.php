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
        $table->integer('score_speed')->nullable(); 
        $table->integer('score_accuracy')->nullable(); 
        $table->integer('score_stability')->nullable();
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
