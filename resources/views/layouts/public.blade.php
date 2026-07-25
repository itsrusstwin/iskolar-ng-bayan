<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Iskolar ng Bayan')</title>
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
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen">

    <nav class="bg-blue-600 text-white px-6 py-3 flex items-center justify-between shadow">
        <div class="flex items-center gap-2">
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo" class="h-9 bg-white rounded-md px-2 py-1">
    </a>
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/iskolar-logo.jpg') }}" alt="Iskolar ng Bayan Logo" class="h-9 bg-white rounded-md px-2 py-1">
    </a>
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/lydo-logo.jpg') }}" alt="LYDO Logo" class="h-9 w-9 rounded-full object-cover">
    </a>
</div>

        <div class="flex items-center gap-8 text-sm font-semibold">
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'text-white border-b-2 border-white pb-1' : 'text-blue-100 hover:text-white' }}">
                Home
            </a>
            <a href="{{ route('about') }}"
               class="{{ request()->routeIs('about') ? 'text-white border-b-2 border-white pb-1' : 'text-blue-100 hover:text-white' }}">
                About us
            </a>
            <a href="{{ route('guides') }}"
               class="{{ request()->routeIs('guides') ? 'text-white border-b-2 border-white pb-1' : 'text-blue-100 hover:text-white' }}">
                Guides
            </a>
            @auth
    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="text-blue-100 hover:text-white">
        Dashboard
    </a>
@else
    <a href="{{ route('login') }}"
               class="text-blue-100 hover:text-white">
                Login\Sign up
            </a>
@endauth
            <button onclick="toggleDarkMode()" class="p-2 rounded-lg hover:bg-white/10 transition" title="Toggle dark mode">
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
            </button>
        </div>
    </nav>

    @yield('content')

    <script>
      function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
        localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
      }
    </script>
</body>
</html>