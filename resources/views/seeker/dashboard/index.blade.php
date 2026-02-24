@extends('layouts.seeker')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h4 class="mb-4">Selamat datang kembali, {{ Auth::user()->name }}!</h4>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary shadow-sm h-100 border-0">
                    <div class="card-body text-center py-4">
                        <div class="mb-2 opacity-75"><i class="fas fa-paper-plane fa-2x text-white"></i></div>
                        <h2 class="display-5 fw-bold text-white mb-1">{{ $data['totalApplications'] }}</h2>
                        <p class="mb-0 text-white fw-medium opacity-75">Total Lamaran</p>
                    </div>
                </div>
            </div>
             <div class="col-md-4">
                <div class="card bg-info shadow-sm h-100 border-0">
                    <div class="card-body text-center py-4">
                        <div class="mb-2 opacity-75"><i class="fas fa-check-circle fa-2x text-white"></i></div>
                        <h2 class="display-5 fw-bold text-white mb-1">{{ $data['shortlistedApplications'] }}</h2>
                        <p class="mb-0 text-white fw-medium opacity-75">Terpilih</p>
                    </div>
                </div>
            </div>
             <div class="col-md-4">
                <div class="card bg-secondary shadow-sm h-100 border-0">
                    <div class="card-body text-center py-4">
                        <div class="mb-2 opacity-75"><i class="fas fa-bookmark fa-2x text-white"></i></div>
                        <h2 class="display-5 fw-bold text-white mb-1">{{ $data['savedJobs'] }}</h2>
                        <p class="mb-0 text-white fw-medium opacity-75">Lowongan Tersimpan</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2 text-primary"></i>Lamaran Terbaru</h5>
            </div>
            <div class="card-body p-0">
                 <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-muted small fw-bold text-uppercase py-3">Posisi Pekerjaan</th>
                                <th class="text-muted small fw-bold text-uppercase py-3">Status</th>
                                <th class="text-end pe-4 text-muted small fw-bold text-uppercase py-3">Tanggal Melamar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recentApplications'] as $app)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $app->job->title }}</div>
                                    <div class="text-muted small">{{ $app->job->company->company_name }}</div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ match($app->status) {
                                        'pending' => 'warning',
                                        'shortlisted' => 'info',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        default => 'secondary'
                                    } }} {{ $app->status == 'pending' ? 'text-dark' : '' }} px-3 py-2">
                                        {{ match($app->status) {
                                            'pending' => 'Menunggu',
                                            'shortlisted' => 'Terpilih',
                                            'accepted' => 'Diterima',
                                            'rejected' => 'Ditolak',
                                            default => ucfirst($app->status)
                                        } }}
                                    </span>
                                </td>
                                <td class="text-end pe-4 py-3">
                                    <span class="text-dark fw-medium small">{{ $app->created_at->translatedFormat('d M Y') }}</span>
                                    <br>
                                    <span class="text-muted smaller">{{ $app->created_at->diffForHumans() }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="text-muted mb-2"><i class="fas fa-folder-open fa-3x opacity-25"></i></div>
                                    <p class="text-muted fw-bold">Belum ada lamaran.</p>
                                    <a href="{{ route('seeker.jobs.index') }}" class="btn btn-sm btn-primary">Cari Pekerjaan Pertama Anda</a>
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
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-star me-2 text-warning"></i>Lowongan Unggulan</h5>
            </div>
            <div class="list-group list-group-flush">
                @forelse($featuredJobs as $job)
                <a href="{{ route('seeker.jobs.show', $job->id) }}" class="list-group-item list-group-item-action p-3">
                    <div class="d-flex w-100 justify-content-between mb-1">
                        <h6 class="mb-0 fw-bold text-primary">{{ $job->title }}</h6>
                        <small class="text-muted smaller">{{ $job->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-dark small mb-1">{{ $job->company->company_name }}</div>
                    <small class="d-block text-muted"><i class="fas fa-map-marker-alt me-1"></i> {{ $job->location->name }} • <i class="fas fa-briefcase me-1"></i> {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</small>
                </a>
                @empty
                    <div class="p-4 text-center text-muted">Tidak ada lowongan unggulan saat ini.</div>
                @endforelse
            </div>
             <div class="card-footer bg-white text-center py-3">
                <a href="{{ route('seeker.jobs.index') }}" class="fw-bold text-decoration-none small">Lihat Semua Lowongan <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
        
        <div class="card bg-light border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="card-title fw-bold text-dark mb-3"><i class="fas fa-user-edit me-2 text-primary"></i>Lengkapi Profil Anda</h6>
                <div class="progress mb-3" style="height: 8px; border-radius: 10px; background-color: rgba(0,0,0,0.05);">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                 <p class="card-text small text-muted mb-4">Menambahkan lebih banyak detail meningkatkan peluang Anda untuk direkrut oleh perusahaan papan atas.</p>
                <a href="{{ route('seeker.profile.edit') }}" class="btn btn-primary w-100 fw-bold py-2">Update Profil</a>
            </div>
        </div>
    </div>
</div>
@endsection
