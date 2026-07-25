<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Dashboard - Iskolar ng Bayan')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = { darkMode: 'class' }
    </script>
    <script>
      if (localStorage.theme === 'dark' ||
          (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
      }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">

    <div class="w-full min-h-screen">

        <!-- Top nav -->
        <div class="flex items-center justify-between bg-blue-600 px-7 py-3.5">
            <div class="flex items-center gap-2">
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo" class="h-9 bg-white rounded-md px-1.5 py-0.5">
    </a>
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/iskolar-logo.jpg') }}" alt="Iskolar ng Bayan Logo" class="h-9 bg-white rounded-md px-1.5 py-0.5">
    </a>
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/lydo-logo.jpg') }}" alt="LYDO Logo" class="h-9 w-9 rounded-full object-cover">
    </a>
</div>

            <div class="flex gap-6 text-sm text-white">
                <a href="{{ route('home') }}" class="hover:opacity-80">Home</a>
                <a href="{{ route('dashboard') }}"
                   class="font-medium border-b-2 border-white pb-0.5">Dashboard</a>
                <a href="{{ route('guides') }}" class="hover:opacity-80">Guides</a>
            </div>

            <div class="flex items-center gap-2 text-white text-sm">
                <button onclick="toggleDarkMode()" class="p-2 rounded-lg hover:bg-white/10 transition" title="Toggle dark mode">
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </button>
                <div class="relative">
                    <button onclick="toggleUserMenu()" id="userMenuBtn" class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-white/10 transition">
                        <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-xs font-medium">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <span>{{ auth()->user()->name ?? 'User' }}</span>
                        <i class="ti ti-chevron-down text-sm"></i>
                    </button>

                    <div id="userMenu" class="hidden absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg text-gray-700 dark:text-gray-200 z-50 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold shrink-0">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        @php $applicant = auth()->user()->applicant ?? null; @endphp
                        <div class="p-4 space-y-2 text-xs">
                            <div class="flex justify-between gap-3">
                                <span class="text-gray-500 dark:text-gray-400">Role</span>
                                <span class="font-medium text-gray-800 dark:text-gray-100">{{ ucfirst(auth()->user()->role ?? '—') }}</span>
                            </div>
                            @if($applicant)
                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-500 dark:text-gray-400">Course</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-100 text-right">{{ $applicant->course ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-500 dark:text-gray-400">Year Level</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ $applicant->year_level ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-500 dark:text-gray-400">School</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-100 text-right">{{ $applicant->school_name ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-500 dark:text-gray-400">Contact</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ $applicant->contact_number ?? '—' }}</span>
                                </div>
                                <div class="flex justify-between gap-3">
                                    <span class="text-gray-500 dark:text-gray-400">Location</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-100 text-right">{{ collect([$applicant->barangay, $applicant->city_municipality, $applicant->province])->filter()->implode(', ') ?: '—' }}</span>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-xs font-medium text-blue-600 dark:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                            View / Edit Full Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body: sidebar + content -->
        <div class="grid grid-cols-1 md:grid-cols-[220px_1fr] bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 min-h-[calc(100vh-64px)]">
            <!-- Sidebar -->
            <div class="p-4 border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <a href="{{ route('dashboard') }}"
   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-sm font-medium mb-1.5">
    <i class="ti ti-user text-lg"></i> My profile
</a>
<a href="{{ route('profile.edit') }}"
   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 text-sm mb-1.5 hover:bg-gray-100 dark:hover:bg-gray-800">
    <i class="ti ti-edit text-lg"></i> Edit Profile
</a>
<a href="#requirements"
   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 text-sm mb-1.5 hover:bg-gray-100 dark:hover:bg-gray-800">
    <i class="ti ti-file-upload text-lg"></i> Requirements
</a>
                <a href="{{ route('home') }}"
                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 text-sm mb-1.5 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <i class="ti ti-bell text-lg"></i> Announcements
                </a>
                <a href="#status"
                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 text-sm mb-1.5 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <i class="ti ti-history text-lg"></i> Application status
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-gray-600 dark:text-gray-300 text-sm hover:bg-gray-100 dark:hover:bg-gray-800">
                        <i class="ti ti-logout text-lg"></i> Logout
                    </button>
                </form>
            </div>

            <!-- Main content -->
            <div class="p-6">
                @if (session('success'))
                    <div class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 p-3 rounded mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script>
      function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
        localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
      }

      function toggleUserMenu() {
        document.getElementById('userMenu').classList.toggle('hidden');
      }

      document.addEventListener('click', function (e) {
        const menu = document.getElementById('userMenu');
        const btn = document.getElementById('userMenuBtn');
        if (!menu || !btn) return;
        if (!menu.contains(e.target) && !btn.contains(e.target)) {
          menu.classList.add('hidden');
        }
      });
    </script>
</body>
</html>