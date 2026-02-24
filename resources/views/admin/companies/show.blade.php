@extends('layouts.admin')

@section('title', 'Detail Perusahaan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.companies.index') }}">Perusahaan</a></li>
    <li class="breadcrumb-item active">{{ $company->company_name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    @if($company->logo)
                        <img class="profile-user-img img-fluid img-circle" src="{{ Storage::url($company->logo) }}" alt="Logo">
                    @else
                        <img class="profile-user-img img-fluid img-circle" src="https://ui-avatars.com/api/?name={{ $company->company_name }}" alt="Logo">
                    @endif
                </div>
                <h3 class="profile-username text-center">{{ $company->company_name }}</h3>
                <p class="text-muted text-center">{{ $company->industry }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Lowongan Terpasang</b> <a class="float-right">{{ $company->jobs()->count() }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Status</b> 
                        <span class="float-right badge badge-{{ $company->is_verified ? 'success' : 'warning' }}">
                            {{ $company->is_verified ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                        </span>
                    </li>
                </ul>

                <form action="{{ route('admin.companies.toggle-verification', $company->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-{{ $company->is_verified ? 'warning' : 'success' }} btn-block">
                        {{ $company->is_verified ? 'Cabut Verifikasi' : 'Verifikasi Perusahaan' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Perusahaan</h3>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Lokasi</strong>
                <p class="text-muted">{{ $company->company_address }}, {{ $company->company_city }}, {{ $company->company_country }}</p>
                <hr>

                <strong><i class="fas fa-globe mr-1"></i> Situs Web</strong>
                <p class="text-muted"><a href="{{ $company->company_website }}" target="_blank">{{ $company->company_website }}</a></p>
                <hr>

                <strong><i class="fas fa-file-alt mr-1"></i> Deskripsi</strong>
                <p class="text-muted">{{ $company->company_description }}</p>
                <hr>

                <strong><i class="fas fa-calendar mr-1"></i> Didirikan</strong>
                <p class="text-muted">{{ $company->founded_date }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
