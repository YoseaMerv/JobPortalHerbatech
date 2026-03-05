@extends('layouts.seeker')

@section('content')
<div class="row">
    <div class="col-md-8">  

        {{-- 1. LOGIKA PENGECEKAN --}}
        @php
        $testInvitation = $data['recentApplications']
        ->whereIn('status', ['test_invited', 'test_in_progress'])
        ->first();
        @endphp

        {{-- 2. BANNER NOTIFIKASI TES --}}
        @if($testInvitation)
        <div class="alert alert-indigo border-0 shadow-sm mb-4 d-flex align-items-center p-4" style="border-radius: 16px;">
            <div class="me-4 flex-shrink-0">
                <i class="fas fa-file-signature fa-3x opacity-50 text-white"></i>
            </div>
            <div class="text-white">
                <h5 class="fw-bold mb-1">
                    {{ $testInvitation->status === 'test_in_progress' ? 'Lanjutkan Tes Kraepelin' : 'Undangan Tes Kraepelin!' }}
                </h5>
                <p class="mb-3 small opacity-90">
                    {{ $testInvitation->status === 'test_in_progress' 
                        ? 'Selesaikan tes Anda yang sedang berlangsung agar progres tersimpan.' 
                        : 'Anda terpilih untuk tahap tes pada posisi ' . ($testInvitation->job?->title ?? 'Posisi Terkait') }}
                </p>

                @php
                $targetRoute = ($testInvitation->status === 'test_in_progress')
                ? route('seeker.kraepelin.start', $testInvitation->id)
                : route('seeker.kraepelin.instructions', $testInvitation->id);
                @endphp

                <a href="{{ $targetRoute }}" class="btn btn-light btn-sm fw-bold px-4 rounded-pill shadow-sm text-indigo">
                    <i class="fas fa-play me-2"></i>
                    {{ $testInvitation->status === 'test_in_progress' ? 'LANJUTKAN SEKARANG' : 'MULAI TES' }}
                </a>
            </div>
        </div>
        @endif

        {{-- 3. STATISTIK UTAMA --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary shadow-sm h-100 border-0 overflow-hidden" style="border-radius: 16px;">
                    <div class="card-body text-center py-4 position-relative">
                        <div class="mb-2 opacity-25 position-absolute end-0 top-0 p-3"><i class="fas fa-paper-plane fa-3x text-white"></i></div>
                        <h2 class="display-5 fw-bold text-white mb-1">{{ $data['totalApplications'] }}</h2>
                        <p class="mb-0 text-white fw-medium opacity-75 small">Total Lamaran</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-info shadow-sm h-100 border-0 overflow-hidden" style="border-radius: 16px;">
                    <div class="card-body text-center py-4 position-relative">
                        <div class="mb-2 opacity-25 position-absolute end-0 top-0 p-3"><i class="fas fa-check-circle fa-3x text-white"></i></div>
                        <h2 class="display-5 fw-bold text-white mb-1">{{ $data['shortlistedApplications'] }}</h2>
                        <p class="mb-0 text-white fw-medium opacity-75 small">Lolos Seleksi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-dark shadow-sm h-100 border-0 overflow-hidden" style="border-radius: 16px;">
                    <div class="card-body text-center py-4 position-relative">
                        <div class="mb-2 opacity-25 position-absolute end-0 top-0 p-3"><i class="fas fa-bookmark fa-3x text-white"></i></div>
                        <h2 class="display-5 fw-bold text-white mb-1">{{ $data['savedJobs'] }}</h2>
                        <p class="mb-0 text-white fw-medium opacity-75 small">Tersimpan</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. TABEL AKTIVITAS LAMARAN --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-body"><i class="fas fa-history me-2 text-primary"></i>Aktivitas Lamaran</h6>
                
                @if($testInvitation)
                <a href="{{ route('seeker.kraepelin.instructions', $testInvitation->id) }}"
                    class="btn btn-primary btn-sm fw-bold px-4 rounded-pill shadow-sm">
                    <i class="fas fa-play me-2"></i>
                    {{ $testInvitation->status === 'test_in_progress' ? 'LANJUTKAN TES' : 'KERJAKAN TES' }}
                </a>
                @else
                <a href="{{ route('seeker.applications.index') }}" class="small text-decoration-none fw-bold">Lihat Semua</a>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-muted small fw-bold text-uppercase py-3 border-0">Posisi</th>
                                <th class="text-muted small fw-bold text-uppercase py-3 text-center border-0">Status</th>
                                <th class="text-end pe-4 text-muted small fw-bold text-uppercase py-3 border-0">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recentApplications'] as $app)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-body" style="font-size: 0.95rem;">{{ $app->job?->title ?? 'Lowongan Telah Dihapus' }}</div>
                                    <div class="text-muted small">{{ $app->job?->company?->company_name ?? 'Perusahaan Tidak Tersedia' }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill px-3 py-2 
                                        bg-{{ match($app->status) {
                                            'pending'          => 'warning text-dark',
                                            'test_invited'     => 'primary',
                                            'test_in_progress' => 'warning text-dark',
                                            'accepted'         => 'success',
                                            'rejected'         => 'danger',
                                            default            => 'secondary'
                                        } }}">
                                        {{ $app->status_label }}
                                    </span>
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <span class="text-body fw-medium small">{{ $app->created_at->translatedFormat('d M Y') }}</span>
                                    <br>
                                    <span class="text-muted extra-small">{{ $app->created_at->diffForHumans() }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted small">Belum ada aktivitas lamaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- SIDEBAR KANAN --}}
    <div class="col-md-4">
        {{-- Widget Profil Skor --}}
        @if($data['profileScore'] < 100)
        <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold text-body mb-1">Skor Profil Anda</h6>
                        <p class="small text-muted mb-0">Lengkapi untuk dilirik HR</p>
                    </div>
                    <div class="fw-bold h4 text-primary mb-0">{{ $data['profileScore'] }}%</div>
                </div>
                <div class="progress mb-4" style="height: 8px; border-radius: 10px; background-color: #f1f5f9;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                        role="progressbar"
                        style="width: {{ $data['profileScore'] }}%;"
                        aria-valuenow="{{ $data['profileScore'] }}"
                        aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <a href="{{ route('seeker.profile.edit') }}" class="btn btn-outline-primary w-100 fw-bold py-2 rounded-pill shadow-sm">
                    <i class="fas fa-user-edit me-2"></i>
                    Update Profil Digital
                </a>
            </div>
        </div>
        @endif

        {{-- Kartu Rekomendasi Kerja --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-header bg-transparent py-3 border-bottom">
                <h6 class="mb-0 fw-bold text-body"><i class="fas fa-star me-2 text-warning"></i>Rekomendasi Kerja</h6>
            </div>
            <div class="list-group list-group-flush rounded-bottom-4">
                @forelse($featuredJobs as $job)
                <a href="{{ route('seeker.jobs.show', $job->id) }}" class="list-group-item list-group-item-action p-3 border-0 border-bottom bg-transparent">
                    <div class="d-flex w-100 justify-content-between mb-1">
                        <h6 class="mb-0 fw-bold text-indigo" style="font-size: 0.9rem;">{{ Str::limit($job->title ?? 'Judul Lowongan', 25) }}</h6>
                        <small class="text-muted smaller">{{ $job->created_at->diffForHumans() }}</small>
                    </div>
                    {{-- Diubah menjadi text-body agar otomatis putih di dark mode --}}
                    <div class="text-body small mb-2">{{ $job->company?->company_name ?? 'Perusahaan' }}</div>
                    
                    {{-- PERBAIKAN BADGE: Menggunakan bg-opacity-10 yang cantik di Light Mode & elegan di Dark Mode --}}
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 fw-medium px-2 py-1" style="font-size: 0.7rem;">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $job->location?->name ?? 'Remote' }}
                        </span>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-medium px-2 py-1" style="font-size: 0.7rem;">
                            <i class="fas fa-briefcase me-1"></i> {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="p-4 text-center text-muted small bg-transparent">Tidak ada lowongan unggulan saat ini.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .alert-indigo {
        background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        color: white;
    }
    .text-indigo { color: #4338ca; }
    .extra-small { font-size: 0.7rem; }
    .smaller { font-size: 0.75rem; }
</style>
@endsection