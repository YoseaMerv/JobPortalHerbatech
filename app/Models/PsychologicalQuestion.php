<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsychologicalQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_type',
        'question_number',
        'option_a',
        'option_b',
        'dimension_a',
        'dimension_b',
    ];

    // Scope untuk mempermudah pengambilan soal berdasarkan jenis tes
    public function scopeMsdt($query)
    {
        return $query->where('test_type', 'msdt');
    }

    public function scopePapi($query)
    {
        return $query->where('test_type', 'papi');
    }
}
