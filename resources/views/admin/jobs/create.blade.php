@extends('layouts.admin')

@section('title', 'Tambah Lowongan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.jobs.index') }}">Lowongan</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Lowongan Baru</h3>
    </div>
    <form action="{{ route('admin.jobs.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Judul Lowongan</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Perusahaan</label>
                        <select name="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                            <option value="">Pilih Perusahaan</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Lokasi</label>
                        <select name="location_id" class="form-control @error('location_id') is-invalid @enderror" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Pekerjaan</label>
                <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                @error('description')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Persyaratan</label>
                        <textarea name="requirements" rows="4" class="form-control @error('requirements') is-invalid @enderror">{{ old('requirements') }}</textarea>
                        @error('requirements')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggung Jawab</label>
                        <textarea name="responsibilities" rows="4" class="form-control @error('responsibilities') is-invalid @enderror">{{ old('responsibilities') }}</textarea>
                        @error('responsibilities')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipe Gaji</label>
                        <select name="salary_type" class="form-control @error('salary_type') is-invalid @enderror" required>
                            <option value="monthly" {{ old('salary_type') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                            <option value="project" {{ old('salary_type') == 'project' ? 'selected' : '' }}>Proyek</option>
                            <option value="hourly" {{ old('salary_type') == 'hourly' ? 'selected' : '' }}>Per Jam</option>
                            <option value="yearly" {{ old('salary_type') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                        </select>
                        @error('salary_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Gaji Minimum</label>
                        <input type="number" name="salary_min" class="form-control @error('salary_min') is-invalid @enderror" value="{{ old('salary_min') }}">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Gaji Maksimum</label>
                        <input type="number" name="salary_max" class="form-control @error('salary_max') is-invalid @enderror" value="{{ old('salary_max') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tipe Pekerjaan</label>
                        <select name="job_type" class="form-control @error('job_type') is-invalid @enderror" required>
                            <option value="full_time" {{ old('job_type') == 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                            <option value="part_time" {{ old('job_type') == 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                            <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                            <option value="freelance" {{ old('job_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                            <option value="internship" {{ old('job_type') == 'internship' ? 'selected' : '' }}>Magang</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Pengalaman</label>
                        <input type="text" name="experience_level" class="form-control @error('experience_level') is-invalid @enderror" value="{{ old('experience_level') }}" required placeholder="Contoh: 1 Tahun, Entry Level">
                        @error('experience_level')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Pendidikan</label>
                        <select name="education_level" class="form-control @error('education_level') is-invalid @enderror">
                            <option value="">Semua Pendidikan</option>
                            <option value="sma" {{ old('education_level') == 'sma' ? 'selected' : '' }}>SMA/SMK</option>
                            <option value="d3" {{ old('education_level') == 'd3' ? 'selected' : '' }}>D3</option>
                            <option value="s1" {{ old('education_level') == 's1' ? 'selected' : '' }}>S1</option>
                            <option value="s2" {{ old('education_level') == 's2' ? 'selected' : '' }}>S2</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Batas Lamaran</label>
                        <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Jumlah Lowongan</label>
                        <input type="number" name="vacancy" min="1" class="form-control @error('vacancy') is-invalid @enderror" value="{{ old('vacancy', 1) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Tayang (Published)</option>
                            <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Tutup (Closed)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch d-inline mr-3">
                    <input type="checkbox" class="custom-control-input" id="is_remote" name="is_remote" value="1" {{ old('is_remote') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_remote">Remote Worker?</label>
                </div>
                <div class="custom-control custom-switch d-inline">
                    <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_featured">Jadikan Lowongan Unggulan (Featured)</label>
                </div>
            </div>

        </div>
        <div class="card-footer text-right">
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Lowongan</button>
        </div>
    </form>
</div>
@endsection
