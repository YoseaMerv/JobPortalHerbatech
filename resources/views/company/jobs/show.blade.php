@extends('layouts.company')

@section('title', 'Detail Lowongan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h3 class="card-title text-primary">{{ $job->title }}</h3>
                    <span class="badge bg-{{ $job->status === 'published' ? 'success' : 'warning' }} fs-6">
                        {{ $job->status === 'published' ? 'Tayang' : ($job->status === 'closed' ? 'Ditutup' : 'Draft') }}
                    </span>
                </div>

                <div class="text-muted mb-4">
                    <i class="fas fa-map-marker-alt me-1"></i> {{ $job->location->name }} ({{ $job->is_remote ? 'Remote' : 'Di lokasi' }})
                    <span class="mx-2">•</span>
                    <i class="fas fa-briefcase me-1"></i> {{ match($job->job_type) {
                                'full_time' => 'Penuh Waktu',
                                'part_time' => 'Paruh Waktu',
                                'contract' => 'Kontrak',
                                'freelance' => 'Freelance',
                                'internship' => 'Magang',
                                default => ucfirst(str_replace('_', ' ', $job->job_type))
                            } }}
                    <span class="mx-2">•</span>
                    <i class="fas fa-money-bill me-1"></i> Rp {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                </div>

                <h5>Deskripsi</h5>
                <p>{!! nl2br(e($job->description)) !!}</p>

                <h5>Persyaratan</h5>
                <p>{!! nl2br(e($job->requirements)) !!}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Statistik Lowongan</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Dilihat</span>
                    <span class="fw-bold">{{ $job->views }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Lamaran</span>
                     <a href="{{ route('company.applications.index', ['job_id' => $job->id]) }}" class="text-decoration-none fw-bold">{{ $job->applications_count }}</a>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Dipasang Pada</span>
                    <span class="fw-bold">{{ $job->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('company.jobs.edit', $job->id) }}" class="btn btn-outline-primary">Ubah Lowongan</a>
            
            @if($job->status === 'published')
            <form action="{{ route('company.jobs.close', $job->id) }}" method="POST" class="d-grid">
                @csrf
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menutup lowongan ini? Lowongan tidak akan lagi terlihat oleh pencari kerja.')">Tutup Lowongan</button>
            </form>
            @elseif($job->status === 'draft' || $job->status === 'closed')
            <form action="{{ route('company.jobs.publish', $job->id) }}" method="POST" class="d-grid">
                @csrf
                <button type="submit" class="btn btn-success">Tayangkan Lowongan</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
