@extends('layouts.company')

@section('title', 'Profil Perusahaan')

@section('content')
<style>
    :root {
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --text-heading: #1e293b;
        --brand-primary: #0d6efd;
    }
    
    .main-card {
        border-radius: 16px;
        border: 1px solid var(--slate-200);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        background: #fff;
        overflow: hidden;
    }
    
    .card-header-custom {
        background: #fff;
        padding: 24px;
        border-bottom: 1px solid var(--slate-100);
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-heading);
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .form-label-custom {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 4px;
        display: block;
    }

    .text-muted-custom {
        font-size: 0.8rem;
        color: #64748b;
        line-height: 1.4;
    }

    .input-style {
        background-color: var(--slate-50);
        border: 1px solid var(--slate-200);
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.2s;
        width: 100%;
    }

    .input-style:focus {
        background-color: #fff;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
    }

    .logo-preview-box {
        border: 2px dashed var(--slate-200);
        border-radius: 16px;
        padding: 8px;
        background: var(--slate-50);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 120px;
        height: 120px;
        overflow: hidden;
    }

    .logo-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .section-divider {
        height: 1px;
        background-color: var(--slate-100);
        margin: 40px 0;
    }
</style>

<div class="container-fluid pb-5">
    {{-- Pesan Berhasil --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #ecfdf5; color: #065f46;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Pesan Error Validasi --}}
    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #fef2f2; color: #991b1b;">
            <ul class="mb-0 small fw-bold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="main-card shadow-sm">
                
                <div class="card-header-custom d-flex align-items-center gap-3">
                    <div class="bg-soft-primary p-3 rounded-circle" style="background: #eef2ff; color: #4338ca; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-building fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--text-heading);">Profil Perusahaan</h4>
                        <p class="text-muted small mb-0">Kelola identitas publik dan branding perusahaan Anda.</p>
                    </div>
                </div>

                {{-- FORM ACTION: Pastikan mengarah ke company.profile.update --}}
                <form action="{{ route('company.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body p-4 p-md-5">
                        
                        {{-- SEGMEN 1: Akun Pengelola --}}
                        <div class="section-title text-primary">
                            <i class="fas fa-user-shield mr-2"></i> Informasi Pengelola Akun
                        </div>
                        
                        <div class="row mb-4 align-items-start">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label for="name" class="form-label-custom">Nama Kontak Utama</label>
                                <p class="text-muted-custom">Nama HR / Rekruter yang mengelola portal ini.</p>
                            </div>
                            <div class="col-md-8">
                                <input type="text" id="name" name="name" class="form-control input-style @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" placeholder="Contoh: Budi Santoso" required>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- SEGMEN 2: Identitas Perusahaan --}}
                        <div class="section-title text-primary">
                            <i class="fas fa-briefcase mr-2"></i> Identitas Perusahaan
                        </div>

                        {{-- Logo --}}
                        <div class="row mb-5 align-items-start">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="form-label-custom">Logo Perusahaan</label>
                                <p class="text-muted-custom">Format: <strong>PNG, JPG</strong> (Maks 2MB).</p>
                            </div>
                            <div class="col-md-8 d-flex flex-column flex-sm-row gap-3 align-items-center">
                                <div class="logo-preview-box shadow-sm">
                                    {{-- Gunakan data dari $company --}}
                                    @if($company && $company->company_logo)
                                        <img src="{{ asset('storage/' . $company->company_logo) }}" alt="Logo">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($company->company_name ?? 'C') }}&background=f1f5f9&color=4338ca&size=128" alt="Placeholder">
                                    @endif
                                </div>
                                <div class="w-100">
                                    <input type="file" id="company_logo" name="company_logo" class="form-control input-style bg-white">
                                    <small class="text-muted mt-1 d-block italic">Pilih file baru jika ingin mengganti logo.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Nama Bisnis --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="company_name" class="form-label-custom">Nama Institusi / Bisnis</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" id="company_name" name="company_name" class="form-control input-style" 
                                       value="{{ old('company_name', $company->company_name ?? '') }}" required>
                            </div>
                        </div>

                        {{-- Email Bisnis --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="company_email" class="form-label-custom">Email Publik</label>
                            </div>
                            <div class="col-md-8">
                                <input type="email" id="company_email" name="company_email" class="form-control input-style" 
                                       value="{{ old('company_email', $company->company_email ?? '') }}" required>
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label for="company_description" class="form-label-custom">Tentang Perusahaan</label>
                            </div>
                            <div class="col-md-8">
                                <textarea id="company_description" name="company_description" class="form-control input-style" 
                                          rows="6" required>{{ old('company_description', $company->company_description ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer bg-light text-end py-4" style="border-top: 1px solid var(--slate-100);">
                        <button type="submit" class="btn btn-primary px-5 font-weight-bold shadow-sm" style="border-radius: 20px;">
                            <i class="fas fa-save mr-2"></i> Perbarui Profil Perusahaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection