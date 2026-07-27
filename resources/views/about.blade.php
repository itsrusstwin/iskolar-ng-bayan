@extends('layouts.public')
@section('title', 'About Us - Iskolar ng Bayan')

@section('content')

<section class="bg-brand-navy text-white py-5">
    <div class="container py-3">
        <span class="badge-soft-gold mb-3 d-inline-block">Who we are</span>
        <h1 class="display-6 fw-bold text-white mb-2">About Us</h1>
        <p class="fs-6 text-white-50 mb-0" style="max-width: 620px;">
            Iskolar ng Bayan is the scholarship program of the Municipality of Santa Cruz, Laguna,
            supporting local youth in their pursuit of higher education.
        </p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-flat p-4 p-md-5">
                <h2 class="h4 fw-bold mb-3">Our Mission</h2>
                <p class="text-muted-soft">
                    Information about the Iskolar ng Bayan scholarship program goes here.
                </p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-flat p-4 h-100">
                <h3 class="h6 fw-bold mb-3">Program handled by</h3>
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