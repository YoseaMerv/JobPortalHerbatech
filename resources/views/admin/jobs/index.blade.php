@extends('layouts.admin')

@section('title', 'Pengelolaan Lowongan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Lowongan</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Semua Lowongan</h3>
        <div class="card-tools">
            <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Lowongan
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Perusahaan</th>
                    <th>Kategori</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Lamaran</th>
                    <th>Dipasang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobs as $job)
                <tr>
                    <td>{{ Str::limit($job->title, 20) }}</td>
                    <td>{{ $job->company->company_name }}</td>
                    <td>{{ $job->category->name }}</td>
                    <td>
                        {{ match($job->job_type) {
                            'full_time' => 'Penuh Waktu',
                            'part_time' => 'Paruh Waktu',
                            'contract' => 'Kontrak',
                            'freelance' => 'Freelance',
                            'internship' => 'Magang',
                            default => ucfirst(str_replace('_', ' ', $job->job_type))
                        } }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $job->status === 'published' ? 'success' : 'warning' }}">
                            {{ $job->status === 'published' ? 'Tayang' : ($job->status === 'closed' ? 'Ditutup' : 'Draft') }}
                        </span>
                    </td>
                    <td>{{ $job->applications_count }}</td>
                    <td>{{ $job->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lowongan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $jobs->links() }}
    </div>
</div>
@endsection
