@extends('layouts.public')
@section('title', 'Home - Iskolar ng Bayan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8 grid md:grid-cols-2 gap-6">

    <!-- Left column: Announcements -->
    <div>
        <div class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden mb-4">
            <div class="bg-white dark:bg-gray-800 px-4 py-2 font-semibold text-gray-800 dark:text-gray-100 border-b dark:border-gray-700">
                Announcements
            </div>
        </div>

        @forelse ($announcements as $announcement)
            <div class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden mb-4">
                <div class="bg-blue-600 text-white px-4 py-2 font-semibold">
                    {{ $announcement->title }}
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 text-sm text-gray-700 dark:text-gray-300">
                    <p class="whitespace-pre-line">{{ $announcement->body }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ $announcement->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        @empty
            <div class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden mb-4">
                <div class="bg-white dark:bg-gray-800 p-4 text-sm text-gray-500 dark:text-gray-400">
                    No announcements at this time.
                </div>
            </div>
        @endforelse

        <div class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden mb-4">
            <div class="bg-blue-600 text-white px-4 py-2 font-semibold">
                Scholarship Requirements
            </div>
            <ul class="bg-white dark:bg-gray-800 p-4 space-y-2 text-sm text-gray-700 dark:text-gray-300 list-disc list-inside">
                <li>Certified True Copy of Grades</li>
                <li>Photocopy PSA Birth Certificate</li>
                <li>Photocopy Latest School ID</li>
                <li>Long Brown Envelope</li>
            </ul>
        </div>

        <div class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden">
            <div class="bg-blue-600 text-white px-4 py-2 font-semibold">
                Period for the Submission of Requirements
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 text-sm text-gray-700 dark:text-gray-300">
                <p>July 20, 2026 – November 20, 2026</p>
            </div>
        </div>
    </div>

    <!-- Right column: Background image -->
<div class="relative rounded-lg overflow-hidden min-h-[500px] md:min-h-[600px]">
        <div class="absolute inset-0 bg-cover bg-center"
             style="background-image: url('{{ asset('images/login-bg.jpg') }}');">
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-blue-800/30 to-blue-600/70"></div>

        <div class="relative z-10 h-full flex flex-col justify-start p-6 text-white">
    <h2 class="text-3xl font-extrabold leading-tight">
        ISKOLAR<br>NG BAYAN
    </h2>
</div>
        </div>
    </div>

</div>
@endsection