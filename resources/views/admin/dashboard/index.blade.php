@extends('layouts.admin')

@section('content')
<style>
    :root {
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --text-main: #334155; 
        --text-heading: #1e293b;
        --brand-indigo: #4338ca; 
    }

    /* Statistik Card Modern */
    .stat-card {
        border-radius: 16px;
        border: 1px solid var(--slate-200);
        background: #fff;
        padding: 24px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: block;
        text-decoration: none;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    /* Warna Ikon Kalem */
    .bg-soft-primary { background: #eef2ff; color: #4338ca; }
    .bg-soft-success { background: #ecfdf5; color: #059669; }
    .bg-soft-info { background: #f0f9ff; color: #0ea5e9; }
    .bg-soft-warning { background: #fffbeb; color: #d97706; }
    .bg-soft-danger { background: #fef2f2; color: #e11d48; } /* Tambahan untuk Admin */

    /* Table Dashboard */
    .dashboard-card {
        border-radius: 16px;
        border: 1px solid var(--slate-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }
    .dashboard-card .card-header {
        background: #fff;
        border-bottom: 1px solid var(--slate-100);
        padding: 20px 24px;
    }
    .table thead th {
        background: var(--slate-50);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        padding: 12px 24px;
        border: none;
    }
    .table td { padding: 16px 24px; border-bottom: 1px solid var(--slate-50); }

    .status-pill {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
    }
</style>

<div class="container-fluid pb-4">
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: var(--text-heading); letter-spacing: -0.02em;">Dashboard Administrator</h3>
        <p class="text-muted small">Ringkasan aktivitas dan metrik keseluruhan portal HerbaTech.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="{{ route('admin.users.index', ['role' => 'seeker']) }}" class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-soft-primary"><i class="fas fa-users"></i></div>
                    <span class="text-muted small fw-bold">PELAMAR</span>
                </div>
                <h6 class="text-muted mb-1 small fw-medium">Total Kandidat</h6>
                <h3 class="fw-bold mb-0" style="color: var(--text-heading);">{{ number_format($data['totalSeekers'] ?? 0) }}</h3>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.users.index', ['role' => 'company']) }}" class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-soft-success"><i class="fas fa-building"></i></div>
                    <span class="text-muted small fw-bold">PERUSAHAAN</span>
                </div>
                <h6 class="text-muted mb-1 small fw-medium">Total Perusahaan</h6>
                <h3 class="fw-bold mb-0" style="color: var(--text-heading);">{{ number_format($data['totalCompanies'] ?? 0) }}</h3>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.jobs.index') }}" class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-soft-warning"><i class="fas fa-briefcase"></i></div>
                    <span class="text-muted small fw-bold">LOWONGAN</span>
                </div>
                <h6 class="text-muted mb-1 small fw-medium">Lowongan Dibuat</h6>
                <h3 class="fw-bold mb-0" style="color: var(--text-heading);">{{ number_format($data['totalJobs'] ?? 0) }}</h3>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.applications.index') ?? '#' }}" class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-soft-danger"><i class="fas fa-file-alt"></i></div>
                    <span class="text-muted small fw-bold">LAMARAN</span>
                </div>
                <h6 class="text-muted mb-1 small fw-medium">Total Lamaran</h6>
                <h3 class="fw-bold mb-0" style="color: var(--text-heading);">{{ number_format($data['totalApplications'] ?? 0) }}</h3>
            </a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="dashboard-card h-100">
                <div class="card-header border-bottom-0 pb-0">
                    <h6 class="mb-0 fw-bold" style="color: var(--text-heading);"><i class="fas fa-chart-line me-2 text-primary"></i>Aktivitas Hari Ini</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded" style="background: var(--slate-50); border: 1px solid var(--slate-100);">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-soft-primary me-3" style="width: 40px; height: 40px; font-size: 1rem;"><i class="fas fa-user-plus"></i></div>
                            <div>
                                <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">Pengguna Baru</h6>
                                <small class="text-muted">Mendaftar hari ini</small>
                            </div>
                        </div>
                        <h5 class="mb-0 fw-bold text-primary">+{{ $data['newUsersCount'] ?? 0 }}</h5>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded" style="background: var(--slate-50); border: 1px solid var(--slate-100);">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-soft-warning me-3" style="width: 40px; height: 40px; font-size: 1rem;"><i class="fas fa-bullhorn"></i></div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">Lowongan Baru</h6>
                                <small class="text-muted">Dipasang hari ini</small>
                            </div>
                        </div>
                        <h5 class="mb-0 fw-bold text-warning">+{{ $data['newJobsCount'] ?? 0 }}</h5>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: var(--slate-50); border: 1px solid var(--slate-100);">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-soft-danger me-3" style="width: 40px; height: 40px; font-size: 1rem;"><i class="fas fa-file-import"></i></div>
                            <div>
                                <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">Lamaran Masuk</h6>
                                <small class="text-muted">Diterima hari ini</small>
                            </div>
                        </div>
                        <h5 class="mb-0 fw-bold text-danger">+{{ $data['newAppsCount'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="dashboard-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold" style="color: var(--text-heading);"><i class="fas fa-briefcase me-2 text-warning"></i>Lowongan Terakhir</h6>
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-sm btn-link text-decoration-none small fw-bold">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Posisi & Perusahaan</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th class="text-end">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recentJobs'] ?? [] as $job)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ Str::limit($job->title, 35) }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $job->company->name ?? 'Perusahaan' }}</div>
                                </td>
                                <td>
                                    <span class="status-pill bg-soft-info text-info">
                                        {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-pill 
                                        bg-{{ $job->status === 'published' ? 'success' : ($job->status === 'closed' ? 'danger' : 'warning') }} bg-opacity-10 
                                        text-{{ $job->status === 'published' ? 'success' : ($job->status === 'closed' ? 'danger' : 'warning') }}">
                                        {{ $job->status === 'published' ? 'Tayang' : ($job->status === 'closed' ? 'Ditutup' : 'Draft') }}
                                    </span>
                                </td>
                                <td class="text-end text-muted small">
                                    {{ $job->created_at->diffForHumans() }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada lowongan dibuat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="dashboard-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold" style="color: var(--text-heading);"><i class="fas fa-user-clock me-2 text-info"></i>Pelamar Baru Mendaftar</h6>
                    <a href="{{ route('admin.users.index', ['role' => 'seeker']) }}" class="btn btn-sm btn-link text-decoration-none small fw-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($data['recentUsers'] ?? [] as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3" style="border-bottom: 1px solid var(--slate-50);">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f1f5f9&color=334155' }}" 
                                     alt="Avatar" class="rounded-circle me-3" width="42" height="42" style="object-fit: cover;">
                                <div>
                                    <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                            <small class="text-muted fw-medium">{{ $user->created_at->diffForHumans() }}</small>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted py-5">Belum ada pelamar baru terdaftar.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold" style="color: var(--text-heading);"><i class="fas fa-paper-plane me-2 text-danger"></i>Lamaran Masuk Terkini</h6>
                    <a href="{{ route('admin.applications.index') ?? '#' }}" class="btn btn-sm btn-link text-decoration-none small fw-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($data['recentApplications'] ?? [] as $app)
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3" style="border-bottom: 1px solid var(--slate-50);">
                            <div>
                                <h6 class="mb-1 fw-bold text-dark" style="font-size: 0.9rem;">{{ $app->user->name ?? 'Pelamar' }}</h6>
                                <small class="text-muted">Untuk: <span class="fw-medium text-dark">{{ Str::limit($app->job->title ?? 'Pekerjaan Dihapus', 30) }}</span></small>
                            </div>
                            <div class="text-end">
                                <span class="status-pill 
                                    bg-{{ match($app->status) {
                                        'pending' => 'warning',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        default => 'info'
                                    } }} bg-opacity-10 
                                    text-{{ match($app->status) {
                                        'pending' => 'warning',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        default => 'info'
                                    } }} mb-1 d-inline-block">
                                    {{ match($app->status) {
                                        'pending' => 'Menunggu',
                                        'accepted' => 'Diterima',
                                        'rejected' => 'Ditolak',
                                        default => ucfirst($app->status)
                                    } }}
                                </span>
                                <div class="small text-muted">{{ $app->created_at->diffForHumans() }}</div>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center text-muted py-5">Belum ada lamaran masuk.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection