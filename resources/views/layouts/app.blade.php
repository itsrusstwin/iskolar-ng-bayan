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
    <link href="{{ asset('css/theme.css') . '?v=' . (file_exists(public_path('css/theme.css')) ? filemtime(public_path('css/theme.css')) : '1') }}" rel="stylesheet">
    @include('partials.theme-init')
    @stack('styles')
</head>
<body class="bg-surface">

@auth
<div class="d-flex" style="min-height: 100vh;">

    <!-- Sidebar -->
    <aside class="d-none d-lg-flex flex-column admin-sidebar text-white p-3" style="width: 264px; flex-shrink: 0; height: 100vh; position: sticky; top: 0; overflow-y: auto;">
        <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 mb-4 px-2">
            <span class="logo-chip"><img src="{{ asset('images/stc-logo.jpg') }}" class="logo-mark-sm" alt="Santa Cruz Logo"></span>
            <span class="logo-chip"><img src="{{ asset('images/iskolar-logo.jpg') }}" class="logo-mark-sm" alt="Iskolar ng Bayan Logo"></span>
            <span class="logo-chip"><img src="{{ asset('images/lydo-logo.jpg') }}" class="logo-mark-sm" alt="LYDO Logo"></span>
        </a>

        <span class="admin-sidebar__badge mb-3 ms-1">Admin Panel</span>

        <nav class="d-flex flex-column gap-1 flex-grow-1">
            <span class="admin-sidebar__label">Overview</span>
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.applicants.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.applicants.*') && request('status') !== 'scholars' ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> <span>Applicants</span>
            </a>
            <a href="{{ route('admin.applicants.index', ['status' => 'scholars']) }}" class="admin-sidebar__link {{ request('status') === 'scholars' ? 'active' : '' }}">
                <i class="bi bi-award-fill"></i> <span>Scholars</span>
            </a>
            <a href="{{ route('admin.master-list.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.master-list.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data-fill"></i> <span>Master List</span>
            </a>

            <span class="admin-sidebar__label mt-3">Management</span>
            <a href="{{ route('admin.students.create') }}" class="admin-sidebar__link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <i class="bi bi-person-plus-fill"></i> <span>Create Account</span>
            </a>
            <a href="{{ route('admin.export.applicants') }}" class="admin-sidebar__link {{ request()->routeIs('admin.export.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i> <span>Export to Excel</span>
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone-fill"></i> <span>Announcements</span>
            </a>
            <a href="{{ route('admin.reminders.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.reminders.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i> <span>Important Reminders</span>
            </a>
            <a href="{{ route('admin.content.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">
                <i class="bi bi-pencil-square"></i> <span>Page Content</span>
            </a>

            <span class="admin-sidebar__label mt-3">Activity</span>
            <a href="{{ route('admin.support.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots-fill"></i> <span>Support Inbox</span>
            </a>
            <a href="{{ route('admin.audit-log.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.audit-log.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> <span>Audit Log</span>
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell-fill"></i> <span>Notifications</span>
                @if (auth()->user()->unreadNotifications->count())
                    <span class="badge rounded-pill bg-danger ms-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>

            <span class="admin-sidebar__label mt-3">Site</span>
            <a href="{{ route('home') }}" class="admin-sidebar__link">
                <i class="bi bi-globe2"></i> <span>Public Site</span>
            </a>
        </nav>

        <div class="admin-sidebar__user mt-3">
            <div class="d-flex align-items-center gap-2">
                <span class="admin-sidebar__avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                <div style="min-width: 0;">
                    <p class="mb-0 small fw-semibold text-truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="mb-0 text-white-50" style="font-size: .7rem;">Administrator</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="admin-sidebar__logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
        <div class="admin-header px-4 py-3 d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMobile">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div>
                    <h1 class="h6 fw-bold mb-0">@yield('title', 'Admin Dashboard')</h1>
                    @hasSection('subtitle')
                        <p class="small text-muted-soft mb-0 mt-1">@yield('subtitle')</p>
                    @endif
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                @include('partials.theme-toggle')
                <div class="dropdown">
                    <button class="btn btn-sm btn-icon position-relative" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" title="Notifications">
                        <i class="bi bi-bell fs-5"></i>
                        @if (auth()->user()->unreadNotifications->count())
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                  style="font-size:.6rem;">
                                {{ auth()->user()->unreadNotifications->count() > 9 ? '9+' : auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow" style="width: 340px; max-height: 420px; overflow-y: auto;">
                        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                            <p class="fw-bold small mb-0">Notifications</p>
                            <a href="{{ route('admin.notifications.index') }}" class="small fw-semibold" style="color: var(--ink-700);">View all</a>
                        </div>
                        @php $recentNotifs = auth()->user()->notifications()->latest()->take(8)->get(); @endphp
                        @forelse ($recentNotifs as $notification)
                            @php $data = $notification->data; @endphp
                            <a href="{{ !empty($data['url']) ? route('admin.notifications.open', ['id' => $notification->id, 'redirect' => $data['url']]) : route('admin.notifications.open', $notification->id) }}"
                               class="dropdown-item d-flex gap-2 align-items-start py-2 {{ $notification->read_at === null ? 'bg-surface' : '' }}">
                                <span class="admin-kpi-icon {{ $notification->read_at === null ? 'admin-kpi-icon--navy' : 'admin-kpi-icon--muted' }}"
                                      style="width:30px;height:30px;font-size:.8rem;flex-shrink:0;">
                                    @php $icon = match(true) { str_contains($data['title'] ?? '', 'appeal') => 'bi-shield-exclamation', str_contains($data['title'] ?? '', 'Requirement') => 'bi-file-earmark-arrow-up', str_contains($data['title'] ?? '', 'support') => 'bi-chat-dots', default => 'bi-person-fill-check' }; @endphp
                                    <i class="bi {{ $icon }}"></i>
                                </span>
                                <span style="min-width:0;">
                                    <span class="d-block small fw-semibold text-truncate" style="color: var(--text-900);">{{ $data['title'] ?? 'Update' }}</span>
                                    <span class="d-block text-muted-soft text-truncate" style="font-size:.72rem;">{{ $data['body'] ?? '' }}</span>
                                    <span class="d-block text-muted-soft" style="font-size:.65rem;">{{ $notification->created_at?->diffForHumans() }}</span>
                                </span>
                            </a>
                        @empty
                            <div class="text-center text-muted-soft py-4">
                                <i class="bi bi-bell-slash d-block mb-1 opacity-50"></i>
                                <span class="small">No notifications yet.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
                @yield('header_actions')
            </div>
        </div>

        <main class="p-4 p-xl-5" style="max-width: 1600px;">
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
        </main>
    </div>
</div>

<!-- Mobile sidebar -->
<div class="offcanvas offcanvas-start admin-sidebar text-white" tabindex="-1" id="adminSidebarMobile">
    <div class="offcanvas-header">
        <span class="admin-sidebar__badge">Admin Panel</span>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <nav class="d-flex flex-column gap-1 flex-grow-1">
            <span class="admin-sidebar__label">Overview</span>
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.applicants.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.applicants.*') && request('status') !== 'scholars' ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> <span>Applicants</span>
            </a>
            <a href="{{ route('admin.applicants.index', ['status' => 'scholars']) }}" class="admin-sidebar__link {{ request('status') === 'scholars' ? 'active' : '' }}">
                <i class="bi bi-award-fill"></i> <span>Scholars</span>
            </a>
            <a href="{{ route('admin.master-list.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.master-list.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data-fill"></i> <span>Master List</span>
            </a>
            <span class="admin-sidebar__label mt-3">Management</span>
            <a href="{{ route('admin.students.create') }}" class="admin-sidebar__link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <i class="bi bi-person-plus-fill"></i> <span>Create Account</span>
            </a>
            <a href="{{ route('admin.export.applicants') }}" class="admin-sidebar__link {{ request()->routeIs('admin.export.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-spreadsheet-fill"></i> <span>Export to Excel</span>
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone-fill"></i> <span>Announcements</span>
            </a>
            <a href="{{ route('admin.reminders.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.reminders.*') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i> <span>Important Reminders</span>
            </a>
            <a href="{{ route('admin.content.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">
                <i class="bi bi-pencil-square"></i> <span>Page Content</span>
            </a>
            <span class="admin-sidebar__label mt-3">Activity</span>
            <a href="{{ route('admin.support.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                <i class="bi bi-chat-dots-fill"></i> <span>Support Inbox</span>
            </a>
            <a href="{{ route('admin.audit-log.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.audit-log.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> <span>Audit Log</span>
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="admin-sidebar__link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <i class="bi bi-bell-fill"></i> <span>Notifications</span>
                @if (auth()->user()->unreadNotifications->count())
                    <span class="badge rounded-pill bg-danger ms-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <span class="admin-sidebar__label mt-3">Site</span>
            <a href="{{ route('home') }}" class="admin-sidebar__link">
                <i class="bi bi-globe2"></i> <span>Public Site</span>
            </a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/theme.js') }}"></script>
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