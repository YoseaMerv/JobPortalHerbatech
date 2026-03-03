@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                {{-- Header MSDT dengan warna Danger (Merah) agar kontras --}}
                <div class="bg-danger p-4 text-center text-white" style="background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);">
                    <i class="fas fa-users-cog fa-3x mb-3"></i>
                    <h3 class="fw-bold mb-0">Instruksi MSDT</h3>
                    <p class="small opacity-75 mb-0">Management Style Diagnostic Test</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    {{-- META DATA --}}
                    <div class="d-flex justify-content-around mb-4 bg-light p-3 rounded-4">
                        <div class="text-center">
                            <small class="text-muted d-block">WAKTU</small>
                            <span class="fw-bold text-danger">30 Menit</span>
                        </div>
                        <div class="text-center border-start border-end px-4">
                            <small class="text-muted d-block">SOAL</small>
                            <span class="fw-bold text-danger">64 Pasang</span>
                        </div>
                        <div class="text-center">
                            <small class="text-muted d-block">TIPE</small>
                            <span class="fw-bold text-danger">Pilihan Paksa</span>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3">Petunjuk Pengerjaan:</h6>
                    <ul class="text-muted small mb-4" style="line-height: 1.6;">
                        <li>Terdapat 64 pasang pernyataan mengenai perilaku kepemimpinan.</li>
                        <li>Pilihlah salah satu pernyataan (A atau B) yang <strong>paling sesuai</strong> dengan kecenderungan Anda dalam memimpin atau bekerja dalam tim.</li>
                        <li>Meskipun kedua pilihan terasa tidak sesuai atau keduanya sangat sesuai, Anda <strong>wajib</strong> memilih salah satu yang paling mendekati.</li>
                        <li>Waktu pengerjaan adalah 30 menit. Manfaatkan waktu dengan bijak.</li>
                    </ul>

                    <div class="alert bg-danger bg-opacity-10 border-0 rounded-4 mb-5">
                        <small class="text-dark d-block mb-2 fw-bold">Contoh Soal:</small>
                        <div class="p-2 border rounded bg-white mb-2 small text-dark">
                            <input type="radio" checked disabled> A. Saya membiarkan orang lain bekerja dengan cara mereka sendiri.
                        </div>
                        <div class="p-2 border rounded bg-white small text-dark">
                            <input type="radio" disabled> B. Saya mengawasi orang lain dengan ketat.
                        </div>
                    </div>

                    <a href="{{ route('seeker.msdt.start', $application->id) }}" class="btn btn-danger w-100 py-3 rounded-pill fw-bold shadow-sm border-0">
                        SAYA MENGERTI, MULAI TES MSDT <i class="fas fa-play ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection