@extends('layouts.company')

@section('title', 'Review Lamaran: ' . $application->user->name)

@section('content')
<style>
    :root {
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --text-main: #334155; 
        --text-heading: #1e293b;
        --brand-indigo: #4338ca; 
    }

    .review-card { 
        border-radius: 16px; 
        border: 1px solid var(--slate-200); 
        background: #fff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .profile-header-card {
        background: linear-gradient(to bottom, var(--brand-indigo), #3730a3);
        border-radius: 16px 16px 0 0;
        padding: 30px 20px;
    }

    .nav-tabs-custom { border-bottom: 2px solid var(--slate-100); gap: 20px; }
    .nav-tabs-custom .nav-link {
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        padding: 12px 0;
        position: relative;
        background: transparent;
    }
    .nav-tabs-custom .nav-link.active {
        color: var(--brand-indigo);
        background: transparent;
    }
    .nav-tabs-custom .nav-link.active::after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--brand-indigo);
    }

    .timeline-item {
        padding-left: 24px;
        border-left: 2px solid var(--slate-100);
        position: relative;
        margin-bottom: 25px;
    }
    .timeline-item::before {
        content: "";
        position: absolute;
        left: -7px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--brand-indigo);
        border: 2px solid white;
    }

    .info-label { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
    .info-value { font-size: 0.95rem; font-weight: 600; color: var(--text-heading); }

    /* Kraepelin Specific Styles */
    .kraepelin-stat-card {
        background: var(--slate-50);
        border-radius: 12px;
        padding: 15px;
        border: 1px solid var(--slate-200);
        text-align: center;
    }
    .kraepelin-badge {
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 700;
        text-transform: uppercase;
    }
</style>

<div class="mb-4">
    <a href="{{ route('company.applications.index') }}" class="text-decoration-none text-muted small fw-bold">
        <i class="fas fa-chevron-left me-1"></i> KEMBALI KE DAFTAR PELAMAR
    </a>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card review-card mb-4 overflow-hidden">
            <div class="profile-header-card text-center text-white">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($application->user->name) }}&background=fff&color=4338ca" 
                     class="rounded-circle border border-4 border-white border-opacity-25 mb-3 shadow-sm" 
                     width="90" alt="Candidate">
                <h5 class="fw-bold mb-1">{{ $application->user->name }}</h5>
                <p class="small opacity-75 mb-0">{{ $application->user->email }}</p>
            </div>
            <div class="card-body p-4">
                @if($application->kraepelinTest)
                    <div class="mb-4 p-3 rounded-3 {{ $application->kraepelinTest->completed_at ? 'bg-success bg-opacity-10' : 'bg-warning bg-opacity-10' }}">
                        <div class="info-label mb-1">Status Tes Kraepelin</div>
                        <div class="d-flex align-items-center">
                            @if($application->kraepelinTest->completed_at)
                                <span class="text-success fw-bold small"><i class="fas fa-check-circle me-1"></i> Selesai Dikirim</span>
                            @else
                                <span class="text-warning fw-bold small"><i class="fas fa-clock me-1"></i> Sedang Berlangsung</span>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <div class="info-label mb-1">Posisi yang Dilamar</div>
                    <div class="info-value text-primary">{{ $application->job->title }}</div>
                </div>

                @if($application->cv_path)
                    <a href="{{ route('company.applications.download-cv', $application->id) }}" class="btn btn-outline-primary w-100 fw-bold py-2 mb-3" style="border-radius: 10px;">
                        <i class="fas fa-file-download me-2"></i> Download CV / Resume
                    </a>
                @endif
                
                <hr class="my-4" style="border-style: dashed;">

                <form action="{{ route('company.applications.update-status', $application->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label class="info-label mb-2">Update Progress Rekrutmen</label>
                    <select name="status" class="form-select mb-3" style="border-radius: 8px; font-weight: 600;">
                        <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Ditinjau (Reviewed)</option>
                        <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Terpilih (Shortlisted)</option>
                        <option value="test_invited" {{ $application->status == 'test_invited' ? 'selected' : '' }}>Undang Tes Kraepelin</option>
                        <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>Wawancara</option>
                        <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Terima (Accepted)</option>
                        <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 10px;">
                        Simpan Status Baru
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card review-card">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <ul class="nav nav-tabs nav-tabs-custom" id="reviewTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="cover-tab" data-bs-toggle="tab" data-bs-target="#cover" type="button">Surat Lamaran</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button">Detail Profil</button>
                    </li>
                    @if($application->kraepelinTest && $application->kraepelinTest->completed_at)
                    <li class="nav-item">
                        <button class="nav-link text-primary" id="kraepelin-tab" data-bs-toggle="tab" data-bs-target="#kraepelin" type="button">
                            <i class="fas fa-brain me-1"></i> Hasil Tes Kraepelin
                        </button>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="reviewTabsContent">
                    <div class="tab-pane fade show active" id="cover" role="tabpanel">
                        <h6 class="fw-bold mb-3" style="color: var(--text-heading);">Surat Lamaran (Cover Letter)</h6>
                        <div class="p-4 rounded-4" style="background: var(--slate-50); color: var(--text-main); line-height: 1.8; white-space: pre-wrap; font-size: 0.95rem;">
                            {{ $application->cover_letter ?? 'Kandidat tidak menyertakan pesan atau surat lamaran tertulis.' }}
                        </div>
                    </div>

                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        @if($application->user->seekerProfile)
                            <div class="mb-5">
                                <h6 class="fw-bold mb-3" style="color: var(--text-heading);"><i class="far fa-user-circle me-2 text-primary"></i>Biografi Ringkas</h6>
                                <p class="text-muted" style="line-height: 1.6;">{{ $application->user->seekerProfile->bio ?? 'Belum ada deskripsi biografi.' }}</p>
                                <div class="row g-3 mt-1">
                                    <div class="col-sm-6">
                                        <div class="info-label">WhatsApp / Telepon</div>
                                        <div class="info-value"><i class="fab fa-whatsapp text-success me-1"></i> {{ $application->user->seekerProfile->phone ?? '-' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Domisili</div>
                                        <div class="info-value"><i class="fas fa-map-marker-alt text-danger opacity-75 me-1"></i> {{ $application->user->seekerProfile->address ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-4" style="color: var(--text-heading);"><i class="fas fa-briefcase me-2 text-primary"></i>Pengalaman Kerja</h6>
                                    @forelse($application->user->seekerProfile->experiences as $exp)
                                        <div class="timeline-item">
                                            <div class="fw-bold text-dark">{{ $exp->job_title }}</div>
                                            <div class="small fw-medium text-muted">{{ $exp->company_name }}</div>
                                            <div class="text-muted" style="font-size: 0.8rem;">
                                                {{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Sekarang' }}
                                            </div>
                                        </div>
                                    @empty
                                        <p class="small text-muted italic">Tidak mencantumkan pengalaman.</p>
                                    @endforelse
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-4" style="color: var(--text-heading);"><i class="fas fa-user-graduate me-2 text-primary"></i>Pendidikan</h6>
                                    @forelse($application->user->seekerProfile->educations as $edu)
                                        <div class="timeline-item">
                                            <div class="fw-bold text-dark">{{ $edu->degree }}</div>
                                            <div class="small fw-medium text-muted">{{ $edu->institution }}</div>
                                            <div class="text-muted" style="font-size: 0.8rem;">
                                                {{ \Carbon\Carbon::parse($edu->start_date)->format('M Y') }} - {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('M Y') : 'Sekarang' }}
                                            </div>
                                        </div>
                                    @empty
                                        <p class="small text-muted italic">Tidak mencantumkan riwayat pendidikan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($application->kraepelinTest && $application->kraepelinTest->completed_at)
                    <div class="tab-pane fade" id="kraepelin" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold mb-0" style="color: var(--text-heading);">Analisis Performa Kraepelin</h6>
                            <span class="badge bg-light text-dark border fw-bold px-3 py-2" style="border-radius: 8px;">
                                <i class="far fa-calendar-alt me-1 text-primary"></i> Selesai: {{ $application->kraepelinTest->completed_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="kraepelin-stat-card">
                                    <div class="info-label mb-2">Kecepatan (PANKER)</div>
                                    <div class="h3 fw-bold text-primary mb-1">{{ $application->kraepelinTest->total_answered }}</div>
                                    <div class="kraepelin-badge bg-primary bg-opacity-10 text-primary">Total Jawaban</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="kraepelin-stat-card">
                                    <div class="info-label mb-2">Ketelitian (TIANKER)</div>
                                    <div class="h3 fw-bold text-success mb-1">{{ $application->kraepelinTest->total_correct }}</div>
                                    <div class="kraepelin-badge bg-success bg-opacity-10 text-success">Jawaban Benar</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php
                                    $accuracy = $application->kraepelinTest->total_answered > 0 
                                        ? round(($application->kraepelinTest->total_correct / $application->kraepelinTest->total_answered) * 100, 1) 
                                        : 0;
                                @endphp
                                <div class="kraepelin-stat-card">
                                    <div class="info-label mb-2">Akurasi Kerja</div>
                                    <div class="h3 fw-bold text-indigo mb-1">{{ $accuracy }}%</div>
                                    <div class="kraepelin-badge bg-indigo bg-opacity-10 text-indigo">Persentase Benar</div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 rounded-4 border bg-light bg-opacity-50">
                            <h6 class="fw-bold mb-3" style="font-size: 0.9rem;"><i class="fas fa-info-circle me-2 text-info"></i>Interpretasi Singkat</h6>
                            <p class="small text-muted mb-0" style="line-height: 1.6;">
                                Hasil tes menunjukkan kandidat mampu menyelesaikan <strong>{{ $application->kraepelinTest->total_answered }}</strong> penjumlahan dalam total waktu yang disediakan. 
                                Dengan tingkat akurasi <strong>{{ $accuracy }}%</strong>, hal ini mengindikasikan kemampuan kandidat dalam menjaga fokus di bawah tekanan waktu (Time Pressure).
                            </p>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <button class="btn btn-sm btn-link text-decoration-none fw-bold" onclick="window.print()">
                                <i class="fas fa-print me-1"></i> Cetak Hasil Seleksi
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection