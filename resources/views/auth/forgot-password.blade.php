<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.theme-init')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Iskolar ng Bayan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
</head>
<body class="bg-surface d-flex align-items-center" style="min-height: 100vh;">

    <div class="container position-relative">
        <div class="position-absolute top-0 end-0 pt-3">
            @include('partials.theme-toggle')
        </div>
        <div class="row justify-content-center">
            <div class="col-md-5 col-sm-8">

                <div class="text-center mb-4">
                    <span class="badge-soft-navy mb-2 d-inline-block"><i class="bi bi-shield-lock me-1"></i> Account Recovery</span>
                </div>

                <div class="card-elevated p-4 p-md-5">
                    <h2 class="fw-bold mb-1">Forgot Password?</h2>
                    <p class="text-muted-soft mb-4">Enter your email and we'll send you a reset link.</p>

                    @if ($errors->any())
                        <div class="alert-brand-danger p-3 mb-4 small">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert-brand-success p-3 mb-4 small">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                        </div>
                        <button type="submit" class="btn btn-navy w-100 py-2">Send Reset Link</button>
                    </form>

                    <p class="text-center small text-muted-soft mt-4 mb-0">
                        <a href="{{ route('login') }}" class="fw-semibold link-brand"><i class="bi bi-arrow-left me-1"></i>Back to Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
</body>
</html>