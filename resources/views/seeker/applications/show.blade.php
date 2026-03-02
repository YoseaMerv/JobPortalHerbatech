@extends('layouts.seeker')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Detail Lamaran</h5>
                    <a href="{{ route('seeker.applications.index') }}" class="btn btn-sm btn-light rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted text-uppercase small ls-1 mb-2">Informasi Pekerjaan</h6>
                        {{-- FIX: Null-safe operator (?->) dan fallback text --}}
                        <h4 class="fw-bold mb-1">{{ $application->job?->title ?? 'Lowongan Telah Dihapus' }}</h4>
                        <div class="text-primary mb-2 fw-semibold">{{ $application->job?->company?->company_name ?? 'Perusahaan Tidak Tersedia' }}</div>
                        <div class="text-muted small">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $application->job?->location?->name ?? 'Lokasi Tidak Tersedia' }}
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                        <h6 class="text-muted text-uppercase small ls-1 mb-2">Status Lamaran</h6>
                        <span class="badge bg-{{ $application->status_badge ?? 'secondary' }} px-3 py-2 rounded-pill shadow-sm fs-6">
                            {{ ucfirst(str_replace('_', ' ', $application->status_label ?? $application->status)) }}
                        </span>
                        <div class="text-muted extra-small mt-2">Dikirim pada: {{ $application->created_at->format('d M Y') }}</div>
                    </div>
                </div>

                {{-- Banner Tes Kraepelin --}}
                @if(in_array($application->status, ['test_invited', 'test_in_progress']))
                    <div class="alert alert-primary border-0 shadow-sm mb-5 p-4 rounded-4" style="background-color: #eef2ff; border-left: 5px solid #4338ca !important;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-primary text-white rounded-circle me-3 flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-file-signature fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">
                                    {{ $application->status === 'test_in_progress' ? 'Tes Sedang Berlangsung' : 'Undangan Tes Psikotes (Kraepelin)' }}
                                </h6>
                                <p class="text-muted small mb-0">Selesaikan tes ini sebagai bagian dari proses seleksi. Pastikan koneksi internet stabil.</p>
                            </div>
                        </div>

                        @php
                            $targetRoute = ($application->status === 'test_in_progress') 
                                ? route('seeker.kraepelin.start', $application->id) 
                                : route('seeker.kraepelin.instructions', $application->id);
                        @endphp

                        <div class="d-grid mt-3">
                            <a href="{{ $targetRoute }}" class="btn btn-primary fw-bold py-2 rounded-3" style="background-color: #4338ca; border: none;">
                                <i class="fas fa-play me-2"></i> 
                                {{ $application->status === 'test_in_progress' ? 'Lanjutkan Mengerjakan Tes' : 'Mulai Kerjakan Tes Sekarang' }}
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Banner Interview --}}
                @if($application->status === 'interview')
                    <div class="alert alert-success border-0 shadow-sm mb-5 p-4 rounded-4" style="background-color: #f0fdf4; border-left: 5px solid #16a34a !important;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-success text-white rounded-circle me-3 flex-shrink-0" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-handshake fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Selamat! Anda Diundang Wawancara</h6>
                                <p class="text-muted small mb-0">Persiapkan diri Anda dengan baik. Berikut adalah detail dari HR:</p>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-3 border mt-3 shadow-sm">
                            <h6 class="text-muted extra-small text-uppercase fw-bold mb-3 border-bottom pb-2">
                                <i class="fas fa-info-circle me-1"></i> Informasi Jadwal & Lokasi
                            </h6>
                            <div class="text-dark" style="font-size: 0.95rem; line-height: 1.8;">
                                @if($application->notes)
                                    {!! nl2br(e($application->notes)) !!}
                                @else
                                    <span class="text-muted fst-italic">Menunggu detail jadwal dari HR...</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <h6 class="text-muted text-uppercase small ls-1 mb-3 fw-bold">Dokumen Lamaran</h6>
                <div class="row g-3 mb-4">
                    {{-- File CV --}}
                    <div class="col-md-6">
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center transition-all hover-shadow">
                            <div class="icon-box bg-danger-subtle text-danger rounded-3 p-3 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background-color: #fee2e2;">
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

                    {{-- File Cover Letter --}}
                    <div class="col-md-6">
                        <div class="p-3 border rounded-4 bg-light d-flex align-items-center transition-all hover-shadow">
                            <div class="icon-box bg-primary-subtle text-primary rounded-3 p-3 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; background-color: #e0e7ff;">
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
    .hover-shadow:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.05); transform: translateY(-2px); }
    .transition-all { transition: all 0.3s ease; }
</style>
@endsection