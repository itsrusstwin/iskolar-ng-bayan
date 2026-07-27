@extends('layouts.public')
@section('title', 'Home - Iskolar ng Bayan')

@section('content')

<!-- Hero -->
<section class="bg-brand-navy text-white position-relative overflow-hidden">
    <div class="container py-5 position-relative" style="z-index: 2;">
        <div class="row align-items-center g-4 py-4">
            <div class="col-lg-7">
                <span class="badge-soft-gold mb-3 d-inline-block">Municipality of Santa Cruz, Laguna</span>
                <h1 class="display-4 fw-bold text-white mb-3">ISKOLAR NG BAYAN</h1>
                <p class="fs-5 text-white-50 mb-4" style="max-width: 560px;">
                    Supporting the scholars of Santa Cruz — from application to release of funds,
                    every step of your scholarship journey in one place.
                </p>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-brand btn-lg px-4">Get Started</a>
                @endguest
            </div>
           
        </div>
    </div>
    <div class="position-absolute top-0 end-0 h-100 opacity-25 d-none d-lg-block"
         style="width: 45%; background-image: url('{{ asset('images/login-bg.jpg') }}'); background-size: cover; background-position: center; z-index: 1; mask-image: linear-gradient(to right, transparent, black);"></div>
</section>

<div class="container py-5">
    <div class="row g-4">

        <!-- Left column: announcements -->
        <div class="col-lg-7">

            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-megaphone-fill" style="color: var(--gold-600);"></i>
                <h2 class="h5 fw-bold mb-0">Announcements</h2>
            </div>

            @forelse ($announcements as $announcement)
                <div class="card-flat mb-3 overflow-hidden">
                    <div class="p-3 text-white" style="background: var(--ink-900);">
                        <p class="fw-semibold mb-0">{{ $announcement->title }}</p>
                    </div>
                    <div class="p-3">
                        <p class="text-body mb-2" style="white-space: pre-line;">{{ $announcement->body }}</p>
                        <p class="small text-muted-soft mb-0">{{ $announcement->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            @empty
                <div class="card-flat p-4 text-center text-muted-soft mb-3">
                    No announcements at this time.
                </div>
            @endforelse
        </div>

        <!-- Right column: image -->
        <div class="col-lg-5">
            <div class="rounded-xl overflow-hidden shadow-soft position-relative" style="min-height: 420px;">
                <div class="position-absolute w-100 h-100" style="background-image: url('{{ asset('images/login-bg.jpg') }}'); background-size: cover; background-position: center;"></div>
                <div class="position-absolute w-100 h-100" style="background: linear-gradient(180deg, rgba(10,38,71,.15), rgba(10,38,71,.75));"></div>
                <div class="position-absolute bottom-0 start-0 p-4 text-white">
                        
                </div>
            </div>
        </div>
    </div>
</div>

@endsection