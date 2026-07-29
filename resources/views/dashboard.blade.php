@extends('layouts.student')
@section('title', 'My Dashboard - Iskolar ng Bayan')

@section('content')

@php
    $schoolCourses = [
        'ACTS COMPUTER COLLEGE' => [
            'Bachelor of Science in Computer Science (BSCS)',
            'Bachelor of Science in Information Technology (BSIT)',
            'Bachelor of Science in Information Systems (BSIS)',
            'Bachelor of Science in Entertainment and Multimedia Computing (BSEMC)',
            'Associate in Computer Technology (ACT)',
            'Computer System Servicing NCII',
        ],
        'AMA COLLEGE' => [
            'Bachelor of Science in Information Technology (BSIT)',
            'Bachelor of Science in Computer Science (BSCS)',
            'Bachelor of Science in Information Systems (BSIS)',
            'Bachelor of Science in Accountancy (BSA)',
            'Bachelor of Science in Business Administration (BSBA)',
            'Bachelor of Science in Psychology (BSP)',
            'Bachelor of Science in Hospitality Management (BSHM)',
            'Bachelor of Science in Tourism Management (BSTM)',
        ],
        @foreach ([
                                'ACTS COMPUTER COLLEGE', 'AMA COLLEGE', 'LAGUNA STATE POLYTECHNIC UNIVERSITY',
                                'LAGUNA UNIVERSITY', 'STI COLLEGE', 'PHINMA UNION COLLEGE',
                                'SOUTHBAY MONTESSORI SCHOOL', "PHILIPPINE WOMEN'S UNIVERSITY",
                            ] as $school)
                                <option value="{{ $school }}" {{ old('school_name') === $school ? 'selected' : '' }}>{{ $school }}</option>
                            @endforeach
    ];
@endphp


@if (!$applicant)

    {{-- Profile not yet completed --}}
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card-elevated p-4 p-md-5">
                <span class="badge-soft-navy mb-2 d-inline-block">Step 1 of 1</span>
                <h1 class="h3 fw-bold mb-1">Complete Your Profile</h1>
                <p class="text-muted-soft mb-4">Just a few more details to finish your scholarship application.</p>

                @if ($errors->any())
                    <div class="alert-brand-danger p-3 mb-4 small">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('applicants.store') }}">
                    @csrf

                    <h2 class="small fw-bold text-uppercase text-muted-soft mb-3">Personal Information</h2>
                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control text-uppercase" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control text-uppercase" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-control text-uppercase">
                        </div>
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" value="{{ old('contact_number') }}" placeholder="e.g. 0917 123 4567" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Application Type</label>
                            <select name="program_type" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="new" {{ old('program_type') === 'new' ? 'selected' : '' }}>New</option>
                                <option value="renewal" {{ old('program_type') === 'renewal' ? 'selected' : '' }}>Renewal</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sex</label>
                            <select name="sex" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-2">
                        <div class="col-md-4">
                            <label class="form-label">Landmark</label>
                            <input type="text" name="landmark" value="{{ old('landmark') }}" placeholder="e.g. Near the barangay hall" class="form-control text-uppercase">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sitio</label>
                            <input type="text" name="sitio" value="{{ old('sitio') }}" class="form-control text-uppercase">
                        </div>
                                                <div class="col-md-4">
                            <label class="form-label">Barangay</label>
                            <select name="barangay" class="form-select" required>
                                <option value="">-- Select --</option>
                                @foreach ([
                                    'Brgy. Alipit', 'Brgy. Bagumbayan', 'Brgy. I (Poblacion)', 'Brgy. II (Poblacion)',
                                    'Brgy. III (Poblacion)', 'Brgy. IV (Poblacion)', 'Brgy. V (Poblacion)',
                                    'Brgy. Bubukal', 'Brgy. Calios', 'Brgy. Duhat', 'Brgy. Gatid',
                                    'Brgy. Jasaan', 'Brgy. Labuin', 'Brgy. Malinao', 'Brgy. Oogong',
                                    'Brgy. Pagsawitan', 'Brgy. Palasan', 'Brgy. Patimbao',
                                    'Brgy. San Jose', 'Brgy. San Juan', 'Brgy. San Pablo Norte',
                                    'Brgy. San Pablo Sur', 'Brgy. Santisima Cruz',
                                    'Brgy. Santo Angel Central', 'Brgy. Santo Angel Norte', 'Brgy. Santo Angel Sur',
                                ] as $brgy)
                                    <option value="{{ $brgy }}" {{ old('barangay') === $brgy ? 'selected' : '' }}>{{ $brgy }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <h2 class="small fw-bold text-uppercase text-muted-soft mt-4 mb-3 pt-3 border-top">Family Information</h2>
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label">Father's Full Name</label>
                            <input type="text" name="father_name" value="{{ old('father_name') }}" class="form-control text-uppercase">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mother's Full Maiden Name</label>
                            <input type="text" name="mother_maiden_name" value="{{ old('mother_maiden_name') }}" class="form-control text-uppercase">
                        </div>
                   <div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Year</label>
        <select name="year_level" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="1st Year" {{ old('year_level') === '1st Year' ? 'selected' : '' }}>1st Year</option>
            <option value="2nd Year" {{ old('year_level') === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
            <option value="3rd Year" {{ old('year_level') === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
            <option value="4th Year" {{ old('year_level') === '4th Year' ? 'selected' : '' }}>4th Year</option>
        </select>
    </div>
    <div class="row g-3 mb-4">
    <div class="col-md-6">
        <label class="form-label">Year</label>
        <select name="year_level" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="1st Year" {{ old('year_level') === '1st Year' ? 'selected' : '' }}>1st Year</option>
            <option value="2nd Year" {{ old('year_level') === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
            <option value="3rd Year" {{ old('year_level') === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
            <option value="4th Year" {{ old('year_level') === '4th Year' ? 'selected' : '' }}>4th Year</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Full Course</label>
        <select name="course" id="courseSelect" class="form-select" required>
            <option value="">-- Select School First --</option>
        </select>
    </div>
</div>

</div>


                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Year</label>
                            <select name="year_level" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="1st Year" {{ old('year_level') === '1st Year' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd Year" {{ old('year_level') === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd Year" {{ old('year_level') === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th Year" {{ old('year_level') === '4th Year' ? 'selected' : '' }}>4th Year</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Full Course</label>
                            <input type="text" name="course" value="{{ old('course') }}" placeholder="e.g. Bachelor of Science in Computer Science" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-navy w-100 py-2">Submit Application</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.text-uppercase').forEach(function (input) {
            input.addEventListener('input', function () {
                const cursorPos = this.selectionStart;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(cursorPos, cursorPos);
            });
        });
    </script>

@else

    @php
        $uploadableRequirements = $applicant->requirements->filter(function ($req) {
            return !str_contains(strtolower($req->requirement->name), 'brown envelope');
        });
        $totalReqs = $uploadableRequirements->count();
        $submittedReqs = $uploadableRequirements->where('is_submitted', true)->count();
        $progressPercent = $totalReqs > 0 ? round(($submittedReqs / $totalReqs) * 100) : 0;

        $statusLabels = [
            'submitted' => ['label' => 'Application submitted', 'class' => 'badge-soft-navy'],
            'pending_mswdo' => ['label' => 'Pending MSWDO assessment', 'class' => 'badge-soft-navy'],
            'exam_scheduled' => ['label' => 'Exam scheduled', 'class' => 'badge-soft-navy'],
            'exam_passed' => ['label' => 'Exam passed', 'class' => 'badge-soft-gold'],
            'oriented' => ['label' => 'Orientation complete', 'class' => 'badge-soft-gold'],
            'compliance_met' => ['label' => 'Compliance met', 'class' => 'badge-soft-gold'],
            'paid_out' => ['label' => 'Scholarship released', 'class' => 'badge-soft-gold'],
        ];
        $currentStatus = $statusLabels[$applicant->status] ?? ['label' => ucfirst(str_replace('_', ' ', $applicant->status)), 'class' => 'badge-soft-navy'];
        $initials = strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1));

        $isDisqualified = str_starts_with($applicant->status, 'disqualified');
        $latestDisqualification = $applicant->disqualifications->last();
        $existingAppeal = $latestDisqualification ? $latestDisqualification->appeals->last() : null;
    @endphp

    @if ($applicant->status === 'exam_passed')
        <div class="alert-brand-success p-3 mb-4 d-flex gap-3 align-items-center">
            <i class="bi bi-check-circle-fill fs-4"></i>
            <div>
                <p class="fw-bold mb-0 small">Congratulations! Your scholarship registration is approved.</p>
                <p class="small mb-0">You passed the qualifying exam. Please watch out for the orientation schedule.</p>
            </div>
        </div>
    @endif

    @if ($applicant->orientation && $applicant->orientation->attended)
        <div class="p-3 mb-4 d-flex gap-3 align-items-center rounded-md" style="background: var(--surface-100);">
            <i class="bi bi-patch-check-fill fs-4" style="color: var(--ink-700);"></i>
            <div>
                <p class="fw-bold mb-0 small">You are officially a Scholar of the Municipality of Santa Cruz.</p>
                <p class="small text-muted-soft mb-0">Orientation attended and acknowledged. Keep track of your remaining requirements below.</p>
            </div>
        </div>
    @endif

    <!-- Profile card -->
    <div id="status" class="card-elevated p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="d-flex gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold fs-4"
                     style="width:64px;height:64px;background: var(--surface-100); color: var(--ink-800); flex-shrink:0;">
                    {{ $initials }}
                </div>
                <div>
                    <p class="fw-bold fs-5 mb-0">{{ $applicant->first_name }} {{ $applicant->last_name }}</p>
                    @if ($applicant->course || $applicant->year_level)
                        <p class="small text-muted-soft mb-0">{{ $applicant->course }}{{ $applicant->course && $applicant->year_level ? ', ' : '' }}{{ $applicant->year_level }}</p>
                    @endif
                    @if ($applicant->school_name)
                        <p class="small text-muted-soft mb-0">{{ $applicant->school_name }}</p>
                    @endif
                </div>
            </div>
            <span class="{{ $currentStatus['class'] }}">{{ $currentStatus['label'] }}</span>
        </div>

        <div class="row g-3 mt-3 pt-3 border-top">
            <div class="col-md-4">
                <p class="small text-muted-soft mb-0">Email</p>
                <p class="mb-0">{{ $applicant->user->email ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <p class="small text-muted-soft mb-0">Contact number</p>
                <p class="mb-0">{{ $applicant->contact_number ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <p class="small text-muted-soft mb-0">Address</p>
                <p class="mb-0">
                    @php $addressParts = array_filter([$applicant->landmark, $applicant->sitio, $applicant->barangay]); @endphp
                    {{ $addressParts ? implode(', ', $addressParts) : 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Assessment & Exam Records -->
    @if ($applicant->mswdoAssessment || $applicant->examResults->count())
        <div class="card-elevated p-4 mb-4">
            <p class="fw-bold mb-3">Assessment &amp; Exam Records</p>

            @if ($applicant->mswdoAssessment)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom flex-wrap gap-2">
                    <div>
                        <p class="mb-0 fw-semibold small">MSWDO Social Case Study Report</p>
                        <p class="small text-muted-soft mb-0">
                            {{ $applicant->mswdoAssessment->is_qualified ? 'Qualified' : 'Not qualified' }}
                            @if ($applicant->mswdoAssessment->assessed_at)
                                — assessed {{ $applicant->mswdoAssessment->assessed_at->format('M d, Y') }}
                            @endif
                        </p>
                    </div>
                    @if ($applicant->mswdoAssessment->social_case_study_report_path)
                        <a href="{{ asset('storage/' . $applicant->mswdoAssessment->social_case_study_report_path) }}" target="_blank" class="small fw-semibold" style="color: var(--ink-700);">
                            <i class="bi bi-file-earmark-pdf"></i> View Report
                        </a>
                    @else
                        <span class="small text-muted-soft">No file uploaded</span>
                    @endif
                </div>
            @endif

            @foreach ($applicant->examResults as $result)
                <div class="d-flex align-items-center justify-content-between py-2 flex-wrap gap-2 {{ $applicant->mswdoAssessment ? 'pt-3' : '' }} {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div>
                        <p class="mb-0 fw-semibold small">Qualifying Exam</p>
                        <p class="small text-muted-soft mb-0">
                            {{ $result->passed ? 'Passed' : 'Failed' }}
                            @if ($result->posted_at)
                                — posted {{ $result->posted_at->format('M d, Y') }}
                            @endif
                        </p>
                    </div>
                    @if ($result->file_path)
                        <a href="{{ asset('storage/' . $result->file_path) }}" target="_blank" class="small fw-semibold" style="color: var(--ink-700);">
                            <i class="bi bi-file-earmark-pdf"></i> View Exam File
                        </a>
                    @else
                        <span class="small text-muted-soft">No file uploaded</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Disqualification / Appeal -->
    @if ($isDisqualified && $latestDisqualification)
        <div class="alert-brand-danger p-4 mb-4">
            <p class="fw-bold mb-1">Application Disqualified</p>
            <p class="small mb-3">{{ $latestDisqualification->reason }}</p>

            @if ($existingAppeal)
                <div class="p-3 rounded-md surface-inset">
                    <p class="small text-muted-soft mb-1">
                        Your appeal, filed {{ $existingAppeal->filed_at?->format('M d, Y') }}:
                    </p>
                    <p class="small mb-2">{{ $existingAppeal->reconsideration_notes }}</p>

                    @if ($existingAppeal->result === 'pending')
                        <span class="badge-soft-navy">Under review</span>
                    @elseif ($existingAppeal->result === 'approved')
                        <span class="badge-soft-gold">Approved — reinstated</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger-emphasis">Denied</span>
                    @endif
                </div>
            @else
                <form method="POST" action="{{ route('appeals.store') }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="disqualification_id" value="{{ $latestDisqualification->id }}">
                    <label class="form-label small fw-semibold">Reason for reconsideration</label>
                    <textarea name="reconsideration_notes" rows="4" class="form-control mb-3" required placeholder="Explain why you believe this decision should be reconsidered...">{{ old('reconsideration_notes') }}</textarea>
                    <button type="submit" class="btn btn-navy btn-sm px-3">File an Appeal</button>
                </form>
            @endif
        </div>
    @endif

    <!-- Requirements checklist -->
    <div id="requirements" class="card-elevated p-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <p class="fw-bold mb-0">Requirements checklist</p>
            <span class="small text-muted-soft">{{ $submittedReqs }} of {{ $totalReqs }} submitted</span>
        </div>
        <div class="progress mb-4" style="height: 6px;">
            <div class="progress-bar" role="progressbar" style="width: {{ $progressPercent }}%; background: var(--ink-700);"></div>
        </div>

        @foreach ($applicant->requirements as $req)
            @php $isPersonalSubmission = str_contains(strtolower($req->requirement->name), 'brown envelope'); @endphp
            <div class="d-flex align-items-center justify-content-between py-3 border-top flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-text fs-5 text-muted-soft"></i>
                    <div>
                        <p class="mb-0">{{ $req->requirement->name }}</p>
                        <p class="small text-muted-soft mb-0">
                            {{ $isPersonalSubmission ? 'To be submitted personally at the office' : 'PDF only, max 5MB' }}
                        </p>
                    </div>
                </div>

                @if ($isPersonalSubmission)
                    <span class="badge-soft-navy">Submit in person</span>
                @elseif ($req->is_submitted)
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        @if ($req->file_path)
                            <a href="{{ asset('storage/' . $req->file_path) }}" target="_blank" class="small fw-semibold" style="color: var(--ink-700);">View</a>
                        @endif

                        @if ($req->approval_status === 'approved')
                            <span class="badge-soft-gold">Approved</span>
                        @elseif ($req->approval_status === 'rejected')
                            <span class="badge bg-danger-subtle text-danger-emphasis">Not approved — please re-upload</span>
                        @else
                            <span class="badge-soft-navy">Pending review</span>
                        @endif

                        <form method="POST" action="{{ route('requirements.upload', $req) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" accept=".pdf" id="file-{{ $req->id }}" class="d-none" onchange="validateAndSubmit(this, {{ $req->id }})">
                            <label for="file-{{ $req->id }}" class="small text-muted-soft text-decoration-underline" style="cursor:pointer;">Replace</label>
                        </form>
                        <form method="POST" action="{{ route('requirements.destroy', $req) }}" onsubmit="return confirm('Remove this uploaded file? You will need to upload it again.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link btn-sm text-danger text-decoration-underline p-0 small">Delete</button>
                        </form>
                    </div>
                @else
                    <form method="POST" action="{{ route('requirements.upload', $req) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".pdf" id="file-{{ $req->id }}" class="d-none" onchange="validateAndSubmit(this, {{ $req->id }})">
                        <label for="file-{{ $req->id }}" class="btn btn-navy btn-sm d-inline-flex align-items-center gap-2" style="cursor:pointer;">
                            <i class="bi bi-upload"></i> Upload
                        </label>
                    </form>
                @endif
                <p id="file-error-{{ $req->id }}" class="d-none small text-danger mt-1 w-100 mb-0"></p>
            </div>
        @endforeach
    </div>

    <script>
        function validateAndSubmit(input, reqId) {
            const errorEl = document.getElementById('file-error-' + reqId);
            const file = input.files[0];
            if (!file) return;

            if (file.type !== 'application/pdf') {
                errorEl.textContent = 'Only PDF files are allowed. Please select a PDF document.';
                errorEl.classList.remove('d-none');
                input.value = '';
                return;
            }

            errorEl.classList.add('d-none');
            input.form.submit();
        }
    </script>

@endif

@endsection