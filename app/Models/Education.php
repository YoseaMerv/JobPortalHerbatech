<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'education';

    protected $fillable = [
        'seeker_profile_id',
        'institution',
        'degree',
        'field_of_study',
        'start_date',
        'end_date',
    ];

    // WAJIB: Agar format('Y') di Blade berfungsi
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function seekerProfile()
    {
        return $this->belongsTo(SeekerProfile::class);
    }
}
