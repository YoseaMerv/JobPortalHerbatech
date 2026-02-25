<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->company_name ?? 'HerbaTech' }} - Career Portal</title>
    
    {{-- PENTING: CSRF Token untuk Axios (Tes Kraepelin) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="{{ asset('css/futuristic.css') }}" rel="stylesheet">
    
    @if(isset($company->favicon) && $company->favicon)
        <link rel="icon" href="{{ asset('storage/' . $company->favicon) }}" type="image/x-icon"/>
    @endif

    @stack('styles')

    <style>
        /* Tambahan sedikit styling agar footer selalu di bawah */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .navbar-brand img {
            object-fit: cover;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('seeker.dashboard') }}">
                @if(isset($company) && $company && $company->company_logo)
                    <img src="{{ asset('storage/' . $company->company_logo) }}" alt="Logo" class="me-2 rounded-circle border" style="width: 35px; height: 35px;">
                @else
                    <i class="fas fa-briefcase me-2 text-primary"></i>
                @endif
                {{ $company->company_name ?? 'Job Portal Herbatech' }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seeker.jobs.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('seeker.jobs.index') }}">Cari Lowongan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seeker.applications.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('seeker.applications.index') }}">Lamaran Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seeker.saved-jobs.*') ? 'active fw-bold text-primary' : '' }}" href="{{ route('seeker.saved-jobs.index') }}">Tersimpan</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3">
                        <button class="theme-toggle btn btn-link text-decoration-none" id="themeToggle" title="Toggle Theme">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff" class="rounded-circle me-2" width="25">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="{{ route('seeker.dashboard') }}"><i class="fas fa-tachometer-alt me-2 opacity-50"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('seeker.profile.edit') }}"><i class="fas fa-user-edit me-2 opacity-50"></i> Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-bold">
                                        <i class="fas fa-sign-out-alt me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row align-items-center text-center text-md-start">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="fw-bold">{{ $company?->company_name ?? 'Job Portal Herbatech' }}</h5>
                    <p class="text-white-50 small mb-0">{{ Str::limit($company?->company_description ?? 'Situs pencarian kerja terpercaya.', 100) }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 small text-white-50">&copy; {{ date('Y') }} {{ $company?->company_name ?? 'Job Portal Herbatech' }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme Toggle Logic
        const toggleBtn = document.getElementById('themeToggle');
        const icon = toggleBtn.querySelector('i');
        const html = document.documentElement;

        if (localStorage.getItem('theme') === 'dark') {
            html.setAttribute('data-theme', 'dark');
            icon.classList.replace('fa-moon', 'fa-sun');
        }

        toggleBtn.addEventListener('click', () => {
            if (html.getAttribute('data-theme') === 'dark') {
                html.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                icon.classList.replace('fa-sun', 'fa-moon');
            } else {
                html.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                icon.classList.replace('fa-moon', 'fa-sun');
            }
        });

        // Copy Clipboard Helper
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Tautan lowongan telah disalin!');
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }
    </script>
    @stack('scripts')
</body>
</html>