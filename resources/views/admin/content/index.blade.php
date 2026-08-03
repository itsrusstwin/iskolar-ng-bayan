@extends('layouts.app')
@section('title', 'Page Content')
@section('subtitle', 'Edit the About Us and Application Guide pages')

@section('content')

<form method="POST" action="{{ route('admin.content.update') }}">
    @csrf
    @method('PUT')

    <div class="card-flat overflow-hidden">
        <ul class="nav nav-tabs admin-tabs px-3 pt-3" id="contentTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="about-tab" data-bs-toggle="tab" data-bs-target="#aboutPane" type="button" role="tab" aria-controls="aboutPane" aria-selected="true">
                    <i class="bi bi-info-circle me-1"></i> About Us
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="guides-tab" data-bs-toggle="tab" data-bs-target="#guidesPane" type="button" role="tab" aria-controls="guidesPane" aria-selected="false">
                    <i class="bi bi-signpost-2 me-1"></i> Application Guide
                </button>
            </li>
        </ul>

        <div class="tab-content">

            <!-- About Us -->
            <div class="tab-pane fade show active p-4 p-md-5" id="aboutPane" role="tabpanel" aria-labelledby="about-tab">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Hero badge</label>
                        <input type="text" name="about_hero_badge" value="{{ $content['about_hero_badge'] }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hero title</label>
                        <input type="text" name="about_hero_title" value="{{ $content['about_hero_title'] }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Hero subtitle</label>
                        <textarea name="about_hero_subtitle" rows="3" class="form-control">{{ $content['about_hero_subtitle'] }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mission title</label>
                        <input type="text" name="about_mission_title" value="{{ $content['about_mission_title'] }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Partner section label</label>
                        <input type="text" name="about_partner_label" value="{{ $content['about_partner_label'] }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mission body</label>
                        <textarea name="about_mission" rows="5" class="form-control">{{ $content['about_mission'] }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Application Guide -->
            <div class="tab-pane fade p-4 p-md-5" id="guidesPane" role="tabpanel" aria-labelledby="guides-tab">
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Hero badge</label>
                        <input type="text" name="guides_hero_badge" value="{{ $content['guides_hero_badge'] }}" class="form-control">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Hero title</label>
                        <input type="text" name="guides_hero_title" value="{{ $content['guides_hero_title'] }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Hero subtitle</label>
                        <textarea name="guides_hero_subtitle" rows="2" class="form-control">{{ $content['guides_hero_subtitle'] }}</textarea>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <label class="form-label mb-0">Guide steps</label>
                    <button type="button" class="btn btn-sm btn-outline-navy" id="addStep">
                        <i class="bi bi-plus-lg"></i> Add step
                    </button>
                </div>

                <div id="stepsWrapper" class="d-flex flex-column gap-3">
                    @foreach ($steps as $i => $step)
                    <div class="step-row p-3 rounded-md" style="background: var(--surface-50); border: 1px solid var(--surface-border);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-semibold text-muted-soft">Step {{ $i + 1 }}</span>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-step"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">English</label>
                                <textarea name="steps_en[]" rows="3" class="form-control">{{ $step['en'] }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Filipino</label>
                                <textarea name="steps_fil[]" rows="3" class="form-control">{{ $step['fil'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 p-4 border-top bg-surface">
            <a href="{{ route('about') }}" target="_blank" class="btn btn-outline-navy px-4">
                <i class="bi bi-eye me-1"></i> Preview About
            </a>
            <a href="{{ route('guides') }}" target="_blank" class="btn btn-outline-navy px-4">
                <i class="bi bi-eye me-1"></i> Preview Guides
            </a>
            <button type="submit" class="btn btn-navy px-4 py-2">
                <i class="bi bi-check-lg me-1"></i> Save Changes
            </button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('stepsWrapper');
    const addBtn = document.getElementById('addStep');

    function stepRow(index, en, fil) {
        const div = document.createElement('div');
        div.className = 'step-row p-3 rounded-md';
        div.style.cssText = 'background: var(--surface-50); border: 1px solid var(--surface-border);';
        div.innerHTML =
            '<div class="d-flex justify-content-between align-items-center mb-2">' +
                '<span class="small fw-semibold text-muted-soft">Step ' + index + '</span>' +
                '<button type="button" class="btn btn-sm btn-outline-danger remove-step"><i class="bi bi-x-lg"></i></button>' +
            '</div>' +
            '<div class="row g-3">' +
                '<div class="col-md-6">' +
                    '<label class="form-label">English</label>' +
                    '<textarea name="steps_en[]" rows="3" class="form-control">' + (en || '') + '</textarea>' +
                '</div>' +
                '<div class="col-md-6">' +
                    '<label class="form-label">Filipino</label>' +
                    '<textarea name="steps_fil[]" rows="3" class="form-control">' + (fil || '') + '</textarea>' +
                '</div>' +
            '</div>';
        return div;
    }

    function renumber() {
        wrapper.querySelectorAll('.step-row').forEach(function (row, i) {
            row.querySelector('.fw-semibold').textContent = 'Step ' + (i + 1);
        });
    }

    addBtn.addEventListener('click', function () {
        const count = wrapper.querySelectorAll('.step-row').length;
        wrapper.appendChild(stepRow(count + 1, '', ''));
    });

    wrapper.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-step');
        if (!btn) return;
        const row = btn.closest('.step-row');
        if (row) {
            row.remove();
            renumber();
        }
    });
});
</script>
@endpush
