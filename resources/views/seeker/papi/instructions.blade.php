{{-- File: resources/views/seeker/papi/instructions.blade.php --}}
@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-primary p-4 text-center text-white">
                    <i class="fas fa-user-tie fa-3x mb-3"></i>
                    <h3 class="fw-bold mb-0">Instruksi PAPI Kostick</h3>
                </div>

                <div class="card-body p-4 p-md-5">
                    {{-- META DATA --}}
                    <div class="d-flex justify-content-around mb-4 bg-light p-3 rounded-4">
                        <div class="text-center">
                            <small class="text-muted d-block">WAKTU</small>
                            <span class="fw-bold text-primary">30 Menit</span>
                        </div>
                        <div class="text-center">
                            <small class="text-muted d-block">SOAL</small>
                            <span class="fw-bold text-primary">90 Pasang</span>
                        </div>
                        <div class="text-center">
                            <small class="text-muted d-block">TIPE</small>
                            <span class="fw-bold text-primary">Pilihan Ganda</span>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3">Cara Mengerjakan:</h6>
                    <ul class="text-muted small mb-4">
                        <li>Anda akan dihadapkan pada <strong>sepasang pernyataan</strong> (A dan B).</li>
                        <li>Pilihlah salah satu pernyataan yang <strong>paling sesuai</strong> dengan diri Anda saat ini.</li>
                        <li>Jika kedua pernyataan terasa sesuai, pilihlah yang <strong>paling dominan</strong>.</li>
                        <li>Kerjakan dengan cepat, jangan terlalu lama berpikir pada satu nomor.</li>
                    </ul>

                    <div class="alert bg-info bg-opacity-10 border-0 rounded-4 mb-5">
                        <small class="text-dark d-block mb-2 fw-bold">Contoh:</small>
                        <div class="p-2 border rounded bg-white mb-2 small">
                            <input type="radio" checked disabled> A. Saya suka memimpin kelompok.
                        </div>
                        <div class="p-2 border rounded bg-white small">
                            <input type="radio" disabled> B. Saya bekerja lebih baik jika diawasi.
                        </div>
                    </div>

                    <a href="{{ route('seeker.papi.start', $application->id) }}" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">
                        SAYA MENGERTI, MULAI TES (30 MENIT) <i class="fas fa-play ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection