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
        height: auto;
    }
    .input-style:focus {
        background-color: #fff;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
    }
    .alert-custom {
        background-color: #f0f9ff;
        border: 1px solid #e0f2fe;
        color: #0369a1;
        border-radius: 12px;
    }
</style>

<div class="container-fluid pb-5">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted fw-bold small">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Pengguna
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-card shadow-sm">
                <div class="form-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--text-heading);">Tambah Pengguna Baru</h4>
                        <p class="text-muted small mb-0">Daftarkan akun admin, rekruter, atau pelamar baru ke dalam sistem.</p>
                    </div>
                    <div class="bg-soft-primary p-3 rounded-circle" style="background: #eef2ff; color: #4338ca;">
                        <i class="fas fa-user-plus fa-lg"></i>
                    </div>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4">
                            {{-- Kolom Kiri: Profil Dasar --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="fas fa-id-card text-primary mr-2"></i>Informasi Akun</h6>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label-custom">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control input-style @error('name') is-invalid @enderror" 
                                           placeholder="Masukkan nama lengkap" value="{{ old('name') }}" required>
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label-custom">Alamat Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control input-style @error('email') is-invalid @enderror" 
                                           placeholder="nama@email.com" value="{{ old('email') }}" required>
                                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label-custom">Peran / Hak Akses <span class="text-danger">*</span></label>
                                    <select name="role" class="form-control input-style @error('role') is-invalid @enderror" required>
                                        <option value="" disabled selected>Pilih Peran User</option>
                                        <option value="seeker" {{ old('role') == 'seeker' ? 'selected' : '' }}>Pencari Kerja (Pelamar)</option>
                                        <option value="company" {{ old('role') == 'company' ? 'selected' : '' }}>HR HerbaTech (Company)</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator System</option>
                                    </select>
                                    @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Kolom Kanan: Keamanan --}}
                            <div class="col-md-6 border-left pl-md-5">
                                <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom"><i class="fas fa-shield-alt text-warning mr-2"></i>Keamanan Akun</h6>
                                
                                <div class="alert alert-custom p-3 mb-4">
                                    <small><i class="fas fa-info-circle mr-1"></i> Pastikan kata sandi aman dan mudah diingat oleh pengguna baru.</small>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label-custom">Kata Sandi <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control input-style @error('password') is-invalid @enderror" 
                                           placeholder="Minimal 8 karakter" required>
                                    @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label-custom">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control input-style" 
                                           placeholder="Ketik ulang kata sandi" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light text-right py-4" style="border-top: 1px solid var(--slate-100); border-radius: 0 0 16px 16px;">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-white border px-4 font-weight-bold" style="border-radius: 20px;">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 ml-2 font-weight-bold shadow-sm" style="border-radius: 20px;">
                            <i class="fas fa-user-check mr-1"></i> Daftarkan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection