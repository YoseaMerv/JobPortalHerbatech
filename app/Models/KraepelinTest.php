<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KraepelinTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'questions',
        'answers',
        'results_chart',
        'total_answered',
        'total_correct',
        'total_wrong',
        'panker',
        'tianker',
        'janker',
        'ganker',
        'stability_score',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'questions'     => 'array',
        'answers'       => 'array',
        'results_chart' => 'array',
        'started_at'    => 'datetime',
        'completed_at'  => 'datetime',
        'panker'        => 'float',
        'tianker'       => 'integer',
        'janker'        => 'float',
        'ganker'        => 'float',
    ];

    protected $hidden = [
        'questions',
        'answers',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    /**
     * Ambil persentase akurasi: $test->accuracy_percentage
     */
    public function getAccuracyPercentageAttribute()
    {
        return $this->total_answered > 0 
            ? round(($this->total_correct / $this->total_answered) * 100, 1) 
            : 0;
    }

    /**
     * Label Kecepatan: $test->panker_label
     */
    public function getPankerLabelAttribute()
    {
        if ($this->panker >= 15) return 'Sangat Cepat';
        if ($this->panker >= 10) return 'Cepat';
        return 'Normal/Lambat';
    }

    public function getTiankerLabelAttribute()
    {
        if ($this->tianker <= 5) return 'Sangat Teliti';
        if ($this->tianker <= 12) return 'Teliti';
        return 'Kurang Teliti';
    }

    public function getGankerLabelAttribute()
    {
        return $this->ganker >= 0 ? 'Daya Tahan Kuat' : 'Mudah Lelah';
    }

    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }
}