<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal - Find Your Dream Job</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="{{ asset('css/futuristic.css') }}" rel="stylesheet">
    @if(isset($company->favicon) && $company->favicon)
        <link rel="icon" href="{{ asset('storage/' . $company->favicon) }}" type="image/x-icon"/>
    @endif
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('seeker.dashboard') }}">
            {{-- Cek apakah variabel $company ada dan tidak null --}}
            @if(isset($company) && $company && $company->company_logo)
                <img src="{{ asset('storage/' . $company->company_logo) }}" alt="Logo" class="me-2 rounded-circle" style="width: 30px; height: 30px;">
            @else
                <i class="fas fa-briefcase me-2"></i>
            @endif
            
            {{-- Tampilkan nama perusahaan atau fallback teks jika data kosong --}}
            {{ $company->company_name ?? 'Job Portal Herbatech' }}
        </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seeker.jobs.index') ? 'active' : '' }}" href="{{ route('seeker.jobs.index') }}">Cari Lowongan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seeker.applications.*') ? 'active' : '' }}" href="{{ route('seeker.applications.index') }}">Lamaran Saya</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('seeker.saved-jobs.*') ? 'active' : '' }}" href="{{ route('seeker.saved-jobs.index') }}">Lowongan Tersimpan</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('seeker.dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('seeker.profile.edit') }}">Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center ms-lg-3">
                    <button class="theme-toggle" id="themeToggle" title="Toggle Theme">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4" style="min-height: 80vh;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                {{-- Gunakan operator ?-> (null safe) atau ?? untuk keamanan --}}
                <h5>{{ $company?->company_name ?? 'Job Portal Herbatech' }}</h5>
                <p>{{ Str::limit($company?->company_description ?? 'Situs pencarian kerja terpercaya.', 100) }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                {{-- Tambahkan ?? 'Job Portal' di sini agar tidak error --}}
                <p>&copy; {{ date('Y') }} {{ $company?->company_name ?? 'Job Portal Herbatech' }}. All rights reserved.</p>
            </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('themeToggle');
        const icon = toggleBtn.querySelector('i');
        const html = document.documentElement;

        // Check local storage
        if (localStorage.getItem('theme') === 'dark') {
            html.setAttribute('data-theme', 'dark');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }

        toggleBtn.addEventListener('click', () => {
            if (html.getAttribute('data-theme') === 'dark') {
                html.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                html.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        });
    </script>
    <script>
        function copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    alert('Tautan lowongan telah disalin ke papan klip!');
                }, function(err) {
                    fallbackCopyTextToClipboard(text);
                });
            } else {
                fallbackCopyTextToClipboard(text);
            }
        }

        function fallbackCopyTextToClipboard(text) {
            var textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                var successful = document.execCommand('copy');
                if(successful) {
                    alert('Tautan lowongan telah disalin ke papan klip!');
                } else {
                    alert('Gagal menyalin tautan.');
                }
            } catch (err) {
                alert('Gagal menyalin tautan.');
            }
            document.body.removeChild(textArea);
        }
    </script>
    @stack('scripts')
</body>
</html>
