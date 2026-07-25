<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Iskolar ng Bayan')</title>
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

@auth
    <div class="w-full min-h-screen flex">

        <!-- Sidebar -->
        <div class="w-60 shrink-0 bg-gray-900 dark:bg-gray-950 text-gray-300 flex flex-col">
            <div class="px-5 py-4 border-b border-gray-800">
                <a href="{{ route('home') }}" class="flex items-center gap-1.5">
                    <img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo" class="h-8 bg-white rounded px-1 py-0.5">
                    <img src="{{ asset('images/iskolar-logo.jpg') }}" alt="Iskolar ng Bayan Logo" class="h-8 bg-white rounded px-1 py-0.5">
                    <img src="{{ asset('images/lydo-logo.jpg') }}" alt="LYDO Logo" class="h-8 w-8 rounded-full object-cover">
                </a>
            </div>

            <div class="px-3 pt-3">
                <span class="block bg-blue-600 text-white font-semibold text-sm px-3 py-2.5 rounded-lg">Admin Panel</span>
            </div>

            <nav class="flex-1 p-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="ti ti-layout-dashboard text-lg"></i> Dashboard
                </a>
                <a href="{{ route('admin.announcements.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.announcements.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="ti ti-speakerphone text-lg"></i> Announcements
                </a>
                <a href="{{ route('admin.students.create') }}"
                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.students.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="ti ti-user-plus text-lg"></i> Create Student Account
                </a>
                <a href="{{ route('home') }}"
                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800">
                    <i class="ti ti-home text-lg"></i> Public Site
                </a>
            </nav>

            <div class="p-3 border-t border-gray-800">
                <div class="flex items-center gap-2.5 px-2 py-2 mb-1 text-xs text-gray-400">
                    <div class="w-7 h-7 rounded-full bg-gray-700 flex items-center justify-center text-white font-medium">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="truncate">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800">
                        <i class="ti ti-logout text-lg"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main area -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-3.5">
                <h1 class="text-sm font-semibold text-gray-700 dark:text-gray-200">@yield('title', 'Admin Dashboard')</h1>
                <button onclick="toggleDarkMode()" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Toggle dark mode">
                    <svg class="w-5 h-5 hidden dark:block text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <svg class="w-5 h-5 block dark:hidden text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </button>
            </div>

            <div class="max-w-7xl mx-auto py-8 px-6">
                @if (session('success'))
                    <div class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 p-4 rounded-lg mb-6 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 p-4 rounded-lg mb-6">
                        <ul class="list-disc list-inside">
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
@else
    <div class="max-w-4xl mx-auto py-10 px-4">
        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 p-4 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
@endauth

    <script>
      function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
        localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
      }
    </script>
</body>
</html>