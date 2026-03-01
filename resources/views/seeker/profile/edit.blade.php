@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    
    {{-- Widget Progress Kelengkapan Profil - Hanya muncul jika < 100% --}}
    @if($completeness < 100)
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">Kelengkapan Profil</h6>
            <span class="badge bg-primary rounded-pill">{{ round($completeness) }}%</span>
        </div>
        <div class="progress" style="height: 10px; border-radius: 5px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                style="width: {{ $completeness }}%" aria-valuenow="{{ $completeness }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <small class="text-muted mt-2 d-block">
            <i class="fas fa-info-circle me-1"></i>
            Lengkapi profil hingga <strong>100%</strong> untuk membuat HR tertarik melihat lamaran Anda.
        </small>
    </div>
    @endif

    {{-- Notifikasi Info Terkunci (Manual, tidak hilang otomatis) --}}
    @if($isLocked)
    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 mb-4 d-flex align-items-center" id="lock-alert">
        <div class="icon-box bg-warning text-white me-3 shadow-sm">
            <i class="fas fa-lock"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-1">Profil Dikunci Sementara</h6>
            <p class="small mb-0 opacity-75">Anda tidak dapat mengubah profil karena sedang memiliki lamaran aktif berstatus <strong>Pending</strong>.</p>
        </div>
    </div>
    @endif

    {{-- NOTIFIKASI SUKSES/ERROR DIHAPUS DARI SINI KARENA SUDAH ADA DI LAYOUT --}}

    <div class="row g-4">
        {{-- Sidebar Tab Navigation --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-2">
                    <div class="list-group list-group-flush" id="profileTabs" role="tablist">
                        <a class="list-group-item list-group-item-action border-0 py-3 rounded-3 mb-1 active" data-bs-toggle="list" href="#identitas" role="tab">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3"><i class="fas fa-user-circle"></i></div>
                                <span class="fw-semibold">Identitas Dasar</span>
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
                                <span class="fw-semibold">Resume / CV</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Contents --}}
        <div class="col-md-9">
            <div class="tab-content">

                {{-- TAB 1: IDENTITAS --}}
                <div class="tab-pane fade show active" id="identitas" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="fw-bold mb-1 text-dark">Informasi Pribadi</h4>
                            <p class="text-muted mb-4 small">Lengkapi data diri Anda secara akurat.</p>

                            <form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="row g-4">
                                    <div class="col-md-12 mb-3 text-center">
                                        <div class="position-relative d-inline-block">
                                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D6EFD&color=fff' }}"
                                                class="rounded-circle shadow-sm border p-1"
                                                style="width: 120px; height: 120px; object-fit: cover;"
                                                id="avatarPreview">

                                            @if(!$isLocked)
                                            <label for="avatarInput" class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle shadow" style="width: 32px; height: 32px; padding: 0; line-height: 32px; cursor: pointer;">
                                                <i class="fas fa-camera"></i>
                                            </label>
                                            <input type="file" name="avatar" class="d-none" id="avatarInput" accept="image/*" onchange="previewImage(this)">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control input-style" value="{{ old('name', $user->name) }}" required {{ $isLocked ? 'readonly' : '' }}>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Nomor Telepon <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control input-style" value="{{ old('phone', $profile?->phone) }}" placeholder="0812..." required {{ $isLocked ? 'readonly' : '' }}>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label-custom">Alamat Lengkap (Domisili saat ini) <span class="text-danger">*</span></label>
                                        <textarea name="home_location_details" 
                                                class="form-control input-style" 
                                                rows="3" 
                                                placeholder="Contoh:  Dusun II, Toyareka, Kec. Kemangkon, Kabupaten Purbalingga, Jawa Tengah" 
                                                required {{ $isLocked ? 'readonly' : '' }}>{{ old('home_location_details', $profile?->home_location_details) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Tanggal Lahir</label>
                                        <input type="date" name="date_of_birth" class="form-control input-style" value="{{ old('date_of_birth', $profile->birth_date ? \Carbon\Carbon::parse($profile->birth_date)->format('Y-m-d') : '') }}" {{ $isLocked ? 'readonly' : '' }}>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Jenis Kelamin</label>
                                        <select name="gender" class="form-select input-style" {{ $isLocked ? 'disabled' : '' }}>
                                            <option value="" selected disabled>Pilih...</option>
                                            <option value="Laki-laki" {{ (old('gender', $profile?->gender) == 'male' || old('gender', $profile?->gender) == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ (old('gender', $profile?->gender) == 'female' || old('gender', $profile?->gender) == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Ekspektasi Gaji</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-light">Rp</span>
                                            <input type="number" name="expected_salary" class="form-control input-style" value="{{ old('expected_salary', $profile?->expected_salary) }}" {{ $isLocked ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-custom">LinkedIn / Portofolio URL</label>
                                        <input type="url" name="linkedin_url" class="form-control input-style" value="{{ old('linkedin_url', $profile?->linkedin_url) }}" placeholder="https://..." {{ $isLocked ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                @if(!$isLocked)
                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">Simpan Identitas</button>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: BIO & SKILL --}}
                <div class="tab-pane fade" id="professional" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <h4 class="fw-bold mb-1 text-dark">Profil Profesional</h4>
                        <form action="{{ route('seeker.profile.update') }}" method="POST">
                            @csrf @method('PATCH')
                            <div class="mb-4">
                                <label class="form-label-custom">Tentang Saya</label>
                                <textarea name="about" class="form-control input-style" rows="5" {{ $isLocked ? 'readonly' : '' }}>{{ old('about', $profile?->summary) }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label-custom mb-3">Bahasa</label>
                                <div class="row g-3">
                                    @foreach(['Indonesia', 'Inggris', 'Mandarin', 'Jepang'] as $lang)
                                    <div class="col-6 col-md-3">
                                        <div class="language-card">
                                            <input class="form-check-input d-none" type="checkbox" name="languages[]" value="{{ $lang }}" id="lang{{ $lang }}"
                                                {{ in_array($lang, (array)($profile?->languages ?? [])) ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                                            <label class="form-check-label fw-semibold w-100 py-2 border rounded-3 text-center cursor-pointer" for="lang{{ $lang }}">{{ $lang }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @if(!$isLocked)
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold">Simpan Bio</button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- TAB 3: RIWAYAT --}}
                <div class="tab-pane fade" id="history" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0">Pengalaman Kerja</h4>
                            @if(!$isLocked)
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalExperience"><i class="fas fa-plus me-1"></i> Tambah</button>
                            @endif
                        </div>
                        <div class="timeline">
                            @forelse($profile?->experiences ?? [] as $exp)
                            <div class="timeline-item pb-4 ps-4 position-relative">
                                <div class="timeline-dot"></div>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $exp->job_title }}</h6>
                                        <p class="text-primary small mb-1">{{ $exp->company_name }}</p>
                                        <small class="text-muted">{{ $exp->start_date?->format('M Y') }} — {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Sekarang' }}</small>
                                    </div>
                                    @if(!$isLocked)
                                    <form action="{{ route('seeker.profile.experience.destroy', $exp) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Hapus?')"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-muted small text-center">Belum ada riwayat kerja.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0">Pendidikan</h4>
                            @if(!$isLocked)
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalEducation"><i class="fas fa-plus me-1"></i> Tambah</button>
                            @endif
                        </div>
                        @forelse($profile?->educations ?? [] as $edu)
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $edu->institution }}</h6>
                                <p class="text-muted small mb-0">{{ $edu->degree }} • {{ $edu->field_of_study }}</p>
                            </div>
                            @if(!$isLocked)
                            <form action="{{ route('seeker.profile.education.destroy', $edu) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Hapus?')"><i class="fas fa-trash-alt"></i></button>
                            </form>
                            @endif
                        </div>
                        @empty
                        <p class="text-muted small text-center">Belum ada riwayat pendidikan.</p>
                        @endforelse
                    </div>
                </div>

                {{-- TAB 4: DOCUMENTS --}}
                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <h4 class="fw-bold mb-1">Resume / CV</h4>
                        <form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PATCH')
                            @if(!$isLocked)
                            <div class="upload-area border-2 border-dashed rounded-4 p-5 text-center bg-light mb-4" onclick="document.getElementById('resumeInput').click()" style="cursor: pointer;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary opacity-25 mb-3"></i>
                                <h6 class="fw-bold">Klik untuk unggah CV Baru</h6>
                                <input type="file" name="resume" class="d-none" id="resumeInput" onchange="this.form.submit()">
                            </div>
                            @endif

                            @if($profile?->resume_path)
                            <div class="p-3 border rounded-4 d-flex align-items-center justify-content-between shadow-sm">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                    <div>
                                        <p class="fw-bold mb-0 small text-truncate" style="max-width: 200px;">{{ $profile->resume_filename ?? 'CV_Aktif.pdf' }}</p>
                                        <a href="{{ asset('storage/' . $profile->resume_path) }}" target="_blank" class="small text-primary fw-bold text-decoration-none">Lihat CV</a>
                                    </div>
                                </div>
                                <span class="badge bg-success rounded-pill">Aktif</span>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fa; }
    .icon-box { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 12px; background-color: #f1f3f5; color: #495057; }
    .list-group-item.active { background-color: #0d6efd !important; border: none; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2); }
    .input-style { background-color: #fcfcfc; border: 1.5px solid #f1f3f5; border-radius: 12px; padding: 12px; font-size: 0.95rem; }
    .input-style:focus { border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.05); outline: none; }
    .form-label-custom { font-size: 0.65rem; font-weight: 800; color: #adb5bd; letter-spacing: 0.08rem; text-transform: uppercase; margin-bottom: 8px; display: block; }
    .language-card input:checked + label { background-color: #0d6efd !important; color: white !important; border-color: #0d6efd !important; }
    .timeline-item::before { content: ""; position: absolute; left: 0; top: 0; height: 100%; width: 2px; background-color: #f1f3f5; }
    .timeline-dot { position: absolute; left: -4px; top: 5px; width: 10px; height: 10px; background-color: #0d6efd; border-radius: 50%; border: 2px solid white; }
    .cursor-pointer { cursor: pointer; }
</small>
</style>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { document.getElementById('avatarPreview').src = e.target.result; }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@include('seeker.profile.partials.modal-experience')
@include('seeker.profile.partials.modal-education')
@endsection