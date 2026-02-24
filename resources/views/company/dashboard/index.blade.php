@extends('layouts.company')

@section('title', 'Dashboard Perusahaan')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Lowongan</h6>
                        <h2 class="mt-2 mb-0">{{ $data['totalJobs'] }}</h2>
                    </div>
                     <i class="fas fa-briefcase fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Lowongan Aktif</h6>
                        <h2 class="mt-2 mb-0">{{ $data['activeJobs'] }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Lamaran</h6>
                        <h2 class="mt-2 mb-0">{{ $data['totalApplications'] }}</h2>
                    </div>
                    <i class="fas fa-file-alt fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Menunggu Peninjauan</h6>
                        <h2 class="mt-2 mb-0">{{ $data['pendingApplications'] }}</h2>
                    </div>
                     <i class="fas fa-clock fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Lamaran Terbaru</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Kandidat</th>
                                <th>Lowongan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data['recentApplications'] as $app)
                            <tr>
                                <td>{{ $app->user->name }}</td>
                                <td>{{ Str::limit($app->job->title, 20) }}</td>
                                <td>{{ $app->created_at->format('d M') }}</td>
                                <td>
                                    <span class="badge bg-{{ match($app->status) {
                                        'pending' => 'warning',
                                        'shortlisted' => 'info',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        default => 'secondary'
                                    } }}">
                                        {{ match($app->status) {
                                            'pending' => 'Menunggu',
                                            'shortlisted' => 'Terpilih',
                                            'accepted' => 'Diterima',
                                            'rejected' => 'Ditolak',
                                            default => ucfirst($app->status)
                                        } }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada lamaran terbaru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('company.applications.index') }}" class="text-decoration-none">Lihat Semua Lamaran</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Lowongan Terbaru</h5>
            </div>
             <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Pelamar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                         <tbody>
                            @forelse($data['recentJobs'] as $job)
                            <tr>
                                <td>{{ Str::limit($job->title, 25) }}</td>
                                <td>
                                     <span class="badge bg-{{ $job->status === 'published' ? 'success' : 'secondary' }}">
                                        {{ $job->status === 'published' ? 'Tayang' : ($job->status === 'closed' ? 'Ditutup' : 'Draft') }}
                                    </span>
                                </td>
                                <td>{{ $job->applications_count }}</td>
                                <td>
                                    <a href="{{ route('company.jobs.show', $job->id) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                                </td>
                            </tr>
                             @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada lowongan yang dipasang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                 <a href="{{ route('company.jobs.index') }}" class="text-decoration-none">Kelola Semua Lowongan</a>
            </div>
        </div>
    </div>
</div>
@endsection
