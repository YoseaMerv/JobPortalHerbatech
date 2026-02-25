@extends('layouts.company')

@section('title', 'Pasang Lowongan Baru')

@section('content')
<style>
    /* Konsistensi Font Modern */
    body, .full-container, button, input, select, textarea {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif !important;
    }

    :root {
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --text-main: #334155; 
        --text-muted: #64748b;
        --text-heading: #1e293b;
        --brand-indigo: #4338ca; 
    }

    /* Container Full Width */
    .full-container { width: 100%; max-width: 100%; padding: 0 15px; }
    
    .create-card { 
        border-radius: 16px; 
        border: 1px solid var(--slate-200); 
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .card-header-full {
        padding: 30px 40px;
        border-bottom: 1px solid var(--slate-100);
        background: linear-gradient(to right, #ffffff, var(--slate-50));
    }

    /* Form Elements Styling */
    .form-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid var(--slate-200);
        padding: 12px 16px;
        font-size: 0.95rem;
        color: var(--text-main);
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--brand-indigo);
        box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1);
        outline: none;
    }

    .section-label-display {
        font-size: 0.85rem; 
        font-weight: 800; 
        color: var(--brand-indigo);
        text-transform: uppercase; 
        letter-spacing: 0.1em; 
        margin: 40px 0 20px 0;
        display: flex;
        align-items: center;
    }
    .section-label-display::after {
        content: ""; flex: 1; height: 1px; background: var(--slate-100); margin-left: 20px;
    }

    .input-group-text {
        background-color: var(--slate-50);
        border-color: var(--slate-200);
        color: var(--text-muted);
        border-radius: 10px 0 0 10px;
        font-weight: 600;
    }
</style>

<div class="full-container">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('company.jobs.index') }}" class="text-decoration-none text-muted fw-bold small">
            <i class="fas fa-chevron-left me-2"></i> KEMBALI KE MANAJEMEN
        </a>
    </div>

    <div class="card create-card border-0">
        <div class="card-header-full">
            <h1 class="h4 fw-bold mb-1" style="color: var(--text-heading);">Pasang Lowongan Baru</h1>
            <p class="text-muted small mb-0">Lengkapi formulir di bawah untuk mempublikasikan peluang karir di HerbaTech.</p>
        </div>

        <div class="card-body p-4 p-md-5">
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px;">
                    <ul class="mb-0 small fw-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('company.jobs.store') }}" method="POST">
                @csrf
                
                <div class="section-label-display" style="margin-top: 0;">Identitas Posisi</div>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Jabatan / Judul Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Senior Pharmacist" required>
                    </div>
                    <div class="col-md-6">
                        <label for="department" class="form-label">Departemen</label>
                        <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ old('department') }}" placeholder="Contoh: Research & Development">
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            @foreach(\App\Models\JobCategory::where('is_active', true)->get() as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="location_id" class="form-label">Lokasi Wilayah <span class="text-danger">*</span></label>
                        <select class="form-select @error('location_id') is-invalid @enderror" id="location_id" name="location_id" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach(\App\Models\JobLocation::where('is_active', true)->get() as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="work_setting" class="form-label">Opsi Tempat Kerja <span class="text-danger">*</span></label>
                        <select class="form-select @error('work_setting') is-invalid @enderror" id="work_setting" name="work_setting" required>
                            <option value="on_site" {{ old('work_setting') == 'on_site' ? 'selected' : '' }}>On-site (Di Kantor)</option>
                            <option value="hybrid" {{ old('work_setting') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="remote" {{ old('work_setting') == 'remote' ? 'selected' : '' }}>Remote (Jarak Jauh)</option>
                        </select>
                    </div>
                </div>

                <div class="section-label-display">Ketentuan & Kompensasi</div>
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label for="job_type" class="form-label">Jenis Pekerjaan <span class="text-danger">*</span></label>
                        <select class="form-select @error('job_type') is-invalid @enderror" id="job_type" name="job_type" required>
                            <option value="full_time" {{ old('job_type') == 'full_time' ? 'selected' : '' }}>Purnawaktu (Full-time)</option>
                            <option value="part_time" {{ old('job_type') == 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                            <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                            <option value="internship" {{ old('job_type') == 'internship' ? 'selected' : '' }}>Magang</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="salary_min" class="form-label">Gaji Minimum (Rp)</label>
                        <input type="number" class="form-control" name="salary_min" value="{{ old('salary_min') }}" placeholder="0">
                    </div>
                    <div class="col-md-4">
                        <label for="salary_max" class="form-label">Gaji Maksimum (Rp)</label>
                        <input type="number" class="form-control" name="salary_max" value="{{ old('salary_max') }}" placeholder="0">
                    </div>
                </div>

                <div class="row g-4 mb-4 align-items-end">
                    <div class="col-md-4">
                        <label for="salary_type" class="form-label">Tampilkan Gaji Per</label>
                        <select class="form-select" name="salary_type">
                            <option value="monthly" {{ old('salary_type') == 'monthly' ? 'selected' : '' }}>Bulan</option>
                            <option value="yearly" {{ old('salary_type') == 'yearly' ? 'selected' : '' }}>Tahun</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="vacancy" class="form-label">Jumlah Lowongan (Orang)</label>
                        <input type="number" class="form-control" name="vacancy" value="{{ old('vacancy', 1) }}" min="1">
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch pb-2">
                            <input type="hidden" name="is_salary_visible" value="0">
                            <input class="form-check-input" type="checkbox" name="is_salary_visible" id="is_salary_visible" value="1" {{ old('is_salary_visible', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold small" for="is_salary_visible">Publikasikan Estimasi Gaji</label>
                        </div>
                    </div>
                </div>

                <div class="section-label-display">Detail Persyaratan</div>
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="experience_level" class="form-label">Minimal Pengalaman <span class="text-danger">*</span></label>
                        <select class="form-select" name="experience_level" required>
                            <option value="entry_level" {{ old('experience_level') == 'entry_level' ? 'selected' : '' }}>Fresh Graduate / Entry Level</option>
                            <option value="1_3_years" {{ old('experience_level') == '1_3_years' ? 'selected' : '' }}>1 - 3 Tahun</option>
                            <option value="3_5_years" {{ old('experience_level') == '3_5_years' ? 'selected' : '' }}>3 - 5 Tahun</option>
                            <option value="more_than_5_years" {{ old('experience_level') == 'more_than_5_years' ? 'selected' : '' }}>Diatas 5 Tahun</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="deadline" class="form-label">Batas Waktu Lamaran <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="deadline" name="deadline" value="{{ old('deadline') }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="6" placeholder="Jelaskan tugas dan tanggung jawab harian..." required style="line-height: 1.6;">{{ old('description') }}</textarea>
                </div>

                <div class="mb-5">
                    <label for="requirements" class="form-label">Kualifikasi Pelamar <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="requirements" name="requirements" rows="6" placeholder="Contoh: Pendidikan minimal, skill teknis, sertifikasi..." required style="line-height: 1.6;">{{ old('requirements') }}</textarea>
                </div>

                <div class="card-footer bg-white px-0 py-4 border-top d-flex justify-content-between align-items-center">
                    <a href="{{ route('company.jobs.index') }}" class="btn btn-link text-muted fw-bold text-decoration-none">
                        BATALKAN
                    </a>
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow" style="border-radius: 12px; background: var(--brand-indigo); border: none;">
                        TERBITKAN LOWONGAN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection