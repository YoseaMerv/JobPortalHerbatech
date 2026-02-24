@extends('layouts.seeker')

@section('title', 'Kelola Profil')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Personal Information & Stats -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center p-4">
                    <div class="position-relative d-inline-block mb-3">
                        @if($profile->profile_picture)
                            <img src="{{ asset('storage/' . $profile->profile_picture) }}" alt="Foto Profil" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto shadow-sm" style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-4x text-secondary"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                    <div class="d-grid gap-2">
                        <form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                            @csrf
                            @method('PUT')
                            <input type="file" name="profile_picture" id="profile_picture" class="d-none" onchange="document.getElementById('photoForm').submit()">
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="document.getElementById('profile_picture').click()">
                                <i class="fas fa-camera me-1"></i> Ubah Foto
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Resume/CV Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Resume Profesional</h5>
                </div>
                <div class="card-body p-4">
                    @if($profile->resume_path)
                        <div class="d-flex align-items-center p-3 bg-light rounded mb-3">
                            <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-0 fw-bold text-truncate">Resume_Saya.pdf</p>
                                <small class="text-muted">Diunggah {{ $profile->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3 mb-3 border border-dashed rounded bg-light">
                            <i class="fas fa-file-upload fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">Belum ada resume yang diunggah</p>
                        </div>
                    @endif

                    <form action="{{ route('seeker.profile.upload-resume') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group input-group-sm">
                            <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" required>
                            <button type="submit" class="btn btn-primary">Unggah</button>
                        </div>
                        <small class="text-muted d-block mt-2">PDF, DOC, DOCX (Maks 2MB)</small>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Profile Forms -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Informasi Dasar</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('seeker.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $profile->phone) }}" placeholder="+62...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Lokasi/Alamat</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $profile->address) }}" placeholder="Contoh: Jakarta, Indonesia">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Bio Singkat</label>
                                <textarea name="bio" class="form-control" rows="4" placeholder="Jelaskan secara singkat latar belakang profesional Anda...">{{ old('bio', $profile->bio) }}</textarea>
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Education & Experience Tabs -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-0">
                    <ul class="nav nav-pills p-3 bg-light" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#education" type="button">Pendidikan</button>
                        </li>
                        <li class="nav-item ms-2" role="presentation">
                            <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#experience" type="button">Pengalaman</button>
                        </li>
                        <li class="nav-item ms-2" role="presentation">
                            <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#skills" type="button">Keahlian</button>
                        </li>
                        <li class="nav-item ms-2" role="presentation">
                            <button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#certificates" type="button">Sertifikat</button>
                        </li>
                    </ul>
                    <div class="tab-content p-4">
                        <!-- Education Tab -->
                        <div class="tab-pane fade show active" id="education">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-bold mb-0">Riwayat Pendidikan</h6>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEducationModal">
                                    <i class="fas fa-plus me-1"></i> Tambah Pendidikan
                                </button>
                            </div>

                            @forelse($educations as $edu)
                                <div class="d-flex border-start border-primary border-4 p-3 bg-light rounded mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $edu->degree }}</h6>
                                        <p class="mb-0 small text-dark">{{ $edu->institution }}</p>
                                        <p class="mb-0 smaller text-muted">{{ \Carbon\Carbon::parse($edu->start_date)->translatedFormat('M Y') }} - {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->translatedFormat('M Y') : 'Sekarang' }}</p>
                                    </div>
                                    <div>
                                        <form action="{{ route('seeker.profile.education.destroy', $edu->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Hapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-muted small">Tidak ada data pendidikan yang ditemukan.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Experience Tab -->
                        <div class="tab-pane fade" id="experience">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-bold mb-0">Pengalaman Kerja</h6>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addExperienceModal">
                                    <i class="fas fa-plus me-1"></i> Tambah Pengalaman
                                </button>
                            </div>

                            @forelse($experiences as $exp)
                                <div class="d-flex border-start border-success border-4 p-3 bg-light rounded mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $exp->job_title }}</h6>
                                        <p class="mb-0 small text-dark">{{ $exp->company_name }} | {{ $exp->location }}</p>
                                        <p class="mb-0 smaller text-muted">{{ \Carbon\Carbon::parse($exp->start_date)->translatedFormat('M Y') }} - {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->translatedFormat('M Y') : 'Sekarang' }}</p>
                                        @if($exp->description)
                                            <p class="mt-2 mb-0 smaller text-muted">{{ Str::limit($exp->description, 150) }}</p>
                                        @endif
                                    </div>
                                    <div>
                                        <form action="{{ route('seeker.profile.experience.destroy', $exp->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Hapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-muted small">Tidak ada data pengalaman kerja yang ditemukan.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Skills Tab -->
                        <div class="tab-pane fade" id="skills">
                             <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-bold mb-0">Keahlian Saya</h6>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSkillModal">
                                    <i class="fas fa-plus me-1"></i> Tambah Keahlian
                                </button>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                @forelse($skills as $skill)
                                    <div class="badge bg-light text-dark border p-2 fw-normal d-flex align-items-center rounded-pill">
                                        <span class="me-2">{{ $skill->skill_name }}</span>
                                        <span class="text-primary small fw-bold me-2">({{ match($skill->proficiency_level) {
                                            'beginner' => 'Pemula',
                                            'intermediate' => 'Menengah',
                                            'advanced' => 'Lanjutan',
                                            'expert' => 'Ahli',
                                            default => ucfirst($skill->proficiency_level)
                                        } }})</span>
                                        <form action="{{ route('seeker.profile.skill.destroy', $skill->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-close" style="font-size: 0.5rem;" onclick="return confirm('Hapus keahlian?')"></button>
                                        </form>
                                    </div>
                                @empty
                                    <div class="w-100 text-center py-4">
                                        <p class="text-muted small">Belum ada keahlian yang ditambahkan.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Certificates Tab -->
                        <div class="tab-pane fade" id="certificates">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-bold mb-0">Sertifikat & Dokumen Pendukung</h6>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCertificateModal">
                                    <i class="fas fa-plus me-1"></i> Tambah Sertifikat
                                </button>
                            </div>

                            @forelse($certificates as $cert)
                                <div class="d-flex border-start border-warning border-4 p-3 bg-light rounded mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $cert->name }}</h6>
                                        <p class="mb-0 small text-dark">{{ $cert->issuer }}</p>
                                        <p class="mb-0 smaller text-muted">Diterbitkan: {{ \Carbon\Carbon::parse($cert->issued_date)->translatedFormat('M Y') }}</p>
                                        @if($cert->description)
                                            <p class="mt-2 mb-0 smaller text-muted">{{ Str::limit($cert->description, 150) }}</p>
                                        @endif
                                        @if($cert->file_path)
                                            <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank" class="btn btn-link btn-sm p-0 mt-1 text-decoration-none">
                                                <i class="fas fa-paperclip me-1"></i> Lihat Dokumen
                                            </a>
                                        @endif
                                    </div>
                                    <div>
                                        <form action="{{ route('seeker.profile.certificate.destroy', $cert->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Hapus sertifikat ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-muted small">Belum ada sertifikat yang ditambahkan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Add Education Modal -->
<div class="modal fade" id="addEducationModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('seeker.profile.education.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Pendidikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Gelar/Kualifikasi</label>
                    <input type="text" name="degree" class="form-control" placeholder="Contoh: Sarjana Ilmu Komputer" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Institusi/Universitas</label>
                    <input type="text" name="institution" class="form-control" placeholder="Contoh: Universitas Indonesia" required>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Tanggal Selesai (estimasi)</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Pendidikan</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Experience Modal -->
<div class="modal fade" id="addExperienceModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('seeker.profile.experience.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Pengalaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Judul Pekerjaan</label>
                    <input type="text" name="job_title" class="form-control" placeholder="Contoh: Software Engineer" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Perusahaan</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Lokasi</label>
                    <input type="text" name="location" class="form-control" placeholder="Contoh: Jakarta (Onsite/Remote)">
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Pengalaman</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Skill Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('seeker.profile.skill.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Keahlian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Keahlian</label>
                    <input type="text" name="skill_name" class="form-control" required placeholder="Contoh: PHP, Laravel">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Kemahiran</label>
                    <select name="proficiency_level" class="form-select">
                        <option value="beginner">Pemula</option>
                        <option value="intermediate" selected>Menengah</option>
                        <option value="advanced">Lanjutan</option>
                        <option value="expert">Ahli</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary w-100">Tambah Keahlian</button>
            </div>
        </form>
    </div>
</div>
<!-- Add Certificate Modal -->
<div class="modal fade" id="addCertificateModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('seeker.profile.certificate.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Sertifikat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Sertifikat/Dokumen</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Sertifikat Kompetensi Web" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Penerbit/Institusi</label>
                    <input type="text" name="issuer" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Tanggal Terbit</label>
                    <input type="date" name="issued_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">File (Opsional)</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">PDF, JPG, PNG (Maks 2MB)</small>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Keterangan Tambahan</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Sertifikat</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-pills .nav-link.active {
        background-color: var(--bs-primary);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    }
    .nav-pills .nav-link {
        color: #555;
        font-weight: 500;
        transition: all 0.2s;
    }
    .border-dashed {
        border-style: dashed !important;
    }
    .smaller {
        font-size: 0.75rem;
    }
</style>
@endpush
