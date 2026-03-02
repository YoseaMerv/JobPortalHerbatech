@php
    // Logika cerdas untuk mendeteksi siapa yang sedang login
    $userRole = Auth::user()->role ?? 'seeker';
    $layout = 'layouts.app'; // Default fallback
    
    if ($userRole == 'admin') {
        $layout = 'layouts.admin';
    } elseif ($userRole == 'company') {
        $layout = 'layouts.company';
    } elseif ($userRole == 'seeker') {
        $layout = 'layouts.seeker'; // Sesuaikan jika layout seeker Anda namanya berbeda
    }
@endphp

@extends($layout)

@section('content')
<style>
    /* CSS Master untuk mengatur tampilan form di dalam partials */
    .profile-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #fff;
        margin-bottom: 2rem;
        overflow: hidden;
    }
    .profile-card-body {
        padding: 32px;
    }
    .input-style {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.2s;
        width: 100%;
    }
    .input-style:focus {
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
    }
    .btn-rounded {
        border-radius: 20px;
        font-weight: 600;
    }
</style>

<div class="container-fluid pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Card 1: Informasi Profil & Email (Yang baru saja kita perbaiki) --}}
            <div class="profile-card shadow-sm">
                <div class="profile-card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Card 2: Ubah Kata Sandi --}}
            <div class="profile-card shadow-sm">
                <div class="profile-card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Card 3: Hapus Akun (Zona Berbahaya) --}}
            <div class="profile-card shadow-sm border-danger" style="border-width: 2px; background-color: #fffcfc;">
                <div class="profile-card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</div>
@endsection