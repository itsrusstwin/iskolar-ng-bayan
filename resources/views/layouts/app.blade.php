<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Iskolar ng Bayan')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-surface">

@auth
<div class="d-flex" style="min-height: 100vh;">

    <!-- Sidebar -->
    <div class="d-none d-lg-flex flex-column bg-brand-navy text-white p-3" style="width: 250px; flex-shrink: 0; height: 100vh; position: sticky; top: 0; overflow-y: auto;">
        <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 mb-4 px-1">
            <span class="logo-chip"><img src="{{ asset('images/stc-logo.jpg') }}" class="logo-mark" alt="Santa Cruz Logo"></span>
            <span class="logo-chip"><img src="{{ asset('images/iskolar-logo.jpg') }}" class="logo-mark" alt="Iskolar ng Bayan Logo"></span>
        </a>

        <span class="badge-soft-gold mb-4 d-inline-block text-center">Admin Panel</span>

        <nav class="nav flex-column gap-1 flex-grow-1">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link text-white d-flex align-items-center gap-2 rounded-md px-3 py-2 {{ request()->routeIs('admin.dashboard') ? '' : 'text-white-50' }}"
               style="{{ request()->routeIs('admin.dashboard') ? 'background: var(--gold-500); color: var(--ink-900) !important; font-weight:600;' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.announcements.index') }}"
               class="nav-link text-white d-flex align-items-center gap-2 rounded-md px-3 py-2 {{ request()->routeIs('admin.announcements.*') ? '' : 'text-white-50' }}"
               style="{{ request()->routeIs('admin.announcements.*') ? 'background: var(--gold-500); color: var(--ink-900) !important; font-weight:600;' : '' }}">
                <i class="bi bi-megaphone"></i> Announcements
            </a>
            <a href="{{ route('admin.students.create') }}"
               class="nav-link text-white d-flex align-items-center gap-2 rounded-md px-3 py-2 {{ request()->routeIs('admin.students.*') ? '' : 'text-white-50' }}"
               style="{{ request()->routeIs('admin.students.*') ? 'background: var(--gold-500); color: var(--ink-900) !important; font-weight:600;' : '' }}">
                <i class="bi bi-person-plus"></i> Create Student Account
            </a>
            <a href="{{ route('admin.audit-log.index') }}"
               class="nav-link text-white d-flex align-items-center gap-2 rounded-md px-3 py-2 {{ request()->routeIs('admin.audit-log.*') ? '' : 'text-white-50' }}"
               style="{{ request()->routeIs('admin.audit-log.*') ? 'background: var(--gold-500); color: var(--ink-900) !important; font-weight:600;' : '' }}">
                <i class="bi bi-journal-text"></i> Audit Log
            </a>
            <a href="{{ route('home') }}" class="nav-link text-white-50 d-flex align-items-center gap-2 rounded-md px-3 py-2">
                <i class="bi bi-globe"></i> Public Site
            </a>
        </nav>

        <div class="border-top border-white border-opacity-10 pt-3 mt-3">
            <div class="d-flex align-items-center gap-2 mb-2 small text-white-50">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                      style="width:28px;height:28px;background:rgba(255,255,255,.15);">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </span>
                <span class="text-truncate">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-white-50 text-decoration-none d-flex align-items-center gap-2 p-0 small">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main -->
    <div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
        <div class="theme-header-bar border-bottom px-4 py-3 d-flex align-items-center justify-content-between">
            <button class="btn btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMobile">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div>
                <h1 class="h6 fw-bold mb-0">@yield('title', 'Admin Dashboard')</h1>
                @hasSection('subtitle')
                    <p class="small text-muted-soft mb-0 mt-1">@yield('subtitle')</p>
                @endif
            </div>
            <div class="d-flex align-items-center gap-2">
                @yield('header_actions')
            </div>
        </div>

        <div class="p-4">
            @if (session('success'))
                <div class="alert-brand-success p-3 mb-4 small">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert-brand-danger p-3 mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<!-- Mobile sidebar -->
<div class="offcanvas offcanvas-start bg-brand-navy text-white" tabindex="-1" id="adminSidebarMobile">
    <div class="offcanvas-header">
        <span class="badge-soft-gold">Admin Panel</span>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="nav flex-column gap-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">Dashboard</a>
            <a href="{{ route('admin.announcements.index') }}" class="nav-link text-white">Announcements</a>
            <a href="{{ route('admin.students.create') }}" class="nav-link text-white">Create Student Account</a>
            <a href="{{ route('admin.audit-log.index') }}" class="nav-link text-white">Audit Log</a>
            <a href="{{ route('home') }}" class="nav-link text-white-50">Public Site</a>
        </nav>
    </div>
</div>

@else
    <div class="container py-5">
        @if (session('success'))
            <div class="alert-brand-success p-3 mb-4">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert-brand-danger p-3 mb-4">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>