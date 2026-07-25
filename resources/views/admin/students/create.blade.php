@extends('layouts.app')
@section('title', 'Create Student Account')

@section('content')

<a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 mb-4">
    <i class="ti ti-arrow-left"></i> Back to dashboard
</a>

<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 max-w-2xl">
    <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">Create Student Account</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
        Create login credentials for a student. They'll use these to log in and complete their own scholarship profile.
    </p>

    <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm"
                       required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm"
                   required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Temporary Password</label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm"
                       required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm"
                       required>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg">
            Create Account
        </button>
    </form>
</div>

@endsection