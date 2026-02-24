@extends('layouts.company')

@section('title', 'Lowongan Saya')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Lowongan</h5>
        <a href="{{ route('company.jobs.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Pasang Lowongan Baru
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Lamaran</th>
                        <th>Status</th>
                        <th>Unggulan</th>
                        <th>Tanggal Tayang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobs as $job)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $job->title }}</div>
                            <small class="text-muted">{{ match($job->job_type) {
                                'full_time' => 'Penuh Waktu',
                                'part_time' => 'Paruh Waktu',
                                'contract' => 'Kontrak',
                                'freelance' => 'Freelance',
                                'internship' => 'Magang',
                                default => ucfirst(str_replace('_', ' ', $job->job_type))
                            } }}</small>
                        </td>
                        <td>{{ $job->category->name }}</td>
                        <td>
                            <a href="{{ route('company.applications.index', ['job_id' => $job->id]) }}" class="badge bg-info text-decoration-none">
                                {{ $job->applications_count }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-{{ $job->status === 'published' ? 'success' : ($job->status === 'closed' ? 'secondary' : 'warning') }}">
                                {{ $job->status === 'published' ? 'Tayang' : ($job->status === 'closed' ? 'Ditutup' : 'Draft') }}
                            </span>
                        </td>
                        <td>
                            @if($job->is_featured)
                                <span class="badge bg-warning text-dark">Unggulan</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $job->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('company.jobs.show', $job->id) }}" class="btn btn-sm btn-info text-white" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('company.jobs.edit', $job->id) }}" class="btn btn-sm btn-warning text-white" title="Ubah">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <img src="https://illustrations.popsy.co/gray/question-mark.svg" alt="No data" width="100" class="mb-3">
                            <p class="text-muted">Anda belum memasang lowongan apa pun.</p>
                            <a href="{{ route('company.jobs.create') }}" class="btn btn-primary">Pasang Lowongan Pertama Anda</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $jobs->links() }}
    </div>
</div>
@endsection
