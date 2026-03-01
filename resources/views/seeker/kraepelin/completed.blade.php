@extends('layouts.seeker')

@section('title', 'Tes Selesai')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 text-center">
            <div class="card border-0 shadow-sm" style="border-radius: 24px; padding: 40px 20px;">
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle" style="width: 100px; height: 100px;">
                            <i class="fas fa-check-circle fa-4x"></i>
                        </div>
                    </div>

                    <h3 class="fw-bold text-dark mb-3">Kerja Bagus, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted mb-4" style="font-size: 1.1rem; line-height: 1.6;">
                        Anda telah berhasil menyelesaikan <strong>Tes Kraepelin</strong> untuk posisi <span class="text-primary fw-bold">{{ $application->job->title }}</span> di {{ $application->job->company->company_name }}.
                    </p>

                    <div class="bg-light p-4 text-start mb-5" style="border-radius: 16px; border-left: 4px solid #4338ca;">
                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-info-circle text-primary me-2"></i>Apa langkah selanjutnya?</h6>
                        <ul class="text-muted small mb-0 ps-3" style="line-height: 1.7;">
                            <li>Jawaban Anda telah dienkripsi dan dikirim ke tim rekrutmen.</li>
                            <li>Tim HR akan meninjau hasil tes beserta profil Anda.</li>
                            <li>Jika Anda lolos ke tahap berikutnya (Wawancara), kami akan mengabari melalui Email dan Update Status di Dashboard.</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-3 d-md-block">
                        <a href="{{ route('seeker.applications.show', $application->id) }}" class="btn btn-primary fw-bold px-4 py-2" style="border-radius: 12px; background-color: #4338ca;">
                            Lihat Status Lamaran
                        </a>
                        <a href="{{ route('seeker.dashboard') }}" class="btn btn-light fw-bold px-4 py-2" style="border-radius: 12px;">
                            Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <p class="text-muted small">Sambil menunggu, <a href="{{ route('seeker.jobs.index') }}" class="text-decoration-none fw-bold text-primary">Cari lowongan lain</a> yang sesuai dengan Anda.</p>
            </div>
        </div>
    </div>
</div>
@endsection