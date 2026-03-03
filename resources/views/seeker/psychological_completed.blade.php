@extends('layouts.seeker')

@section('content')
<div class="container py-5 text-center">
    <div class="card py-5 border-0 shadow-sm">
        <div class="card-body">
            <i class="fas fa-check-circle text-success fa-5x mb-4"></i>
            <h2 class="fw-bold">Terima Kasih!</h2>
            <p class="lead">Anda telah menyelesaikan tes psikotes. Hasil pengerjaan Anda telah tersimpan ke dalam sistem.</p>
            <hr class="my-4 mx-auto" style="width: 50%;">
            <p class="text-muted">Perusahaan akan meninjau hasil tes Anda sebagai bagian dari proses seleksi.</p>
            <a href="{{ route('seeker.applications.index') }}" class="btn btn-primary px-4 mt-3">
                Kembali ke Daftar Lamaran
            </a>
        </div>
    </div>
</div>
@endsection