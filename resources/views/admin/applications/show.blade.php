@extends('layouts.admin')

@section('content')
<style>
    :root {
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --text-heading: #1e293b;
        --brand-primary: #0d6efd;
    }
    .detail-card {
        border-radius: 16px;
        border: 1px solid var(--slate-200);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }
    .nav-pills-custom .nav-link {
        border-radius: 10px;
        color: #64748b;
        font-weight: 600;
        padding: 12px 20px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .nav-pills-custom .nav-link.active {
        background-color: var(--brand-primary) !important;
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
    .info-box-custom {
        background: var(--slate-50);
        border: 1px solid var(--slate-100);
        border-radius: 12px;
        padding: 15px;
    }
    .pdf-container {
        width: 100%;
        height: 700px;
        border-radius: 12px;
        border: 1px solid var(--slate-200);
    }
    .status-badge-lg {
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
</style>

<div class="container-fluid pb-5">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.applications.index') }}" class="text-decoration-none text-muted fw-bold small">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="detail-card text-center p-4 mb-4">
                @php
                    $avatarUrl = $application->user->avatar 
                        ? asset('storage/' . $application->user->avatar) 
                        : 'https://ui-avatars.com/api/?name='.urlencode($application->user->name).'&background=f1f5f9&color=334155';
                @endphp
                <img src="{{ $avatarUrl }}" 
                     class="rounded-circle border border-4 border-white shadow-sm mb-3 object-fit-cover" width="110" height="110" alt="Avatar">
                
                <h5 class="fw-bold text-dark mb-1">{{ $application->user->name }}</h5>
                <p class="text-muted small mb-3">{{ $application->user->email }}</p>
                
                <div class="mb-4">
                    <span class="status-badge-lg 
                        bg-{{ match($application->status) {
                            'pending' => 'warning text-dark',
                            'accepted' => 'success text-white',
                            'rejected' => 'danger text-white',
                            default => 'info text-white'
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
                </div>

                <div class="text-left mt-4">
                    <div class="info-box-custom mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Melamar Posisi</small>
                        <span class="fw-bold text-primary">{{ $application->job->title }}</span>
                    </div>
                    <div class="info-box-custom">
                        <small class="text-muted d-block fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Waktu Melamar</small>
                        <span class="fw-medium text-dark">{{ $application->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="detail-card p-4">
                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-brain text-info mr-2"></i>Hasil Tes Kraepelin</h6>
                @if($application->kraepelin_id || $application->kraepelinTest)
                    <div class="bg-light p-3 rounded-lg border text-center">
                        <div class="text-success fw-bold mb-1"><i class="fas fa-check-circle mr-1"></i> Tes Selesai</div>
                        <a href="{{ route('admin.kraepelin.show', $application->kraepelin_id ?? ($application->kraepelinTest->id ?? 1)) }}" class="btn btn-sm btn-outline-primary w-100 fw-bold mt-2" style="border-radius: 10px;">
                            Lihat Detail Grafik
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-user-clock fa-2x text-muted mb-2 opacity-50"></i>
                        <p class="text-muted small mb-0">Kandidat belum mengerjakan tes Kraepelin.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-8">
            <div class="detail-card">
                <div class="card-header bg-white p-3 border-bottom-0">
                    <ul class="nav nav-pills nav-pills-custom" id="applicationTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active w-100 text-left" id="tab-cv-btn" data-bs-toggle="pill" data-bs-target="#tab-cv" type="button" role="tab">
                                <i class="fas fa-file-pdf mr-2"></i>Dokumen CV
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link w-100 text-left" id="tab-profile-btn" data-bs-toggle="pill" data-bs-target="#tab-profile" type="button" role="tab">
                                <i class="fas fa-user-circle mr-2"></i>Profil Profesional
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link w-100 text-left" id="tab-internal-btn" data-bs-toggle="pill" data-bs-target="#tab-internal" type="button" role="tab">
                                <i class="fas fa-clipboard-check mr-2"></i>Catatan & Pesan
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content" id="applicationTabContent">
                        <div class="tab-pane fade show active" id="tab-cv" role="tabpanel" aria-labelledby="tab-cv-btn">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-dark mb-0">Berkas Curriculum Vitae</h6>
                                @if($application->cv_path)
                                    <a href="{{ route('admin.applications.download-cv', $application->id) }}" class="btn btn-sm btn-primary px-3 rounded-pill fw-bold shadow-sm">
                                        <i class="fas fa-download mr-1"></i> Unduh PDF
                                    </a>
                                @endif
                            </div>
                            
                            @if($application->cv_path)
                                <iframe src="{{ asset('storage/' . $application->cv_path) }}#toolbar=0" class="pdf-container"></iframe>
                            @else
                                <div class="alert alert-warning border-0 rounded-lg">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> File CV tidak ditemukan.
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="tab-profile" role="tabpanel" aria-labelledby="tab-profile-btn">
                            @php $profile = $application->user->seekerProfile; @endphp
                            @if($profile)
                                <h6 class="fw-bold text-primary mb-3">Ringkasan Profesional</h6>
                                <p class="text-muted bg-light p-3 rounded" style="line-height: 1.6;">{{ $profile->summary ?? 'Pelamar tidak mencantumkan ringkasan.' }}</p>

                                <div class="row mt-4">
                                    <div class="col-md-6 mb-4">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-briefcase text-success mr-2"></i>Pengalaman Kerja</h6>
                                        @forelse($profile->experiences ?? [] as $exp)
                                            <div class="mb-3 pl-3 border-left border-3" style="border-left-color: #10b981 !important;">
                                                <div class="fw-bold text-dark">{{ $exp->job_title }}</div>
                                                <small class="text-primary fw-medium">{{ $exp->company_name }}</small><br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Sekarang' }}</small>
                                            </div>
                                        @empty
                                            <p class="text-muted small italic">Tidak ada data pengalaman.</p>
                                        @endforelse
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-graduation-cap text-primary mr-2"></i>Pendidikan</h6>
                                        @forelse($profile->educations ?? [] as $edu)
                                            <div class="mb-3 pl-3 border-left border-3" style="border-left-color: #3b82f6 !important;">
                                                <div class="fw-bold text-dark">{{ $edu->institution }}</div>
                                                <small class="text-dark">{{ $edu->degree }} {{ $edu->field_of_study ? '- ' . $edu->field_of_study : '' }}</small><br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($edu->start_date)->format('Y') }} - {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('Y') : 'Sekarang' }}</small>
                                            </div>
                                        @empty
                                            <p class="text-muted small italic">Tidak ada data pendidikan.</p>
                                        @endforelse
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-user-slash fa-3x text-muted mb-3 opacity-50"></i>
                                    <p class="text-muted">Profil profesional belum dilengkapi oleh kandidat.</p>
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="tab-internal" role="tabpanel" aria-labelledby="tab-internal-btn">
                            <div class="mb-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-envelope-open-text text-warning mr-2"></i>Surat Lamaran (Cover Letter)</h6>
                                <div class="bg-light p-4 rounded-lg border shadow-sm" style="white-space: pre-line; color: #475569; min-height: 100px;">
                                    {{ $application->cover_letter ?? 'Kandidat tidak melampirkan pesan atau surat lamaran.' }}
                                </div>
                            </div>

                            <div>
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-user-shield text-danger mr-2"></i>Catatan Internal Admin / HR</h6>
                                <div class="p-4 rounded-lg border" style="background-color: #f0f7ff; border-color: #cfe2ff !important;">
                                    @if($application->notes)
                                        <p class="mb-0 text-primary fw-medium" style="font-size: 1rem;">{{ $application->notes }}</p>
                                    @else
                                        <p class="mb-0 text-muted italic">Belum ada catatan evaluasi untuk lamaran ini. Anda bisa menambahkannya melalui menu "Ubah Status".</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection