<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('job_categories')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('job_locations')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('responsibilities')->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->enum('salary_type', ['monthly', 'yearly', 'hourly', 'project'])->default('monthly');
            $table->enum('job_type', ['full_time', 'part_time', 'contract', 'freelance', 'internship'])->default('full_time');
            $table->enum('experience_level', ['entry', 'junior', 'mid', 'senior', 'lead', 'manager'])->default('junior');
            $table->enum('education_level', ['sd', 'smp', 'sma', 'd3', 's1', 's2', 's3'])->nullable();
            $table->date('deadline')->nullable();
            $table->integer('vacancy')->default(1);
            $table->enum('status', ['draft', 'published', 'closed', 'expired'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_remote')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};