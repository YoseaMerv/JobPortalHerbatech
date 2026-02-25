<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use App\Models\JobLocation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@jobportal.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        // Create Sample Company
        $companyUser = User::firstOrCreate(
            ['email' => 'company@jobportal.test'],
            [
                'name' => 'Tech Company',
                'password' => Hash::make('password'),
                'role' => 'company',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        if (!$companyUser->company()->exists()) {
            $companyUser->company()->create([
                'company_name' => 'Tech Solutions Inc.',
                'company_email' => 'info@techsolutions.com',
                'company_phone' => '+1234567890',
                'company_website' => 'https://techsolutions.com',
                'company_description' => 'Leading technology solutions provider.',
                'company_address' => '123 Tech Street',
                'company_city' => 'Jakarta',
                'company_province' => 'DKI Jakarta',
                'company_country' => 'Indonesia',
                'industry' => 'Technology',
                'company_size' => 100,
                'founded_date' => '2010-01-01',
                'is_verified' => true,
            ]);
        }

        // Create Sample Seeker
        $seekerUser = User::firstOrCreate(
            ['email' => 'seeker@jobportal.test'],
            [
                'name' => 'Job Seeker',
                'password' => Hash::make('password'),
                'role' => 'seeker',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        

        if (!$seekerUser->seekerProfile()->exists()) {
            $seekerUser->seekerProfile()->create([
                'phone' => '+628123456789',
                'address' => '456 Seeker Street',
                'city' => 'Bandung',
                'province' => 'West Java',
                'country' => 'Indonesia',
                'birth_date' => '1990-01-01',
                'gender' => 'male',
                'education_level' => 's1',
                'current_job' => 'Software Developer',
                'current_company' => 'Previous Company',
                'skills' => 'PHP, Laravel, JavaScript, MySQL',
                'experience' => '5 years experience in web development',
                'bio' => 'Passionate software developer seeking new opportunities.',
                'is_public' => true,
            ]);
        }

        // Seed Job Categories
        $categories = [
            'Technology', 'Finance', 'Healthcare', 'Education', 'Marketing',
            'Sales', 'Design', 'Engineering', 'Human Resources', 'Customer Service',
        ];

        foreach ($categories as $category) {
            JobCategory::firstOrCreate(
                ['name' => $category],
                [
                    'slug' => Str::slug($category),
                    'description' => $category . ' related jobs',
                    'is_active' => true,
                ]
            );
        }

        // Seed Job Locations
        $locations = [
            'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Makassar',
            'Bali', 'Yogyakarta', 'Semarang', 'Malang', 'Remote',
        ];

        foreach ($locations as $location) {
            JobLocation::firstOrCreate(
                ['name' => $location],
                [
                    'slug' => Str::slug($location),
                    'is_active' => true,
                ]
            );
        }

        // Create sample jobs
        $company = $companyUser->company;
        if ($company->jobs()->count() == 0) {
            $categories = JobCategory::all();
            $locations = JobLocation::all();

            if ($categories->count() > 0 && $locations->count() > 0) {
                for ($i = 1; $i <= 10; $i++) {
                    $company->jobs()->create([
                        'category_id' => $categories->random()->id,
                        'location_id' => $locations->random()->id,
                        'title' => 'Software Developer ' . $i,
                        'slug' => 'software-developer-' . $i . '-' . time(),
                        'description' => 'We are looking for a skilled Software Developer to join our team.',
                        'requirements' => 'PHP, Laravel, JavaScript, MySQL experience required.',
                        'responsibilities' => 'Develop and maintain web applications.',
                        'salary_min' => 10000000,
                        'salary_max' => 20000000,
                        'salary_type' => 'monthly',
                        'job_type' => 'full_time',
                        'experience_level' => 'mid',
                        'education_level' => 's1',
                        'deadline' => now()->addDays(30),
                        'vacancy' => 2,
                        'status' => 'published',
                        'is_featured' => $i <= 3,
                        'is_remote' => $i % 3 == 0,
                        'views' => rand(100, 1000),
                    ]);
                }
            }
        }
    }
}