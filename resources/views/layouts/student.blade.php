<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Dashboard - Iskolar ng Bayan')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    @include('partials.theme-init')
    @stack('styles')
</head>
<body class="bg-surface">

    <!-- Top nav -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-brand-navy shadow-soft py-2 sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span class="logo-chip"><img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo" class="logo-mark"></span>
                <span class="logo-chip"><img src="{{ asset('images/iskolar-logo.jpg') }}" alt="Iskolar ng Bayan Logo" class="logo-mark"></span>
                <span class="logo-chip"><img src="{{ asset('images/lydo-logo.jpg') }}" alt="LYDO Logo" class="logo-mark"></span>
            </a>

            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#studentNav">
                <i class="bi bi-list fs-2 text-white"></i>
            </button>

            <div class="collapse navbar-collapse" id="studentNav">
                <ul class="navbar-nav mx-auto gap-lg-4 mt-3 mt-lg-0">
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link text-white-50 fw-semibold">Home</a></li>
                    <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link text-white fw-semibold border-bottom border-2" style="border-color: var(--gold-500) !important;">Dashboard</a></li>
                    <li class="nav-item"><a href="{{ route('guides') }}" class="nav-link text-white-50 fw-semibold">Guides</a></li>
                </ul>

                <ul class="navbar-nav mt-3 mt-lg-0 align-items-lg-center gap-lg-2">
                    <li class="nav-item d-flex align-items-center">
                        @include('partials.theme-toggle', ['class' => 'text-white-50'])
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-white" href="#" role="button" data-bs-toggle="dropdown">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold"
                                  style="width:32px;height:32px;background:rgba(255,255,255,.15);font-size:.85rem;">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </span>
                            <span class="d-none d-md-inline small">{{ auth()->user()->name ?? 'User' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-soft border-0 p-3" style="min-width: 280px;">
                            @php $applicant = auth()->user()->applicant ?? null; @endphp
                            <li class="mb-2">
                                <p class="fw-bold mb-0">{{ auth()->user()->name ?? 'User' }}</p>
                                <p class="small text-muted-soft mb-0">{{ auth()->user()->email ?? '' }}</p>
                            </li>
                            @if($applicant)
                                <li><hr class="dropdown-divider"></li>
                                <li class="small text-muted-soft d-flex justify-content-between mb-1"><span>Course</span><span class="fw-semibold">{{ $applicant->course ?? '—' }}</span></li>
                                <li class="small text-muted-soft d-flex justify-content-between mb-1"><span>Year Level</span><span class="fw-semibold">{{ $applicant->year_level ?? '—' }}</span></li>
                                <li class="small text-muted-soft d-flex justify-content-between"><span>School</span><span class="fw-semibold text-end">{{ $applicant->school_name ?? '—' }}</span></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item small fw-semibold link-brand" href="{{ route('profile.show') }}">My Profile</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-lg-2 d-none d-lg-block bg-surface min-vh-100 py-4 px-3">
                <div class="student-sidebar-panel">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                        <span class="student-sidebar-brand-icon"><i class="bi bi-mortarboard-fill"></i></span>
                        <div style="min-width: 0;">
                            <p class="fw-bold mb-0 small">Student Panel</p>
                            <p class="text-muted-soft mb-0 text-truncate" style="font-size: .7rem;">{{ auth()->user()->name ?? 'User' }}</p>
                        </div>
                    </div>

                    <nav class="d-flex flex-column gap-1">
                        <a href="{{ route('dashboard') }}" class="student-nav-item {{ request()->routeIs('dashboard') ? 'student-nav-item--active' : '' }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.show') }}" class="student-nav-item {{ request()->routeIs('profile.*') ? 'student-nav-item--active' : '' }}">
                            <i class="bi bi-person-fill"></i> My profile
                        </a>
                        <a href="{{ route('dashboard') }}#requirements" class="student-nav-item">
                            <i class="bi bi-file-earmark-arrow-up-fill"></i> Requirements
                        </a>
                        <a href="{{ route('home') }}" class="student-nav-item">
                            <i class="bi bi-bell-fill"></i> Announcements
                        </a>
                        <a href="{{ route('dashboard') }}#application-status" class="student-nav-item">
                            <i class="bi bi-clock-history"></i> Application status
                        </a>
                        <a href="{{ route('support.index') }}" class="student-nav-item {{ request()->routeIs('support.*') ? 'student-nav-item--active' : '' }}">
                            <i class="bi bi-headset"></i> Contact Support
                        </a>
                    </nav>

                    <hr class="my-3">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="student-nav-item student-nav-item--logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-lg-10 py-4 px-3 px-lg-4">
                @if (session('success'))
                    <div class="alert-brand-success p-3 mb-4 small">{{ session('success') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <!-- File preview modal (used by "View" links for uploaded requirement / exam / report files) -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="height: 85vh;">
                <div class="modal-header py-2">
                    <h5 class="modal-title small fw-bold mb-0" id="filePreviewModalLabel">File Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="filePreviewFrame" src="" style="width: 100%; height: 100%; border: 0;"></iframe>
                </div>
                <div class="modal-footer py-2">
                    <a id="filePreviewOpenLink" href="#" target="_blank" class="small fw-semibold link-brand">
                        Open in new tab <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user() && !auth()->user()->isAdmin() && !auth()->user()->hasAcceptedTerms())
        @include('partials.terms-modal')
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
    @if (auth()->user() && !auth()->user()->isAdmin() && !auth()->user()->hasAcceptedTerms())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const termsModalEl = document.getElementById('termsModal');
            if (termsModalEl) {
                bootstrap.Modal.getOrCreateInstance(termsModalEl, { backdrop: 'static', keyboard: false }).show();
            }
        });
    </script>
    @endif
    <script>
        function previewFile(url, title) {
            const frame = document.getElementById('filePreviewFrame');
            const label = document.getElementById('filePreviewModalLabel');
            const openLink = document.getElementById('filePreviewOpenLink');
            frame.src = url;
            openLink.href = url;
            if (label) label.textContent = title || 'File Preview';
            const modalEl = document.getElementById('filePreviewModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
        document.getElementById('filePreviewModal')?.addEventListener('hidden.bs.modal', function () {
            document.getElementById('filePreviewFrame').src = '';
        });
    </script>
    @stack('scripts')
</body>
</html>