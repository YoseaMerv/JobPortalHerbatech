@extends('layouts.seeker')

@section('content')
<style>
    :root {
        --brand-indigo: #4338ca;
        --brand-indigo-light: #eef2ff;
    }

    .ls-1 {
        letter-spacing: 0.05rem;
    }

    .extra-small {
        font-size: 0.75rem;
    }

    .hover-shadow {
        transition: all 0.3s ease;
    }

    .hover-shadow:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        transform: translateY(-3px);
    }

    .assessment-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        background: #fff;
        transition: all 0.2s ease;
    }

    .assessment-card:hover {
        border-color: var(--brand-indigo);
        background: var(--brand-indigo-light);
    }

    .assessment-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
</style>

<div class="row justify-content-center pb-5">
    <div class="col-md-8">

        {{-- Header Navigation --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.5px;">Detail Lamaran</h4>
            <a href="{{ route('seeker.applications.index') }}" class="btn btn-white border shadow-sm rounded-pill px-4 fw-bold text-muted hover-shadow">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            {{-- JOB INFO SECTION --}}
            <div class="card-body p-4 p-md-5 border-bottom">
                <div class="row align-items-center">
                    <div class="col-sm-7 mb-4 mb-sm-0">
                        <div class="d-inline-block bg-primary bg-opacity-10 text-primary fw-bold rounded-pill px-3 py-1 extra-small mb-3">
                            ID: #APP-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}
                        </div>
                        <h3 class="fw-bold mb-1" style="color: #1e293b;">{{ $application->job?->title ?? 'Lowongan Telah Dihapus' }}</h3>
                        <div class="text-primary mb-2 fw-semibold fs-6">
                            <i class="fas fa-building me-1"></i> {{ $application->job?->company?->company_name ?? 'Perusahaan Tidak Tersedia' }}
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $application->job?->location?->name ?? 'Lokasi Tidak Tersedia' }}
                        </div>
                    </div>
                    <div class="col-sm-5 text-sm-end">
                        <h6 class="text-muted text-uppercase small ls-1 mb-2">Status Saat Ini</h6>
                        <span class="badge bg-{{ $application->status_badge ?? 'secondary' }} px-4 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem;">
                            @if(in_array($application->status, ['test_invited', 'test_in_progress']))
                            <i class="fas fa-spinner fa-spin me-1"></i> Sedang Mengerjakan Tes
                            @else
                            {{ $application->status_label }}
                            @endif
                        </span>
                        <div class="text-muted extra-small mt-3">
                            <i class="far fa-clock me-1"></i> Dikirim: {{ $application->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">

                {{-- ASSESSMENT CENTER --}}
                @if(in_array($application->status, ['test_invited', 'test_in_progress', 'test_completed']))
                <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fas fa-clipboard-list text-primary me-2"></i> Assessment Center
                        </h5>
                        @if($application->status === 'test_completed')
                        <span class="badge bg-success rounded-pill px-3">Semua Tes Selesai</span>
                        @else
                        <span class="badge bg-warning text-dark rounded-pill">Wajib Diselesaikan</span>
                        @endif
                    </div>
                    <p class="text-muted small mb-4">Selesaikan rangkaian tes psikologi di bawah ini. Status lamaran akan berubah otomatis setelah keempat tes selesai.</p>

                    <div class="d-flex flex-column gap-3">

                        @php
                        // Ambil data hasil tes psikotes untuk pengecekan status
                        $results = $application->psychologicalResults;

                        $tests = [
                        [
                        'id' => 'kraepelin',
                        'label' => 'Tes Kraepelin (Pauli)',
                        'desc' => 'Tes kecepatan, ketelitian, dan ketahanan kerja angka.',
                        'icon' => 'fa-calculator',
                        'color' => 'primary',
                        'route' => ($application->status === 'test_invited' ? 'seeker.kraepelin.instructions' : 'seeker.kraepelin.start'),
                        'is_done' => $application->kraepelinTest()->whereNotNull('completed_at')->exists()
                        ],
                        [
                        'id' => 'disc',
                        'label' => 'DISC Personality Test',
                        'desc' => 'Evaluasi dominasi, pengaruh, stabilitas, dan kepatuhan.',
                        'icon' => 'fa-shapes',
                        'color' => 'success',
                        'route' => 'seeker.disc.instructions',
                        'is_done' => $results->where('test_type', 'disc')->where('status', 'completed')->isNotEmpty()
                        ],
                        [
                        'id' => 'msdt',
                        'label' => 'MSDT (Management Style)',
                        'desc' => 'Identifikasi gaya manajerial dan kepemimpinan Anda.',
                        'icon' => 'fa-users-cog',
                        'color' => 'danger',
                        'route' => 'seeker.msdt.instructions',
                        'is_done' => $results->where('test_type', 'msdt')->where('status', 'completed')->isNotEmpty()
                        ],
                        [
                        'id' => 'papi',
                        'label' => 'PAPI Kostick',
                        'desc' => 'Inventori persepsi gaya kerja dan kepribadian.',
                        'icon' => 'fa-clipboard-check',
                        'color' => 'info',
                        'route' => 'seeker.papi.instructions',
                        'is_done' => $results->where('test_type', 'papi')->where('status', 'completed')->isNotEmpty()
                        ]
                        ];
                        @endphp

                        @foreach($tests as $test)
                        <div class="assessment-card d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 {{ $test['is_done'] ? 'bg-light opacity-75' : '' }}">
                            <div class="d-flex align-items-center gap-3">
                                <div class="assessment-icon bg-{{ $test['color'] }} bg-opacity-10 text-{{ $test['color'] }}">
                                    <i class="fas {{ $test['is_done'] ? 'fa-check-circle text-success' : $test['icon'] }}"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 {{ $test['is_done'] ? 'text-decoration-line-through text-muted' : '' }}">{{ $test['label'] }}</h6>
                                    <p class="extra-small text-muted mb-0">{{ $test['desc'] }}</p>
                                </div>
                            </div>
                            <div class="text-md-end">
                                @if($test['is_done'])
                                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-4 py-2 rounded-pill fw-bold">
                                    <i class="fas fa-check me-1"></i> Selesai
                                </span>
                                @else
                                <a href="{{ route($test['route'], $application->id) }}" class="btn btn-outline-{{ $test['color'] }} btn-sm px-4 rounded-pill fw-bold w-100">
                                    Mulai Tes
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
                @endif

                {{-- ============================================== --}}
                {{-- DOKUMEN LAMARAN                                --}}
                {{-- ============================================== --}}
                <div class="pt-2">
                    <h6 class="text-muted text-uppercase small ls-1 mb-3 fw-bold"><i class="fas fa-folder-open me-2"></i>Dokumen Terlampir</h6>
                    <div class="row g-3">
                        {{-- File CV --}}
                        <div class="col-md-6">
                            <div class="p-3 border rounded-4 bg-light d-flex align-items-center hover-shadow">
                                <div class="icon-box bg-danger-subtle text-danger rounded-3 p-3 me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #fee2e2;">
                                    <i class="fas fa-file-pdf fa-lg"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="mb-0 small fw-bold text-dark">Curriculum Vitae (CV)</p>
                                    @if($application->cv_path)
                                    <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="text-primary extra-small fw-bold text-decoration-none">
                                        Lihat Dokumen <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                    @else
                                    <span class="text-muted extra-small">Tidak ada file</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- File Cover Letter --}}
                        <div class="col-md-6">
                            <div class="p-3 border rounded-4 bg-light d-flex align-items-center hover-shadow">
                                <div class="icon-box bg-primary-subtle text-primary rounded-3 p-3 me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: #e0e7ff;">
                                    <i class="fas fa-envelope-open-text fa-lg"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="mb-0 small fw-bold text-dark">Surat Lamaran Tambahan</p>
                                    @if($application->cover_letter_path)
                                    <a href="{{ asset('storage/' . $application->cover_letter_path) }}" target="_blank" class="text-primary extra-small fw-bold text-decoration-none">
                                        Lihat Dokumen <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                    @else
                                    <span class="text-muted extra-small">Tidak dilampirkan</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================== --}}
                {{-- DANGER ZONE (Tarik Lamaran)                    --}}
                {{-- ============================================== --}}
                @if($application->status === 'pending')
                <div class="mt-5 pt-4 border-top text-center">
                    <form action="{{ route('seeker.applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menarik lamaran ini? Tindakan ini tidak dapat dibatalkan dan HRD tidak akan lagi melihat lamaran Anda.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light text-danger border-danger border-opacity-25 rounded-pill px-4 py-2 small fw-bold hover-shadow">
                            <i class="fas fa-trash-alt me-2"></i> Tarik Lamaran Pekerjaan Ini
                        </button>
                    </form>
                    <p class="text-muted mt-2" style="font-size: 0.7rem;">Hanya dapat dilakukan jika lamaran belum diproses (Status: Pending).</p>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection