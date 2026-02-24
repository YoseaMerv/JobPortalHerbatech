@extends('layouts.company')

@section('title', 'Pasang Lowongan Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('company.jobs.store') }}" method="POST">
                    @csrf
                    
                    <h5 class="mb-4 text-primary">Detail Pekerjaan</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Judul Pekerjaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach(\App\Models\JobCategory::where('is_active', true)->get() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="location_id" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <select class="form-select @error('location_id') is-invalid @enderror" id="location_id" name="location_id" required>
                                <option value="">Pilih Lokasi</option>
                                @foreach(\App\Models\JobLocation::where('is_active', true)->get() as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @error('location_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="job_type" class="form-label">Tipe Pekerjaan <span class="text-danger">*</span></label>
                            <select class="form-select @error('job_type') is-invalid @enderror" id="job_type" name="job_type" required>
                                <option value="full_time" {{ old('job_type') == 'full_time' ? 'selected' : '' }}>Penuh Waktu</option>
                                <option value="part_time" {{ old('job_type') == 'part_time' ? 'selected' : '' }}>Paruh Waktu</option>
                                <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                                <option value="freelance" {{ old('job_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                <option value="internship" {{ old('job_type') == 'internship' ? 'selected' : '' }}>Magang</option>
                            </select>
                            @error('job_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="salary_min" class="form-label">Gaji Minimum</label>
                            <input type="number" class="form-control @error('salary_min') is-invalid @enderror" id="salary_min" name="salary_min" value="{{ old('salary_min') }}" placeholder="contoh: 5000000">
                            @error('salary_min') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="salary_max" class="form-label">Gaji Maksimum</label>
                            <input type="number" class="form-control @error('salary_max') is-invalid @enderror" id="salary_max" name="salary_max" value="{{ old('salary_max') }}" placeholder="contoh: 10000000">
                            @error('salary_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         <div class="col-md-4">
                            <label for="vacancy" class="form-label">Lowongan (Orang)</label>
                            <input type="number" class="form-control @error('vacancy') is-invalid @enderror" id="vacancy" name="vacancy" value="{{ old('vacancy', 1) }}" min="1">
                            @error('vacancy') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="experience_level" class="form-label">Pengalaman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('experience_level') is-invalid @enderror" id="experience_level" name="experience_level" value="{{ old('experience_level') }}" required placeholder="Contoh: 1 Tahun, Entry Level">
                             @error('experience_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         <div class="col-md-6">
                             <!-- Placeholder for other potential fields or empty column -->
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="requirements" class="form-label">Persyaratan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('requirements') is-invalid @enderror" id="requirements" name="requirements" rows="4" required>{{ old('requirements') }}</textarea>
                        @error('requirements') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Batas Waktu Lamaran <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('deadline') is-invalid @enderror" id="deadline" name="deadline" value="{{ old('deadline') }}" required>
                         @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                         <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_remote" id="is_remote" value="1" {{ old('is_remote') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_remote">
                                Ini adalah posisi remote
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('company.jobs.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Pasang Lowongan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
