@extends('layouts.public')
@section('title', 'About Us - Iskolar ng Bayan')

@section('content')

<section class="bg-brand-navy text-white py-5">
    <div class="container py-3">
        <span class="badge-soft-gold mb-3 d-inline-block">{{ $content['about_hero_badge'] }}</span>
        <h1 class="display-6 fw-bold text-white mb-2">{{ $content['about_hero_title'] }}</h1>
        <p class="fs-6 text-white-50 mb-0" style="max-width: 620px;">
            {{ $content['about_hero_subtitle'] }}
        </p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-flat p-4 p-md-5">
                <h2 class="h4 fw-bold mb-3">{{ $content['about_mission_title'] }}</h2>
                <p class="text-muted-soft">
                    {{ $content['about_mission'] }}
                </p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-flat p-4 h-100">
                <h3 class="h6 fw-bold mb-3">{{ $content['about_partner_label'] }}</h3>
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo" height="36">
                    <span class="small text-muted-soft">Municipality of Santa Cruz</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('images/lydo-logo.jpg') }}" alt="LYDO Logo" height="36" width="36" class="rounded-circle" style="object-fit:cover;">
                    <span class="small text-muted-soft">Local Youth Development Office</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection