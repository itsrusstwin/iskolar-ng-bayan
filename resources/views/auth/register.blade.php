<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Iskolar ng Bayan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
</head>
<body class="bg-surface">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">

                <div class="text-center mb-4">
                    <a href="{{ route('home') }}"><img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo" height="52"></a>
                </div>

                <div class="card-elevated p-4 p-md-5">
                    <h2 class="fw-bold mb-1">Create Your Account</h2>
                    <p class="text-muted-soft mb-4">Just a few details to get you started on your scholarship application.</p>

                    @if ($errors->any())
                        <div class="alert-brand-danger p-3 mb-4 small">
                            @foreach ($errors->all() as $error)
                                <div class="d-flex gap-2 mb-1"><i class="bi bi-exclamation-circle-fill mt-1"></i><span>{{ $error }}</span></div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-navy w-100 py-2 mt-2">Continue</button>
                    </form>

                    <p class="text-center small text-muted-soft mt-4 mb-0">
                        Already have an account? <a href="{{ route('login') }}" class="fw-semibold" style="color: var(--ink-700);">Log in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>