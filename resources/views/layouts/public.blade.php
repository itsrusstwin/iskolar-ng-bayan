<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Iskolar ng Bayan')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-surface">

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top bg-brand-navy shadow-soft py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('home') }}">
                <span class="logo-chip"><img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo" class="logo-mark"></span>
                <span class="logo-chip"><img src="{{ asset('images/iskolar-logo.jpg') }}" alt="Iskolar ng Bayan Logo" class="logo-mark"></span>
                <span class="logo-chip"><img src="{{ asset('images/lydo-logo.jpg') }}" alt="LYDO Logo" class="logo-mark"></span>
            </a>

            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <i class="bi bi-list fs-2 text-white"></i>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-4 mt-3 mt-lg-0">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link fw-semibold {{ request()->routeIs('home') ? 'text-white border-bottom border-2' : 'text-white-50' }}" style="border-color: var(--gold-500) !important;">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('about') }}" class="nav-link fw-semibold {{ request()->routeIs('about') ? 'text-white border-bottom border-2' : 'text-white-50' }}" style="border-color: var(--gold-500) !important;">About us</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('guides') }}" class="nav-link fw-semibold {{ request()->routeIs('guides') ? 'text-white border-bottom border-2' : 'text-white-50' }}" style="border-color: var(--gold-500) !important;">Guides</a>
                    </li>
                    <li class="nav-item mt-2 mt-lg-0">
                        @auth
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="btn btn-brand btn-sm px-3">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-brand btn-sm px-3">Login / Sign up</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-brand-navy text-white-50 py-4 mt-5">
        <div class="container text-center small">
            &copy; {{ date('Y') }} Iskolar ng Bayan — Municipality of Santa Cruz, Laguna
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>