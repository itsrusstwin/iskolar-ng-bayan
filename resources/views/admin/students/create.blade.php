@extends('layouts.app')
@section('title', 'Create Student Account')

@section('content')

<a href="{{ route('admin.dashboard') }}" class="d-inline-flex align-items-center gap-1 small text-muted-soft mb-3 text-decoration-none">
    <i class="bi bi-arrow-left"></i> Back to dashboard
</a>

<div class="row justify-content-start">
    <div class="col-lg-7">
        <div class="card-flat p-4 p-md-5" style="border: none; box-shadow: var(--app-shadow-md); background:
            radial-gradient(120% 180% at 100% -10%, rgba(232,163,61,.12), transparent 55%),
            var(--surface-0);">
            <h1 class="h5 fw-bold mb-1">Create Student Account</h1>
            <p class="small text-muted-soft mb-4">
                Create login credentials for a student. They'll use these to log in and complete their own scholarship profile.
            </p>

            <form method="POST" action="{{ route('admin.students.store') }}">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control text-uppercase" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control text-uppercase" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Temporary Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-navy px-4 py-2 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-person-plus"></i> Create Account
                </button>
            </form>
        </div>
    </div>

    <div class="col-lg-5 mt-4 mt-lg-0">
        <div class="admin-panel h-100">
            <div class="admin-panel__header">
                <h2 class="h6 fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-people-fill"></i> Created Accounts
                </h2>
                <p class="small text-muted-soft mb-0">
                    Recently created credentials — {{ $createdAccounts->count() }} total.
                    <span class="d-inline-flex align-items-center gap-1">
                        <i class="bi bi-info-circle" data-bs-toggle="tooltip" title="Removing an account from this list does NOT delete the actual student account."></i>
                    </span>
                </p>
            </div>
            <div class="admin-panel__body admin-panel__body--flush" style="max-height: 560px; overflow-y: auto;">
                @forelse ($createdAccounts as $account)
                    <div class="admin-activity-item">
                        <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                            <div class="d-flex gap-2" style="min-width:0;">
                                <span class="admin-kpi-icon admin-kpi-icon--navy" style="width:34px;height:34px;font-size:.85rem;flex-shrink:0;">
                                    <i class="bi bi-person-badge"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p class="fw-semibold small mb-0 text-truncate" style="max-width: 220px;">{{ $account->name }}</p>
                                    <p class="small text-muted-soft mb-0 text-truncate" style="font-size:.75rem; max-width:220px;">
                                        <i class="bi bi-envelope"></i> {{ $account->email }}
                                    </p>
                                    <div class="d-flex align-items-center gap-1 mt-1" style="min-width:0; max-width:220px;">
                                        <code class="small text-truncate" style="font-size:.72rem; color: var(--text-500); min-width:0; max-width:calc(100% - 24px);" id="password-{{ $account->id }}">
                                            {{ $account->password }}
                                        </code>
                                        <button type="button" class="btn btn-link p-0 text-muted-soft small flex-shrink-0"
                                                style="font-size:.75rem; text-decoration:none;"
                                                onclick="togglePassword('{{ $account->id }}')" title="Show / hide password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    <p class="text-muted-soft mb-0 mt-1" style="font-size:.68rem;">
                                        <i class="bi bi-person"></i> {{ $account->creator?->name ?? 'Unknown' }} ·
                                        {{ $account->created_at?->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.students.created-account.destroy', $account) }}"
                                  class="flex-shrink-0"
                                  onsubmit="return confirm('Remove this account from the list? The student account itself will NOT be deleted.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon text-danger" title="Remove from list">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <span class="empty-state__icon"><i class="bi bi-person-plus"></i></span>
                        <h6>No accounts created yet</h6>
                        <p>Credentials you create for students will show up here for easy reference.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        [].forEach.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'), function (el) {
            new bootstrap.Tooltip(el);
        });

        [].forEach.call(document.querySelectorAll('.text-uppercase'), function (input) {
            input.addEventListener('input', function () {
                const cursorPos = this.selectionStart;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(cursorPos, cursorPos);
            });
        });
    });

    function togglePassword(id) {
        const code = document.getElementById('password-' + id);
        const icon = code.parentElement.querySelector('i');
        if (code.classList.contains('hidden')) {
            code.classList.remove('hidden');
            code.style.WebkitTextSecurity = 'none';
            icon.className = 'bi bi-eye';
        } else {
            code.classList.add('hidden');
            code.style.WebkitTextSecurity = 'disc';
            icon.className = 'bi bi-eye-slash';
        }
    }
</script>
@endpush
