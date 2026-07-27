@extends('layouts.app')
@section('title', 'Create Student Account')

@section('content')

<a href="{{ route('admin.dashboard') }}" class="d-inline-flex align-items-center gap-1 small text-muted-soft mb-3 text-decoration-none">
    <i class="bi bi-arrow-left"></i> Back to dashboard
</a>

<div class="row justify-content-start">
    <div class="col-lg-7">
        <div class="card-flat p-4 p-md-5">
            <h1 class="h5 fw-bold mb-1">Create Student Account</h1>
            <p class="small text-muted-soft mb-4">
                Create login credentials for a student. They'll use these to log in and complete their own scholarship profile.
            </p>

            <form method="POST" action="{{ route('admin.students.store') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Temporary Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-navy px-4 py-2">Create Account</button>
            </form>
        </div>
    </div>
</div>

@endsection