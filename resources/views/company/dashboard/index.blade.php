@extends('layouts.company')

@section('title', 'Dashboard Perusahaan')

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

<div class="mb-4">
    <h2 class="fw-bold mb-1" style="color: var(--text-heading); letter-spacing: -0.02em;">Ringkasan HerbaTech</h2>
    <p class="text-muted small">Selamat datang kembali! Berikut adalah statistik aktivitas rekrutmen Anda.</p>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-box bg-soft-primary"><i class="fas fa-briefcase"></i></div>
                <span class="text-muted small fw-bold">TOTAL</span>
            </div>
            <h6 class="text-muted mb-1 small fw-medium">Total Lowongan</h6>
            <h3 class="fw-bold mb-0" style="color: var(--text-heading);">{{ $data['totalJobs'] }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-box bg-soft-success"><i class="fas fa-check-circle"></i></div>
                <span class="text-muted small fw-bold">AKTIF</span>
            </div>
            <h6 class="text-muted mb-1 small fw-medium">Lowongan Tayang</h6>
            <h3 class="fw-bold mb-0" style="color: var(--text-heading);">{{ $data['activeJobs'] }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-box bg-soft-info"><i class="fas fa-file-alt"></i></div>
                <span class="text-muted small fw-bold">KANDIDAT</span>
            </div>
            <h6 class="text-muted mb-1 small fw-medium">Total Lamaran</h6>
            <h3 class="fw-bold mb-0" style="color: var(--text-heading);">{{ $data['totalApplications'] }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="icon-box bg-soft-warning"><i class="fas fa-clock"></i></div>
                <span class="text-muted small fw-bold">REVIEW</span>
            </div>
            <h6 class="text-muted mb-1 small fw-medium">Perlu Ditinjau</h6>
            <h3 class="fw-bold mb-0" style="color: var(--text-heading);">{{ $data['pendingApplications'] }}</h3>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="dashboard-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color: var(--text-heading);"><i class="fas fa-user-clock me-2 text-primary"></i>Lamaran Terbaru</h6>
                <a href="{{ route('company.applications.index') }}" class="btn btn-sm btn-link text-decoration-none small fw-bold">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kandidat</th>
                            <th>Lowongan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['recentApplications'] as $app)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ $app->user->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $app->created_at->format('d M Y') }}</div>
                            </td>
                            <td>
                                <span class="text-muted" style="font-size: 0.85rem;">{{ Str::limit($app->job->title, 25) }}</span>
                            </td>
                            <td>
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
                                    } }}">
                                    {{ match($app->status) {
                                        'pending' => 'Menunggu',
                                        'accepted' => 'Diterima',
                                        'rejected' => 'Ditolak',
                                        default => ucfirst($app->status)
                                    } }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">Belum ada lamaran masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="dashboard-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color: var(--text-heading);"><i class="fas fa-bullhorn me-2 text-primary"></i>Lowongan Terakhir</h6>
                <a href="{{ route('company.jobs.index') }}" class="btn btn-sm btn-link text-decoration-none small fw-bold">Kelola</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Posisi</th>
                            <th class="text-center">Pelamar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['recentJobs'] as $job)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">{{ Str::limit($job->title, 30) }}</div>
                                @if($job->status === 'published')
                                    <span class="text-success small fw-medium"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> Tayang</span>
                                @else
                                    <span class="text-muted small fw-medium"><i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> Non-aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-primary border rounded-pill">{{ $job->applications_count }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center py-5 text-muted">Belum ada lowongan dibuat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection