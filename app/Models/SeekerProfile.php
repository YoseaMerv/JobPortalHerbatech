<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeekerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'home_location_details',
        'city',
        'province',
        'country',
        'birth_date',
        'gender',
        'marital_status',
        'citizenship',
        'education_level',
        'current_job',
        'current_company',
        'skills', // Legacy column, kept for backup
        'experience', // Legacy column, kept for backup
        'bio',
        'summary',     
        'languages',
        'resume_path',
        'resume_filename',
        'portfolio_url',
        'profile_picture',
        'is_public',
        'preferred_position',
        'preferred_location',
        'preferred_job_type',
        'expected_salary',
        'availability',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_public' => 'boolean',
        'languages' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
