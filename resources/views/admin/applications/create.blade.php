@extends('layouts.admin')

@section('title', 'Tambah Lamaran')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.applications.index') }}">Lamaran</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Input Lamaran Manual</h3>
            </div>
            <form action="{{ route('admin.applications.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Pilih Lowongan <span class="text-danger">*</span></label>
                        <select name="job_id" class="form-control select2" required>
                            <option value="">Pilih Lowongan</option>
                            @foreach($jobs as $job)
                                <option value="{{ $job->id }}" {{ old('job_id') == $job->id ? 'selected' : '' }}>
                                    {{ $job->title }} ({{ $job->company->company_name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pilih Kandidat <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control select2" required>
                            <option value="">Pilih Kandidat</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Unggah CV (PDF/DOC) <span class="text-danger">*</span></label>
                        <input type="file" name="cv_path" class="form-control-file" required>
                    </div>

                    <div class="form-group">
                        <label>Surat Lamaran</label>
                        <textarea name="cover_letter" class="form-control" rows="5">{{ old('cover_letter') }}</textarea>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-default">Batal</a>
                    <button type="submit" class="btn btn-primary float-right">Kirim Lamaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
