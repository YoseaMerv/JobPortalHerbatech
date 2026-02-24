@extends('layouts.admin')

@section('title', 'Semua Lamaran')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Lamaran</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lamaran Kerja</h3>
        <div class="card-tools">
            <a href="{{ route('admin.applications.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Baru
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kandidat</th>
                    <th>Judul Lowongan</th>
                    <th>Perusahaan</th>
                    <th>Status</th>
                    <th>Dilamar Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                <tr>
                    <td>{{ $application->id }}</td>
                    <td>{{ $application->user->name }}</td>
                    <td>{{ Str::limit($application->job->title, 20) }}</td>
                    <td>{{ $application->job->company->company_name }}</td>
                    <td>
                        <span class="badge badge-{{ match($application->status) {
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
                    <td>{{ $application->created_at->format('d M Y') }}</td>
                    <td>
                        <form action="{{ route('admin.applications.destroy', $application->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin?')">
                            @csrf
                            @method('DELETE')
                            <a href="{{ route('admin.applications.show', $application->id) }}" class="btn btn-sm btn-info" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.applications.edit', $application->id) }}" class="btn btn-sm btn-warning" title="Ubah">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
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
        {{ $applications->links() }}
    </div>
</div>
@endsection
