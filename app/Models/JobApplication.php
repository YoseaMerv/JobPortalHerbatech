<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    // Konstanta Status
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
        'cover_letter',
        'status',
        'notes',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    // --- RELATIONSHIPS ---

    /**
     * Relasi ke Tes Kraepelin (TAMBAHKAN INI)
     */
    public function kraepelinTest()
    {
        return $this->hasOne(KraepelinTest::class);
    }

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

    // --- HELPER METHODS ---

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isShortlisted(): bool
    {
        return $this->status === self::STATUS_SHORTLISTED;
    }

    public function isInvitedToTest(): bool
    {
        return $this->status === self::STATUS_TEST_INVITED;
    }

    public function isTestInProgress(): bool
    {
        return $this->status === self::STATUS_TEST_IN_PROGRESS;
    }

    public function isTestCompleted(): bool
    {
        return $this->status === self::STATUS_TEST_COMPLETED;
    }

    // --- ACCESSORS ---

    /**
     * Mendapatkan class warna badge Bootstrap berdasarkan status.
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending'          => 'warning',
            'reviewed'         => 'secondary',
            'shortlisted'      => 'info',
            'test_invited'     => 'primary', 
            'test_in_progress' => 'warning',
            'test_completed'   => 'success',
            'interview'        => 'dark',
            'accepted'         => 'success',
            'rejected'         => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Mendapatkan label teks Indonesia untuk status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'          => 'Menunggu',
            'reviewed'         => 'Ditinjau',
            'shortlisted'      => 'Terpilih',
            'test_invited'     => 'Undangan Tes',
            'test_in_progress' => 'Sedang Mengerjakan',
            'test_completed'   => 'Ujian Selesai',
            'interview'        => 'Wawancara',
            'accepted'         => 'Diterima',
            'rejected'         => 'Ditolak',
            default            => ucfirst($this->status),
        };
    }
}