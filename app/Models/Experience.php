<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'seeker_profile_id',
        'company_name',
        'job_title',
        'location',
        'start_date',
        'end_date',
        'description',
    ];

    public function seekerProfile()
    {
        return $this->belongsTo(SeekerProfile::class);
    }
}
