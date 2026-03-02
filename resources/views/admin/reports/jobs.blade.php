@extends('layouts.admin')

@section('title', 'Laporan Lowongan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Laporan Lowongan</li>
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
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.2s ease-in-out;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
    }
    .icon-shape {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
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
</style>

<div class="container-fluid pb-5">
    
    {{-- Section: Ringkasan Metrik (Stat Cards) --}}
    <div class="row mb-4 g-4">
        {{-- Total Lowongan --}}
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="stat-card">
                <div class="icon-shape" style="background-color: #eff6ff; color: #3b82f6;">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.05em;">Total Lowongan</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.8rem;">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>

        {{-- Lowongan Aktif --}}
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="stat-card">
                <div class="icon-shape" style="background-color: #ecfdf5; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.05em;">Lowongan Aktif</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.8rem;">{{ $stats['active'] }}</h3>
                </div>
            </div>
        </div>

        {{-- Lowongan Kedaluwarsa --}}
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-shape" style="background-color: #fef2f2; color: #ef4444;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1" style="letter-spacing: 0.05em;">Kedaluwarsa</p>
                    <h3 class="fw-bold mb-0 text-dark" style="font-size: 1.8rem;">{{ $stats['expired'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Section: Tabel Breakdown Kategori --}}
    <div class="main-card">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-soft-primary p-3 rounded-circle d-none d-md-flex" style="background: #f8fafc; color: #64748b; width: 48px; height: 48px; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                    <i class="fas fa-chart-pie fa-lg"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--text-heading);">Distribusi per Kategori</h4>
                    <p class="text-muted small mb-0">Rincian jumlah lowongan pekerjaan berdasarkan bidang industrinya.</p>
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
                        <th width="60%">Nama Kategori</th>
                        <th class="text-right">Total Lowongan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['by_category'] as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-tag text-muted opacity-50 mr-3"></i>
                                <span class="fw-bold text-dark">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td class="text-right">
                            <span class="count-badge">
                                {{ $item->total }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-box-open fa-2x text-muted opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Data Kosong</h6>
                                <p class="text-muted small">Belum ada data lowongan yang tercatat dalam kategori apapun.</p>
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