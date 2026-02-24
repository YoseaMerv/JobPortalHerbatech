@extends('layouts.company')

@section('title', 'Lamaran yang Diterima')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Lamaran Kerja</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kandidat</th>
                        <th>Melamar Sebagai</th>
                        <th>Tanggal Melamar</th>
                        <th>Resume</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $application->user->name }}</div>
                            <small class="text-muted">{{ $application->user->email }}</small>
                        </td>
                        <td>{{ $application->job->title }}</td>
                        <td>{{ $application->created_at->format('d M Y') }}</td>
                        <td>
                            @if($application->resume_path)
                                <a href="{{ route('company.applications.download-cv', $application->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> CV
                                </a>
                            @else
                                <span class="text-muted">Tanpa CV</span>
                            @endif
                        </td>
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
                        <td>
                            <a href="{{ route('company.applications.show', $application->id) }}" class="btn btn-sm btn-info text-white">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <p class="text-muted mb-0">Belum ada lamaran yang diterima.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $applications->links() }}
    </div>
</div>
@endsection
