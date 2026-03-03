{{-- File: resources/views/seeker/disc/instructions.blade.php --}}
@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                {{-- Header dengan warna Indigo khas DISC --}}
                <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);">
                    <div class="mb-3">
                        <i class="fas fa-clipboard-check fa-3x"></i>
                    </div>
                    <h3 class="fw-bold mb-0">Instruksi Psikotes D.I.S.C.</h3>
                    <p class="small opacity-75 mb-0">Personal Profile Analysis</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    {{-- META DATA --}}
                    <div class="d-flex justify-content-around mb-4 bg-light p-3 rounded-4">
                        <div class="text-center">
                            <small class="text-muted d-block">WAKTU</small>
                            <span class="fw-bold" style="color: #4338ca;">15 Menit</span>
                        </div>
                        <div class="text-center border-start border-end px-4">
                            <small class="text-muted d-block">SOAL</small>
                            <span class="fw-bold" style="color: #4338ca;">24 Nomor</span>
                        </div>
                        <div class="text-center">
                            <small class="text-muted d-block">TIPE</small>
                            <span class="fw-bold" style="color: #4338ca;">Paling & Kurang</span>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-dark">Cara Mengerjakan:</h6>
                    <ul class="text-muted small mb-4" style="line-height: 1.6;">
                        <li>Terdapat 24 kelompok pernyataan. Setiap kelompok terdiri dari 4 pernyataan.</li>
                        <li>Pilihlah satu pernyataan yang **Paling (P)** menggambarkan diri Anda.</li>
                        <li>Pilihlah satu pernyataan yang **Kurang (K)** menggambarkan diri Anda.</li>
                        <li>Setiap nomor **Wajib** memiliki satu pilihan P dan satu pilihan K.</li>
                        <li>Jangan terlalu lama berpikir, berikan respon pertama yang muncul di benak Anda.</li>
                    </ul>

                    {{-- Contoh Visual DISC --}}
                    <div class="alert bg-indigo bg-opacity-10 border-0 rounded-4 mb-5" style="background-color: #f5f7ff;">
                        <small class="text-dark d-block mb-3 fw-bold"><i class="fas fa-info-circle me-1 text-primary"></i> Contoh Pengisian:</small>
                        <table class="table table-sm table-borderless align-middle mb-0 extra-small">
                            <thead>
                                <tr class="text-center text-muted">
                                    <th width="40">P</th>
                                    <th width="40">K</th>
                                    <th class="text-start ps-3">Pernyataan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><input type="radio" class="form-check-input" checked disabled></td>
                                    <td class="text-center"><input type="radio" class="form-check-input" disabled></td>
                                    <td class="ps-3">Gampang setuju, mudah diajak kerjasama</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="radio" class="form-check-input" disabled></td>
                                    <td class="text-center"><input type="radio" class="form-check-input" checked disabled></td>
                                    <td class="ps-3">Berani, suka mengambil risiko</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Link Start --}}
                    <div class="text-center">
                        <a href="{{ route('seeker.disc.start', $application->id) }}" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" style="background-color: #4338ca; border: none;">
                            SAYA MENGERTI, MULAI TES (15 MENIT) <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <p class="extra-small text-muted mt-3 mb-0 italic">
                            <i class="fas fa-history me-1"></i> Waktu akan otomatis berjalan setelah Anda menekan tombol mulai.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection