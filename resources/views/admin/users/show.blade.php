@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted fw-bold small">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Pengguna
        </a>
    </div>

    <div class="row">
        {{-- Kolom Kiri: Profil Singkat --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="bg-primary p-4 text-center" style="border-radius: 12px 12px 0 0;">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=fff&color=0D6EFD' }}" 
                         class="rounded-circle border border-white shadow-sm mb-3" width="100" height="100" style="object-fit: cover; border-width: 4px !important;" alt="Avatar">
                    <h5 class="fw-bold text-white mb-0">{{ $user->name }}</h5>
                    <p class="text-white-50 small mb-0">Terdaftar sejak {{ $user->created_at->format('d M Y') }}</p>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Email</small>
                        <div class="font-weight-medium text-dark">{{ $user->email }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Telepon</small>
                        <div class="font-weight-medium text-dark">{{ $user->seekerProfile?->phone ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Gender</small>
                        <div class="font-weight-medium text-dark">
                            @if($user->seekerProfile?->gender === 'male') Laki-laki
                            @elseif($user->seekerProfile?->gender === 'female') Perempuan
                            @else -
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Tanggal Lahir</small>
                        <div class="font-weight-medium text-dark">{{ $user->seekerProfile?->birth_date ? \Carbon\Carbon::parse($user->seekerProfile->birth_date)->format('d F Y') : '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Domisili</small>
                        <div class="font-weight-medium text-dark">{{ $user->seekerProfile?->home_location_details ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Ekspektasi Gaji</small>
                        <div class="font-weight-bold text-success">Rp {{ number_format($user->seekerProfile?->expected_salary ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="mb-4">
                        <small class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem; letter-spacing: 1px;">LinkedIn / Web</small>
                        <div class="font-weight-medium text-dark">
                            @if($user->seekerProfile?->linkedin_url)
                                <a href="{{ $user->seekerProfile->linkedin_url }}" target="_blank">{{ Str::limit($user->seekerProfile->linkedin_url, 30) }}</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    @if($user->seekerProfile?->resume_path)
                    <a href="{{ asset('storage/' . $user->seekerProfile->resume_path) }}" target="_blank" class="btn btn-outline-primary btn-block" style="border-radius: 20px; font-weight: bold;">
                        <i class="fas fa-file-pdf mr-2"></i> Unduh Resume
                    </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Riwayat --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4 p-md-5">
                    <h5 class="font-weight-bold mb-4 border-bottom pb-2 text-dark">Tentang Pelamar</h5>
                    <p class="text-muted" style="line-height: 1.6;">
                        {{ $user->seekerProfile?->summary ?? 'Pelamar belum menuliskan ringkasan profil.' }}
                    </p>

                    <h5 class="font-weight-bold mb-4 mt-5 border-bottom pb-2 text-dark">Bahasa yang Dikuasai</h5>
                    <div class="d-flex flex-wrap" style="gap: 8px;">
                        @forelse($user->seekerProfile?->languages ?? [] as $lang)
                            <span class="badge bg-light text-dark border px-3 py-2" style="border-radius: 20px; font-size: 0.85rem; font-weight: normal;">{{ $lang }}</span>
                        @empty
                            <span class="text-muted small">Belum ada data bahasa.</span>
                        @endforelse
                    </div>

                    <h5 class="font-weight-bold mb-4 mt-5 border-bottom pb-2 text-dark">Pengalaman Kerja</h5>
                    <div class="pl-3" style="border-left: 2px solid #e2e8f0;">
                        @forelse($user->seekerProfile?->experiences ?? [] as $exp)
                            <div class="position-relative mb-4 pl-3">
                                <div class="position-absolute bg-primary rounded-circle" style="width: 12px; height: 12px; left: -23px; top: 5px;"></div>
                                <h6 class="font-weight-bold mb-1 text-dark">{{ $exp->job_title }}</h6>
                                <div class="text-primary small font-weight-bold mb-1">{{ $exp->company_name }}</div>
                                <div class="text-muted small mb-2">{{ \Carbon\Carbon::parse($exp->start_date)->format('M Y') }} - {{ $exp->end_date ? \Carbon\Carbon::parse($exp->end_date)->format('M Y') : 'Sekarang' }}</div>
                                <p class="text-muted small mb-0">{{ $exp->description }}</p>
                            </div>
                        @empty
                            <p class="text-muted small">Belum ada data pengalaman kerja.</p>
                        @endforelse
                    </div>

                    <h5 class="font-weight-bold mb-4 mt-5 border-bottom pb-2 text-dark">Riwayat Pendidikan</h5>
                    <div class="pl-3" style="border-left: 2px solid #e2e8f0;">
                        @forelse($user->seekerProfile?->educations ?? [] as $edu)
                            <div class="position-relative mb-4 pl-3">
                                <div class="position-absolute bg-success rounded-circle" style="width: 12px; height: 12px; left: -23px; top: 5px;"></div>
                                <h6 class="font-weight-bold mb-1 text-dark">{{ $edu->institution }}</h6>
                                <div class="text-dark small font-weight-bold mb-1">{{ $edu->degree }} - {{ $edu->field_of_study }}</div>
                                <div class="text-muted small mb-0">{{ \Carbon\Carbon::parse($edu->start_date)->format('Y') }} - {{ $edu->end_date ? \Carbon\Carbon::parse($edu->end_date)->format('Y') : 'Masih Menempuh' }}</div>
                            </div>
                        @empty
                            <p class="text-muted small">Belum ada data pendidikan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection