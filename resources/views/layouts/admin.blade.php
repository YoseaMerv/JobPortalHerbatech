<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Job Portal') }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    
    @php
        // Ambil data company global jika belum di-passing dari controller
        $company = \App\Models\Company::first();
    @endphp
    
    @if(isset($company->favicon) && $company->favicon)
        <link rel="icon" href="{{ asset('storage/' . $company->favicon) }}" type="image/x-icon"/>
    @endif

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        /* Override Content Wrapper Background */
        .content-wrapper {
            background-color: #f8fafc !important;
        }

        /* Modernize Navbar */
        .main-header {
            border-bottom: none !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px);
        }
        .navbar-light .navbar-nav .nav-link {
            color: #475569;
            font-weight: 500;
        }
        .navbar-light .navbar-nav .nav-link:hover {
            color: #0d6efd;
        }

        /* Modernize Sidebar */
        .main-sidebar {
            background-color: #0f172a !important; /* Deep Slate */
            box-shadow: 4px 0 10px rgba(0,0,0,0.05) !important;
        }
        
        /* FIX: Area Logo agar tidak nabrak menu Dashboard */
        .brand-link {
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
            padding: 0.8125rem 0.5rem !important; /* Standar AdminLTE */
        }
        .brand-text {
            font-weight: 700 !important;
            letter-spacing: 0.5px;
            color: #f8fafc !important;
            font-size: 1rem;
        }
        
        /* Sidebar Menu Items */
        .nav-sidebar .nav-item {
            margin-bottom: 2px;
        }
        .nav-sidebar .nav-link {
            border-radius: 8px !important;
            margin: 0 8px;
            color: #94a3b8 !important;
            transition: all 0.2s ease;
        }
        .nav-sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.05) !important;
            color: #f8fafc !important;
            transform: translateX(3px);
        }
        .nav-sidebar .nav-link.active {
            background-color: #0d6efd !important;
            color: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgba(13, 110, 253, 0.3);
        }
        .nav-header {
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            color: #64748b !important;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 1.5rem 1.5rem 0.5rem 1.5rem !important;
        }

        /* Dropdown Modernization */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 10px;
        }
        .dropdown-item {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            color: #475569;
            transition: all 0.2s;
        }
        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #0d6efd;
        }

        /* Content Header */
        .content-header h1 {
            font-weight: 800;
            color: #1e293b;
            font-size: 1.5rem;
        }
        .breadcrumb-item a {
            color: #64748b;
            font-weight: 500;
        }
        .breadcrumb-item.active {
            color: #0d6efd;
            font-weight: 600;
        }

        /* Alerts Styling */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        
        /* Footer */
        .main-footer {
            background: #fff;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.85rem;
            padding: 1rem;
        }
    </style>
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        
        <nav class="main-header navbar navbar-expand navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="border-radius: 8px; background: #f1f5f9; padding: 0.5rem 0.75rem; margin-left: 0.5rem;">
                        <i class="fas fa-bars text-primary"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item dropdown mr-2">
                    <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="dropdown" href="#" role="button" style="background: #f8fafc; border-radius: 20px; padding: 4px 16px 4px 4px; border: 1px solid #e2e8f0;">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: bold;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="fw-bold text-dark" style="font-size: 0.9rem;">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down ml-1 text-muted" style="font-size: 0.7rem;"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right mt-2">
                        <div class="dropdown-header text-left">
                            <h6 class="mb-0 fw-bold text-dark">{{ Auth::user()->name }}</h6>
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        </div>
                        <div class="dropdown-divider my-2"></div>
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user-cog mr-2 text-muted"></i> Pengaturan Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt mr-2"></i> Keluar Sistem
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar elevation-4">
            
            <a href="{{ route('admin.dashboard') }}" class="brand-link">
                @if(isset($company->company_logo) && $company->company_logo)
                    <img src="{{ asset('storage/' . $company->company_logo) }}" alt="Logo" class="brand-image img-circle elevation-1 bg-white" style="object-fit: contain; padding: 2px;">
                @else
                    <div class="brand-image img-circle elevation-1 bg-primary d-flex align-items-center justify-content-center" style="width: 33px; height: 33px; margin-top: -3px;">
                        <i class="fas fa-briefcase text-white" style="font-size: 0.8rem;"></i>
                    </div>
                @endif
                <span class="brand-text">{{ $company->company_name ?? 'Herbatech Admin' }}</span>
            </a>

            <div class="sidebar mt-3">
                <nav class="mt-2 pb-5">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-border-all"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        
                        <li class="nav-header">PENGELOLAAN DATA</li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Manajemen Pengguna</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.jobs.index') }}" class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-briefcase"></i>
                                <p>Daftar Lowongan</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.applications.index') }}" class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-signature"></i>
                                <p>Data Lamaran Masuk</p>
                            </a>
                        </li>
                        
                        <li class="nav-header">PENGATURAN PORTAL</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-building"></i>
                                <p>Identitas & Branding</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Kategori Pekerjaan</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.locations.index') }}" class="nav-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-map-marker-alt"></i>
                                <p>Lokasi Penempatan</p>
                            </a>
                        </li>
                        
                        <li class="nav-header">ANALITIK & LAPORAN</li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.jobs') }}" class="nav-link {{ request()->routeIs('admin.reports.jobs') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>Laporan Lowongan</p>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.reports.applications') }}" class="nav-link {{ request()->routeIs('admin.reports.applications') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Tren Lamaran</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header pt-4 pb-2">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>

        <footer class="main-footer d-flex justify-content-between align-items-center">
            <div>
                <strong>Hak Cipta &copy; {{ date('Y') }} <span class="text-primary">{{ config('app.name', 'JobPortal') }}</span>.</strong>
            </div>
            <div class="d-none d-sm-inline-block">
                <b>Versi</b> 1.0.0
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Fungsi Copy Clipboard Global
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