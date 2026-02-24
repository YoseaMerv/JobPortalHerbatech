@extends('layouts.seeker')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h3 class="mb-1">{{ $job->title }}</h3>
                    <div class="text-muted">
                        <i class="fas fa-briefcase me-1"></i> {{ ucfirst(str_replace('_', ' ', $job->job_type)) }}
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4 mb-2">
                        <div class="p-3 bg-light rounded text-center">
                            <i class="fas fa-map-marker-alt text-primary fa-lg mb-2"></i>
                            <div class="fw-bold">Lokasi</div>
                            <div class="small">{{ $job->location->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="p-3 bg-light rounded text-center">
                            <i class="fas fa-dollar-sign text-success fa-lg mb-2"></i>
                            <div class="fw-bold">Gaji</div>
                            <div class="small">{{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="p-3 bg-light rounded text-center">
                            <i class="fas fa-briefcase text-info fa-lg mb-2"></i>
                            <div class="fw-bold">Tipe</div>
                            <div class="small">{{ match($job->job_type) {
                                'full_time' => 'Penuh Waktu',
                                'part_time' => 'Paruh Waktu',
                                'contract' => 'Kontrak',
                                'freelance' => 'Freelance',
                                'internship' => 'Magang',
                                default => ucfirst(str_replace('_', ' ', $job->job_type))
                            } }}</div>
                        </div>
                    </div>
                </div>

                <h5 class="mb-3 border-bottom pb-2">Deskripsi</h5>
                <p class="mb-4">{!! nl2br(e($job->description)) !!}</p>

                <h5 class="mb-3 border-bottom pb-2">Persyaratan</h5>
                <p class="mb-4">{!! nl2br(e($job->requirements)) !!}</p>

                <h5 class="mb-3 border-bottom pb-2">Tanggung Jawab</h5>
                <p class="mb-4">{!! nl2br(e($job->responsibilities)) !!}</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Aksi Pekerjaan</h5>
                
                @if($hasApplied)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-1"></i> Anda telah melamar pekerjaan ini.
                    </div>
                @else
                    <form action="{{ route('seeker.jobs.apply', $job->id) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Surat Lamaran (Opsional)</label>
                            <textarea name="cover_letter" class="form-control" rows="3" placeholder="Beritahu perusahaan mengapa Anda cocok untuk posisi ini..."></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Lamar Sekarang</button>
                        </div>
                    </form>
                @endif

                <div class="d-grid">
                    @if($isSaved)
                        <form action="{{ route('seeker.jobs.unsave', $job->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-heart text-danger"></i> Hapus dari Simpanan
                            </button>
                        </form>
                    @else
                        <form action="{{ route('seeker.jobs.save', $job->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="far fa-heart"></i> Simpan Lowongan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="text-center">
                    <p class="small text-muted mb-2">Bagikan lowongan ini:</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-outline-primary rounded-circle"><i class="fab fa-facebook-f"></i></button>
                        <button class="btn btn-sm btn-outline-info rounded-circle"><i class="fab fa-twitter"></i></button>
                        <button class="btn btn-sm btn-outline-primary rounded-circle"><i class="fab fa-linkedin-in"></i></button>
                        <button class="btn btn-sm btn-outline-secondary rounded-circle" onclick="copyToClipboard('{{ route('public.jobs.show', $job->id) }}')" title="Salin Tautan"><i class="fas fa-link"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Tentang Kami</h5>
                <p class="small text-muted mb-3">{{ Str::limit($job->company->company_description, 150) }}</p>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2"><i class="fas fa-globe me-2 text-muted"></i> <a href="{{ $job->company->company_website }}" target="_blank">Website</a></li>
                    <li class="mb-2"><i class="fas fa-industry me-2 text-muted"></i> {{ $job->company->industry }}</li>
                    <li><i class="fas fa-users me-2 text-muted"></i> {{ $job->company->company_size }} Karyawan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
