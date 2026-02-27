@extends('layouts.company')

@section('title', 'Profil Perusahaan')

@section('content')
<style>
    :root {
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --text-main: #334155; 
        --text-heading: #1e293b;
        --brand-indigo: #4338ca; 
    }

    .profile-card { 
        border-radius: 16px; 
        border: 1px solid var(--slate-200); 
        background: #fff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .section-title { 
        font-size: 1.1rem; 
        font-weight: 700; 
        color: var(--text-heading); /* Teks Hitam */
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .section-title i {
        color: var(--brand-indigo);
        margin-right: 10px;
    }

    .form-label { 
        font-size: 0.85rem; 
        font-weight: 600; 
        color: #64748b; 
        text-transform: uppercase; 
        letter-spacing: 0.025em;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border-color: var(--slate-200);
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        color: var(--text-main);
    }

    .form-control:focus {
        border-color: var(--brand-indigo);
        box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1);
    }

    .logo-preview-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 15px;
    }

    .logo-preview {
        width: 120px;
        height: 120px;
        object-fit: contain;
        border-radius: 12px;
        border: 2px dashed var(--slate-200);
        padding: 5px;
        background: var(--slate-50);
    }

    .btn-save {
        background-color: var(--brand-indigo);
        border: none;
        border-radius: 10px;
        padding: 0.7rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        background-color: #3730a3;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(67, 56, 202, 0.2);
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card profile-card border-0">
            <div class="card-body p-4 p-md-5">
                <form action="{{ route('company.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="section-title">
                        <i class="fas fa-user-circle"></i> Informasi Akun Persona
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="name" class="form-label">Nama Kontak Utama</label>
                            <p class="small text-muted">Nama individu yang mengelola akun ini.</p>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required placeholder="Contoh: Budi Santoso">
                        </div>
                    </div>
                    <div class="section-title">
                        <i class="fas fa-building"></i> Identitas Perusahaan
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Logo Perusahaan</label>
                            <p class="small text-muted">Gunakan format PNG atau JPG (Max 2MB).</p>
                        </div>
                        <div class="col-md-8 text-center text-md-start">
                            <div class="logo-preview-wrapper">
                                @if($company->logo)
                                    <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="logo-preview">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($company->company_name) }}&background=f1f5f9&color=4338ca&size=128" class="logo-preview">
                                @endif
                            </div>
                            <input class="form-control mt-2" type="file" id="logo" name="logo">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="company_name" class="form-label">Nama Institusi/Bisnis</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $company->company_name) }}" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="company_email" class="form-label">Email Bisnis</label>
                        </div>
                        <div class="col-md-8">
                            <input type="email" class="form-control" id="company_email" name="company_email" value="{{ old('company_email', $company->company_email) }}" required>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="company_phone" class="form-label">Nomor Telepon</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="company_phone" name="company_phone" value="{{ old('company_phone', $company->company_phone) }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="company_website" class="form-label">Situs Web Resmi</label>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-globe"></i></span>
                                <input type="url" class="form-control border-start-0" id="company_website" name="company_website" value="{{ old('company_website', $company->company_website) }}" placeholder="https://perusahaan.com">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="company_address" class="form-label">Alamat Kantor</label>
                        </div>
                        <div class="col-md-8">
                            <textarea class="form-control" id="company_address" name="company_address" rows="3">{{ old('company_address', $company->company_address) }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="company_description" class="form-label">Deskripsi Profil</label>
                            <p class="small text-muted">Ceritakan visi, misi, dan budaya kerja perusahaan Anda.</p>
                        </div>
                        <div class="col-md-8">
                            <textarea class="form-control" id="company_description" name="company_description" rows="6" required>{{ old('company_description', $company->company_description) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5 pt-4 border-top">
                        <button type="submit" class="btn btn-primary btn-save shadow-sm">
                            <i class="fas fa-save me-2"></i> Perbarui Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection