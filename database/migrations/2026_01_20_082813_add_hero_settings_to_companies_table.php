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
        Schema::table('companies', function (Blueprint $table) {
            // --- BAGIAN 1: MEDIA SOSIAL & PROFIL ---
            if (!Schema::hasColumn('companies', 'facebook')) {
                $table->string('facebook')->nullable()->after('company_website');
            }
            if (!Schema::hasColumn('companies', 'twitter')) {
                $table->string('twitter')->nullable()->after('facebook');
            }
            if (!Schema::hasColumn('companies', 'linkedin')) {
                $table->string('linkedin')->nullable()->after('twitter');
            }
            if (!Schema::hasColumn('companies', 'instagram')) {
                $table->string('instagram')->nullable()->after('linkedin');
            }
            if (!Schema::hasColumn('companies', 'company_profile_url')) {
                $table->string('company_profile_url')->nullable()->after('instagram');
            }

            // --- BAGIAN 2: HERO SECTION (YANG TADI ERROR) ---
            if (!Schema::hasColumn('companies', 'hero_title')) {
                $table->string('hero_title')->nullable()->after('company_profile_url');
            }
            if (!Schema::hasColumn('companies', 'hero_description')) {
                $table->text('hero_description')->nullable()->after('hero_title');
            }
            if (!Schema::hasColumn('companies', 'hero_image')) {
                $table->string('hero_image')->nullable()->after('hero_description');
            }
            if (!Schema::hasColumn('companies', 'hero_cta_text')) {
                $table->string('hero_cta_text')->nullable()->after('hero_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'facebook', 'twitter', 'linkedin', 'instagram', 
                'company_profile_url', 'hero_title', 'hero_description', 
                'hero_image', 'hero_cta_text'
            ]);
        });
    }
};