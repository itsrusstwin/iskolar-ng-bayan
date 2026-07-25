@extends('layouts.app')
@section('title', 'Announcements')

@section('content')

<div class="flex justify-between items-center mb-5">
    <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Announcements</h1>
    <a href="{{ route('admin.announcements.create') }}"
       class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg flex items-center gap-1.5">
        <i class="ti ti-plus"></i> New Announcement
    </a>
</div>

<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
    @forelse ($announcements as $announcement)
        <div class="flex items-center justify-between p-5 border-t border-gray-100 dark:border-gray-700 first:border-t-0">
            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <p class="font-medium text-sm text-gray-900 dark:text-gray-100 truncate">{{ $announcement->title }}</p>
                    @if ($announcement->is_published)
                        <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 text-xs font-medium px-2 py-0.5 rounded-full">Published</span>
                    @else
                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-medium px-2 py-0.5 rounded-full">Draft</span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Str::limit($announcement->body, 100) }}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $announcement->created_at->format('M d, Y g:ia') }}</p>
            </div>
            <div class="flex items-center gap-3 shrink-0 ml-4">
                <a href="{{ route('admin.announcements.edit', $announcement) }}"
                   class="text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline">Edit</a>
                <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}"
                      onsubmit="return confirm('Delete this announcement?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 dark:text-red-400 text-sm font-medium hover:underline">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <div class="p-10 text-center text-gray-500 dark:text-gray-400">
            No announcements yet. Click "New Announcement" to add one.
        </div>
    @endforelse
</div>

@endsection
