@extends('layouts.seeker')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
        <h4 class="fw-bold mb-3">Cari Lowongan Pekerjaan</h4>
        <form action="{{ route('seeker.jobs.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="input-group bg-light rounded-3 p-1">
                        <span class="input-group-text border-0 bg-transparent text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="keyword" class="form-control border-0 bg-transparent shadow-none fs-7" placeholder="Judul pekerjaan atau perusahaan..." value="{{ request('keyword') }}">
                    </div>
                </div>
                {{-- Bagian Lokasi --}}
                <div class="col-md-3">
                    <select name="location" class="form-select border-0 bg-light rounded-3 py-2 fs-7 shadow-none">
                        <option value="">Semua Lokasi</option>
                        {{-- Ubah \App\Models\Location menjadi \App\Models\JobLocation --}}
                        @foreach(\App\Models\JobLocation::all() as $loc)
                        <option value="{{ $loc->id }}" {{ request('location') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="category" class="form-select border-0 bg-light rounded-3 py-2 fs-7 shadow-none">
                        <option value="">Semua Kategori</option>
                        {{-- Ubah \App\Models\Category menjadi \App\Models\JobCategory --}}
                        @foreach(\App\Models\JobCategory::all() as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 shadow-sm"><i class="fas fa-filter"></i></button>
                </div>
            </div>
        </form>
    </div>

    <div class="row g-4">
        @forelse($jobs as $job)
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 transition-all hover-shadow">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="company-logo bg-light rounded-3 p-2 text-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-building text-muted opacity-50 fa-lg mt-2"></i>
                        </div>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 small fw-bold">
                            {{ ucfirst($job->job_type) }}
                        </span>
                    </div>

                    <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $job->title }}</h6>
                    <p class="text-primary small fw-bold mb-3">{{ $job->company->name ?? 'Perusahaan' }}</p>

                    <div class="text-muted fs-8 mb-4">
                        <div class="mb-1"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> {{ $job->location->name ?? 'Lokasi tidak diatur' }}</div>
                        <div><i class="fas fa-money-bill-wave me-2 text-success"></i> Rp {{ number_format($job->salary_min, 0, ',', '.') }} - {{ number_format($job->salary_max, 0, ',', '.') }}</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                        <small class="text-muted fs-9">{{ $job->created_at->diffForHumans() }}</small>
                        <a href="{{ route('seeker.jobs.show', $job->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">Detail</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <img src="https://illustrations.popsy.co/gray/crashed-error.svg" alt="No jobs" width="180" class="mb-3 opacity-50">
            <h5 class="text-muted">Tidak ada lowongan ditemukan</h5>
            <p class="text-secondary small">Coba sesuaikan kriteria pencarian Anda.</p>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $jobs->appends(request()->query())->links() }}
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .fs-7 {
        font-size: 0.9rem;
    }

    .fs-8 {
        font-size: 0.8rem;
    }

    .fs-9 {
        font-size: 0.75rem;
    }

    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection