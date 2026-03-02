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

    .input-group-text-custom {
        background-color: var(--slate-50);
        border: 1px solid var(--slate-200);
        border-right: none;
        border-radius: 12px 0 0 12px;
        color: #94a3b8;
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
        border-radius: 10px;
    }

    .section-divider {
        height: 1px;
        background-color: var(--slate-100);
        margin: 40px 0;
    }
</style>

<div class="container-fluid pb-5">
    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #ecfdf5; color: #065f46;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 12px; background-color: #fef2f2; color: #991b1b;">
            <i class="fas fa-exclamation-triangle mr-2"></i> Terdapat kesalahan pada input Anda. Silakan periksa kembali.
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="main-card shadow-sm">
                
                {{-- Header --}}
                <div class="card-header-custom d-flex align-items-center gap-3">
                    <div class="bg-soft-primary p-3 rounded-circle d-none d-md-flex" style="background: #eef2ff; color: #4338ca; width: 48px; height: 48px; align-items: center; justify-content: center;">
                        <i class="fas fa-building fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--text-heading);">Profil Perusahaan</h4>
                        <p class="text-muted small mb-0">Kelola identitas, kontak, dan branding publik perusahaan Anda.</p>
                    </div>
                </div>

                {{-- Form Utama --}}
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
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
                                <p class="text-muted-custom">Nama individu (HR / Rekruter) yang mengelola akun portal karir ini.</p>
                            </div>
                            <div class="col-md-8">
                                <input type="text" id="name" name="name" class="form-control input-style @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" placeholder="Contoh: Budi Santoso" required>
                                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        {{-- SEGMEN 2: Identitas Perusahaan --}}
                        <div class="section-title text-primary">
                            <i class="fas fa-briefcase mr-2"></i> Identitas Perusahaan
                        </div>

                        {{-- Logo Upload --}}
                        <div class="row mb-5 align-items-start">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label class="form-label-custom">Logo Perusahaan</label>
                                <p class="text-muted-custom">Logo ini akan muncul di halaman lowongan Anda. <br><br>Format yang didukung: <strong>PNG, JPG, JPEG</strong> (Maks 2MB).</p>
                            </div>
                            <div class="col-md-8 d-flex flex-column flex-sm-row gap-3 align-items-start">
                                <div class="logo-preview-box shadow-sm">
                                    @if($company->logo)
                                        <img src="{{ Storage::url($company->logo) }}" alt="Logo">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($company->company_name) }}&background=f1f5f9&color=4338ca&size=128" alt="Placeholder">
                                    @endif
                                </div>
                                <div>
                                    <input type="file" id="logo" name="logo" class="form-control-file border p-2 rounded input-style bg-white w-100 @error('logo') is-invalid @enderror">
                                    @error('logo') <span class="invalid-feedback d-block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Nama Bisnis --}}
                        <div class="row mb-4 align-items-start">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label for="company_name" class="form-label-custom">Nama Institusi / Bisnis</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" id="company_name" name="company_name" class="form-control input-style @error('company_name') is-invalid @enderror" 
                                       value="{{ old('company_name', $company->company_name) }}" placeholder="PT Nama Perusahaan" required>
                                @error('company_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Email Bisnis --}}
                        <div class="row mb-4 align-items-start">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label for="company_email" class="form-label-custom">Email Publik Perusahaan</label>
                                <p class="text-muted-custom">Email ini mungkin terlihat oleh pelamar untuk komunikasi rekrutmen.</p>
                            </div>
                            <div class="col-md-8">
                                <input type="email" id="company_email" name="company_email" class="form-control input-style @error('company_email') is-invalid @enderror" 
                                       value="{{ old('company_email', $company->company_email) }}" placeholder="hrd@perusahaan.com" required>
                                @error('company_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Telepon --}}
                        <div class="row mb-4 align-items-start">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label for="company_phone" class="form-label-custom">Nomor Telepon</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" id="company_phone" name="company_phone" class="form-control input-style @error('company_phone') is-invalid @enderror" 
                                       value="{{ old('company_phone', $company->company_phone) }}" placeholder="(021) 1234567">
                                @error('company_phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Website --}}
                        <div class="row mb-4 align-items-start">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label for="company_website" class="form-label-custom">Situs Web Resmi</label>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text input-group-text-custom"><i class="fas fa-globe"></i></span>
                                    <input type="url" id="company_website" name="company_website" class="form-control input-style @error('company_website') is-invalid @enderror" 
                                           style="border-radius: 0 12px 12px 0;"
                                           value="{{ old('company_website', $company->company_website) }}" placeholder="https://www.perusahaan.com">
                                </div>
                                @error('company_website') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div class="row mb-4 align-items-start">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label for="company_address" class="form-label-custom">Alamat Kantor Pusat</label>
                            </div>
                            <div class="col-md-8">
                                <textarea id="company_address" name="company_address" class="form-control input-style @error('company_address') is-invalid @enderror" 
                                          rows="3" placeholder="Jl. Nama Jalan No. 123, Kota, Provinsi">{{ old('company_address', $company->company_address) }}</textarea>
                                @error('company_address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Deskripsi Profil --}}
                        <div class="row mb-2 align-items-start">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <label for="company_description" class="form-label-custom">Deskripsi Profil Bisnis</label>
                                <p class="text-muted-custom">Jelaskan visi, misi, budaya kerja, dan mengapa pelamar harus bergabung dengan perusahaan Anda.</p>
                            </div>
                            <div class="col-md-8">
                                <textarea id="company_description" name="company_description" class="form-control input-style @error('company_description') is-invalid @enderror" 
                                          rows="6" placeholder="Kami adalah perusahaan teknologi terkemuka yang berfokus pada..." required>{{ old('company_description', $company->company_description) }}</textarea>
                                @error('company_description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    </div>

                    {{-- Footer Button --}}
                    <div class="card-footer bg-light text-right py-4" style="border-top: 1px solid var(--slate-100); border-radius: 0 0 16px 16px;">
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