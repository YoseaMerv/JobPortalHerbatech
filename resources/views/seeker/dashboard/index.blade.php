@extends('layouts.seeker')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h4 class="mb-4">Selamat datang kembali, {{ Auth::user()->name }}!</h4>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary shadow-sm h-100 border-0 overflow-hidden" style="border-radius: 16px;">
                    <div class="card-body text-center py-4 position-relative">
                        <div class="mb-2 opacity-25 position-absolute end-0 top-0 p-3"><i class="fas fa-paper-plane fa-3x text-white"></i></div>
                        <h2 class="display-5 fw-bold text-white mb-1">{{ $data['totalApplications'] }}</h2>
                        <p class="mb-0 text-white fw-medium opacity-75 small">Total Lamaran</p>
                    </div>
                </div>
            </div>
             <div class="col-md-4">
                <div class="card bg-info shadow-sm h-100 border-0">
                    <div class="card-body text-center py-4">
                        <div class="mb-2 opacity-75"><i class="fas fa-check-circle fa-2x text-white"></i></div>
                        <h2 class="display-5 fw-bold text-white mb-1">{{ $data['shortlistedApplications'] }}</h2>
                        <p class="mb-0 text-white fw-medium opacity-75 small">Lolos Seleksi</p>
                    </div>
                </div>
            </div>
             <div class="col-md-4">
                <div class="card bg-secondary shadow-sm h-100 border-0">
                    <div class="card-body text-center py-4">
                        <div class="mb-2 opacity-75"><i class="fas fa-bookmark fa-2x text-white"></i></div>
                        <h2 class="display-5 fw-bold text-white mb-1">{{ $data['savedJobs'] }}</h2>
                        <p class="mb-0 text-white fw-medium opacity-75 small">Tersimpan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2 text-primary"></i>Aktivitas Lamaran</h6>
                <a href="{{ route('seeker.kraepelin.instructions', $testInvitation->id) }}" 
                class="btn btn-light btn-sm fw-bold px-4 rounded-pill shadow-sm text-indigo">
                    <i class="fas fa-play me-2"></i> 
                    {{ $testInvitation->status === 'test_in_progress' ? 'LANJUTKAN SEKARANG' : 'MULAI TES' }}
                </a>
            </div>
            <div class="card-body p-0">
                 <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-muted small fw-bold text-uppercase py-3">Posisi</th>
                                <th class="text-muted small fw-bold text-uppercase py-3 text-center">Status Progres</th>
                                <th class="text-end pe-4 text-muted small fw-bold text-uppercase py-3">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recentApplications'] as $app)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $app->job->title }}</div>
                                    <div class="text-muted small">{{ $app->job->company->company_name }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill px-3 py-2 
                                        bg-{{ match($app->status) {
                                            'pending'          => 'warning text-dark',
                                            'reviewed'         => 'secondary',
                                            'shortlisted'      => 'info',
                                            'test_invited'     => 'primary',
                                            'test_in_progress' => 'warning text-dark',
                                            'test_completed'   => 'success',
                                            'interview'        => 'dark',
                                            'accepted'         => 'success',
                                            'rejected'         => 'danger',
                                            default            => 'secondary'
                                        } }}">
                                        {{ $app->status_label }}
                                    </span>
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <span class="text-dark fw-medium small">{{ $app->created_at->translatedFormat('d M Y') }}</span>
                                    <br>
                                    <span class="text-muted extra-small">{{ $app->created_at->diffForHumans() }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="text-muted mb-2"><i class="fas fa-folder-open fa-3x opacity-25"></i></div>
                                    <p class="text-muted fw-bold">Belum ada lamaran.</p>
                                    <a href="{{ route('seeker.jobs.index') }}" class="btn btn-sm btn-primary rounded-pill px-4">Mulai Cari Kerja</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Ganti bagian Skor Profil di dashboard.blade.php Anda --}}
        <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold text-dark mb-1">Skor Profil Anda</h6>
                        <p class="small text-muted mb-0">
                            {{ $data['profileScore'] < 100 ? 'Lengkapi untuk dilirik HR' : 'Profil Anda sudah luar biasa!' }}
                        </p>
                    </div>
                    {{-- Tampilkan skor dinamis --}}
                    <div class="fw-bold h4 text-primary mb-0">{{ $data['profileScore'] }}%</div>
                </div>
                <div class="progress mb-4" style="height: 8px; border-radius: 10px; background-color: #f1f5f9;">
                    {{-- Lebar progress bar mengikuti skor --}}
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{ $data['profileScore'] == 100 ? 'success' : 'primary' }}"
                        role="progressbar"
                        style="width: {{ $data['profileScore'] }}%;"
                        aria-valuenow="{{ $data['profileScore'] }}"
                        aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
                <a href="{{ route('seeker.profile.edit') }}" class="btn {{ $data['profileScore'] < 100 ? 'btn-outline-primary' : 'btn-success' }} w-100 fw-bold py-2 rounded-pill shadow-sm">
                    <i class="fas {{ $data['profileScore'] < 100 ? 'fa-user-edit' : 'fa-check-circle' }} me-2"></i>
                    {{ $data['profileScore'] < 100 ? 'Update Profil Digital' : 'Profil Lengkap' }}
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-star me-2 text-warning"></i>Rekomendasi Kerja</h6>
            </div>
            <div class="list-group list-group-flush">
                @forelse($featuredJobs as $job)
                <a href="{{ route('seeker.jobs.show', $job->id) }}" class="list-group-item list-group-item-action p-3 border-0 border-bottom">
                    <div class="d-flex w-100 justify-content-between mb-1">
                        <h6 class="mb-0 fw-bold text-indigo" style="font-size: 0.9rem;">{{ Str::limit($job->title, 25) }}</h6>
                        <small class="text-muted smaller">{{ $job->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-dark small mb-2">{{ $job->company->company_name }}</div>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge bg-light text-muted fw-normal border" style="font-size: 0.7rem;">{{ $job->location->name }}</span>
                        <span class="badge bg-light text-muted fw-normal border" style="font-size: 0.7rem;">{{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</span>
                    </div>
                </a>
                @empty
                    <div class="p-4 text-center text-muted">Tidak ada lowongan unggulan saat ini.</div>
                @endforelse
            </div>
            <div class="card-footer bg-white text-center py-3 border-0">
                <a href="{{ route('seeker.jobs.index') }}" class="fw-bold text-decoration-none small">Cek Semua Lowongan <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection