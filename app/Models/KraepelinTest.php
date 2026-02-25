<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KraepelinTest extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi (mass assignable).
     */
    protected $fillable = [
        'job_application_id',
        'questions',
        'answers',
        'total_answered',
        'total_correct',
        'total_wrong',
        'stability_score',
        'started_at',
        'completed_at',
    ];

    /**
     * Casting atribut untuk memastikan tipe data yang tepat saat diakses.
     */
    protected $casts = [
        'questions'    => 'array',    // Otomatis konversi JSON ke Array
        'answers'      => 'array',    // Otomatis konversi JSON ke Array
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Relasi Balik ke Lamaran Pekerjaan (Job Application).
     */
    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    /**
     * Helper untuk mengecek apakah tes sudah selesai.
     */
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    /**
     * Helper untuk mendapatkan durasi pengerjaan dalam menit.
     */
    public function getDurationInMinutesAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInMinutes($this->completed_at);
        }
        return 0;
    }
}