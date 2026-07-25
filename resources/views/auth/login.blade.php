<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Iskolar ng Bayan</title>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen">

    <div class="w-full min-h-screen bg-white dark:bg-gray-900 flex flex-col md:flex-row">

        <!-- Left panel: image with overlay text -->
        <div class="md:w-1/2 relative min-h-[280px] md:min-h-screen">
            <div class="absolute inset-0 bg-cover bg-center"
                 style="background-image: url('{{ asset('images/login-bg.jpg') }}');">
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-blue-800/40 to-blue-500/70"></div>

            <!-- Logo top-left -->
<div class="absolute top-6 left-6 z-20 flex items-center gap-2">
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo"
             class="h-10 bg-white rounded-md px-2 py-1 shadow-sm transition-all duration-200 hover:opacity-90 hover:scale-105 hover:shadow-md">
    </a>
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/iskolar-logo.jpg') }}" alt="Iskolar ng Bayan Logo"
             class="h-10 bg-white rounded-md px-2 py-1 shadow-sm transition-all duration-200 hover:opacity-90 hover:scale-105 hover:shadow-md">
    </a>
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/lydo-logo.jpg') }}" alt="LYDO Logo"
             class="h-10 w-10 rounded-full object-cover shadow-sm transition-all duration-200 hover:opacity-90 hover:scale-105 hover:shadow-md">
    </a>
</div>

            <div class="relative z-10 h-full flex flex-col justify-end p-8 text-white">
                <p class="text-sm font-medium mb-1 opacity-90">LOGIN</p>
                <h2 class="text-3xl font-extrabold leading-tight">
                    ISKOLAR<br>NG BAYAN
                </h2>
            </div>
        </div>

        <!-- Right panel: login form -->
        <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <div class="flex justify-end mb-2">
                <button onclick="toggleDarkMode()" class="p-2 rounded-lg hover:bg-black/5 dark:hover:bg-white/10 transition" title="Toggle dark mode">
                    <svg class="w-5 h-5 hidden dark:block text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <svg class="w-5 h-5 block dark:hidden text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                </button>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-2">Welcome!</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">
                Log in with the account credentials provided by your scholarship administrator.
            </p>

            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show"
                     class="relative bg-red-100 text-red-700 p-4 pr-10 rounded-lg mb-4 text-sm">
                    <button type="button" @click="show = false"
                            class="absolute top-2 right-3 text-red-500 hover:text-red-800 font-bold text-lg leading-none">
                        &times;
                    </button>
                    @foreach ($errors->all() as $error)
                        <p class="mb-2">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                @csrf

                <div>
                   <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="Enter your email"
                        class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required autofocus>
                </div>

                <div x-data="{ showPassword: false }">
                    <label class="block text-sm font-semibold text-gray-800 dark:text-gray-200 mb-1">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password"
                            placeholder="Enter your password"
                            class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                            required>
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                        <input type="checkbox" name="remember" class="rounded border-gray-300">
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Forgot Password?</a>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition">
                    Login to Your Space
                </button>
            </form>

        </div>
    </div>

    <script>
      function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
        localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
      }
    </script>
</body>
</html>