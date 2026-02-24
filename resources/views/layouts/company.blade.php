<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - Job Portal</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @if(isset($company->favicon) && $company->favicon)
        <link rel="icon" href="{{ asset('storage/' . $company->favicon) }}" type="image/x-icon"/>
    @endif
    
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .nav-link {
            color: rgba(255,255,255,.8);
        }
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,.1);
        }
        .main-content {
            padding: 20px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar" style="width: 280px;">
            <a href="{{ route('company.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                @if($company->company_logo)
                    <img src="{{ asset('storage/' . $company->company_logo) }}" alt="Logo" class="me-2 rounded-circle" style="width: 40px; height: 40px;">
                @else
                    <i class="fas fa-building fa-2x me-2"></i>
                @endif
                <span class="fs-4">{{ Str::limit($company->company_name, 15) }}</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('company.dashboard') }}" class="nav-link {{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('company.jobs.index') }}" class="nav-link {{ request()->routeIs('company.jobs.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase me-2"></i> Lowongan
                    </a>
                </li>
                <li>
                    <a href="{{ route('company.applications.index') }}" class="nav-link {{ request()->routeIs('company.applications.*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i> Lamaran
                    </a>
                </li>
                <li>
                    <a href="{{ route('company.profile.edit') }}" class="nav-link {{ request()->routeIs('company.profile.*') ? 'active' : '' }}">
                        <i class="fas fa-id-card me-2"></i> Profil Perusahaan
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong>{{ Auth::user()->name }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Keluar</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-100 overflow-auto" style="height: 100vh;">
            <nav class="navbar navbar-light bg-light border-bottom px-4">
                <span class="navbar-brand mb-0 h1">@yield('title')</span>
            </nav>

            <div class="main-content">
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

            <footer class="bg-light text-center text-lg-start mt-auto border-top p-3">
                <div class="text-center p-3">
                    © {{ date('Y') }} JobPortal.
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
