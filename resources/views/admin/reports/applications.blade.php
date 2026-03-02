@extends('layouts.admin')

@section('title', 'Laporan Lamaran Masuk')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Laporan Lamaran</li>
@endsection

@section('content')
<style>
    :root {
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-600: #475569;
        --text-heading: #1e293b;
    }
    /* Stat Cards */
    .stat-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--slate-200);
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.2s ease-in-out;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
    }
    .icon-shape {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    /* Table Styles */
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
    .table-modern thead th {
        background-color: var(--slate-50);
        color: var(--slate-600);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        border-top: none;
        padding: 16px 24px;
    }
    .table-modern tbody td {
        padding: 18px 24px;
        vertical-align: middle;
        color: #334155;
        font-size: 0.95rem;
        border-bottom: 1px solid var(--slate-50);
    }
    .table-modern tbody tr:hover {
        background-color: #f8fafc;
    }
    .count-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
        border: 1px solid #e2e8f0;
        display: inline-block;
        min-width: 48px;
        text-align: center;
    }
    .date-pill {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        color: #475569;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    /* ---------------------------------------------------
       MAGIC PRINT CSS (Hanya aktif saat diklik Cetak)
       --------------------------------------------------- */
    @media print {
        /* Sembunyikan elemen bawaan AdminLTE (sidebar, navbar, footer) */
        body * {
            visibility: hidden;
        }
        
        /* Hanya tampilkan area tabel (.main-card) */
        .main-card, .main-card * {
            visibility: visible;
        }
        
        /* Posisikan tabel ke sudut kiri atas kertas */
        .main-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
        
        /* Sembunyikan tombol cetak dan teks pembantu agar hasil print bersih */
        .btn, .card-header-custom p {
            display: none !important;
        }
        
        /* Paksa browser mencetak warna background (badge, icon) */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<div class="container-fluid pb-5">
    
    {{-- Section: Ringkasan Metrik (4 Kolom) --}}
    <div class="row mb-4 g-4">
        {{-- Total Lamaran --}}
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="stat-card">
                <div class="icon-shape" style="background-color: #eff6ff; color: #3b82f6;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.05em;">Total Lamaran</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.6rem;">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>

        {{-- Menunggu (Pending) --}}
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="stat-card">
                <div class="icon-shape" style="background-color: #fffbeb; color: #d97706;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.05em;">Menunggu</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.6rem;">{{ $stats['pending'] }}</h3>
                </div>
            </div>
        </div>

        {{-- Diterima (Accepted) --}}
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="stat-card">
                <div class="icon-shape" style="background-color: #ecfdf5; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.05em;">Diterima</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.6rem;">{{ $stats['accepted'] }}</h3>
                </div>
            </div>
        </div>

        {{-- Ditolak (Rejected) --}}
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="stat-card">
                <div class="icon-shape" style="background-color: #fef2f2; color: #ef4444;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.05em;">Ditolak</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.6rem;">{{ $stats['rejected'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Section: Tabel Tren Harian --}}
    <div class="main-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-soft-primary p-3 rounded-circle d-none d-md-flex" style="background: #f8fafc; color: #64748b; width: 48px; height: 48px; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                    <i class="fas fa-chart-line fa-lg"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--text-heading);">Tren Lamaran Harian</h4>
                    <p class="text-muted small mb-0">Statistik jumlah pelamar yang masuk dalam 30 hari terakhir.</p>
                </div>
            </div>
            <button class="btn btn-light border text-muted shadow-sm" onclick="window.print()" style="border-radius: 12px;">
                <i class="fas fa-print mr-1"></i> Cetak
            </button>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th width="60%">Tanggal Rekaman</th>
                        <th class="text-right">Jumlah Lamaran Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['daily_applications'] as $item)
                    <tr>
                        <td>
                            <div class="date-pill">
                                <i class="far fa-calendar-alt text-primary opacity-75 mr-2"></i>
                                {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}
                            </div>
                        </td>
                        <td class="text-right">
                            <span class="count-badge" style="background: {{ $item->total > 0 ? '#eff6ff' : '#f1f5f9' }}; color: {{ $item->total > 0 ? '#2563eb' : '#94a3b8' }}; border-color: {{ $item->total > 0 ? '#bfdbfe' : '#e2e8f0' }};">
                                {{ $item->total }} Pelamar
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-inbox fa-2x text-muted opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Data Kosong</h6>
                                <p class="text-muted small">Belum ada aktivitas lamaran dalam 30 hari terakhir.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection