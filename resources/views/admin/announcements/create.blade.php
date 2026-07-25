@extends('layouts.app')
@section('title', 'New Announcement')

@section('content')

<a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 mb-4">
    <i class="ti ti-arrow-left"></i> Back to announcements
</a>

<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 max-w-2xl">
    <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-5">New Announcement</h1>

    <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm"
                   required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
            <textarea name="body" rows="6"
                      class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm"
                      required>{{ old('body') }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
            <input type="checkbox" name="is_published" value="1" checked>
            Publish immediately (visible on the Home page)
        </label>

        <button type="submit" class="bg-blue-600 text-white text-sm font-medium px-5 py-2.5 rounded-lg">
            Save Announcement
        </button>
    </form>
</div>

@endsection