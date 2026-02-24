@extends('layouts.seeker')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Lamaran</h5>
                    <a href="{{ route('seeker.applications.index') }}" class="btn btn-sm btn-light">Kembali ke Daftar</a>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase small ls-1">Informasi Pekerjaan</h6>
                        <h4 class="mb-1">{{ $application->job->title }}</h4>
                        <div class="text-primary mb-2">{{ $application->job->company->company_name }}</div>
                        <div class="text-muted small">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $application->job->location->name }}
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <h6 class="text-muted text-uppercase small ls-1">Status Lamaran</h6>
                         <span class="badge bg-{{ match($application->status) {
                            'pending' => 'warning',
                            'shortlisted' => 'info',
                            'accepted' => 'success',
                            'rejected' => 'danger',
                            default => 'secondary'
                        } }} fs-6">
                            {{ match($application->status) {
                                'pending' => 'Menunggu',
                                'shortlisted' => 'Terpilih',
                                'accepted' => 'Diterima',
                                'rejected' => 'Ditolak',
                                default => ucfirst($application->status)
                            } }}
                        </span>
                        <div class="text-muted small mt-2">Dilamar pada {{ $application->created_at->format('d M Y') }}</div>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="text-muted text-uppercase small ls-1 mb-3">Surat Lamaran Anda</h6>
                <div class="bg-light p-3 rounded">
                    {{ $application->cover_letter ?? 'Tidak ada surat lamaran yang diberikan.' }}
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Resume Dikirim</strong>
                        @if($application->resume_path)
                            <div class="small text-muted">File yang dikaitkan dengan lamaran ini</div>
                        @else
                            <div class="small text-muted">Tidak ada resume yang terlampir</div>
                        @endif
                    </div>
                    <!-- Download link if needed, usually just for company -->
                </div>
                
                @if($application->status === 'pending')
                    <div class="mt-5 pt-3 border-top">
                        <form action="{{ route('seeker.applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menarik lamaran ini?');">
                            @csrf
                            @method('DELETE')
                             <button type="submit" class="btn btn-outline-danger">Tarik Lamaran</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
