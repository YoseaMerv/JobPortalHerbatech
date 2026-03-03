<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    // --- KONSTANTA STATUS ---
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_SHORTLISTED = 'shortlisted';
    const STATUS_TEST_INVITED = 'test_invited';
    const STATUS_TEST_IN_PROGRESS = 'test_in_progress';
    const STATUS_TEST_COMPLETED = 'test_completed';
    const STATUS_INTERVIEW = 'interview';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'job_id',
        'user_id',
        'cv_path',
        'birth_date',
        'cover_letter_path',
        'status',
        'notes',
        'answers',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'answers' => 'array',
    ];

    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_PENDING          => 'Menunggu Review',
            self::STATUS_REVIEWED         => 'Sedang Ditinjau',
            self::STATUS_SHORTLISTED      => 'Lolos Seleksi Berkas',
            self::STATUS_TEST_INVITED     => 'Undangan Tes',
            self::STATUS_TEST_IN_PROGRESS => 'Sedang Mengerjakan Tes',
            self::STATUS_TEST_COMPLETED   => 'Tes Selesai',
            self::STATUS_INTERVIEW        => 'Wawancara',
            self::STATUS_ACCEPTED         => 'Diterima',
            self::STATUS_REJECTED         => 'Ditolak',
        ];
    }

    public function allTestsCompleted()
    {
        // Cek Kraepelin
        $kraepelin = $this->kraepelinTest()->whereNotNull('completed_at')->exists();

        // Cek MSDT & PAPI
        $completedTypes = $this->psychologicalResults()
            ->where('status', 'completed')
            ->pluck('test_type')
            ->toArray();

        $psikotesDone = in_array('msdt', $completedTypes) &&
            in_array('papi', $completedTypes) &&
            in_array('disc', $completedTypes);

        return $kraepelin && $psikotesDone;
    }
    // --- RELATIONSHIPS ---

    public function kraepelinTest()
    {
        // Menggunakan latestOfMany() sudah sangat tepat untuk retake test
        return $this->hasOne(KraepelinTest::class)->latestOfMany();
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- ACCESSORS (UI Logic) ---

    // Menambahkan Label Status agar bisa dipanggil di Blade via $application->status_label
    public function getStatusLabelAttribute(): string
    {
        return self::getAllStatuses()[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING          => 'warning',
            self::STATUS_REVIEWED         => 'secondary',
            self::STATUS_SHORTLISTED      => 'info',
            self::STATUS_TEST_INVITED     => 'primary',
            self::STATUS_TEST_IN_PROGRESS => 'warning',
            self::STATUS_TEST_COMPLETED   => 'success',
            self::STATUS_INTERVIEW        => 'dark',
            self::STATUS_ACCEPTED         => 'success',
            self::STATUS_REJECTED         => 'danger',
            default                       => 'light',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING          => 'fa-clock',
            self::STATUS_REVIEWED         => 'fa-eye',
            self::STATUS_SHORTLISTED      => 'fa-user-check',
            self::STATUS_TEST_INVITED     => 'fa-file-signature',
            self::STATUS_TEST_IN_PROGRESS => 'fa-spinner fa-spin',
            self::STATUS_TEST_COMPLETED   => 'fa-poll-h',
            self::STATUS_INTERVIEW        => 'fa-comments',
            self::STATUS_ACCEPTED         => 'fa-check-double',
            self::STATUS_REJECTED         => 'fa-times-circle',
            default                       => 'fa-info-circle',
        };
    }



    public function psychologicalResults()
    {
        return $this->hasMany(PsychologicalTestResult::class);
    }
}
