<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'category_id',
        'location_id',
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'salary_min',
        'salary_max',
        'salary_type',
        'job_type',
        'experience_level',
        'education_level',
        'deadline',
        'vacancy',
        'status',
        'is_featured',
        'is_remote',
        'views',
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'deadline' => 'date',
        'is_featured' => 'boolean',
        'is_remote' => 'boolean',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function location()
    {
        return $this->belongsTo(JobLocation::class, 'location_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Scope methods
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', now());
            });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
            ->published();
    }

    public function scopeRemote($query)
    {
        return $query->where('is_remote', true);
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'published' && 
               (!$this->deadline || $this->deadline >= now());
    }

    public function isExpired(): bool
    {
        return $this->deadline && $this->deadline < now();
    }

    public function getSalaryFormattedAttribute(): string
    {
        if (!$this->salary_min && !$this->salary_max) {
            return 'Negosiasi';
        }

        $currency = 'IDR';
        $formatter = new \NumberFormatter('id_ID', \NumberFormatter::CURRENCY);
        
        $salaryType = $this->salary_type;
        $typeMap = [
            'monthly' => 'per bulan',
            'hourly' => 'per jam',
            'yearly' => 'per tahun',
            'daily' => 'per hari',
            'weekly' => 'per minggu',
        ];

        $displayType = $typeMap[strtolower($salaryType)] ?? $salaryType;

        if ($this->salary_min && $this->salary_max) {
            return $formatter->formatCurrency($this->salary_min, $currency) . ' - ' . 
                   $formatter->formatCurrency($this->salary_max, $currency) . ' ' . 
                    $displayType;
        }

        if ($this->salary_min) {
            return 'Min. ' . $formatter->formatCurrency($this->salary_min, $currency) . ' ' . $displayType;
        }

        return 'Maks. ' . $formatter->formatCurrency($this->salary_max, $currency) . ' ' . $displayType;
    }
}