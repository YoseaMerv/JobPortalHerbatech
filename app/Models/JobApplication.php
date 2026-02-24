<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
        'cv_path',
        'cover_letter',
        'status',
        'notes',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    // Relationships
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seekerProfile()
    {
        return $this->hasOneThrough(SeekerProfile::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    // Status helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isShortlisted(): bool
    {
        return $this->status === 'shortlisted';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'warning',
            'reviewed' => 'info',
            'shortlisted' => 'primary',
            'interview' => 'success',
            'rejected' => 'danger',
            'accepted' => 'success',
        ];

        return $badges[$this->status] ?? 'secondary';
    }
}