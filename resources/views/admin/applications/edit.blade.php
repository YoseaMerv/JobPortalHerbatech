@extends('layouts.admin')

@section('title', 'Ubah Lamaran')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.applications.index') }}">Lamaran</a></li>
    <li class="breadcrumb-item active">Ubah</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Ubah Lamaran #{{ $application->id }}</h3>
            </div>
            <form action="{{ route('admin.applications.update', $application->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl>
                                <dt>Kandidat</dt>
                                <dd>{{ $application->user->name }}</dd>
                                <dt>Lowongan</dt>
                                <dd>{{ $application->job->title }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl>
                                <dt>Dilamar Pada</dt>
                                <dd>{{ $application->created_at->format('d M Y H:i') }}</dd>
                                <dt>Status Saat Ini</dt>
                                <dd><span class="badge badge-info">{{ ucfirst($application->status) }}</span></dd>
                            </dl>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control">
                            <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Ditinjau</option>
                            <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Terpilih</option>
                            <option value="interview" {{ $application->status == 'interview' ? 'selected' : '' }}>Wawancara</option>
                            <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Catatan Internal</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Catatan hanya terlihat oleh admin">{{ old('notes', $application->notes) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Surat Lamaran (Dapat Diubah)</label>
                        <textarea name="cover_letter" class="form-control" rows="5">{{ old('cover_letter', $application->cover_letter) }}</textarea>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-default">Batal</a>
                    <button type="submit" class="btn btn-warning float-right">Perbarui Lamaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
