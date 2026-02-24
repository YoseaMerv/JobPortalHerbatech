@extends('layouts.admin')

@section('title', 'Pengaturan Perusahaan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Kelola Informasi Perusahaan</h3>
            </div>
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="company_name">Nama Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $company->company_name) }}" required>
                        @error('company_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="company_logo">Logo Perusahaan</label>
                        @if($company->company_logo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $company->company_logo) }}" alt="Logo" class="img-thumbnail" style="height: 100px;">
                            </div>
                        @endif
                        <input type="file" name="company_logo" class="form-control-file @error('company_logo') is-invalid @enderror">
                        <small class="text-muted">Ukuran file maksimum: 2MB</small>
                        @error('company_logo') <div class="invalid-feedback display-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="favicon">Favicon (Ikon)</label>
                        @if($company->favicon)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $company->favicon) }}" alt="Favicon" class="img-thumbnail" style="height: 32px; width: 32px;">
                            </div>
                        @endif
                        <input type="file" name="favicon" class="form-control-file @error('favicon') is-invalid @enderror">
                        <small class="text-muted">Ukuran yang disarankan: 16x16 atau 32x32. Ukuran maks: 1MB</small>
                        @error('favicon') <div class="invalid-feedback display-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="company_description" class="form-control @error('company_description') is-invalid @enderror" rows="5">{{ old('company_description', $company->company_description) }}</textarea>
                        @error('company_description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Industri</label>
                                <input type="text" name="industry" class="form-control" value="{{ old('industry', $company->industry) }}" placeholder="Contoh: Teknologi Informasi">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jumlah Karyawan</label>
                                <input type="number" name="company_size" class="form-control" value="{{ old('company_size', $company->company_size) }}" placeholder="Contoh: 100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Website Perusahaan</label>
                                <input type="url" name="company_website" class="form-control" value="{{ old('company_website', $company->company_website) }}" placeholder="https://example.com">
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Tautan Media Sosial</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fab fa-facebook text-primary"></i> Facebook URL</label>
                                <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $company->facebook) }}" placeholder="https://facebook.com/...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fab fa-twitter text-info"></i> Twitter URL</label>
                                <input type="url" name="twitter" class="form-control" value="{{ old('twitter', $company->twitter) }}" placeholder="https://twitter.com/...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fab fa-linkedin text-primary"></i> LinkedIn URL</label>
                                <input type="url" name="linkedin" class="form-control" value="{{ old('linkedin', $company->linkedin) }}" placeholder="https://linkedin.com/...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fab fa-instagram text-danger"></i> Instagram URL</label>
                                <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $company->instagram) }}" placeholder="https://instagram.com/...">
                            </div>
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Pengaturan Tambahan</h5>
                    <div class="form-group">
                        <label><i class="fas fa-building text-success"></i> URL Profil Perusahaan</label>
                        <input type="url" name="company_profile_url" class="form-control" value="{{ old('company_profile_url', $company->company_profile_url) }}" placeholder="https://perusahaananda.com/tentang">
                        <small class="text-muted">Tautan ke halaman "Tentang Kami" atau profil perusahaan Anda</small>
                    </div>

                    <h5 class="mt-4 mb-3 text-primary"><i class="fas fa-home me-2"></i> Bagian Hero Halaman Depan</h5>
                    <div class="card card-outline card-primary p-3">
                        <div class="form-group">
                            <label>Judul Hero</label>
                            <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $company->hero_title) }}" placeholder="misal: Bangun Masa Depan Bersama Kami">
                            <small class="text-muted">Gunakan **kata** untuk menonjolkan kata dalam warna biru.</small>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi Hero</label>
                            <textarea name="hero_description" class="form-control" rows="3" placeholder="Bergabunglah dengan tim visioner...">{{ old('hero_description', $company->hero_description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Teks CTA Hero</label>
                                    <input type="text" name="hero_cta_text" class="form-control" value="{{ old('hero_cta_text', $company->hero_cta_text) }}" placeholder="misal: Mulai Perjalanan Anda">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gambar Hero</label>
                                    @if($company->hero_image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $company->hero_image) }}" alt="Hero" class="img-thumbnail" style="height: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" name="hero_image" class="form-control-file">
                                    <small class="text-muted">Disarankan: Orientasi lanskap. Maks 2MB.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
