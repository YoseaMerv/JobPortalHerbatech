@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    @if($isLocked)
    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 mb-4 d-flex align-items-center">
        <div class="icon-box bg-warning text-white me-3 shadow-sm">
            <i class="fas fa-lock"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1">Profil Dikunci Sementara</h6>
            <p class="small mb-0 opacity-75">Anda tidak dapat mengubah profil karena sedang memiliki lamaran aktif bertatus <strong>Pending</strong>. Tunggu hingga proses selesai untuk memperbarui data kembali.</p>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-2">
                    <div class="list-group list-group-flush" id="profileTabs" role="tablist">
                        <a class="list-group-item list-group-item-action border-0 py-3 rounded-3 mb-1 active" data-bs-toggle="list" href="#identitas" role="tab">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3"><i class="fas fa-user-circle"></i></div>
                                <span class="fw-semibold">Identitas</span>
                            </div>
                        </a>
                        <a class="list-group-item list-group-item-action border-0 py-3 rounded-3 mb-1" data-bs-toggle="list" href="#professional" role="tab">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3"><i class="fas fa-id-badge"></i></div>
                                <span class="fw-semibold">Bio & Skill</span>
                            </div>
                        </a>
                        <a class="list-group-item list-group-item-action border-0 py-3 rounded-3 mb-1" data-bs-toggle="list" href="#history" role="tab">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3"><i class="fas fa-briefcase"></i></div>
                                <span class="fw-semibold">Riwayat</span>
                            </div>
                        </a>
                        <a class="list-group-item list-group-item-action border-0 py-3 rounded-3" data-bs-toggle="list" href="#documents" role="tab">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3"><i class="fas fa-file-upload"></i></div>
                                <span class="fw-semibold">Resume</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">

                <div class="tab-pane fade show active" id="identitas" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="fw-bold mb-1 text-dark">Informasi Pribadi</h4>
                            <p class="text-muted mb-4 small">Lengkapi data diri Anda untuk mempermudah HR menghubungi Anda.</p>

                            <form action="{{ route('seeker.profile.update') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control input-style" value="{{ $user->name }}" required {{ $isLocked ? 'readonly' : '' }}>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Alamat Email</label>
                                        <input type="email" class="form-control input-style bg-light" value="{{ $user->email }}" readonly disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nomor Telepon</label>
                                        <input type="text" name="phone" class="form-control input-style" value="{{ $profile?->phone }}" placeholder="Contoh: 08123456789" {{ $isLocked ? 'readonly' : '' }}>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Lokasi Domisili</label>
                                        <input type="text" name="home_location_details" class="form-control input-style" value="{{ $profile?->home_location_details }}" placeholder="Contoh: Jakarta Selatan" {{ $isLocked ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                @if(!$isLocked)
                                <div class="text-end mt-5">
                                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm transition-all hover-lift">Simpan Perubahan</button>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="professional" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <h4 class="fw-bold mb-1 text-dark">Profil Profesional</h4>
                        <p class="text-muted mb-4 small">Berikan ringkasan singkat mengenai nilai jual Anda di mata perusahaan.</p>

                        <form action="{{ route('seeker.profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-5">
                                <label class="form-label-custom">Tentang Saya / Ringkasan</label>
                                <textarea name="summary" class="form-control input-style p-3" rows="6" style="resize: none;" placeholder="Tuliskan pengalaman singkat dan keahlian utama Anda..." {{ $isLocked ? 'readonly' : '' }}>{{ $profile->summary }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-custom mb-3">Bahasa yang Dikuasai</label>
                                <div class="row g-3">
                                    @php $langs = ['Indonesia', 'Inggris', 'Mandarin', 'Jepang']; @endphp
                                    @foreach($langs as $lang)
                                    <div class="col-6 col-md-3">
                                        <div class="language-card border rounded-3 p-3 text-center position-relative transition-all {{ $isLocked ? 'opacity-75' : '' }}">
                                            <input class="form-check-input stretched-link d-none" type="checkbox" name="languages[]" value="{{ $lang }}" id="lang{{ $lang }}"
                                                {{ is_array($profile?->languages) && in_array($lang, $profile->languages) ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                                            <label class="form-check-label fw-semibold stretched-link" for="lang{{ $lang }}">{{ $lang }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @if(!$isLocked)
                            <div class="text-end mt-5">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-pill shadow-sm transition-all hover-lift">Simpan Profil</button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="tab-pane fade" id="history" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="fw-bold mb-0 text-dark">Pengalaman Kerja</h4>
                                    <small class="text-muted">Daftar riwayat karier profesional Anda.</small>
                                </div>
                                @if(!$isLocked)
                                <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold transition-all hover-lift" data-bs-toggle="modal" data-bs-target="#modalExperience">
                                    <i class="fas fa-plus me-1"></i> Tambah
                                </button>
                                @endif
                            </div>

                            <div class="timeline">
                                @forelse($profile->experiences as $exp)
                                <div class="timeline-item pb-4 position-relative ps-4">
                                    <div class="timeline-dot"></div>
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="pe-3">
                                            <h6 class="fw-bold text-dark mb-0">{{ $exp->job_title }}</h6>
                                            <p class="text-primary mb-1 small fw-semibold">{{ $exp->company_name }}</p>
                                            <small class="text-muted fw-medium">{{ $exp->start_date?->format('M Y') }} — {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Sekarang' }}</small>
                                            @if($exp->description)
                                            <p class="mt-2 text-secondary small opacity-75">{{ $exp->description }}</p>
                                            @endif
                                        </div>
                                        @if(!$isLocked)
                                        <form action="{{ route('seeker.profile.experience.destroy', $exp) }}" method="POST" class="flex-shrink-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 opacity-50 hover-opacity-100 transition-all" onclick="return confirm('Hapus riwayat ini?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <img src="https://illustrations.popsy.co/gray/work-from-home.svg" class="mb-3" style="height: 120px;">
                                    <p class="text-muted small">Belum ada riwayat pengalaman kerja.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">Pendidikan</h4>
                                <small class="text-muted">Riwayat pendidikan akademik terakhir Anda.</small>
                            </div>
                            @if(!$isLocked)
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold transition-all hover-lift" data-bs-toggle="modal" data-bs-target="#modalEducation">
                                <i class="fas fa-plus me-1"></i> Tambah
                            </button>
                            @endif
                        </div>

                        <div class="list-group list-group-flush border-0">
                            @forelse($profile->educations as $edu)
                            <div class="list-group-item border-0 p-0 mb-4 bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">{{ $edu->institution }}</h6>
                                        <p class="text-muted small mb-0">{{ $edu->degree }} • {{ $edu->field_of_study }}</p>
                                        <small class="text-primary fw-medium small">{{ $edu->start_date?->format('Y') }} — {{ $edu->end_date ? $edu->end_date->format('Y') : 'Masih Menempuh' }}</small>
                                    </div>
                                    @if(!$isLocked)
                                    <form action="{{ route('seeker.profile.education.destroy', $edu) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 opacity-50 hover-opacity-100 transition-all" onclick="return confirm('Hapus riwayat ini?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-center text-muted py-4 small">Belum ada riwayat pendidikan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <h4 class="fw-bold mb-1 text-dark">Resume & Portofolio</h4>
                        <p class="text-muted mb-5 small">Unggah CV terbaru agar perusahaan dapat mereview kualifikasi Anda dengan akurat.</p>

                        <form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data" id="resumeForm">
                            @csrf
                            @method('PATCH')

                            @if(!$isLocked)
                            <div class="upload-area border-2 border-dashed rounded-4 p-5 text-center bg-light transition-all mb-4"
                                onclick="document.getElementById('resumeInput').click()"
                                style="cursor: pointer;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary opacity-25 mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">Klik untuk pilih file CV</h6>
                                <p class="text-muted small mb-4">Format yang diterima: PDF, DOCX (Maksimal 5MB)</p>
                                <input type="file" name="resume" class="form-control d-none" id="resumeInput" onchange="this.form.submit()">
                                <button type="button" class="btn btn-white shadow-sm border rounded-pill px-4 py-2 fw-bold">Pilih File Baru</button>
                            </div>
                            @endif

                            @if($profile->resume_path)
                            <div class="d-flex align-items-center p-4 bg-white border rounded-4 shadow-sm mb-4 transition-all hover-shadow">
                                <div class="icon-box bg-danger-subtle text-danger rounded-3 p-3 me-3">
                                    <i class="fas fa-file-pdf fa-2x"></i>
                                </div>
                                <div class="overflow-hidden flex-grow-1">
                                    <p class="fw-bold text-dark mb-0 text-truncate small">{{ $profile->resume_filename ?? 'CV_Terunggah.pdf' }}</p>
                                    <div class="d-flex gap-3">
                                        <a href="{{ asset('storage/' . $profile->resume_path) }}" target="_blank" class="text-primary text-decoration-none small fw-bold">Pratinjau CV</a>
                                    </div>
                                </div>
                                <div class="status-badge">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Aktif</span>
                                </div>
                            </div>
                            @endif

                            @if(!$isLocked)
                            <div class="alert alert-light border-0 small text-muted rounded-3 px-4">
                                <i class="fas fa-info-circle me-2"></i> Perubahan pada CV akan otomatis tersimpan saat Anda memilih file baru.
                            </div>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Perbaikan Style agar Lebih Konsisten & Berkesan Premium --}}
<style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        border-radius: 1rem;
        border: none;
    }

    .icon-box {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background-color: #f1f3f5;
        color: #495057;
    }

    /* Navigasi */
    .list-group-item {
        transition: all 0.2s;
        border: none;
    }

    .list-group-item.active {
        background-color: #0d6efd !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }

    .list-group-item.active .icon-box {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Input */
    .input-style {
        background-color: #fcfcfc;
        border: 1.5px solid #f1f3f5;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .input-style:focus {
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.05);
        outline: none;
    }

    .form-label-custom {
        font-size: 0.65rem;
        font-weight: 800;
        color: #adb5bd;
        letter-spacing: 0.08rem;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    /* Utility */
    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
        border-color: #dee2e6 !important;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-2px);
    }

    .hover-shadow:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05) !important;
    }

    .hover-opacity-100:hover {
        opacity: 1 !important;
    }

    /* Bahasa */
    .language-card label {
        padding: 14px;
        border: 2px solid #f1f3f5;
        border-radius: 14px;
        cursor: pointer;
        display: block;
        font-size: 0.85rem;
        background: #fff;
    }

    .language-card input:checked+label {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    }

    /* Timeline */
    .timeline-item::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: #f1f3f5;
    }

    .timeline-dot {
        position: absolute;
        left: -4px;
        top: 5px;
        width: 10px;
        height: 10px;
        background-color: #0d6efd;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }
</style>

@include('seeker.profile.partials.modal-experience')
@include('seeker.profile.partials.modal-education')

@endsection