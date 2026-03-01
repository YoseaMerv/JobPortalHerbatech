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
    .form-card {
        border-radius: 16px;
        border: 1px solid var(--slate-200);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        background: #fff;
    }
    .form-card-header {
        border-bottom: 1px solid var(--slate-100);
        padding: 24px;
        background: #fff;
        border-radius: 16px 16px 0 0;
    }
    .form-label-custom {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .input-style {
        background-color: var(--slate-50);
        border: 1px solid var(--slate-200);
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .input-style:focus {
        background-color: #fff;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
    }
    .input-group-text-custom {
        background-color: var(--slate-100);
        border: 1px solid var(--slate-200);
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #64748b;
        font-weight: 600;
    }
    .switch-label {
        font-weight: 600;
        color: var(--text-heading);
        cursor: pointer;
    }
</style>

<div class="container-fluid pb-5">
    <div class="mb-4">
        <a href="{{ route('admin.jobs.index') }}" class="text-decoration-none text-muted fw-bold small">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Lowongan
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="form-card">
                <div class="form-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--text-heading);">Buat Lowongan Baru</h4>
                        <p class="text-muted small mb-0">Isi formulir di bawah ini untuk membuka posisi baru di HerbaTech.</p>
                    </div>
                </div>

                <form action="{{ route('admin.jobs.store') }}" method="POST">
                    @csrf
                    
                    <div class="card-body p-4 p-md-5">
                        {{-- Bagian 1: Informasi Dasar --}}
                        <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="fas fa-info-circle text-primary mr-2"></i>Informasi Dasar</h6>
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label class="form-label-custom">Judul Lowongan <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control input-style @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Senior Software Engineer" required>
                                @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Perusahaan <span class="text-danger">*</span></label>
                                <select name="company_id" class="form-control input-style @error('company_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Perusahaan</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-md-6 mt-4">
                                <label class="form-label-custom">Kategori Pekerjaan <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control input-style @error('category_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mt-4">
                                <label class="form-label-custom">Lokasi Penempatan <span class="text-danger">*</span></label>
                                <select name="location_id" class="form-control input-style @error('location_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Lokasi</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Bagian 2: Detail Pekerjaan --}}
                        <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="fas fa-align-left text-warning mr-2"></i>Detail Pekerjaan</h6>
                        <div class="row g-4 mb-5">
                            <div class="col-12">
                                <label class="form-label-custom">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                                <textarea name="description" rows="5" class="form-control input-style @error('description') is-invalid @enderror" placeholder="Tuliskan deskripsi lengkap tentang pekerjaan ini..." required>{{ old('description') }}</textarea>
                                @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-md-6 mt-4">
                                <label class="form-label-custom">Persyaratan Khusus</label>
                                <textarea name="requirements" rows="4" class="form-control input-style @error('requirements') is-invalid @enderror" placeholder="Gunakan baris baru (Enter) untuk setiap poin.">{{ old('requirements') }}</textarea>
                                @error('requirements') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6 mt-4">
                                <label class="form-label-custom">Tanggung Jawab Utama</label>
                                <textarea name="responsibilities" rows="4" class="form-control input-style @error('responsibilities') is-invalid @enderror" placeholder="Gunakan baris baru (Enter) untuk setiap poin.">{{ old('responsibilities') }}</textarea>
                                @error('responsibilities') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Bagian 3: Kompensasi & Kualifikasi --}}
                        <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="fas fa-money-bill-wave text-success mr-2"></i>Kompensasi & Kualifikasi</h6>
                        <div class="row g-4 mb-5">
                            <div class="col-md-4">
                                <label class="form-label-custom">Tipe Gaji <span class="text-danger">*</span></label>
                                <select name="salary_type" class="form-control input-style @error('salary_type') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Tipe</option>
                                    <option value="monthly" {{ old('salary_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="project" {{ old('salary_type') == 'project' ? 'selected' : '' }}>Sistem Proyek</option>
                                    <option value="hourly" {{ old('salary_type') == 'hourly' ? 'selected' : '' }}>Per Jam</option>
                                    <option value="yearly" {{ old('salary_type') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Gaji Minimum (IDR)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text input-group-text-custom">Rp</span>
                                    </div>
                                    <input type="number" name="salary_min" class="form-control input-style" style="border-radius: 0 12px 12px 0;" placeholder="0" value="{{ old('salary_min') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Gaji Maksimum (IDR)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text input-group-text-custom">Rp</span>
                                    </div>
                                    <input type="number" name="salary_max" class="form-control input-style" style="border-radius: 0 12px 12px 0;" placeholder="0" value="{{ old('salary_max') }}">
                                </div>
                            </div>

                            <div class="col-md-4 mt-4">
                                <label class="form-label-custom">Tipe Pekerjaan <span class="text-danger">*</span></label>
                                <select name="job_type" class="form-control input-style @error('job_type') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Tipe Pekerjaan</option>
                                    <option value="full_time" {{ old('job_type') == 'full_time' ? 'selected' : '' }}>Penuh Waktu (Full Time)</option>
                                    <option value="part_time" {{ old('job_type') == 'part_time' ? 'selected' : '' }}>Paruh Waktu (Part Time)</option>
                                    <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                                    <option value="freelance" {{ old('job_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="internship" {{ old('job_type') == 'internship' ? 'selected' : '' }}>Magang (Internship)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-4">
                                <label class="form-label-custom">Level Pengalaman <span class="text-danger">*</span></label>
                                <input type="text" name="experience_level" class="form-control input-style @error('experience_level') is-invalid @enderror" value="{{ old('experience_level') }}" required placeholder="Misal: Fresh Graduate, Min 2 Tahun">
                            </div>
                            <div class="col-md-4 mt-4">
                                <label class="form-label-custom">Minimal Pendidikan</label>
                                <select name="education_level" class="form-control input-style @error('education_level') is-invalid @enderror">
                                    <option value="">Tidak Ada Syarat Khusus</option>
                                    <option value="sma" {{ old('education_level') == 'sma' ? 'selected' : '' }}>SMA / SMK Sederajat</option>
                                    <option value="d3" {{ old('education_level') == 'd3' ? 'selected' : '' }}>Diploma 3 (D3)</option>
                                    <option value="s1" {{ old('education_level') == 's1' ? 'selected' : '' }}>Sarjana (S1)</option>
                                    <option value="s2" {{ old('education_level') == 's2' ? 'selected' : '' }}>Magister (S2)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Bagian 4: Pengaturan Tayang --}}
                        <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="fas fa-cog text-danger mr-2"></i>Pengaturan Publikasi</h6>
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label-custom">Jumlah Lowongan <span class="text-danger">*</span></label>
                                <input type="number" name="vacancy" min="1" class="form-control input-style @error('vacancy') is-invalid @enderror" value="{{ old('vacancy', 1) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Batas Akhir Lamaran</label>
                                <input type="date" name="deadline" class="form-control input-style @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Status Publikasi <span class="text-danger">*</span></label>
                                <select name="status" class="form-control input-style font-weight-bold text-dark @error('status') is-invalid @enderror" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>DRAFT (Disembunyikan)</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>TAYANG (Dapat dilamar)</option>
                                    <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>TUTUP (Selesai)</option>
                                </select>
                            </div>
                        </div>

                        {{-- Switch / Checkbox --}}
                        <div class="bg-light p-4 rounded-lg border">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="custom-control custom-switch custom-switch-lg">
                                        <input type="checkbox" class="custom-control-input" id="is_remote" name="is_remote" value="1" {{ old('is_remote') ? 'checked' : '' }}>
                                        <label class="custom-control-label switch-label" for="is_remote">Tersedia untuk Remote Worker (WFH)</label>
                                        <small class="d-block text-muted mt-1">Centang jika pelamar diperbolehkan bekerja dari rumah.</small>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <div class="custom-control custom-switch custom-switch-lg">
                                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="custom-control-label switch-label text-primary" for="is_featured">Jadikan Lowongan Prioritas (Featured)</label>
                                        <small class="d-block text-muted mt-1">Lowongan akan muncul di urutan teratas atau halaman beranda.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="card-footer bg-white text-right py-4" style="border-top: 1px solid var(--slate-100); border-radius: 0 0 16px 16px;">
                        <a href="{{ route('admin.jobs.index') }}" class="btn btn-light border px-4 font-weight-bold" style="border-radius: 20px;">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 ml-2 font-weight-bold shadow-sm" style="border-radius: 20px;">
                            Simpan Lowongan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection