@extends('layouts.admin')

@section('title', 'Pengaturan Perusahaan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

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
        height: auto;
    }
    .input-style:focus {
        background-color: #fff;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
    }
    .img-preview-box {
        border: 2px dashed var(--slate-200);
        border-radius: 12px;
        padding: 10px;
        background: var(--slate-50);
        display: inline-block;
    }
    .section-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-heading);
        border-left: 4px solid var(--brand-primary);
        padding-left: 12px;
        margin-bottom: 25px;
        margin-top: 10px;
    }
</style>

<div class="container-fluid pb-5">
    {{-- Tampilkan Pesan Error Validasi Jika Ada --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm mb-4" style="border-radius: 12px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tampilkan Pesan Sukses --}}
    @if (session('success'))
        <div class="alert alert-success shadow-sm mb-4" style="border-radius: 12px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="form-card shadow-sm">
                {{-- HEADER --}}
                <div class="form-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--text-heading);">Identitas & Branding Portal</h4>
                        <p class="text-muted small mb-0">Kelola tampilan visual dan informasi dasar portal karir HerbaTech.</p>
                    </div>
                    <div class="bg-soft-primary p-3 rounded-circle" style="background: #eef2ff; color: #4338ca;">
                        <i class="fas fa-cogs fa-lg"></i>
                    </div>
                </div>

                {{-- FORM START (Hanya ada SATU tag form) --}}
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body p-4 p-md-5">
                        <div class="row">
                            {{-- KOLOM KIRI --}}
                            <div class="col-lg-7">
                                <div class="section-title">Informasi Dasar Perusahaan</div>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label-custom">Nama Perusahaan <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control input-style" 
                                           value="{{ old('company_name', $company->company_name) }}" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label-custom">Deskripsi Singkat</label>
                                    <textarea name="company_description" class="form-control input-style" 
                                              rows="4">{{ old('company_description', $company->company_description) }}</textarea>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Industri</label>
                                        <input type="text" name="industry" class="form-control input-style" 
                                               value="{{ old('industry', $company->industry) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label-custom">Jumlah Karyawan</label>
                                        <input type="number" name="company_size" class="form-control input-style" 
                                               value="{{ old('company_size', $company->company_size) }}">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label-custom">Website Resmi</label>
                                    <input type="url" name="company_website" class="form-control input-style" 
                                           value="{{ old('company_website', $company->company_website) }}">
                                </div>

                                <div class="section-title mt-5 text-primary">Media Sosial</div>
                                <div class="row g-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-custom"><i class="fab fa-facebook mr-1"></i> Facebook</label>
                                        <input type="url" name="facebook" class="form-control input-style" value="{{ old('facebook', $company->facebook) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-custom"><i class="fab fa-instagram mr-1"></i> Instagram</label>
                                        <input type="url" name="instagram" class="form-control input-style" value="{{ old('instagram', $company->instagram) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-custom"><i class="fab fa-linkedin mr-1"></i> LinkedIn</label>
                                        <input type="url" name="linkedin" class="form-control input-style" value="{{ old('linkedin', $company->linkedin) }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label-custom"><i class="fab fa-twitter mr-1"></i> Twitter</label>
                                        <input type="url" name="twitter" class="form-control input-style" value="{{ old('twitter', $company->twitter) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="col-lg-5 border-left pl-lg-5">
                                <div class="section-title">Aset Visual</div>

                                <div class="form-group mb-5">
                                    <label class="form-label-custom">Logo Perusahaan</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="img-preview-box mr-3">
                                            <img src="{{ $company->company_logo ? asset('storage/' . $company->company_logo) : 'https://placehold.co/80x80' }}" 
                                                 style="height: 60px; width: 60px; object-fit: contain;">
                                        </div>
                                        <input type="file" name="company_logo" class="form-control-file border p-1 rounded">
                                    </div>
                                </div>

                                <div class="form-group mb-5">
                                    <label class="form-label-custom">Favicon</label>
                                    <div class="d-flex align-items-center">
                                        <div class="img-preview-box mr-3">
                                            <img src="{{ $company->favicon ? asset('storage/' . $company->favicon) : 'https://placehold.co/32x32' }}" 
                                                 style="height: 32px; width: 32px;">
                                        </div>
                                        <input type="file" name="favicon" class="form-control-file border p-1 rounded">
                                    </div>
                                </div>

                                <div class="bg-light p-4 rounded border">
                                    <h6 class="fw-bold mb-3">Hero Section (Beranda)</h6>
                                    <div class="form-group mb-3">
                                        <label class="form-label-custom">Judul Hero</label>
                                        <input type="text" name="hero_title" class="form-control input-style bg-white" 
                                               value="{{ old('hero_title', $company->hero_title) }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label-custom">Deskripsi Hero</label>
                                        <textarea name="hero_description" class="form-control input-style bg-white" rows="3">{{ old('hero_description', $company->hero_description) }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label-custom">Teks Tombol (CTA)</label>
                                        <input type="text" name="hero_cta_text" class="form-control input-style bg-white" 
                                               value="{{ old('hero_cta_text', $company->hero_cta_text) }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label-custom">Gambar Hero</label>
                                        <input type="file" name="hero_image" class="form-control-file border p-1 rounded bg-white w-100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER BUTTON --}}
                    <div class="card-footer bg-white text-right py-4 border-top">
                        <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm" style="border-radius: 20px;">
                            <i class="fas fa-save mr-1"></i> Simpan Semua Perubahan
                        </button>
                    </div>
                </form>
                {{-- FORM END --}}
            </div>
        </div>
    </div>
</div>
@endsection