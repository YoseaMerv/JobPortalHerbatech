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
        color: #64748b;
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

    .info-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
    .info-value { font-size: 0.95rem; font-weight: 600; color: var(--text-heading); }

    /* Kraepelin Dashboard Enhanced */
    .kraepelin-card {
        border-radius: 16px;
        padding: 24px;
        background: #fff;
        border: 1px solid var(--slate-200);
        height: 100%;
    }
    .metric-value-lg { font-size: 2.25rem; font-weight: 800; line-height: 1; }
    .metric-label-sm { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
    
    .progress-kraepelin { height: 8px; border-radius: 10px; background-color: var(--slate-100); }
    
    .analysis-section {
        border-radius: 12px;
        padding: 15px;
        background-color: var(--slate-50);
        border-left: 4px solid var(--brand-indigo);
    }

    /* Cover Letter Preview Styling */
    .cv-preview-container {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--slate-200);
        background: #f1f5f9;
        position: relative;
        min-height: 400px;
    }
    .file-info-bar {
        background: #fff;
        border-bottom: 1px solid var(--slate-200);
        padding: 12px 20px;
    }
</style>

<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('company.applications.index') }}" class="text-decoration-none text-muted small fw-bold">
        <i class="fas fa-chevron-left me-1"></i> KEMBALI KE DAFTAR PELAMAR
    </a>
    <div id="status-spinner" class="spinner-border spinner-border-sm text-primary d-none" role="status"></div>
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
                        <div class="d-flex align-items-center justify-content-between">
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

                <div class="bg-light p-3 rounded-4 border">
                    <label class="info-label mb-2">Update Progress Rekrutmen</label>
                    <select id="status-selector" class="form-select border-0 shadow-sm fw-bold mb-2" 
                            onchange="handleStatusChange(this, {{ $application->id }})"
                            style="border-radius: 10px; font-size: 0.9rem; color: var(--text-heading);">
                        @foreach(\App\Models\JobApplication::getAllStatuses() as $value => $label)
                            <option value="{{ $value }}" {{ $application->status == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
                    <li class="nav-item {{ $application->status !== 'interview' ? 'd-none' : '' }}" id="interview-tab-nav">
                        <button class="nav-link text-danger fw-bold" id="interview-tab" data-bs-toggle="tab" data-bs-target="#interview-pane" type="button">
                            <i class="fas fa-calendar-check me-1"></i> Detail Wawancara
                        </button>
                    </li>
                    @if($application->kraepelinTest && $application->kraepelinTest->completed_at)
                    <li class="nav-item">
                        <button class="nav-link text-primary fw-bold" id="kraepelin-tab" data-bs-toggle="tab" data-bs-target="#kraepelin" type="button">
                            <i class="fas fa-brain me-1"></i> Analisis Kraepelin
                        </button>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="reviewTabsContent">
                    
                    <div class="tab-pane fade show active" id="cover" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0" style="color: var(--text-heading);">Dokumen Surat Lamaran</h6>
                            @if($application->cover_letter_path)
                            <a href="{{ route('company.applications.download-cover', $application->id) }}" class="btn btn-sm btn-outline-dark fw-bold">
                                <i class="fas fa-download me-1"></i> Unduh Asli
                            </a>
                            @endif
                        </div>

                        @if($application->cover_letter_path)
                            <div class="cv-preview-container shadow-sm">
                                <div class="file-info-bar d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-pdf text-danger fa-lg me-2"></i>
                                        <span class="small fw-bold text-muted text-truncate" style="max-width: 250px;">
                                            {{ basename($application->cover_letter_path) }}
                                        </span>
                                    </div>
                                    <span class="badge bg-success bg-opacity-10 text-success small">Document</span>
                                </div>
                                <iframe src="{{ Storage::url($application->cover_letter_path) }}#toolbar=0" width="100%" height="500px" style="border: none;"></iframe>
                            </div>
                        @else
                            <div class="p-5 rounded-4 text-center border" style="background: var(--slate-50); border-style: dashed !important;">
                                <i class="fas fa-file-invoice text-muted mb-3 fa-3x opacity-25"></i>
                                <h6 class="fw-bold text-muted">Tidak Ada Lampiran PDF</h6>
                                <p class="small text-muted mb-0">Kandidat tidak melampirkan file Surat Lamaran khusus.</p>
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="interview-pane" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h6 class="fw-bold mb-1" style="color: var(--text-heading);">Jadwal Wawancara Terdaftar</h6>
                                <p class="small text-muted">Detail ini ditampilkan pada dashboard pelamar.</p>
                            </div>
                            <button class="btn btn-sm btn-outline-primary fw-bold px-3" onclick="editInterview()" style="border-radius: 8px;">
                                <i class="fas fa-edit me-1"></i> Edit Jadwal
                            </button>
                        </div>
                        <div class="p-4 rounded-4 border bg-light shadow-sm">
                            <div class="info-label text-muted mb-2">Ringkasan Info</div>
                            <div class="info-value text-dark" id="text-notes-display" style="white-space: pre-line; line-height: 1.6;">
                                {{ $application->notes ?? 'Belum ada detail wawancara.' }}
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        @if($application->user->seekerProfile)
                            <div class="mb-5">
                                <h6 class="fw-bold mb-3" style="color: var(--text-heading);">Biografi Ringkas</h6>
                                <p class="text-muted" style="line-height: 1.6;">{{ $application->user->seekerProfile->bio ?? 'Belum ada biografi.' }}</p>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-4" style="color: var(--text-heading);">Pengalaman Kerja</h6>
                                    @forelse($application->user->seekerProfile->experiences as $exp)
                                        <div class="timeline-item">
                                            <div class="fw-bold text-dark">{{ $exp->job_title }}</div>
                                            <div class="small fw-medium text-muted">{{ $exp->company_name }}</div>
                                        </div>
                                    @empty
                                        <p class="small text-muted">Tidak ada pengalaman.</p>
                                    @endforelse
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-4" style="color: var(--text-heading);">Pendidikan</h6>
                                    @forelse($application->user->seekerProfile->educations as $edu)
                                        <div class="timeline-item">
                                            <div class="fw-bold text-dark">{{ $edu->degree }}</div>
                                            <div class="small fw-medium text-muted">{{ $edu->institution }}</div>
                                        </div>
                                    @empty
                                        <p class="small text-muted">Tidak ada pendidikan.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($application->kraepelinTest && $application->kraepelinTest->completed_at)
                    <div class="tab-pane fade" id="kraepelin" role="tabpanel">
                        @php
                            $test = $application->kraepelinTest;
                            $total = $test->total_answered ?: 1;
                            $accuracy = round(($test->total_correct / $total) * 100, 1);
                            $panker = $total;
                            $correct = $test->total_correct;
                            $ganker = round((1 - (($total - $correct) / $total)) * 100, 1);
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold mb-0">Hasil Evaluasi Kraepelin</h6>
                            <a href="{{ route('company.applications.kraepelin-pdf', $application->id) }}" class="btn btn-sm btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fas fa-file-pdf me-2"></i> Ekspor Laporan
                            </a>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6 col-xl-3">
                                <div class="kraepelin-card shadow-sm border-bottom border-4 border-primary">
                                    <div class="metric-label-sm text-primary mb-2">PANKER (Kecepatan)</div>
                                    <div class="metric-value-lg text-dark">{{ $panker }}</div>
                                    <div class="small text-muted mt-2">Total input data</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="kraepelin-card shadow-sm border-bottom border-4 border-success">
                                    <div class="metric-label-sm text-success mb-2">TIANKER (Ketelitian)</div>
                                    <div class="metric-value-lg text-dark">{{ $correct }}</div>
                                    <div class="small text-muted mt-2">Jawaban benar</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="kraepelin-card shadow-sm border-bottom border-4 border-warning">
                                    <div class="metric-label-sm text-warning mb-2">JANKER (Ketahanan)</div>
                                    <div class="metric-value-lg text-dark">{{ $accuracy }}%</div>
                                    <div class="small text-muted mt-2">Akurasi performa</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="kraepelin-card shadow-sm border-bottom border-4 border-info">
                                    <div class="metric-label-sm text-info mb-2">GANKER (Stabilitas)</div>
                                    <div class="metric-value-lg text-dark">{{ $ganker }}%</div>
                                    <div class="small text-muted mt-2">Konsistensi ritme</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Grafik Indikator Performa</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Energi Kerja</span>
                                        <span class="fw-bold">{{ $panker > 1000 ? 'Sangat Tinggi' : 'Normal' }}</span>
                                    </div>
                                    <div class="progress-kraepelin"><div class="progress-bar bg-primary" style="width: {{ min(($panker/1500)*100, 100) }}%"></div></div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Pengendalian Diri</span>
                                        <span class="fw-bold">{{ $accuracy > 90 ? 'Baik' : 'Cukup' }}</span>
                                    </div>
                                    <div class="progress-kraepelin"><div class="progress-bar bg-success" style="width: {{ $accuracy }}%"></div></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="analysis-section h-100">
                                    <h6 class="fw-bold mb-3 text-primary">Interpretasi Psikologis</h6>
                                    <p class="small text-muted mb-0" style="line-height: 1.7;">
                                        @if($accuracy >= 85 && $panker >= 800)
                                            Kandidat memiliki kapasitas kerja prima dengan fokus tajam. Mampu menangani tugas repetitif secara konsisten.
                                        @else
                                            Kandidat menunjukkan fluktuasi fokus. Disarankan untuk peran dengan ritme kerja yang dinamis namun tidak monoton.
                                        @endif
                                    </p>
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

<div class="modal fade" id="interviewModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="fas fa-calendar-alt me-2 text-primary"></i>Jadwal Wawancara</h5>
                <button type="button" class="btn-close" onclick="closeInterviewModal()"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="info-label mb-2">Tipe</label>
                    <select id="int_type" class="form-select">
                        <option value="Online">Online (Zoom/Meet)</option>
                        <option value="Offline">Offline (Kantor)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="info-label mb-2">Lokasi / Link</label>
                    <textarea id="int_location" class="form-control" rows="1"></textarea>
                </div>
                <div class="mb-0">
                    <label class="info-label mb-2">Waktu</label>
                    <input type="datetime-local" id="int_schedule" class="form-control">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light fw-bold" onclick="closeInterviewModal()">Batal</button>
                <button type="button" class="btn btn-primary fw-bold" onclick="confirmInterview()">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let currentAppId = {{ $application->id }};
    let oldStatus = "{{ $application->status }}";
    const interviewModal = new bootstrap.Modal(document.getElementById('interviewModal'));

    function handleStatusChange(selectElement, applicationId) {
        currentAppId = applicationId;
        if (selectElement.value === 'interview') {
            editInterview();
        } else {
            submitStatusUpdate(applicationId, selectElement.value);
        }
    }

    function editInterview() {
        const rawNotes = document.getElementById('text-notes-display').innerText;
        if (rawNotes && !rawNotes.includes('Belum ada')) {
            const data = parseNotes(rawNotes);
            document.getElementById('int_type').value = data.tipe || 'Online';
            document.getElementById('int_location').value = data.lokasi || '';
            if (data.waktu) document.getElementById('int_schedule').value = data.waktu.trim().replace(' ', 'T');
        }
        interviewModal.show();
    }

    function parseNotes(notes) {
        const lines = notes.split('\n');
        let data = { tipe: 'Online', lokasi: '', waktu: '' };
        lines.forEach(line => {
            if (line.includes('Tipe:')) data.tipe = line.replace('Tipe:', '').trim();
            if (line.includes('Lokasi:')) data.lokasi = line.replace('Lokasi:', '').trim();
            if (line.includes('Waktu:')) data.waktu = line.replace('Waktu:', '').trim();
        });
        return data;
    }

    function closeInterviewModal() {
        document.getElementById('status-selector').value = oldStatus;
        interviewModal.hide();
    }

    function confirmInterview() {
        const type = document.getElementById('int_type').value;
        const loc = document.getElementById('int_location').value;
        const time = document.getElementById('int_schedule').value;
        if(!loc || !time) return alert('Lengkapi data!');
        const notes = `Tipe: ${type}\nLokasi: ${loc}\nWaktu: ${time.replace('T', ' ')}`;
        submitStatusUpdate(currentAppId, 'interview', notes);
        interviewModal.hide();
    }

    function submitStatusUpdate(applicationId, status, notes = null) {
        const spinner = document.getElementById('status-spinner');
        const selector = document.getElementById('status-selector');
        const tabNav = document.getElementById('interview-tab-nav');
        spinner.classList.remove('d-none');
        selector.disabled = true;

        axios.put(`/company/applications/${applicationId}/status`, { status, notes })
        .then(res => {
            if (res.data.success) {
                oldStatus = status;
                if (status === 'interview') {
                    tabNav.classList.remove('d-none');
                    if(notes) document.getElementById('text-notes-display').innerText = notes;
                } else { tabNav.classList.add('d-none'); }
                alert('Berhasil diperbarui');
            }
        })
        .catch(() => { alert('Gagal memperbarui'); selector.value = oldStatus; })
        .finally(() => { spinner.classList.add('d-none'); selector.disabled = false; });
    }
</script>
@endpush
@endsection