<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Iskolar ng Bayan</title>
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
<body class="bg-gray-100 min-h-screen py-10">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow p-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow p-8">
        <div class="flex justify-center mb-6">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo" class="h-12">
            </a>
        </div>
        <h1 class="text-2xl font-bold text-blue-700 mb-6">Create Your Account</h1>
            <button onclick="toggleDarkMode()" class="p-2 rounded-lg hover:bg-black/5 transition" title="Toggle dark mode">
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
            </button>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block font-medium text-gray-700">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" class="mt-1 w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" class="mt-1 w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Password</label>
                <input type="password" name="password" class="mt-1 w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" class="mt-1 w-full border rounded p-2" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Continue
            </button>
        </form>

        <p class="mt-4 text-sm text-gray-600 text-center">
            Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a>
        </p>
    </div>
    <script>
      function toggleDarkMode() {
        document.documentElement.classList.toggle('dark');
        localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
      }
    </script>
</body>
</html>