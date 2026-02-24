@extends('layouts.seeker')

@section('content')
<div class="row mb-5">
    <div class="col-12">
        <div class="card bg-white border-0 shadow-sm p-4">
            <h4 class="mb-3">Temukan Pekerjaan Impian Anda</h4>
            <form action="{{ route('seeker.jobs.index') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="keyword" placeholder="Judul pekerjaan atau kata kunci" value="{{ request('keyword') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="location">
                            <option value="">Semua Lokasi</option>
                            @foreach(\App\Models\JobLocation::where('is_active', true)->get() as $location)
                                <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                         <select class="form-select" name="category">
                            <option value="">Semua Kategori</option>
                            @foreach(\App\Models\JobCategory::where('is_active', true)->get() as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    @forelse($jobs as $job)
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm job-card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    @if($job->is_featured)
                        <span class="badge bg-warning text-dark align-self-start">Unggulan</span>
                    @endif
                </div>
                <h5 class="card-title text-truncate" title="{{ $job->title }}">{{ $job->title }}</h5>
                
                <div class="mb-3 text-muted small">
                    <div class="mb-1"><i class="fas fa-map-marker-alt me-2 text-primary"></i> {{ $job->location->name }}</div>
                    <div class="mb-1"><i class="fas fa-briefcase me-2 text-primary"></i> {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}</div>
                    <div><i class="fas fa-money-bill me-2 text-success"></i> Rp {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}</div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">{{ $job->created_at->diffForHumans() }}</small>
                    <a href="{{ route('seeker.jobs.show', $job->id) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <img src="https://illustrations.popsy.co/gray/crashed-error.svg" alt="No jobs" width="200" class="mb-3">
        <h4>Tidak ada lowongan ditemukan</h4>
        <p class="text-muted">Coba sesuaikan kriteria pencarian Anda atau periksa kembali nanti.</p>
        <a href="{{ route('seeker.jobs.index') }}" class="btn btn-primary">Hapus Filter</a>
    </div>
    @endforelse
</div>

<div class="row mt-4">
    <div class="col-12">
        {{ $jobs->links() }}
    </div>
</div>
@endsection
