@extends('layouts.company')

@section('title', 'Review Lamaran: ' . $application->user->name)

@section('content')
<style>
    :root {
        --slate-50: #f8fafc; --slate-100: #f1f5f9; --slate-200: #e2e8f0;
        --text-main: #334155; --text-heading: #1e293b; --brand-indigo: #4338ca; 
    }
    .review-card { border-radius: 16px; border: 1px solid var(--slate-200); background: #fff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .profile-header-card { background: linear-gradient(to bottom, var(--brand-indigo), #3730a3); border-radius: 16px 16px 0 0; padding: 30px 20px; }
    .nav-tabs-custom { border-bottom: 2px solid var(--slate-100); gap: 20px; }
    .nav-tabs-custom .nav-link { border: none; color: #64748b; font-weight: 600; padding: 12px 0; position: relative; background: transparent; transition: all 0.3s; }
    .nav-tabs-custom .nav-link:hover { color: var(--brand-indigo); }
    .nav-tabs-custom .nav-link.active { color: var(--brand-indigo); }
    .nav-tabs-custom .nav-link.active::after { content: ""; position: absolute; bottom: -2px; left: 0; width: 100%; height: 2px; background: var(--brand-indigo); }
    .timeline-item { padding-left: 24px; border-left: 2px solid var(--slate-100); position: relative; margin-bottom: 25px; }
    .timeline-item::before { content: ""; position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: var(--brand-indigo); border: 2px solid white; }
    .info-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
    .info-value { font-size: 0.95rem; font-weight: 600; color: var(--text-heading); }
    
    /* Kraepelin Specific Styles */
    .kraepelin-card { border-radius: 16px; padding: 24px; background: #fff; border: 1px solid var(--slate-200); }
    .k-chart-container { position: relative; width: 100%; }
    .k-stat-box { padding: 12px 16px; border-radius: 10px; background: var(--slate-50); border: 1px solid var(--slate-100); }
    .cv-preview-container { border-radius: 12px; overflow: hidden; border: 1px solid var(--slate-200); background: #f1f5f9; min-height: 500px; }
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
            <div class="card-header bg-white border-0 p-4 pb-0">
                <ul class="nav nav-tabs nav-tabs-custom" id="reviewTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#cover">Surat Lamaran</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile">Detail Profil</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#kuesioner">Kuesioner</button></li>
                    <li class="nav-item {{ $application->status !== 'interview' ? 'd-none' : '' }}" id="interview-tab-nav">
                        <button class="nav-link text-danger fw-bold" data-bs-toggle="tab" data-bs-target="#interview-pane">
                            <i class="fas fa-calendar-check me-1"></i> Wawancara
                        </button>
                    </li>
                    @if($application->kraepelinTest && $application->kraepelinTest->completed_at)
                    <li class="nav-item ms-auto">
                        <button class="nav-link text-primary fw-bold active-indicator" id="kraepelin-tab" data-bs-toggle="tab" data-bs-target="#kraepelin">
                            <i class="fas fa-brain me-1"></i> Evaluasi Kraepelin
                        </button>
                    </li>
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
                                @forelse($application->user->seekerProfile->experiences as $exp)
                                    <div class="timeline-item">
                                        <div class="fw-bold text-dark">{{ $exp->job_title }}</div>
                                        <div class="small fw-bold text-primary">{{ $exp->company_name }}</div>
                                        <div class="extra-small text-muted mt-1"><i class="far fa-calendar-alt me-1"></i>{{ $exp->start_date->format('M Y') }} - {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Sekarang' }}</div>
                                    </div>
                                @empty <p class="small text-muted italic">Belum ada pengalaman kerja yang diisi.</p> @endforelse
                            </div>
                            <div class="col-md-6 ps-md-4">
                                <h6 class="fw-bold mb-4 text-indigo"><i class="fas fa-graduation-cap me-2"></i>Riwayat Pendidikan</h6>
                                @forelse($application->user->seekerProfile->educations as $edu)
                                    <div class="timeline-item">
                                        <div class="fw-bold text-dark">{{ $edu->degree }}</div>
                                        <div class="small fw-bold text-primary">{{ $edu->institution }}</div>
                                        <div class="extra-small text-muted mt-1"><i class="far fa-calendar-alt me-1"></i>Tahun Lulus: {{ $edu->end_date ? $edu->end_date->format('Y') : 'Sekarang' }}</div>
                                    </div>
                                @empty <p class="small text-muted italic">Belum ada data pendidikan.</p> @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- TAB 3: KUESIONER --}}
                    <div class="tab-pane fade" id="kuesioner">
                        <div class="mb-4">
                            <h6 class="fw-bold mb-1">Hasil Kuesioner Pra-Seleksi</h6>
                            <p class="small text-muted">Data ini diisi kandidat saat melakukan lamaran.</p>
                        </div>
                        <div class="row g-3">
                            @php
                                $questions = [
                                    'q1' => 'Pernyataan Kejujuran Data', 'q2' => 'Ketersediaan Full-Time', 'q3' => 'Kesediaan Relokasi', 'q4' => 'Kendaraan Pribadi',
                                    'q5' => 'Ekspektasi Gaji', 'q6' => 'Skill Teknis (1-10)', 'q15' => 'Tanggal Tercepat Mulai', 'q7' => 'Pencapaian Terbesar',
                                    'q13' => 'Motivasi Utama Melamar', 'q14' => 'Visi Karier 3 Tahun ke Depan'
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
                                                @elseif($key === 'q15') <i class="far fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($application->answers[$key])->translatedFormat('d F Y') }}
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
                                <p class="small text-muted mb-0">Data di bawah ini dikirimkan ke email & dashboard kandidat.</p>
                            </div>
                            <button class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm" onclick="editInterview()">
                                <i class="fas fa-edit me-1"></i> Atur Jadwal
                            </button>
                        </div>
                        <div class="p-4 rounded-4 border bg-indigo bg-opacity-10">
                            <div class="info-value text-dark" id="text-notes-display" style="white-space: pre-line; line-height: 1.8;">
                                {{ $application->notes ?? 'Belum ada jadwal wawancara yang diatur.' }}
                            </div>
                        </div>
                    </div>

                    {{-- TAB 5: ANALISIS KRAEPELIN (INDUSTRIAL GRADE) --}}
                    @if($application->kraepelinTest && $application->kraepelinTest->completed_at)
                    <div class="tab-pane fade" id="kraepelin">
                        @php
                            $test = $application->kraepelinTest;
                            $chartData = is_string($test->results_chart) ? json_decode($test->results_chart, true) : $test->results_chart;
                            
                            // 1. Data Distribusi Donut
                            $correct = $test->total_correct;
                            $error = $test->total_answered - $test->total_correct;
                            $skipped = max(0, $test->tianker - $error); // Menghindari minus jika ada anomali
                            
                            // 2. Data Bar Kuartal (Tiap 10 Kolom)
                            $quarters = [];
                            if (is_array($chartData) && count($chartData) > 0) {
                                for ($i = 0; $i < 50; $i += 10) {
                                    $slice = array_slice($chartData, $i, 10);
                                    $quarters[] = count($slice) > 0 ? round(array_sum($slice) / count($slice), 1) : 0;
                                }
                            } else {
                                $quarters = [0,0,0,0,0];
                            }

                            // 3. Persentase Metrik
                            $accuracy = $test->total_answered > 0 ? round(($test->total_correct / $test->total_answered) * 100, 1) : 0;
                            $pankerPerc = min(($test->panker / 25) * 100, 100); // Asumsi 25 adalah kecepatan max sangat luar biasa
                            $jankerPerc = max(100 - ($test->janker * 6), 0); // Semakin kecil janker, makin stabil (mendekati 100%)
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <div>
                                <h5 class="fw-bold mb-1" style="color: var(--text-heading);">Executive Summary Kraepelin</h5>
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
                                    <div class="k-chart-container mx-auto" style="height: 180px; width: 180px;">
                                        <canvas id="donutAnswers"></canvas>
                                    </div>
                                    <div class="mt-4 k-stat-box bg-white">
                                        <div class="d-flex justify-content-between small mb-2 border-bottom pb-1">
                                            <span class="text-muted">Jawaban Benar</span> <span class="fw-bold text-success">{{ $correct }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between small mb-2 border-bottom pb-1">
                                            <span class="text-muted">Salah Hitung</span> <span class="fw-bold text-danger">{{ $error }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between small">
                                            <span class="text-muted">Hole (Lompatan)</span> <span class="fw-bold text-warning">{{ $skipped }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="kraepelin-card h-100 shadow-sm border-0">
                                    <h6 class="info-label mb-4 text-dark"><i class="fas fa-chart-bar me-2"></i>Analisis 4 Faktor Utama (P-T-J-G)</h6>
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <div class="k-chart-container" style="height: 220px;">
                                                <canvas id="barPerformance"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="analysis-section border-0 bg-transparent p-0 ps-md-3 border-start">
                                                <div class="mb-3">
                                                    <div class="fw-bold extra-small text-primary mb-1">PK (PANKER) - Kecepatan</div>
                                                    <p class="extra-small text-muted mb-0">Rata-rata <b>{{ round($test->panker, 1) }}</b> baris per kolom. Mengukur energi & daya dorong kerja.</p>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="fw-bold extra-small text-danger mb-1">TK (TIANKER) - Ketelitian</div>
                                                    <p class="extra-small text-muted mb-0">Total <b>{{ $test->tianker }}</b> kesalahan. Mengukur kehati-hatian di bawah tekanan.</p>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="fw-bold extra-small text-warning mb-1">JK (JANKER) - Irama/Stabilitas</div>
                                                    <p class="extra-small text-muted mb-0">Rentang fluktuasi <b>{{ $test->janker }}</b>. Mengukur konsistensi emosi.</p>
                                                </div>
                                                <div class="mb-0">
                                                    <div class="fw-bold extra-small text-success mb-1">GK (GANKER) - Ketahanan</div>
                                                    <p class="extra-small text-muted mb-0">Tren <b>{{ $test->ganker >= 0 ? 'Positif' : 'Negatif' }}</b>. Mengukur daya tahan terhadap kelelahan (Fatigue).</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
                            <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                                <h6 class="fw-bold mb-0 text-indigo"><i class="fas fa-wave-square me-2"></i>Kurva Kerja (Work Rhythm Trend)</h6>
                                <p class="extra-small text-muted mt-1">Menggambarkan ritme capaian per kolom (interval 30-45 detik). Kurva ideal adalah stabil dengan sedikit tanjakan di akhir.</p>
                            </div>
                            <div class="card-body p-4">
                                <div class="k-chart-container" style="height: 280px;"><canvas id="lineTrend"></canvas></div>
                            </div>
                        </div>

                        <div class="row g-4 mb-2">
                            <div class="col-md-6">
                                <div class="kraepelin-card shadow-sm border-0 h-100">
                                    <h6 class="info-label mb-4 text-dark"><i class="fas fa-cubes me-2"></i>Produktivitas Fase Kuartal</h6>
                                    <div class="k-chart-container" style="height: 200px;"><canvas id="barQuarters"></canvas></div>
                                    <p class="extra-small text-center text-muted mt-3 mb-0">Melihat perbandingan stamina rata-rata pada setiap kelipatan 10 kolom tes.</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="kraepelin-card shadow-sm border-0 h-100">
                                    <h6 class="info-label mb-4 text-dark"><i class="fas fa-percentage me-2"></i>Persentase Indeks Klinis</h6>
                                    
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between extra-small fw-bold mb-1">
                                            <span class="text-muted">Tingkat Akurasi (Accuracy Rate)</span> <span class="text-success">{{ $accuracy }}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px; border-radius: 10px;">
                                            <div class="progress-bar bg-success" style="width: {{ $accuracy }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between extra-small fw-bold mb-1">
                                            <span class="text-muted">Kapasitas Kecepatan (Speed Capacity)</span> <span class="text-primary">{{ round($pankerPerc) }}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px; border-radius: 10px;">
                                            <div class="progress-bar bg-primary" style="width: {{ $pankerPerc }}%"></div>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between extra-small fw-bold mb-1">
                                            <span class="text-muted">Indeks Stabilitas (Stability Index)</span> <span class="text-warning">{{ round($jankerPerc) }}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px; border-radius: 10px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $jankerPerc }}%"></div>
                                        </div>
                                    </div>

                                    <div class="mt-4 p-3 rounded-3" style="background: var(--slate-100); border-left: 4px solid var(--brand-indigo);">
                                        <div class="fw-bold small text-indigo mb-1">KESIMPULAN EVALUASI:</div>
                                        <p class="small text-dark mb-0" style="line-height: 1.5;">
                                            @if($accuracy > 90 && $test->panker > 12)
                                                Kandidat berprofil <b>"High Achiever"</b>. Memiliki kecepatan tinggi tanpa mengorbankan kualitas. Sangat direkomendasikan.
                                            @elseif($test->ganker < 0 && $test->janker > 8)
                                                Kandidat menunjukkan gejala <b>"Fatigue & Impulsif"</b>. Performa tidak stabil dan cepat lelah. Pertimbangkan ulang untuk peran di bawah tekanan.
                                            @else
                                                Kandidat berprofil <b>"Steady Worker"</b>. Menunjukkan etos kerja normal dan cukup stabil. Cocok untuk tugas reguler operasional.
                                            @endif
                                        </p>
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

{{-- ================= MODAL WAWANCARA ================= --}}
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
        // Populate modal with existing data if available
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
    
    function closeInterviewModal() { 
        document.getElementById('status-selector').value = oldStatus; 
        interviewModal.hide(); 
    }

    function confirmInterview() {
        const type = document.getElementById('int_type').value;
        const loc = document.getElementById('int_location').value;
        const time = document.getElementById('int_schedule').value;
        if(!loc || !time) return alert('Peringatan: Lengkapi data lokasi dan waktu wawancara!');
        
        const notes = `Tipe: ${type}\nLokasi: ${loc}\nWaktu: ${time.replace('T', ' ')}`;
        submitStatusUpdate(currentAppId, 'interview', notes);
        interviewModal.hide();
    }

    function submitStatusUpdate(applicationId, status, notes = null) {
        document.getElementById('status-spinner').classList.remove('d-none');
        axios.put(`/company/applications/${applicationId}/status`, { status, notes })
        .then(res => { if (res.data.success) { oldStatus = status; location.reload(); } })
        .catch(err => { alert('Gagal memperbarui status. Silakan coba lagi.'); document.getElementById('status-selector').value = oldStatus; })
        .finally(() => document.getElementById('status-spinner').classList.add('d-none'));
    }

    // --- INISIALISASI GRAFIK KRAEPELIN (CHART.JS) ---
    document.addEventListener('DOMContentLoaded', function() {
        const kraepelinTab = document.querySelector('#kraepelin-tab');
        if (kraepelinTab) {
            kraepelinTab.addEventListener('shown.bs.tab', function () {
                initKraepelinCharts();
            }, { once: true }); // Mencegah re-render ganda
        }
        
        // Auto-render jika tab Kraepelin secara default aktif
        if(kraepelinTab && kraepelinTab.classList.contains('active')) {
            initKraepelinCharts();
        }
    });

    function initKraepelinCharts() {
        @if($application->kraepelinTest && $application->kraepelinTest->completed_at)
        
        // 1. DONUT CHART (Distribusi Jawaban)
        new Chart(document.getElementById('donutAnswers').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Benar', 'Salah', 'Hole/Skipped'],
                datasets: [{
                    data: [{{ $correct }}, {{ $error }}, {{ $skipped }}],
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 10
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false }, tooltip: { padding: 12, bodyFont: { size: 14 } } } }
        });

        // 2. BAR CHART (4 Faktor Kraepelin: PK, TK, JK, GK)
        new Chart(document.getElementById('barPerformance').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['PANKER', 'TIANKER', 'JANKER', 'GANKER'],
                datasets: [{
                    data: [{{ $test->panker }}, {{ $test->tianker }}, {{ $test->janker }}, {{ $test->ganker }}],
                    backgroundColor: ['#4338ca', '#ef4444', '#f59e0b', '#10b981'],
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 40
                }]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                plugins: { legend: { display: false }, tooltip: { padding: 10 } },
                scales: { 
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, border: { dash: [4, 4] } }, 
                    x: { grid: { display: false } } 
                }
            }
        });

        // 3. LINE CHART (Grafik Tren Kurva Kerja Utama)
        const trendCtx = document.getElementById('lineTrend').getContext('2d');
        const trendDataRaw = {!! json_encode($chartData ?? []) !!};
        
        const gradient = trendCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(67, 56, 202, 0.25)');
        gradient.addColorStop(1, 'rgba(67, 56, 202, 0.01)');

        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendDataRaw.map((_, i) => `Kolom ${i + 1}`),
                datasets: [{
                    label: 'Capaian Baris',
                    data: trendDataRaw,
                    borderColor: '#4338ca',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 2,
                    pointBackgroundColor: '#4338ca',
                    pointHoverRadius: 6
                }]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                plugins: { legend: { display: false }, tooltip: { padding: 12, backgroundColor: '#1e293b' } },
                scales: { 
                    x: { grid: { display: false }, ticks: { maxTicksLimit: 15 } },
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } }
                }
            }
        });

        // 4. BAR CHART (Analisis Per Kuartal/Fase 10 Kolom)
        new Chart(document.getElementById('barQuarters').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Fase 1 (1-10)', 'Fase 2 (11-20)', 'Fase 3 (21-30)', 'Fase 4 (31-40)', 'Fase 5 (41-50)'],
                datasets: [{
                    label: 'Rata-rata Baris',
                    data: {!! json_encode($quarters) !!},
                    backgroundColor: '#e2e8f0',
                    hoverBackgroundColor: '#4338ca',
                    borderRadius: 6,
                    barPercentage: 0.6
                }]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                plugins: { legend: { display: false }, tooltip: { padding: 10 } },
                scales: { 
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
        
        @endif
    }
</script>
@endpush
@endsection