<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Iskolar ng Bayan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
</head>
<body class="bg-surface">

    <div class="container-fluid p-0">
        <div class="row g-0 min-vh-100">

            <!-- Left: brand panel -->
            <div class="col-lg-6 d-none d-lg-flex position-relative bg-brand-navy text-white flex-column justify-content-between p-5"
                 style="background-image: linear-gradient(165deg, rgba(10,38,71,.92), rgba(28,79,143,.85)), url('{{ asset('images/login-bg.jpg') }}'); background-size: cover; background-position: center;">

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('home') }}" class="logo-chip"><img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo" class="logo-mark"></a>
                    <a href="{{ route('home') }}" class="logo-chip"><img src="{{ asset('images/iskolar-logo.jpg') }}" alt="Iskolar ng Bayan Logo" class="logo-mark"></a>
                    <a href="{{ route('home') }}" class="logo-chip"><img src="{{ asset('images/lydo-logo.jpg') }}" alt="LYDO Logo" class="logo-mark"></a>
                </div>

                <div>
                    <span class="badge-soft-gold mb-3 d-inline-block">Municipality of Santa Cruz, Laguna</span>
                    <h1 class="display-5 fw-bold text-white mb-3">ISKOLAR<br>NG BAYAN</h1>
                    <p class="fs-5 text-white-50 mb-0" style="max-width: 420px;">
                        A scholarship built for the youth of Santa Cruz — track your application,
                        submit requirements, and follow every step from home.
                    </p>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <svg class="seal-badge" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="38" r="30" fill="#E8A33D"/>
                        <circle cx="50" cy="38" r="30" stroke="#fff" stroke-width="2"/>
                        <path d="M50 22L54.5 32.3L65.7 33.5L57.3 41.1L59.7 52.2L50 46.5L40.3 52.2L42.7 41.1L34.3 33.5L45.5 32.3L50 22Z" fill="#0A2647"/>
                        <path d="M35 60L30 95L50 84L70 95L65 60" fill="#0A2647"/>
                    </svg>
                    <p class="small text-white-50 mb-0">Verified accounts only — created and managed by your scholarship administrator.</p>
                </div>
            </div>

            <!-- Right: form -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-md-5">
                <div style="width: 100%; max-width: 420px;">

                    <div class="d-lg-none text-center mb-4">
                        <img src="{{ asset('images/iskolar-logo.jpg') }}" alt="Iskolar ng Bayan Logo" height="48">
                    </div>

                    <h2 class="fw-bold mb-1">Welcome back</h2>
                    <p class="text-muted-soft mb-4">Log in with the credentials provided by your scholarship administrator.</p>

                    @if ($errors->any())
                        <div class="alert-brand-danger p-3 mb-4 small">
                            @foreach ($errors->all() as $error)
                                <div class="d-flex gap-2 mb-1"><i class="bi bi-exclamation-circle-fill mt-1"></i><span>{{ $error }}</span></div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert-brand-success p-3 mb-4 small">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control" placeholder="you@example.com" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordField"
                                       class="form-control" placeholder="Enter your password" required>
                                <button class="btn btn-icon" type="button" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-muted-soft" for="remember">Remember me</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small fw-semibold" style="color: var(--ink-700);">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn btn-navy w-100 py-2">Login to Your Space</button>
                    </form>

                    <p class="text-center small text-muted-soft mt-4 mb-0">
                        Iskolar ng Bayan · Municipality of Santa Cruz, Laguna
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const field = document.getElementById('passwordField');
            const icon = document.getElementById('toggleIcon');
            const isPassword = field.type === 'password';
            field.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        }
    </script>
</body>
</html>