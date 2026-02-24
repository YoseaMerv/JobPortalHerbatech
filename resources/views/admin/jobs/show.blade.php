@extends('layouts.admin')

@section('title', 'Detail Lowongan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Lowongan</a></li>
    <li class="breadcrumb-item active">{{ $job->title }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Lowongan</h3>
                <div class="card-tools">
                    <span class="badge badge-{{ $job->status === 'published' ? 'success' : 'warning' }}">
                        {{ $job->status === 'published' ? 'Tayang' : ($job->status === 'closed' ? 'Ditutup' : 'Draft') }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <h4>{{ $job->title }}</h4>
                <p class="text-muted">Dipasang oleh {{ $job->company->company_name }} • {{ $job->created_at->diffForHumans() }}</p>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <strong>Kategori:</strong> {{ $job->category->name }}
                    </div>
                    <div class="col-md-4">
                        <strong>Tipe:</strong> {{ match($job->job_type) {
                            'full_time' => 'Penuh Waktu',
                            'part_time' => 'Paruh Waktu',
                            'contract' => 'Kontrak',
                            'freelance' => 'Freelance',
                            'internship' => 'Magang',
                            default => ucfirst(str_replace('_', ' ', $job->job_type))
                        } }}
                    </div>
                    <div class="col-md-4">
                        <strong>Gaji:</strong> {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                    </div>
                </div>

                <hr>
                <h5>Deskripsi</h5>
                <p>{!! nl2br(e($job->description)) !!}</p>

                <h5>Persyaratan</h5>
                <p>{!! nl2br(e($job->requirements)) !!}</p>

                <h5>Tanggung Jawab</h5>
                <p>{!! nl2br(e($job->responsibilities)) !!}</p>
            </div>
            <div class="card-footer">
                @if($job->status === 'pending')
                    <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Setujui Lowongan</button>
                    </form>
                    <form action="{{ route('admin.jobs.reject', $job->id) }}" method="POST" class="d-inline ml-2">
                        @csrf
                        <button type="submit" class="btn btn-danger">Tolak Lowongan</button>
                    </form>
                @endif
                
                @if($job->status === 'published')
                     <form action="{{ route('admin.jobs.reject', $job->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning">Tangguhkan Lowongan</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $job->applications()->count() }}</h3>
                <p>Lamaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bagikan Lowongan</h3>
            </div>
            <div class="card-body text-center">
                <p class="text-muted small">Salin tautan publik untuk lowongan ini:</p>
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-outline-secondary rounded-circle" onclick="copyToClipboard('{{ route('public.jobs.show', $job->id) }}')" title="Salin Tautan">
                        <i class="fas fa-link"></i>
                    </button>
                    <a href="https://wa.me/?text={{ urlencode('Lowongan Kerja ' . $job->title . ' di ' . $job->company->company_name . ': ' . route('public.jobs.show', $job->id)) }}" target="_blank" class="btn btn-outline-success rounded-circle" title="Bagikan ke WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
