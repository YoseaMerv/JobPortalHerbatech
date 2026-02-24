<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'seeker_profile_id',
        'skill_name',
        'proficiency_level',
    ];

    public function seekerProfile()
    {
        return $this->belongsTo(SeekerProfile::class);
    }
}
