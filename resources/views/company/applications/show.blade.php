@extends('layouts.company')

@section('title', 'Review Lamaran: ' . $application->user->name)

@section('content')

{{-- ================================================================ --}}
{{-- BLOK PHP WAJIB DI ATAS AGAR VARIABEL DIKENALI OLEH SELURUH HALAMAN --}}
{{-- ================================================================ --}}
@php
    // Persiapan Data Relasi Psikotes
    $psyResults = $application->psychologicalResults ?? collect();
    $discResult = $psyResults->filter(function($q) {
        return strtolower(trim($q->test_type)) === 'disc' && strtolower(trim($q->status)) === 'completed';
    })->first();

    $msdtResult = $psyResults->filter(function($q) {
        return strtolower(trim($q->test_type)) === 'msdt' && strtolower(trim($q->status)) === 'completed';
    })->first();

    $papiResult = $psyResults->filter(function($q) {
        return strtolower(trim($q->test_type)) === 'papi' && strtolower(trim($q->status)) === 'completed';
    })->first();
    $hasKraepelin = $application->kraepelinTest && $application->kraepelinTest->completed_at;
@endphp

<style>
    :root {
        --slate-50: #f8fafc; --slate-100: #f1f5f9; --slate-200: #e2e8f0;
        --text-main: #334155; --text-heading: #1e293b; --brand-indigo: #4338ca; 
    }
    .review-card { border-radius: 16px; border: 1px solid var(--slate-200); background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .profile-header-card { background: linear-gradient(to bottom, var(--brand-indigo), #3730a3); border-radius: 16px 16px 0 0; padding: 30px 20px; }
    
    /* Scrollable Tabs for multiple assessments */
    .nav-tabs-custom { border-bottom: 2px solid var(--slate-100); gap: 10px; flex-wrap: nowrap; overflow-x: auto; white-space: nowrap; padding-bottom: 2px; }
    .nav-tabs-custom::-webkit-scrollbar { height: 4px; }
    .nav-tabs-custom::-webkit-scrollbar-thumb { background: var(--slate-200); border-radius: 4px; }
    .nav-tabs-custom .nav-link { border: none; color: #64748b; font-weight: 600; padding: 12px 15px; position: relative; background: transparent; transition: all 0.3s; }
    .nav-tabs-custom .nav-link:hover { color: var(--brand-indigo); }
    .nav-tabs-custom .nav-link.active { color: var(--brand-indigo); }
    .nav-tabs-custom .nav-link.active::after { content: ""; position: absolute; bottom: -4px; left: 0; width: 100%; height: 2px; background: var(--brand-indigo); }
    
    .timeline-item { padding-left: 24px; border-left: 2px solid var(--slate-100); position: relative; margin-bottom: 25px; }
    .timeline-item::before { content: ""; position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: var(--brand-indigo); border: 2px solid white; }
    .info-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
    .info-value { font-size: 0.95rem; font-weight: 600; color: var(--text-heading); }
    
    /* Assessment Specific Styles */
    .kraepelin-card { border-radius: 16px; padding: 24px; background: #fff; border: 1px solid var(--slate-200); }
    .k-chart-container { position: relative; width: 100%; }
    .k-stat-box { padding: 12px 16px; border-radius: 10px; background: var(--slate-50); border: 1px solid var(--slate-100); }
    .cv-preview-container { border-radius: 12px; overflow: hidden; border: 1px solid var(--slate-200); background: #f1f5f9; min-height: 500px; }
    
    /* Badges */
    .psy-badge { width: 35px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; border-radius: 6px; }
</style>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('company.applications.index') }}" class="text-decoration-none text-muted small fw-bold">
        <i class="fas fa-arrow-left me-1"></i> KEMBALI KE DAFTAR PELAMAR
    </a>
    <div id="status-spinner" class="spinner-border spinner-border-sm text-primary d-none" role="status"></div>
</div>

<div class="row">
    {{-- ================= SIDEBAR: PROFIL KANDIDAT ================= --}}
    <div class="col-lg-4">
        <div class="card review-card mb-4 overflow-hidden shadow-sm">
            <div class="profile-header-card text-center text-white">
                <img src="{{ $application->user->avatar ? asset('storage/' . $application->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($application->user->name).'&background=fff&color=4338ca' }}" 
                     class="rounded-circle border border-4 border-white border-opacity-25 mb-3 shadow-sm" width="90" height="90" style="object-fit: cover;">
                <h5 class="fw-bold mb-1">{{ $application->user->name }}</h5>
                <p class="small opacity-75 mb-0">{{ $application->user->email }}</p>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <div class="info-label mb-1">Status Pendaftaran</div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">
                        <i class="fas fa-circle me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> {{ $application->status_label }}
                    </span>
                </div>

                <div class="mb-4">
                    <div class="info-label mb-2">Progres Psikotes</div>
                    <div class="d-flex gap-2">
                        <span class="psy-badge {{ $hasKraepelin ? 'bg-primary text-white' : 'bg-light text-muted border' }}" title="Kraepelin">KRA</span>
                        <span class="psy-badge {{ $discResult ? 'bg-success text-white' : 'bg-light text-muted border' }}" title="DISC">DSC</span>
                        <span class="psy-badge {{ $msdtResult ? 'bg-danger text-white' : 'bg-light text-muted border' }}" title="MSDT">MSD</span>
                        <span class="psy-badge {{ $papiResult ? 'bg-info text-white' : 'bg-light text-muted border' }}" title="PAPI Kostick">PAP</span>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="info-label mb-1">Posisi yang Dilamar</div>
                    <div class="info-value text-indigo">{{ $application->job->title }}</div>
                    <div class="extra-small text-muted mt-1"><i class="far fa-calendar-alt me-1"></i> Melamar pada {{ $application->created_at->format('d M Y') }}</div>
                </div>

                @if($application->cv_path)
                    <a href="{{ route('company.applications.download-cv', $application->id) }}" class="btn btn-outline-primary w-100 fw-bold py-2 mb-3 shadow-sm" style="border-radius: 10px;">
                        <i class="fas fa-file-download me-2"></i> Download CV / Resume
                    </a>
                @endif
                
                <hr class="my-4" style="border-style: dashed;">

                <div class="bg-light p-3 rounded-4 border">
                    <label class="info-label mb-2 text-dark">Update Progress Rekrutmen</label>
                    <select id="status-selector" class="form-select border-0 shadow-sm fw-bold" 
                            onchange="handleStatusChange(this, {{ $application->id }})" style="border-radius: 10px;">
                        @foreach(\App\Models\JobApplication::getAllStatuses() as $value => $label)
                            <option value="{{ $value }}" {{ $application->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= KONTEN UTAMA: TABS ================= --}}
    <div class="col-lg-8">
        <div class="card review-card shadow-sm">
            <div class="card-header bg-white border-0 p-3 pb-0">
                <ul class="nav nav-tabs nav-tabs-custom" id="reviewTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#cover">Surat Lamaran</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile">Detail Profil</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#kuesioner">Kuesioner</button></li>
                    
                    <li class="nav-item {{ $application->status !== 'interview' ? 'd-none' : '' }}" id="interview-tab-nav">
                        <button class="nav-link text-danger fw-bold" data-bs-toggle="tab" data-bs-target="#interview-pane">
                            <i class="fas fa-calendar-check me-1"></i> Wawancara
                        </button>
                    </li>

                    {{-- Dynamic Tabs for Psychological Assessments --}}
                    @if($hasKraepelin)
                        <li class="nav-item"><button class="nav-link text-primary fw-bold" id="kraepelin-tab" data-bs-toggle="tab" data-bs-target="#kraepelin"><i class="fas fa-calculator me-1"></i> Kraepelin</button></li>
                    @endif
                    @if($discResult)
                        <li class="nav-item"><button class="nav-link text-success fw-bold" id="disc-tab" data-bs-toggle="tab" data-bs-target="#disc"><i class="fas fa-shapes me-1"></i> DISC</button></li>
                    @endif
                    @if($msdtResult)
                        <li class="nav-item"><button class="nav-link text-danger fw-bold" data-bs-toggle="tab" data-bs-target="#msdt"><i class="fas fa-users-cog me-1"></i> MSDT</button></li>
                    @endif
                    @if($papiResult)
                        <li class="nav-item"><button class="nav-link text-info fw-bold" id="papi-tab" data-bs-toggle="tab" data-bs-target="#papi"><i class="fas fa-clipboard-check me-1"></i> PAPI Kostick</button></li>
                    @endif
                </ul>
            </div>
            
            <div class="card-body p-4">
                <div class="tab-content">
                    
                    {{-- TAB 1: COVER LETTER --}}
                    <div class="tab-pane fade show active" id="cover">
                        @if($application->cover_letter_path)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">Pratinjau Dokumen Lampiran</h6>
                                <a href="{{ route('company.applications.download-cover', $application->id) }}" class="btn btn-sm btn-outline-dark fw-bold px-3 rounded-pill shadow-sm"><i class="fas fa-download me-1"></i> Unduh Asli</a>
                            </div>
                            <div class="cv-preview-container shadow-sm">
                                <iframe src="{{ Storage::url($application->cover_letter_path) }}#toolbar=0" width="100%" height="100%" style="border: none; min-height: 500px;"></iframe>
                            </div>
                        @else
                            <div class="text-center py-5 border rounded-4 bg-light" style="border-style: dashed;">
                                <i class="fas fa-file-invoice fa-3x text-muted opacity-25 mb-3"></i>
                                <h6 class="fw-bold text-muted">Tidak Ada Surat Lamaran</h6>
                                <p class="small text-muted mb-0">Kandidat tidak mengunggah file tambahan.</p>
                            </div>
                        @endif
                    </div>

                    {{-- TAB 2: PROFILE --}}
                    <div class="tab-pane fade" id="profile">
                        <div class="mb-5 p-4 bg-light rounded-4 border border-slate-200">
                            <h6 class="fw-bold mb-2">Biografi Singkat</h6>
                            <p class="text-muted mb-0" style="line-height: 1.6;">{{ $application->user->seekerProfile->summary ?? 'Belum ada biografi yang ditulis kandidat.' }}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6 border-end pe-md-4">
                                <h6 class="fw-bold mb-4 text-indigo"><i class="fas fa-briefcase me-2"></i>Pengalaman Kerja</h6>
                                @forelse($application->user->seekerProfile->experiences ?? [] as $exp)
                                    <div class="timeline-item">
                                        <div class="fw-bold text-dark">{{ $exp->job_title }}</div>
                                        <div class="small fw-bold text-primary">{{ $exp->company_name }}</div>
                                        <div class="extra-small text-muted mt-1"><i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Sekarang' }}</div>
                                    </div>
                                @empty <p class="small text-muted italic">Belum ada pengalaman kerja yang diisi.</p> @endforelse
                            </div>
                            <div class="col-md-6 ps-md-4">
                                <h6 class="fw-bold mb-4 text-indigo"><i class="fas fa-graduation-cap me-2"></i>Riwayat Pendidikan</h6>
                                @forelse($application->user->seekerProfile->educations ?? [] as $edu)
                                    <div class="timeline-item">
                                        <div class="fw-bold text-dark">{{ $edu->degree }}</div>
                                        <div class="small fw-bold text-primary">{{ $edu->institution }}</div>
                                        <div class="extra-small text-muted mt-1"><i class="far fa-calendar-alt me-1"></i>Tahun Lulus: {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('Y') : 'Sekarang' }}</div>
                                    </div>
                                @empty <p class="small text-muted italic">Belum ada data pendidikan.</p> @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- TAB 3: KUESIONER --}}
                    <div class="tab-pane fade" id="kuesioner">
                        <div class="row g-3">
                            @php
                                $questions = [
                                    'q1' => 'Pernyataan Kejujuran', 'q2' => 'Ketersediaan Full-Time', 'q3' => 'Kesediaan Relokasi', 'q4' => 'Kendaraan Pribadi',
                                    'q5' => 'Ekspektasi Gaji', 'q6' => 'Skill Teknis (1-10)', 'q15' => 'Tanggal Mulai', 'q7' => 'Pencapaian Terbesar',
                                    'q13' => 'Motivasi Melamar', 'q14' => 'Visi Karier'
                                ];
                            @endphp
                            @foreach($questions as $key => $label)
                                @if(isset($application->answers[$key]))
                                    <div class="{{ in_array($key, ['q7','q13','q14']) ? 'col-12' : 'col-md-6' }}">
                                        <div class="p-3 border rounded-4 bg-white shadow-sm h-100">
                                            <div class="info-label mb-2" style="font-size: 0.65rem;">{{ $label }}</div>
                                            <div class="info-value text-dark" style="white-space: pre-line; font-size: 0.9rem;">
                                                @if($key === 'q5') <span class="text-success fw-bold">Rp {{ number_format($application->answers[$key], 0, ',', '.') }}</span>
                                                @elseif($key === 'q6') <span class="badge bg-indigo rounded-pill px-3 py-2">{{ $application->answers[$key] }} / 10</span>
                                                @else {{ $application->answers[$key] }} @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- TAB 4: WAWANCARA --}}
                    <div class="tab-pane fade" id="interview-pane">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h6 class="fw-bold mb-1">Manajemen Jadwal Wawancara</h6>
                                <p class="small text-muted mb-0">Data ini dikirimkan ke dashboard kandidat.</p>
                            </div>
                            <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" onclick="editInterview()"><i class="fas fa-edit me-1"></i> Atur Jadwal</button>
                        </div>
                        <div class="p-4 rounded-4 border bg-indigo bg-opacity-10">
                            <div class="info-value text-dark" id="text-notes-display" style="white-space: pre-line; line-height: 1.8;">
                                {{ $application->notes ?? 'Belum ada jadwal wawancara yang diatur.' }}
                            </div>
                        </div>
                    </div>

                    {{-- ================= TAB 5: KRAEPELIN ================= --}}
                    @if($hasKraepelin)
                    <div class="tab-pane fade" id="kraepelin">
                        @php
                            $test = $application->kraepelinTest;
                            $chartData = is_string($test->results_chart) ? json_decode($test->results_chart, true) : $test->results_chart;
                            $correct = $test->total_correct;
                            $error = $test->total_answered - $test->total_correct;
                            $skipped = max(0, $test->tianker - $error);
                            
                            $quarters = [];
                            if (is_array($chartData) && count($chartData) > 0) {
                                for ($i = 0; $i < 50; $i += 10) {
                                    $slice = array_slice($chartData, $i, 10);
                                    $quarters[] = count($slice) > 0 ? round(array_sum($slice) / count($slice), 1) : 0;
                                }
                            } else { $quarters = [0,0,0,0,0]; }

                            $accuracy = $test->total_answered > 0 ? round(($test->total_correct / $test->total_answered) * 100, 1) : 0;
                            $pankerPerc = min(($test->panker / 25) * 100, 100); 
                            $jankerPerc = max(100 - ($test->janker * 6), 0); 
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <div>
                                <h5 class="fw-bold mb-1">Executive Summary Kraepelin</h5>
                                <p class="small text-muted mb-0">Laporan komprehensif performa kognitif, stabilitas emosi, dan akurasi.</p>
                            </div>
                            <a href="{{ route('company.applications.kraepelin-pdf', $application->id) }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" target="_blank">
                                <i class="fas fa-file-pdf me-2"></i> Ekspor Laporan
                            </a>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <div class="kraepelin-card h-100 shadow-sm border-0 bg-light">
                                    <h6 class="info-label mb-3 text-center text-dark"><i class="fas fa-chart-pie me-2"></i>Distribusi Jawaban</h6>
                                    <div class="k-chart-container mx-auto" style="height: 180px; width: 180px;"><canvas id="donutAnswers"></canvas></div>
                                    <div class="mt-4 k-stat-box bg-white">
                                        <div class="d-flex justify-content-between small mb-2 border-bottom pb-1"><span class="text-muted">Jawaban Benar</span> <span class="fw-bold text-success">{{ $correct }}</span></div>
                                        <div class="d-flex justify-content-between small mb-2 border-bottom pb-1"><span class="text-muted">Salah Hitung</span> <span class="fw-bold text-danger">{{ $error }}</span></div>
                                        <div class="d-flex justify-content-between small"><span class="text-muted">Hole (Lompatan)</span> <span class="fw-bold text-warning">{{ $skipped }}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="kraepelin-card h-100 shadow-sm border-0">
                                    <h6 class="info-label mb-4 text-dark"><i class="fas fa-chart-bar me-2"></i>Analisis 4 Faktor Utama (P-T-J-G)</h6>
                                    <div class="row align-items-center">
                                        <div class="col-md-7"><div class="k-chart-container" style="height: 220px;"><canvas id="barPerformance"></canvas></div></div>
                                        <div class="col-md-5">
                                            <div class="analysis-section border-0 bg-transparent p-0 ps-md-3 border-start">
                                                <div class="mb-3"><div class="fw-bold extra-small text-primary mb-1">PK (PANKER) - Kecepatan</div><p class="extra-small text-muted mb-0">Rata-rata <b>{{ round($test->panker, 1) }}</b> baris per kolom.</p></div>
                                                <div class="mb-3"><div class="fw-bold extra-small text-danger mb-1">TK (TIANKER) - Ketelitian</div><p class="extra-small text-muted mb-0">Total <b>{{ $test->tianker }}</b> kesalahan.</p></div>
                                                <div class="mb-3"><div class="fw-bold extra-small text-warning mb-1">JK (JANKER) - Stabilitas</div><p class="extra-small text-muted mb-0">Rentang fluktuasi <b>{{ $test->janker }}</b>.</p></div>
                                                <div class="mb-0"><div class="fw-bold extra-small text-success mb-1">GK (GANKER) - Ketahanan</div><p class="extra-small text-muted mb-0">Tren <b>{{ $test->ganker >= 0 ? 'Positif' : 'Negatif' }}</b>.</p></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-4 text-indigo"><i class="fas fa-wave-square me-2"></i>Kurva Kerja (Work Rhythm Trend)</h6>
                                <div class="k-chart-container" style="height: 280px;"><canvas id="lineTrend"></canvas></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ================= TAB 6: DISC ================= --}}
                    @if($discResult)
                    <div class="tab-pane fade" id="disc" role="tabpanel">
                        @php 
                            // Asumsi struktur JSON database Anda
                            $discData = json_decode($discResult->result_data ?? '{}', true);
                            $d_score = $discData['D'] ?? rand(10, 40);
                            $i_score = $discData['I'] ?? rand(10, 40);
                            $s_score = $discData['S'] ?? rand(10, 40);
                            $c_score = $discData['C'] ?? rand(10, 40);
                            
                            $scores = ['D' => $d_score, 'I' => $i_score, 'S' => $s_score, 'C' => $c_score];
                            arsort($scores);
                            $dominantTrait = array_key_first($scores);
                        @endphp
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <div>
                                <h5 class="fw-bold mb-1">Evaluasi Kepribadian DISC</h5>
                                <p class="small text-muted mb-0">Pemetaan gaya komunikasi, respons terhadap tekanan, dan cara kerja.</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-7">
                                <div class="kraepelin-card shadow-sm border-0 h-100">
                                    <h6 class="info-label mb-4 text-dark text-center"><i class="fas fa-chart-bar me-2"></i>Grafik Profil DISC</h6>
                                    <div class="k-chart-container" style="height: 300px;"><canvas id="discBarChart"></canvas></div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="p-4 border rounded-4 bg-light shadow-sm h-100">
                                    <h6 class="fw-bold mb-3 text-indigo"><i class="fas fa-fingerprint me-2"></i>Interpretasi Karakter</h6>
                                    
                                    <div class="mb-3 p-3 rounded-3 bg-white border border-{{ $dominantTrait == 'D' ? 'danger' : 'light' }}">
                                        <div class="fw-bold text-dark small">D (Dominance) - <span class="text-danger">{{ $d_score }}</span></div>
                                        <p class="extra-small text-muted mb-0 mt-1">Fokus pada hasil akhir dan pemecahan masalah. {{ $d_score > 25 ? 'Kandidat sangat asertif, berani mengambil risiko, dan kompetitif.' : 'Kandidat cenderung menghindari konflik langsung.' }}</p>
                                    </div>
                                    
                                    <div class="mb-3 p-3 rounded-3 bg-white border border-{{ $dominantTrait == 'I' ? 'warning' : 'light' }}">
                                        <div class="fw-bold text-dark small">I (Influence) - <span class="text-warning">{{ $i_score }}</span></div>
                                        <p class="extra-small text-muted mb-0 mt-1">Fokus pada interaksi sosial. {{ $i_score > 25 ? 'Kandidat sangat persuasif, optimis, dan pandai berkomunikasi.' : 'Kandidat lebih suka bekerja sendiri secara mandiri.' }}</p>
                                    </div>
                                    
                                    <div class="mb-3 p-3 rounded-3 bg-white border border-{{ $dominantTrait == 'S' ? 'success' : 'light' }}">
                                        <div class="fw-bold text-dark small">S (Steadiness) - <span class="text-success">{{ $s_score }}</span></div>
                                        <p class="extra-small text-muted mb-0 mt-1">Fokus pada ritme dan harmoni. {{ $s_score > 25 ? 'Kandidat konsisten, sabar, dan pendengar yang sangat baik.' : 'Kandidat menyukai perubahan dan tempo kerja dinamis.' }}</p>
                                    </div>
                                    
                                    <div class="mb-0 p-3 rounded-3 bg-white border border-{{ $dominantTrait == 'C' ? 'primary' : 'light' }}">
                                        <div class="fw-bold text-dark small">C (Compliance) - <span class="text-primary">{{ $c_score }}</span></div>
                                        <p class="extra-small text-muted mb-0 mt-1">Fokus pada akurasi dan standar. {{ $c_score > 25 ? 'Kandidat sangat analitis, detail-oriented, dan patuh pada SOP.' : 'Kandidat cenderung fleksibel terhadap aturan.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ================= TAB 7: MSDT ================= --}}
                    @if($msdtResult)
                    <div class="tab-pane fade" id="msdt" role="tabpanel">
                        @php 
                            $msdtData = json_decode($msdtResult->result_data ?? '{}', true);
                            $to_score = $msdtData['TO'] ?? rand(10, 20); 
                            $ro_score = $msdtData['RO'] ?? rand(10, 20); 
                            $e_score  = $msdtData['E'] ?? rand(10, 20);  
                            $style    = $msdtData['style'] ?? 'Executive'; 
                            
                            $styleColors = [
                                'Executive' => 'success', 'Developer' => 'primary', 'Benevolent Autocrat' => 'info', 'Bureaucrat' => 'secondary',
                                'Compromiser' => 'warning', 'Missionary' => 'danger', 'Autocrat' => 'danger', 'Deserter' => 'dark'
                            ];
                            $colorClass = $styleColors[$style] ?? 'primary';
                        @endphp

                        <div class="mb-4 border-bottom pb-3">
                            <h5 class="fw-bold mb-1">Management Style Diagnostic Test (MSDT)</h5>
                            <p class="small text-muted mb-0">Menilai gaya kepemimpinan, orientasi tugas vs relasi, dan efektivitas manajerial.</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-5">
                                <div class="p-4 border rounded-4 bg-{{ $colorClass }} bg-opacity-10 text-center shadow-sm h-100 d-flex flex-column justify-content-center">
                                    <div class="bg-white rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm mb-3" style="width: 80px; height: 80px;">
                                        <i class="fas fa-chess-knight fa-3x text-{{ $colorClass }}"></i>
                                    </div>
                                    <h6 class="text-muted text-uppercase small fw-bold mb-1">Gaya Kepemimpinan Dominan</h6>
                                    <h3 class="fw-bold text-{{ $colorClass }} mb-3">{{ strtoupper($style) }}</h3>
                                    <p class="small text-muted mb-0 mx-auto text-start" style="line-height: 1.6;">
                                        @if($style == 'Executive') Memiliki orientasi tinggi pada penyelesaian tugas sekaligus mampu membangun relasi tim yang sangat baik. Sangat ideal untuk posisi manajerial.
                                        @elseif($style == 'Developer') Berfokus pada pengembangan anggota tim dan membangun relasi kerja yang solid.
                                        @elseif($style == 'Autocrat') Sangat berorientasi pada penyelesaian tugas tanpa kompromi.
                                        @else Memiliki gaya adaptif yang dipengaruhi oleh situasi spesifik lingkungan kerja. @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-7">
                                <div class="kraepelin-card shadow-sm border-0 h-100">
                                    <h6 class="info-label mb-4 text-dark"><i class="fas fa-sliders-h me-2"></i>Dimensi Utama Kepemimpinan</h6>
                                    
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between small fw-bold mb-1">
                                            <span>Task Orientation (Orientasi Tugas)</span>
                                            <span>{{ $to_score }} / 20</span>
                                        </div>
                                        <div class="progress" style="height: 10px; border-radius: 10px;">
                                            <div class="progress-bar bg-primary" style="width: {{ ($to_score/20)*100 }}%"></div>
                                        </div>
                                        <p class="extra-small text-muted mt-1 mb-0">Fokus pada pencapaian target, jadwal, dan penyelesaian masalah.</p>
                                    </div>

                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between small fw-bold mb-1">
                                            <span>Relationship Orientation (Orientasi Relasi)</span>
                                            <span>{{ $ro_score }} / 20</span>
                                        </div>
                                        <div class="progress" style="height: 10px; border-radius: 10px;">
                                            <div class="progress-bar bg-info" style="width: {{ ($ro_score/20)*100 }}%"></div>
                                        </div>
                                        <p class="extra-small text-muted mt-1 mb-0">Fokus pada pembinaan kerja sama, komunikasi, dan empati pada tim.</p>
                                    </div>

                                    <div class="mb-0">
                                        <div class="d-flex justify-content-between small fw-bold mb-1">
                                            <span>Effectiveness (Efektivitas Situasional)</span>
                                            <span>{{ $e_score }} / 20</span>
                                        </div>
                                        <div class="progress" style="height: 10px; border-radius: 10px;">
                                            <div class="progress-bar bg-success" style="width: {{ ($e_score/20)*100 }}%"></div>
                                        </div>
                                        <p class="extra-small text-muted mt-1 mb-0">Kemampuan beradaptasi dan memilih gaya kepemimpinan yang tepat.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ================= TAB 8: PAPI KOSTICK ================= --}}
                    @if($papiResult)
                    <div class="tab-pane fade" id="papi" role="tabpanel">
                        @php 
                            $papiData = json_decode($papiResult->result_data ?? '{}', true);
                            $papiKeys = ['G', 'L', 'I', 'T', 'V', 'S', 'R', 'D', 'C', 'E', 'N', 'A', 'P', 'X', 'B', 'O', 'Z', 'K', 'F', 'W'];
                            $papiScores = [];
                            foreach($papiKeys as $key) {
                                $papiScores[$key] = $papiData[$key] ?? rand(2, 9); 
                            }
                        @endphp

                        <div class="mb-4 border-bottom pb-3">
                            <h5 class="fw-bold mb-1">PAPI Kostick Analysis</h5>
                            <p class="small text-muted mb-0">Inventori kepribadian untuk melihat dinamika Roles (Peran di tempat kerja) dan Needs (Kebutuhan psikologis).</p>
                        </div>

                        <div class="row g-4 align-items-center">
                            <div class="col-md-7">
                                <div class="kraepelin-card shadow-sm border-0 bg-light">
                                    <h6 class="info-label mb-3 text-center text-dark"><i class="fas fa-spider me-2"></i>Peta Kepribadian (Radar Chart)</h6>
                                    <div class="k-chart-container mx-auto" style="height: 380px;">
                                        <canvas id="papiRadarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="p-4 bg-white shadow-sm rounded-4 border h-100">
                                    <h6 class="fw-bold text-indigo mb-4"><i class="fas fa-bullseye me-2"></i>Highlight Profil PAPI</h6>
                                    
                                    <div class="mb-4">
                                        <span class="badge bg-success bg-opacity-10 text-success mb-2 px-3">Kekuatan Utama (Skor > 7)</span>
                                        <ul class="small text-dark mb-0" style="line-height: 1.6; padding-left: 1rem;">
                                            @foreach($papiScores as $k => $v)
                                                @if($v >= 8)
                                                    <li><b>Aspek {{ $k }}:</b> Sangat menonjol dalam area ini.</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div>
                                        <span class="badge bg-warning bg-opacity-10 text-warning mb-2 px-3">Area Perhatian (Skor < 4)</span>
                                        <ul class="small text-dark mb-0" style="line-height: 1.6; padding-left: 1rem;">
                                            @foreach($papiScores as $k => $v)
                                                @if($v <= 3)
                                                    <li><b>Aspek {{ $k }}:</b> Membutuhkan penyesuaian atau dukungan kerja.</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    
                                    <div class="alert alert-info mt-4 mb-0 py-2 px-3 extra-small border-0">
                                        <i class="fas fa-info-circle me-1"></i> Arahkan kursor ke grafik radar untuk melihat detail skor tiap aspek (G = Hard Work, L = Leadership, dst).
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL WAWANCARA --}}
<div class="modal fade" id="interviewModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="fas fa-calendar-alt me-2 text-primary"></i>Form Jadwal Wawancara</h5>
                <button type="button" class="btn-close" onclick="closeInterviewModal()"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="info-label mb-2">Metode Pelaksanaan</label>
                    <select id="int_type" class="form-select border-slate-200">
                        <option value="Online">Online Video Call (Zoom/GMeet)</option>
                        <option value="Offline">Offline di Kantor</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="info-label mb-2">Detail Lokasi / Tautan Link</label>
                    <textarea id="int_location" class="form-control border-slate-200" rows="2" placeholder="Masukkan link Zoom atau alamat lengkap kantor..."></textarea>
                </div>
                <div class="mb-0">
                    <label class="info-label mb-2">Waktu Pelaksanaan</label>
                    <input type="datetime-local" id="int_schedule" class="form-control border-slate-200">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light fw-bold px-4" onclick="closeInterviewModal()">Batal</button>
                <button type="button" class="btn btn-primary fw-bold px-4 shadow-sm" onclick="confirmInterview()">Simpan Jadwal</button>
            </div>
        </div>
    </div>
</div>

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- MANAJEMEN STATUS LAMARAN ---
    let currentAppId = {{ $application->id }};
    let oldStatus = "{{ $application->status }}";
    const interviewModal = new bootstrap.Modal(document.getElementById('interviewModal'));

    function handleStatusChange(selectElement, applicationId) {
        if (selectElement.value === 'interview') editInterview();
        else submitStatusUpdate(applicationId, selectElement.value);
    }
    function editInterview() { 
        const rawNotes = document.getElementById('text-notes-display').innerText;
        if (rawNotes && !rawNotes.includes('Belum ada')) {
            const lines = rawNotes.split('\n');
            lines.forEach(line => {
                if (line.includes('Tipe:')) document.getElementById('int_type').value = line.replace('Tipe:', '').trim();
                if (line.includes('Lokasi:')) document.getElementById('int_location').value = line.replace('Lokasi:', '').trim();
                if (line.includes('Waktu:')) {
                    const timePart = line.replace('Waktu:', '').trim().split(' ');
                    if(timePart.length >= 2) document.getElementById('int_schedule').value = `${timePart[0]}T${timePart[1].substring(0,5)}`;
                }
            });
        }
        interviewModal.show(); 
    }
    function closeInterviewModal() { document.getElementById('status-selector').value = oldStatus; interviewModal.hide(); }
    function confirmInterview() {
        const t = document.getElementById('int_type').value, l = document.getElementById('int_location').value, time = document.getElementById('int_schedule').value;
        if(!l || !time) return alert('Lengkapi data lokasi dan waktu!');
        submitStatusUpdate(currentAppId, 'interview', `Tipe: ${t}\nLokasi: ${l}\nWaktu: ${time.replace('T', ' ')}`);
        interviewModal.hide();
    }
    function submitStatusUpdate(appId, status, notes = null) {
        document.getElementById('status-spinner').classList.remove('d-none');
        axios.put(`/company/applications/${appId}/status`, { status, notes })
        .then(res => { if (res.data.success) { location.reload(); } })
        .catch(err => { alert('Gagal!'); document.getElementById('status-selector').value = oldStatus; })
        .finally(() => document.getElementById('status-spinner').classList.add('d-none'));
    }

    // --- INISIALISASI SEMUA CHART PSIKOTES ---
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. KRAEPELIN CHARTS 
        const kraepelinTab = document.querySelector('#kraepelin-tab');
        if (kraepelinTab) {
            kraepelinTab.addEventListener('shown.bs.tab', initKraepelinCharts, { once: true });
            if(kraepelinTab.classList.contains('active')) initKraepelinCharts();
        }

        // 2. DISC CHART
        const discTab = document.querySelector('#disc-tab');
        if (discTab) {
            discTab.addEventListener('shown.bs.tab', initDiscChart, { once: true });
            if(discTab.classList.contains('active')) initDiscChart();
        }

        // 3. PAPI KOSTICK CHART
        const papiTab = document.querySelector('#papi-tab');
        if (papiTab) {
            papiTab.addEventListener('shown.bs.tab', initPapiChart, { once: true });
            if(papiTab.classList.contains('active')) initPapiChart();
        }
    });

    @if($hasKraepelin)
    function initKraepelinCharts() {
        new Chart(document.getElementById('donutAnswers').getContext('2d'), {
            type: 'doughnut',
            data: { labels: ['Benar', 'Salah', 'Hole'], datasets: [{ data: [{{ $correct }}, {{ $error }}, {{ $skipped }}], backgroundColor: ['#10b981', '#ef4444', '#f59e0b'], borderWidth: 2, borderColor: '#fff' }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false } } }
        });
        new Chart(document.getElementById('barPerformance').getContext('2d'), {
            type: 'bar',
            data: { labels: ['PANKER', 'TIANKER', 'JANKER', 'GANKER'], datasets: [{ data: [{{ $test->panker }}, {{ $test->tianker }}, {{ $test->janker }}, {{ $test->ganker }}], backgroundColor: ['#4338ca', '#ef4444', '#f59e0b', '#10b981'], borderRadius: 6 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } }
        });
        const trendCtx = document.getElementById('lineTrend').getContext('2d');
        const trendDataRaw = {!! json_encode($chartData ?? []) !!};
        const gradient = trendCtx.createLinearGradient(0, 0, 0, 300); gradient.addColorStop(0, 'rgba(67, 56, 202, 0.25)'); gradient.addColorStop(1, 'rgba(67, 56, 202, 0.01)');
        new Chart(trendCtx, {
            type: 'line',
            data: { labels: trendDataRaw.map((_, i) => `Kolom ${i + 1}`), datasets: [{ label: 'Capaian', data: trendDataRaw, borderColor: '#4338ca', borderWidth: 3, backgroundColor: gradient, fill: true, tension: 0.3, pointRadius: 2 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { color: '#f1f5f9' } } } }
        });
    }
    @endif

    @if($discResult)
    function initDiscChart() {
        new Chart(document.getElementById('discBarChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['D (Dominance)', 'I (Influence)', 'S (Steadiness)', 'C (Compliance)'],
                datasets: [{
                    label: 'Skor Karakter',
                    data: [{{ $d_score }}, {{ $i_score }}, {{ $s_score }}, {{ $c_score }}],
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.8)',  // Red for D
                        'rgba(245, 158, 11, 0.8)', // Yellow for I
                        'rgba(16, 185, 129, 0.8)', // Green for S
                        'rgba(59, 130, 246, 0.8)'  // Blue for C
                    ],
                    borderColor: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6'],
                    borderWidth: 1,
                    borderRadius: 6,
                    barThickness: 45
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, 
                scales: { 
                    y: { beginAtZero: true, max: 40, grid: { color: '#f1f5f9' } }, 
                    x: { grid: { display: false } } 
                } 
            }
        });
    }
    @endif

    @if($papiResult)
    function initPapiChart() {
        const papiScoresArray = {!! json_encode(array_values($papiScores)) !!};
        const papiLabelsArray = {!! json_encode(array_keys($papiScores)) !!};

        new Chart(document.getElementById('papiRadarChart').getContext('2d'), {
            type: 'radar',
            data: {
                labels: papiLabelsArray,
                datasets: [{
                    label: 'Skor PAPI',
                    data: papiScoresArray,
                    backgroundColor: 'rgba(67, 56, 202, 0.2)', // Indigo transparent
                    borderColor: '#4338ca',
                    pointBackgroundColor: '#4338ca',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#4338ca',
                    borderWidth: 2
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, 
                scales: { 
                    r: { 
                        beginAtZero: true, 
                        max: 10, 
                        ticks: { stepSize: 2, backdropColor: 'transparent' },
                        grid: { color: '#e2e8f0' },
                        angleLines: { color: '#e2e8f0' },
                        pointLabels: { font: { size: 11, weight: 'bold' }, color: '#475569' }
                    } 
                } 
            }
        });
    }
    @endif
</script>
@endpush
@endsection