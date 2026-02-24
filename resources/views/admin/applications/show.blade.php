@extends('layouts.admin')

@section('title', 'Detail Lamaran')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.applications.index') }}">Lamaran</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="row">
    <!-- Candidate Info -->
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{ $application->user->seekerProfile->profile_picture ? asset('storage/' . $application->user->seekerProfile->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($application->user->name) }}"
                         alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ $application->user->name }}</h3>
                <p class="text-muted text-center">{{ $application->user->email }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Melamar Sebagai</b> <a class="float-right">{{ $application->job->title }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Melamar Pada</b> <a class="float-right">{{ $application->created_at->format('d M Y, H:i') }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Status Saat Ini</b> 
                        <span class="float-right badge badge-{{ match($application->status) {
                            'pending' => 'warning',
                            'reviewed' => 'info',
                            'shortlisted' => 'primary',
                            'interview' => 'purple',
                            'accepted' => 'success',
                            'rejected' => 'danger',
                            default => 'secondary'
                        } }}">
                            {{ match($application->status) {
                                'pending' => 'Menunggu',
                                'reviewed' => 'Ditinjau',
                                'shortlisted' => 'Terpilih',
                                'interview' => 'Wawancara',
                                'accepted' => 'Diterima',
                                'rejected' => 'Ditolak',
                                default => ucfirst($application->status)
                            } }}
                        </span>
                    </li>
                </ul>

                <a href="{{ route('admin.applications.download-cv', $application->id) }}" class="btn btn-primary btn-block">
                    <i class="fas fa-download mr-1"></i> Unduh CV
                </a>
            </div>
        </div>

        <!-- Workflow Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kelola Status</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.applications.update-status', $application->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Ubah Status</label>
                        <select name="status" class="form-control">
                            <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Ditinjau</option>
                            <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Terpilih</option>
                            <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>Wawancara</option>
                            <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Perbarui Status</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Application Content -->
    <div class="col-md-8">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tabs-info-tab" data-bs-toggle="pill" data-bs-target="#tabs-info" href="#" role="tab">Informasi Lamaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-profile-tab" data-bs-toggle="pill" data-bs-target="#tabs-profile" href="#" role="tab">Profil Lengkap Pelamar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-job-tab" data-bs-toggle="pill" data-bs-target="#tabs-job" href="#" role="tab">Detail Pekerjaan</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <!-- Tab: Application Info -->
                    <div class="tab-pane fade show active" id="tabs-info" role="tabpanel">
                        <strong><i class="fas fa-file-alt mr-1"></i> Surat Lamaran (Cover Letter)</strong>
                        <p class="text-muted mt-2 p-3 bg-light rounded">
                            {!! nl2br(e($application->cover_letter ?? 'Tidak ada surat lamaran.')) !!}
                        </p>
                        
                        <hr>
                        
                        <strong><i class="fas fa-sticky-note mr-1"></i> Catatan Internal Admin</strong>
                        <p class="text-muted mt-2">
                            {{ $application->notes ?? 'Belum ada catatan.' }}
                        </p>
                    </div>

                    <!-- Tab: Full Profile -->
                    <div class="tab-pane fade" id="tabs-profile" role="tabpanel">
                        @if($application->user->seekerProfile)
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="text-primary fw-bold mb-3"><i class="fas fa-id-card mr-1"></i> Tentang Pelamar</h6>
                                    <p class="text-muted">{{ $application->user->seekerProfile->bio ?? 'Tidak ada deskripsi profil.' }}</p>
                                    
                                    <div class="row mb-4">
                                        <div class="col-sm-6">
                                            <p class="mb-1 small fw-bold text-muted">Nomor Telepon</p>
                                            <p class="mb-0">{{ $application->user->seekerProfile->phone ?? '-' }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="mb-1 small fw-bold text-muted">Alamat</p>
                                            <p class="mb-0">{{ $application->user->seekerProfile->address ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <h6 class="text-primary fw-bold mb-3"><i class="fas fa-briefcase mr-1"></i> Pengalaman Kerja</h6>
                                    @forelse($application->user->seekerProfile->experiences ?? [] as $exp)
                                        <div class="mb-3 p-2 border-left border-success">
                                            <p class="mb-0 fw-bold">{{ $exp->job_title }}</p>
                                            <p class="mb-0 small text-dark">{{ $exp->company_name }} | {{ $exp->location }}</p>
                                            <p class="mb-0 smaller text-muted">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Sekarang' }}</p>
                                            @if($exp->description)
                                                <p class="mt-1 mb-0 small text-muted">{{ $exp->description }}</p>
                                            @endif
                                        </div>
                                    @empty
                                        <p class="text-muted small">Tidak ada data pengalaman.</p>
                                    @endforelse

                                    <h6 class="text-primary fw-bold mb-3 mt-4"><i class="fas fa-user-graduate mr-1"></i> Riwayat Pendidikan</h6>
                                    @forelse($application->user->seekerProfile->educations ?? [] as $edu)
                                        <div class="mb-3 p-2 border-left border-primary">
                                            <p class="mb-0 fw-bold">{{ $edu->degree }}</p>
                                            <p class="mb-0 small text-dark">{{ $edu->institution }}</p>
                                            <p class="mb-0 smaller text-muted">{{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} - {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Sekarang' }}</p>
                                        </div>
                                    @empty
                                        <p class="text-muted small">Tidak ada data pendidikan.</p>
                                    @endforelse

                                    <h6 class="text-primary fw-bold mb-3 mt-4"><i class="fas fa-tools mr-1"></i> Keahlian (Skills)</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @forelse($application->user->seekerProfile->skills ?? [] as $skill)
                                            <span class="badge badge-info mr-1">{{ $skill->skill_name }} ({{ ucfirst($skill->proficiency_level) }})</span>
                                        @empty
                                            <span class="text-muted small">Tidak ada data keahlian.</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-user-slash fa-4x text-muted mb-3"></i>
                                <p class="text-muted lead">Lengkapi profil pelamar terlebih dahulu untuk melihat detail ini.</p>
                                <p class="text-muted small">(Pelamar belum mengisi data profil profesional mereka secara lengkap di sistem)</p>
                            </div>
                        @endif
                    </div>

                    <!-- Tab: Job Detail -->
                    <div class="tab-pane fade" id="tabs-job" role="tabpanel">
                        <strong><i class="fas fa-briefcase mr-1"></i> Deskripsi Pekerjaan</strong>
                        <p class="text-muted mt-2">
                            {{ Str::limit($application->job->description, 500) }}
                        </p>
                        <a href="{{ route('public.jobs.show', $application->job->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">Lihat Detail Loker Lengkap</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
