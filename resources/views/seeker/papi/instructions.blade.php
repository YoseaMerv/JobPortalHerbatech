@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    <div class="card border-0 shadow-sm text-center">
        <div class="card-body p-5">
            <h2 class="fw-bold mb-4">Instruksi Tes PAPI Kostick</h2>
            <p class="text-muted mb-4">
                Tes ini terdiri dari 90 pasang pernyataan. Anda diminta untuk memilih salah satu pernyataan (A atau B) yang paling menggambarkan diri Anda.
                Kerjakan dengan cepat dan jujur sesuai dengan kepribadian Anda.
            </p>
            <div class="d-grid gap-2 d-md-block">
                <a href="{{ route('seeker.applications.show', $application->id) }}" class="btn btn-light px-5">Kembali</a>
                <a href="{{ route('seeker.papi.start', $application->id) }}" class="btn btn-success px-5">Mulai Tes PAPI</a>
            </div>
        </div>
    </div>
</div>
@endsection