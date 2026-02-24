@extends('layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Stats Cards -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $data['totalSeekers'] }}</h3>
                    <p>Total Kandidat</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                    Info selengkapnya <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <!-- Total Companies Removed -->
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $data['totalJobs'] }}</h3>
                    <p>Total Lowongan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <a href="{{ route('admin.jobs.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $data['totalApplications'] }}</h3>
                    <p>Total Lamaran</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <a href="{{ route('admin' . (request()->routeIs('*.jobs.*') ? '.jobs.index' : '.applications.index')) }}" class="small-box-footer">
                    Info selengkapnya <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Verifications -->
        <!-- Pending Verifications Removed -->

        <!-- Recent Activities -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aktivitas Terkini</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-user-plus text-success mr-2"></i>
                            <strong>{{ $data['newUsersCount'] ?? 0 }} pengguna baru</strong> mendaftar hari ini
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-briefcase text-info mr-2"></i>
                            <strong>{{ $data['newJobsCount'] ?? 0 }} lowongan baru</strong> dipasang hari ini
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-file-alt text-warning mr-2"></i>
                            <strong>{{ $data['newAppsCount'] ?? 0 }} lamaran baru</strong> diterima
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Users -->
        <!-- Recent Users Removed -->

        <!-- Recent Jobs -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lowongan Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Dipasang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['recentJobs'] as $job)
                            <tr>
                                <td>{{ Str::limit($job->title, 20) }}</td>
                                <td>
                                    <span class="badge badge-{{ $job->status === 'published' ? 'success' : 'warning' }}">
                                        {{ $job->status === 'published' ? 'Tayang' : ($job->status === 'closed' ? 'Ditutup' : 'Draft') }}
                                    </span>
                                </td>
                                <td>{{ $job->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection