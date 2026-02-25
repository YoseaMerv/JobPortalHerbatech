@extends('layouts.seeker')

@section('content')
<div class="container py-5">
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
                                <span class="fw-semibold">Pengalaman</span>
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
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="fw-bold mb-1 text-dark">Informasi Pribadi</h4>
                            <p class="text-muted mb-4 small">Lengkapi data diri Anda untuk mempermudah HR menghubungi Anda.</p>
                            
                            <form action="{{ route('seeker.profile.update') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase tracking-wider text-muted">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control form-control-lg bg-light border-0 fs-6" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase tracking-wider text-muted">Alamat Email</label>
                                        <input type="email" class="form-control form-control-lg bg-light border-0 fs-6" value="{{ $user->email }}" readonly disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase tracking-wider text-muted">Nomor Telepon</label>
                                        <input type="text" name="phone" class="form-control form-control-lg bg-light border-0 fs-6" value="{{ $profile?->phone }}" placeholder="Contoh: 08123456789">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-uppercase tracking-wider text-muted">Lokasi Domisili</label>
                                        <input type="text" name="home_location_details" class="form-control form-control-lg bg-light border-0 fs-6" value="{{ $profile?->home_location_details }}" placeholder="Contoh: Jakarta Selatan">
                                    </div>
                                </div>
                                <div class="text-end mt-5">
                                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-3 shadow-sm">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="professional" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <h4 class="fw-bold mb-1 text-dark">Profil Profesional</h4>
                        <p class="text-muted mb-4 small">Berikan ringkasan singkat mengenai nilai jual Anda.</p>
                        
                        <form action="{{ route('seeker.profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-5">
                                <label class="form-label fw-bold small text-uppercase tracking-wider text-muted">Tentang Saya</label>
                                <textarea name="summary" class="form-control bg-light border-0 p-3" rows="6" style="resize: none;" placeholder="Tuliskan pengalaman singkat Anda...">{{ $profile->summary }}</textarea>
                            </div>
                            
                            <div>
                                <label class="form-label fw-bold small text-uppercase tracking-wider text-muted mb-3">Bahasa yang Dikuasai</label>
                                <div class="row g-3">
                                    @php $langs = ['Indonesia', 'Inggris', 'Mandarin', 'Jepang']; @endphp
                                    @foreach($langs as $lang)
                                        <div class="col-6 col-md-3">
                                            <div class="language-card border rounded-3 p-3 text-center position-relative transition-all">
                                                <input class="form-check-input stretched-link d-none" type="checkbox" name="languages[]" value="{{ $lang }}" id="lang{{ $lang }}" 
                                                {{ is_array($profile?->languages) && in_array($lang, $profile->languages) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-semibold stretched-link" for="lang{{ $lang }}">{{ $lang }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-end mt-5">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-3 shadow-sm">Simpan Profil</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="tab-pane fade" id="history" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="fw-bold mb-0 text-dark">Pengalaman Kerja</h4>
                                    <small class="text-muted">Riwayat karier profesional Anda.</small>
                                </div>
                                <button class="btn btn-outline-primary btn-sm rounded-3 px-3 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalExperience">
                                    <i class="fas fa-plus me-2"></i> Tambah
                                </button>
                            </div>

                            <div class="timeline">
                                @forelse($profile->experiences as $exp)
                                    <div class="timeline-item pb-4 position-relative ps-4">
                                        <div class="timeline-dot"></div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0">{{ $exp->job_title }}</h6>
                                                <p class="text-primary mb-1 small fw-semibold">{{ $exp->company_name }}</p>
                                                <small class="text-muted fw-medium">{{ $exp->start_date?->format('M Y') }} — {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Sekarang' }}</small>
                                                <p class="mt-2 text-secondary small opacity-75">{{ $exp->description }}</p>
                                            </div>
                                            <form action="{{ route('seeker.profile.experience.destroy', $exp) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 opacity-50 hover-opacity-100" onclick="return confirm('Hapus riwayat ini?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center text-muted py-4">Belum ada pengalaman kerja.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">Pendidikan</h4>
                                <small class="text-muted">Riwayat pendidikan akademik Anda.</small>
                            </div>
                            <button class="btn btn-outline-primary btn-sm rounded-3 px-3 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalEducation">
                                <i class="fas fa-plus me-2"></i> Tambah
                            </button>
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
                                        <form action="{{ route('seeker.profile.education.destroy', $edu) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 opacity-50" onclick="return confirm('Hapus riwayat ini?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted py-4">Belum ada riwayat pendidikan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <h4 class="fw-bold mb-1 text-dark">Resume & Portofolio</h4>
                        <p class="text-muted mb-5 small">Pastikan file terbaru diunggah agar perusahaan dapat mengunduhnya.</p>
                        
                        <form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            
                            <div class="upload-area border-2 border-dashed rounded-4 p-5 text-center bg-light transition-all mb-4">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary opacity-25 mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">Klik untuk pilih file CV</h6>
                                <p class="text-muted small mb-4">PDF, DOCX (Maksimal 5MB)</p>
                                <input type="file" name="resume" class="form-control d-none" id="resumeInput">
                                <button type="button" class="btn btn-white shadow-sm border rounded-3 px-4 py-2" onclick="document.getElementById('resumeInput').click()">Pilih File</button>
                            </div>

                            @if($profile->resume_path)
                                <div class="d-flex align-items-center p-4 bg-white border rounded-4 shadow-sm mb-4">
                                    <div class="icon-box bg-danger-subtle text-danger rounded-3 p-3 me-3">
                                        <i class="fas fa-file-pdf fa-2x"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="fw-bold text-dark mb-0 text-truncate small">{{ $profile->resume_filename ?? 'CV_Terunggah.pdf' }}</p>
                                        <a href="{{ asset('storage/' . $profile->resume_path) }}" target="_blank" class="text-primary text-decoration-none small fw-bold">Buka File</a>
                                    </div>
                                </div>
                            @endif

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold rounded-3 shadow-sm">Upload & Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Custom Styles for Premium Feel --}}
<style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 1rem; }
    .list-group-item.active { background-color: #0d6efd !important; color: white !important; }
    .list-group-item.active .icon-box { background-color: rgba(255, 255, 255, 0.2); }
    .icon-box { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 10px; background-color: #f1f3f5; color: #495057; }
    .transition-all { transition: all 0.3s ease; }
    .language-card input:checked + label { background-color: #0d6efd; color: white; border-color: #0d6efd; }
    .language-card label { padding: 12px; border: 2px solid #e9ecef; border-radius: 12px; cursor: pointer; display: block; }
    .timeline-item::before { content: ""; position: absolute; left: 0; top: 0; height: 100%; width: 2px; background-color: #e9ecef; }
    .timeline-dot { position: absolute; left: -4px; top: 5px; width: 10px; height: 10px; background-color: #0d6efd; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1); }
    .border-dashed { border-style: dashed !important; border-width: 2px !important; border-color: #dee2e6 !important; }
    .upload-area:hover { background-color: #f1f3f5 !important; border-color: #0d6efd !important; }
    .hover-opacity-100:hover { opacity: 1 !important; }
    .tracking-wider { letter-spacing: 0.05em; }
</style>

{{-- Modals --}}
@include('seeker.profile.partials.modal-experience')
@include('seeker.profile.partials.modal-education')

@endsection