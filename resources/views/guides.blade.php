@extends('layouts.public')
@section('title', 'Guides - Iskolar ng Bayan')

@section('content')

<section class="bg-brand-navy text-white py-5">
    <div class="container py-3">
        <span class="badge-soft-gold mb-3 d-inline-block">Step-by-step</span>
        <h1 class="display-6 fw-bold text-white mb-2">Application Guide</h1>
        <p class="fs-6 text-white-50 mb-0">Follow these four steps to complete your scholarship application.</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">

        <!-- Steps -->
        <div class="col-lg-7">
            @php
                $steps = [
                    [
                        'en' => 'Register on the iskolar.ng.bayan website. Click Login/Sign up above and it will take you to the login page — then click Create new account. If you already have an account, just log in.',
                        'fil' => 'Magrehistro sa website na iskolar.ng.bayan. I-click ang Login/Sign up sa itaas, at dadalhin ka nito sa login page. Pagkatapos, i-click ang Create new account kung wala ka pang account. Kung mayroon ka nang account, mag-login na lamang.',
                    ],
                    [
                        'en' => 'Fill up the scholar profile and upload the CERTIFIED TRUE COPIES of the required documents as a PDF file.',
                        'fil' => 'Magfill-up ng scholar profile at i-upload ang mga CERTIFIED TRUE COPIES ng mga kinakailangang dokumento na naka-PDF.',
                    ],
                    [
                        'en' => 'Wait for the verification of your submitted requirements. You will be notified through your account status if you qualify to proceed to the next step.',
                        'fil' => 'Hintayin ang beripikasyon ng iyong mga isinumiteng requirements. Ikaw ay mapapabatid sa pamamagitan ng iyong account status kung ikaw ay kwalipikado upang magpatuloy sa susunod na hakbang.',
                    ],
                    [
                        'en' => 'Once qualified, take the scholarship exam on the scheduled date. Results will be posted on your account.',
                        'fil' => 'Kapag kwalipikado, kunin ang scholarship exam sa naka-iskedyul na petsa. Ang mga resulta ay ipo-post sa iyong account.',
                    ],
                ];
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