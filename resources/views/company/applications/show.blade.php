@extends('layouts.company')

@section('title', 'Detail Lamaran')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ $application->user->name }}" class="rounded-circle img-thumbnail" width="100" alt="User">
                </div>
                <h5 class="mb-1">{{ $application->user->name }}</h5>
                <p class="text-muted mb-3">{{ $application->user->email }}</p>
                
                @if($application->cv_path)
                    <div class="d-grid gap-2">
                        <a href="{{ route('company.applications.download-cv', $application->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i> Unduh Resume
                        </a>
                    </div>
                @endif
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Melamar Sebagai</span>
                    <span class="fw-bold">{{ $application->job->title }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Tanggal Melamar</span>
                    <span>{{ $application->created_at->format('d M Y') }}</span>
                </li>
            </ul>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Perbarui Status</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('company.applications.update-status', $application->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Saat Ini</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Terpilih</option>
                            <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Perbarui Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <ul class="nav nav-tabs card-header-tabs" id="applicationTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">Informasi Lamaran</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button">Profil Pelamar</button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="applicationTabsContent">
                    <!-- Tab: Info Lamaran -->
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-file-alt text-primary me-2"></i> Surat Lamaran (Cover Letter)</h6>
                        <div class="p-3 bg-light rounded text-muted mb-4" style="white-space: pre-wrap;">{{ $application->cover_letter ?? 'Tidak ada surat lamaran yang disertakan.' }}</div>
                        
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-info-circle text-primary me-2"></i> Detail Lowongan</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <p class="mb-1 small text-muted">Posisi</p>
                                <p class="mb-0 fw-bold">{{ $application->job->title }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-1 small text-muted">Tipe Pekerjaan</p>
                                <p class="mb-0 fw-bold badge bg-light text-primary border border-primary-subtle">{{ ucfirst($application->job->job_type) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Profil Pelamar -->
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        @if($application->user->seekerProfile)
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-id-card text-primary me-2"></i> Tentang Pelamar</h6>
                            <p class="text-muted mb-4 small">{{ $application->user->seekerProfile->bio ?? 'Tidak ada bio.' }}</p>

                            <div class="row g-3 mb-4">
                                <div class="col-sm-6">
                                    <p class="mb-1 small text-muted">No. Telepon / WhatsApp</p>
                                    <p class="mb-0"><i class="fab fa-whatsapp text-success me-1"></i> {{ $application->user->seekerProfile->phone ?? '-' }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p class="mb-1 small text-muted">Alamat / Lokasi</p>
                                    <p class="mb-0"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $application->user->seekerProfile->address ?? '-' }}</p>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-briefcase text-primary me-2"></i> Pengalaman Kerja</h6>
                            @forelse($application->user->seekerProfile->experiences ?? [] as $exp)
                                <div class="mb-3 ps-3 border-start border-3 border-success">
                                    <p class="mb-1 fw-bold text-dark">{{ $exp->job_title }}</p>
                                    <p class="mb-1 small text-muted fw-medium">{{ $exp->company_name }} | {{ $exp->location }}</p>
                                    <p class="mb-1 smaller text-muted">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Sekarang' }}</p>
                                    @if($exp->description)
                                        <p class="mt-2 mb-0 small text-muted">{{ $exp->description }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="small text-muted italic">Tidak ada rincian pengalaman kerja.</p>
                            @endforelse

                            <h6 class="fw-bold text-dark mb-3 mt-4"><i class="fas fa-user-graduate text-primary me-2"></i> Riwayat Pendidikan</h6>
                            @forelse($application->user->seekerProfile->educations ?? [] as $edu)
                                <div class="mb-3 ps-3 border-start border-3 border-primary">
                                    <p class="mb-1 fw-bold text-dark">{{ $edu->degree }}</p>
                                    <p class="mb-1 small text-muted fw-medium">{{ $edu->institution }}</p>
                                    <p class="mb-1 smaller text-muted">{{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} - {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Sekarang' }}</p>
                                </div>
                            @empty
                                <p class="small text-muted italic">Tidak ada rincian riwayat pendidikan.</p>
                            @endforelse

                            <h6 class="fw-bold text-dark mb-3 mt-4"><i class="fas fa-tools text-primary me-2"></i> Keahlian (Skills)</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($application->user->seekerProfile->skills ?? [] as $skill)
                                    <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2 fw-normal">
                                        {{ $skill->skill_name }} ({{ ucfirst($skill->proficiency_level) }})
                                    </span>
                                @empty
                                    <p class="small text-muted italic">Tidak ada daftar keahlian.</p>
                                @endforelse
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Profil pelamar belum dilengkapi.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
