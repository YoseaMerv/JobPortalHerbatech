@extends('layouts.seeker')

@section('content')
<div class="row">
    <div class="col-12">
        <h3 class="mb-4">Lowongan Tersimpan</h3>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Posisi Pekerjaan</th>
                                <th>Perusahaan</th>
                                <th>Tanggal Disimpan</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($savedJobs as $job)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $job->title }}</div>
                                    <div class="small text-muted">{{ $job->location->name }}</div>
                                </td>
                                <td>{{ $job->company->company_name }}</td>
                                <td>{{ $job->pivot->created_at->format('d M Y') }}</td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('seeker.jobs.show', $job->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                        Lihat
                                    </a>
                                    <form action="{{ route('seeker.jobs.unsave', $job->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <img src="https://illustrations.popsy.co/gray/work-from-home.svg" alt="No saved jobs" width="150" class="mb-3">
                                    <p class="text-muted">Anda belum menyimpan lowongan apa pun.</p>
                                    <a href="{{ route('seeker.jobs.index') }}" class="btn btn-primary">Cari Lowongan</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                {{ $savedJobs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
