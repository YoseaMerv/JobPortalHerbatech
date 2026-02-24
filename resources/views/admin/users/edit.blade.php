@extends('layouts.admin')

@section('title', 'Ubah Pengguna')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Pengguna</a></li>
    <li class="breadcrumb-item active">Ubah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Formulir Ubah Pengguna</h3>
            </div>
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="role">Peran <span class="text-danger">*</span></label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="seeker" {{ old('role', $user->role) == 'seeker' ? 'selected' : '' }}>Pencari Kerja</option>
                            <option value="company" {{ old('role', $user->role) == 'company' ? 'selected' : '' }}>Perusahaan</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="isActive" name="is_active" {{ $user->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="isActive">Status Aktif</label>
                        </div>
                    </div>

                    <hr>
                    <p class="text-muted text-sm">Kosongkan kolom kata sandi jika Anda tidak ingin mengubahnya.</p>
                    
                    <div class="form-group">
                        <label for="password">Kata Sandi Baru</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kata Sandi Baru">
                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ketik Ulang Kata Sandi">
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-default">Batal</a>
                    <button type="submit" class="btn btn-warning float-right">Perbarui Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
