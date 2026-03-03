@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-sm text-center">
        <div class="card-body p-5">
            <h2 class="fw-bold mb-4">Instruksi Tes MSDT</h2>
            <p class="text-muted mb-4">
                Tes ini terdiri dari 64 pasang pernyataan. Anda diminta untuk memilih salah satu pernyataan yang paling menggambarkan perilaku Anda dalam situasi kerja.
                Tidak ada jawaban benar atau salah, jawablah sesuai dengan kondisi Anda yang sebenarnya.
            </p>
            <div class="d-grid gap-2 d-md-block">
                <a href="{{ route('seeker.applications.show', $application->id) }}" class="btn btn-light px-5">Batal</a>
                <a href="{{ route('seeker.msdt.start', $application->id) }}" class="btn btn-primary px-5">Mulai Tes Sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection