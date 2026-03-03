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
            $table->string('dimension_a')->nullable();
            $table->string('dimension_b')->nullable();
            $table->timestamps();
        });

        Schema::create('psychological_test_results', function (Blueprint $table) {
            $table->id();
            // Relasi ke job_applications dan users seperti pada kraepelin_tests
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('test_type', ['msdt', 'papi']);

            // Status pengerjaan
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');

            $table->json('answers')->nullable(); // Nullable jika baru mulai tes
            $table->json('final_score')->nullable();

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
