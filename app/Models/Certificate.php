<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'seeker_profile_id',
        'name',
        'issuer',
        'issued_date',
        'description',
        'file_path',
    ];

    protected $casts = [
        'issued_date' => 'date',
    ];

    public function seekerProfile()
    {
        return $this->belongsTo(SeekerProfile::class);
    }
}
