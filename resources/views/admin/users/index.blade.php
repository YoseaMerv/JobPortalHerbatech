@extends('layouts.admin')

@section('title', 'Pengelolaan Pengguna')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengguna</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Semua Pengguna</h3>
        <div class="card-tools d-flex">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm mr-3">
                <i class="fas fa-plus"></i> Tambah Pengguna Baru
            </a>
            <form action="{{ route('admin.users.index') }}" method="GET" class="input-group input-group-sm" style="width: 250px;">
                <input type="text" name="search" class="form-control float-right" placeholder="Cari" value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th>Status</th>
                    <th>Melamar</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'company' ? 'success' : 'primary') }}">
                            {{ match($user->role) {
                                'admin' => 'Administrator',
                                'company' => 'Perusahaan',
                                'seeker' => 'Pencari Kerja',
                                default => ucfirst($user->role)
                            } }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $user->is_active ? 'success' : 'secondary' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>{{ $user->role === 'seeker' ? $user->applications_count : '-' }}</td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        @if($user->id !== Auth::id())
                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-{{ $user->is_active ? 'warning' : 'success' }}">
                                <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $users->links() }}
    </div>
</div>
@endsection
