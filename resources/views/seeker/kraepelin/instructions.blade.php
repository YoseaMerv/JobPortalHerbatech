@extends('layouts.seeker')

@section('title', 'Petunjuk Tes Kraepelin')

@section('content')
<style>
    .instruction-card { border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .demo-box { background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 15px; padding: 20px; }
    .number-col { width: 50px; display: inline-block; text-align: center; }
    .num-item { font-size: 1.25rem; font-weight: 800; padding: 5px 0; color: #1e293b; }
    .ans-item { color: #4338ca; font-weight: 900; background: #e0e7ff; border-radius: 5px; margin: 2px 0; }
    .step-badge { width: 30px; height: 30px; background: #4338ca; color: white; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; margin-right: 10px; }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card instruction-card">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold" style="color: #1e293b;">Petunjuk Tes Kraepelin</h2>
                        <p class="text-muted">Mohon baca dengan seksama sebelum memulai ujian profesional Anda.</p>
                    </div>

                    <div class="row g-5">
                        <div class="col-md-7">
                            <h5 class="fw-bold mb-4">Cara Mengerjakan:</h5>
                            
                            <div class="d-flex mb-4">
                                <div class="step-badge">1</div>
                                <div>
                                    <h6 class="fw-bold mb-1">Penjumlahan Bawah ke Atas</h6>
                                    <p class="small text-muted">Jumlahkan dua angka yang berdekatan mulai dari angka paling bawah menuju ke atas dalam satu kolom.</p>
                                </div>
                            </div>

                            <div class="d-flex mb-4">
                                <div class="step-badge">2</div>
                                <div>
                                    <h6 class="fw-bold mb-1">Ambil Digit Terakhir</h6>
                                    <p class="small text-muted">Jika hasil penjumlahan adalah puluhan (misal: 9+7=16), cukup masukkan angka satuannya saja (yaitu: <strong>6</strong>).</p>
                                </div>
                            </div>

                            <div class="d-flex mb-4">
                                <div class="step-badge">3</div>
                                <div>
                                    <h6 class="fw-bold mb-1">Pindah Kolom Otomatis</h6>
                                    <p class="small text-muted">Setiap <strong>15 detik</strong>, sistem akan otomatis memindahkan Anda ke kolom berikutnya. Pastikan Anda bergerak cepat dan teliti.</p>
                                </div>
                            </div>

                            <div class="alert alert-warning border-0 small">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Peringatan:</strong> Jangan me-refresh halaman saat tes berlangsung karena progres akan hilang.
                            </div>
                        </div>

                        <div class="col-md-5">
                            <h5 class="fw-bold mb-4 text-center">Demo Pengerjaan:</h5>
                            <div class="demo-box text-center">
                                <div class="number-col">
                                    <div class="num-item opacity-50">9</div>
                                    <div class="ans-item">5</div> <div class="num-item">6</div>
                                    <div class="ans-item">1</div> <div class="num-item">5</div>
                                    <div class="ans-item">7</div> <div class="num-item">2</div>
                                    <div class="text-muted small mt-2">Mulai dari sini ↑</div>
                                </div>
                                <div class="mt-4 p-3 bg-white rounded shadow-sm">
                                    <p class="small mb-0 text-start"><strong>Contoh:</strong><br>
                                    2 + 5 = <span class="text-primary fw-bold">7</span><br>
                                    5 + 6 = 1<span class="text-primary fw-bold">1</span> (Tulis 1)<br>
                                    6 + 9 = 1<span class="text-primary fw-bold">5</span> (Tulis 5)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="text-center">
                        <p class="fw-bold text-dark mb-4">Sudah siap memulai tes?</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('seeker.dashboard') }}" class="btn btn-light px-5 fw-bold py-3" style="border-radius: 12px;">NANTI SAJA</a>
                            <a href="{{ route('seeker.kraepelin.start', $application->id) }}" class="btn btn-primary px-5 fw-bold py-3 shadow" style="border-radius: 12px; background: #4338ca;">MULAI TES SEKARANG</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection