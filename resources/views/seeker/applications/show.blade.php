@extends('layouts.seeker')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Detail Lamaran</h5>
                    <a href="{{ route('seeker.applications.index') }}" class="btn btn-sm btn-light rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="row mb-5">
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase small ls-1 mb-2">Informasi Pekerjaan</h6>
                        <h4 class="fw-bold mb-1">{{ $application->job->title }}</h4>
                        <div class="text-primary mb-2 fw-semibold">{{ $application->job->company->company_name }}</div>
                        <div class="text-muted small">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $application->job->location->name }}
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                        <h6 class="text-muted text-uppercase small ls-1 mb-2">Status Lamaran</h6>
                        <span class="badge bg-{{ $application->status_badge }} px-3 py-2 rounded-pill shadow-sm">
                            {{ ucfirst($application->status) }}
                        </span>
                        <div class="text-muted extra-small mt-2">Dikirim pada: {{ $application->created_at->format('d M Y') }}</div>
                    </div>
                </div>

                <h6 class="text-muted text-uppercase small ls-1 mb-3 fw-bold">Dokumen Lamaran</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center transition-all hover-shadow">
                            <div class="icon-box bg-danger-subtle text-danger rounded-3 p-3 me-3">
                                <i class="fas fa-file-pdf fa-lg"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="mb-0 small fw-bold text-dark">Curriculum Vitae (CV)</p>
                                @if($application->cv_path)
                                    <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="text-primary small fw-bold text-decoration-none">
                                        Lihat Dokumen <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                @else
                                    <span class="text-muted small">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center transition-all hover-shadow">
                            <div class="icon-box bg-primary-subtle text-primary rounded-3 p-3 me-3">
                                <i class="fas fa-envelope-open-text fa-lg"></i>
                            </div>
                            <div class="overflow-hidden">
                                <p class="mb-0 small fw-bold text-dark">Surat Lamaran</p>
                                @if($application->cover_letter_path)
                                    <a href="{{ asset('storage/' . $application->cover_letter_path) }}" target="_blank" class="text-primary small fw-bold text-decoration-none">
                                        Lihat Dokumen <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                @else
                                    <span class="text-muted small">Tidak ada file</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($application->status === 'pending')
                    <div class="mt-5 pt-4 border-top text-center">
                        <form action="{{ route('seeker.applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menarik lamaran ini? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger text-decoration-none small fw-bold">
                                <i class="fas fa-trash-alt me-1"></i> Tarik Lamaran Pekerjaan Ini
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.05rem; }
    .extra-small { font-size: 0.75rem; }
    .icon-box { width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
    .hover-shadow:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.05); transform: translateY(-2px); }
    .transition-all { transition: all 0.3s ease; }
</style>
@endsection