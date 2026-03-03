<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsychologicalTestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'user_id',
        'test_type',
        'status',
        'answers',
        'final_score',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'final_score' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }
}
