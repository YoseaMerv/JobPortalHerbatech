<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_email',
        'company_phone',
        'company_website',
        'company_logo',
        'favicon',
        'company_description',
        'company_address',
        'company_city',
        'company_province',
        'company_country',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'company_profile_url',
        'hero_title',
        'hero_description',
        'hero_image',
        'hero_cta_text',
        'industry',
        'company_size',
        'founded_date',
        'is_verified',
    ];

    protected $casts = [
        'founded_date' => 'date',
        'is_verified' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function activeJobs()
    {
        return $this->hasMany(Job::class)->where('status', 'published');
    }

    // Scope methods
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}