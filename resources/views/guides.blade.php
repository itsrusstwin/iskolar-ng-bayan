@extends('layouts.public')
@section('title', 'Guides - Iskolar ng Bayan')

@section('content')

<section class="bg-brand-navy text-white py-5">
    <div class="container py-3">
        <span class="badge-soft-gold mb-3 d-inline-block">{{ $content['guides_hero_badge'] }}</span>
        <h1 class="display-6 fw-bold text-white mb-2">{{ $content['guides_hero_title'] }}</h1>
        <p class="fs-6 text-white-50 mb-0">{{ $content['guides_hero_subtitle'] }}</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">

        <!-- Steps -->
        <div class="col-lg-7">
            @php
                $steps = json_decode($content['guides_steps'] ?? '[]', true) ?: [];
            @endphp

            @foreach ($steps as $i => $step)
                <div class="d-flex gap-3 mb-4">
                    <div class="flex-shrink-0">
                        <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white"
                             style="width: 42px; height: 42px; background: var(--ink-900);">
                            {{ $i + 1 }}
                        </div>
                        @if (!$loop->last)
                            <div class="mx-auto" style="width: 2px; height: 100%; min-height: 40px; background: var(--surface-border);"></div>
                        @endif
                    </div>
                    <div class="card-flat p-3 p-md-4 flex-grow-1">
                        <p class="mb-2">{{ $step['en'] }}</p>
                        <p class="small text-muted-soft fst-italic mb-0">{{ $step['fil'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Image -->
        <div class="col-lg-5">
            <div class="rounded-xl overflow-hidden shadow-soft position-relative" style="min-height: 460px;">
                <div class="position-absolute w-100 h-100" style="background-image: url('{{ asset('images/login-bg.jpg') }}'); background-size: cover; background-position: center;"></div>
                <div class="position-absolute w-100 h-100" style="background: linear-gradient(180deg, rgba(10,38,71,.2), rgba(10,38,71,.75));"></div>
                <div class="position-absolute bottom-0 start-0 p-4 text-white">
                    <h3 class="fw-bold text-white mb-0">ISKOLAR<br>NG BAYAN</h3>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection