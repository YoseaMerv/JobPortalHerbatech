@extends('layouts.seeker')

@section('title', 'Petunjuk Tes Kraepelin')

@section('content')
<style>
    :root {
        --primary-indigo: #4338ca;
        --soft-indigo: #eef2ff;
    }

    /* Ukuran kartu diperkecil sedikit agar lebih kompak */
    .instruction-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.07);
        background: #ffffff;
    }

    .demo-box {
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        position: relative;
    }

    .kraepelin-column {
        background: white;
        padding: 12px;
        border-radius: 10px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        display: inline-block;
    }

    .num-item {
        font-size: 1.2rem; /* Ukuran font angka diperkecil */
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin: 8px 0;
    }

    .ans-item {
        color: var(--primary-indigo);
        font-weight: 900;
        background: var(--soft-indigo);
        border-radius: 5px;
        font-size: 0.9rem;
        padding: 1px 6px;
        display: inline-block;
        border: 1px solid #c7d2fe;
    }

    .step-badge {
        width: 28px; /* Ukuran badge diperkecil */
        height: 28px;
        background: var(--primary-indigo);
        color: white;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 12px;
        flex-shrink: 0;
        font-size: 0.85rem;
    }

    .prep-item {
        background: #f8fafc;
        border-radius: 10px;
        padding: 12px;
        margin-bottom: 8px;
        border-left: 3px solid #cbd5e1;
    }

    .btn-start {
        background: var(--primary-indigo);
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .btn-start:hover {
        background: #3730a3;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(67, 56, 202, 0.2);
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card instruction-card overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    {{-- Header --}}
                    <div class="text-center mb-4">
                        <span class="badge bg-soft-indigo text-primary px-3 py-2 rounded-pill mb-2 fw-bold" style="font-size: 0.75rem;">TAHAP ASESMEN</span>
                        <h3 class="fw-bold" style="color: #1e293b;">Petunjuk Tes Kraepelin</h3>
                        <p class="text-muted mx-auto small" style="max-width: 500px;">Tes ini mengukur ketahanan, ketelitian, dan stabilitas kerja melalui metode penjumlahan angka sederhana.</p>
                    </div>

                    <div class="row g-4">
                        {{-- Sisi Kiri: Instruksi --}}
                        <div class="col-md-7">
                            <h6 class="fw-bold mb-3 d-flex align-items-center">
                                <i class="fas fa-list-ol me-2 text-primary"></i> Cara Mengerjakan:
                            </h6>

                            <div class="d-flex mb-3">
                                <div class="step-badge">1</div>
                                <div>
                                    <h6 class="fw-bold mb-1 small">Penjumlahan Berantai</h6>
                                    <p class="extra-small text-muted mb-0">Jumlahkan dua angka yang berdekatan. Fokus pada satu kolom mulai dari <strong>bawah ke atas</strong>.</p>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="step-badge">2</div>
                                <div>
                                    <h6 class="fw-bold mb-1 small">Tulis Satuan Saja</h6>
                                    <p class="extra-small text-muted mb-0">Jika hasil berjumlah belasan, ambil angka terakhirnya saja. <br>
                                        <span class="badge bg-light text-dark border mt-1" style="font-size: 0.7rem;">Contoh: 8 + 7 = 15 &rarr; Ketik 5</span>
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="step-badge">3</div>
                                <div>
                                    <h6 class="fw-bold mb-1 small">Perpindahan Kolom</h6>
                                    <p class="extra-small text-muted mb-0">Setiap beberapa detik, kolom akan berpindah otomatis. Segera pindahkan fokus Anda ke kolom yang baru.</p>
                                </div>
                            </div>

                            <h6 class="fw-bold mt-4 mb-2 d-flex align-items-center">
                                <i class="fas fa-check-shield me-2 text-success"></i> Persiapan Tes:
                            </h6>
                            <div class="prep-item d-flex align-items-center">
                                <i class="fas fa-wifi me-3 text-muted small"></i>
                                <span class="extra-small fw-medium">Pastikan koneksi internet stabil dan baterai perangkat mencukupi.</span>
                            </div>
                            <div class="prep-item d-flex align-items-center">
                                <i class="fas fa-headset me-3 text-muted small"></i>
                                <span class="extra-small fw-medium">Cari tempat tenang tanpa gangguan selama 15-20 menit ke depan.</span>
                            </div>
                        </div>

                        {{-- Sisi Kanan: Demo Visual --}}
                        <div class="col-md-5">
                            <div class="demo-box shadow-sm">
                                <div class="text-center">
                                    <div class="kraepelin-column">
                                        <div class="num-item opacity-25">4</div>
                                        <div class="ans-item">3</div>
                                        <div class="num-item">9</div>
                                        <div class="ans-item">5</div>
                                        <div class="num-item">6</div>
                                        <div class="ans-item">1</div>
                                        <div class="num-item">5</div>
                                        <div class="ans-item">7</div>
                                        <div class="num-item">2</div>
                                        <div class="extra-small fw-bold text-primary mt-2"><i class="fas fa-arrow-up"></i> MULAI</div>
                                    </div>
                                </div>

                                <div class="mt-3 p-2 bg-white rounded-3 border">
                                    <h6 class="fw-bold extra-small mb-2 text-uppercase text-center">Logika Input:</h6>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="d-flex justify-content-between extra-small">
                                            <span class="text-muted">2 + 5 =</span>
                                            <span class="fw-bold">7</span>
                                        </div>
                                        <div class="d-flex justify-content-between extra-small">
                                            <span class="text-muted">5 + 6 = 11</span>
                                            <span class="fw-bold text-danger">Ketik 1</span>
                                        </div>
                                        <div class="d-flex justify-content-between extra-small">
                                            <span class="text-muted">6 + 9 = 15</span>
                                            <span class="fw-bold text-danger">Ketik 5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-danger border-0 rounded-3 mt-3 mb-0 d-flex align-items-start py-2">
                                <i class="fas fa-info-circle me-2 mt-1 small"></i>
                                <span style="font-size: 0.7rem; line-height: 1.3;">Dilarang menekan tombol <strong>Kembali</strong> atau <strong>Refresh</strong> saat tes berlangsung agar progres tidak hangus.</span>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-secondary mb-3 extra-small">Dengan menekan tombol di bawah, Anda menyatakan siap mengikuti tes secara jujur dan mandiri.</p>
                        <div class="d-flex flex-column flex-md-row justify-content-center gap-2">
                            <a href="{{ route('seeker.dashboard') }}" class="btn btn-light px-4 fw-bold py-2 rounded-pill order-2 order-md-1 small">
                                NANTI SAJA
                            </a>
                            <a href="{{ route('seeker.kraepelin.start', $application->id) }}" class="btn btn-start text-white px-4 fw-bold py-2 rounded-pill shadow order-1 order-md-2">
                                SAYA SIAP, MULAI TES <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    .extra-small {
        font-size: 0.75rem;
        line-height: 1.4;
    }
    .bg-soft-indigo {
        background-color: #eef2ff;
    }
</style>
@endsection