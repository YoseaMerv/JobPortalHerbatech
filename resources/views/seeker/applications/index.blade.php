@extends('layouts.seeker')

@section('content')
<div class="row">
    <div class="col-12">
        <h3 class="mb-4">Lamaran Saya</h3>
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Posisi Pekerjaan</th>
                                <th>Perusahaan</th>
                                <th>Tanggal Melamar</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applications as $application)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $application->job->title }}</div>
                                    <div class="small text-muted">{{ $application->job->location->name }}</div>
                                </td>
                                <td>{{ $application->job->company->company_name }}</td>
                                <td>{{ $application->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ match($application->status) {
                                        'pending' => 'warning',
                                        'shortlisted' => 'info',
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        default => 'secondary'
                                    } }}">
                                        {{ match($application->status) {
                                            'pending' => 'Menunggu',
                                            'shortlisted' => 'Terpilih',
                                            'accepted' => 'Diterima',
                                            'rejected' => 'Ditolak',
                                            default => ucfirst($application->status)
                                        } }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('seeker.applications.show', $application->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                        Lihat
                                    </a>
                                    @if($application->status === 'pending')
                                    <form action="{{ route('seeker.applications.destroy', $application->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menarik lamaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Tarik Lamaran">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="https://illustrations.popsy.co/gray/paper-plane.svg" alt="No applications" width="150" class="mb-3">
                                    <p class="text-muted">Anda belum melamar pekerjaan apa pun.</p>
                                    <a href="{{ route('seeker.jobs.index') }}" class="btn btn-primary">Cari Lowongan</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($applications->hasPages())
            <div class="card-footer bg-white">
                {{ $applications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
