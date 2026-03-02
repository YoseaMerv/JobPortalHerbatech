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
    .info-box-soft {
        background-color: #f0f9ff;
        border: 1px solid #e0f2fe;
        border-radius: 12px;
        padding: 16px;
    }
</style>

<div class="container-fluid pb-5">
    <div class="mb-4">
        <a href="{{ route('admin.locations.index') }}" class="text-decoration-none text-muted fw-bold small">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Lokasi
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-card shadow-sm">
                <div class="form-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1" style="color: var(--text-heading);">Buat Lokasi Baru</h4>
                        <p class="text-muted small mb-0">Tambahkan area atau kota penempatan kerja baru untuk portal karir Anda.</p>
                    </div>
                    <div class="bg-soft-primary p-3 rounded-circle" style="background: #eef2ff; color: #4338ca;">
                        <i class="fas fa-map-marker-alt fa-lg"></i>
                    </div>
                </div>

                <form action="{{ route('admin.locations.store') }}" method="POST">
                    @csrf
                    
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4">
                            {{-- Kolom Kiri: Input Utama --}}
                            <div class="col-md-7">
                                <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom">
                                    <i class="fas fa-map-pin text-primary mr-2"></i>Detail Lokasi
                                </h6>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label-custom" for="name">Nama Lokasi / Kota <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control input-style @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" placeholder="Contoh: Jakarta Selatan, Bandung, dll" required>
                                    @error('name') 
                                        <span class="invalid-feedback">{{ $message }}</span> 
                                    @enderror
                                    <small class="text-muted d-block mt-2">Gunakan format nama kota atau provinsi yang umum digunakan.</small>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Status & Info --}}
                            <div class="col-md-5 border-left pl-md-5">
                                <h6 class="fw-bold text-dark mb-4 pb-2 border-bottom">
                                    <i class="fas fa-cog text-secondary mr-2"></i>Pengaturan
                                </h6>
                                
                                <div class="form-group mb-4">
                                    <label class="form-label-custom d-block">Status Lokasi</label>
                                    <div class="custom-control custom-switch custom-switch-lg mt-2">
                                        {{-- Secara default dicentang saat membuat lokasi baru --}}
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                                        <label class="custom-control-label fw-bold" for="is_active" style="cursor: pointer;">Langsung Aktifkan</label>
                                    </div>
                                    <small class="text-muted d-block mt-2">Lokasi akan langsung tersedia untuk dipilih saat HRD membuat lowongan baru.</small>
                                </div>

                                <div class="info-box-soft mt-5">
                                    <h6 class="fw-bold text-dark mb-2" style="color: #0369a1 !important;">
                                        <i class="fas fa-info-circle mr-1"></i> Informasi
                                    </h6>
                                    <p class="text-muted small mb-0" style="color: #0c4a6e !important;">
                                        Sistem akan secara otomatis membuatkan <strong>slug (URL)</strong> yang ramah mesin pencari (SEO) berdasarkan Nama Lokasi yang Anda masukkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light text-right py-4" style="border-top: 1px solid var(--slate-100); border-radius: 0 0 16px 16px;">
                        <a href="{{ route('admin.locations.index') }}" class="btn btn-white border px-4 font-weight-bold" style="border-radius: 20px;">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 ml-2 font-weight-bold shadow-sm" style="border-radius: 20px;">
                            <i class="fas fa-save mr-1"></i> Simpan Lokasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection