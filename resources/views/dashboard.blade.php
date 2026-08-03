@extends('layouts.student')
@section('title', 'My Dashboard - Iskolar ng Bayan')

@section('content')

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
                    </div>

                    <h2 class="small fw-bold text-uppercase text-muted-soft mt-4 mb-3 pt-3 border-top">Educational Background</h2>
                    <div class="mb-3">
                        <label class="form-label">School Enrolled</label>
                        <select name="school_name" id="dashboard_school_name" class="form-select" required>
                            <option value="">-- Select --</option>
                            @foreach ([
                                'ACTS COMPUTER COLLEGE', 'AMA COLLEGE', 'LAGUNA STATE POLYTECHNIC UNIVERSITY',
                                'LAGUNA UNIVERSITY', 'STI COLLEGE', 'PHINMA UNION COLLEGE',
                                'SOUTHBAY MONTESSORI SCHOOL', "PHILIPPINE WOMEN'S UNIVERSITY",
                            ] as $school)
                                <option value="{{ $school }}" {{ old('school_name') === $school ? 'selected' : '' }}>{{ $school }}</option>
                            @endforeach
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
                            <select name="course" id="dashboard_course" class="form-select" required>
                                <option value="">-- Select a school first --</option>
                            </select>
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

        const schoolCourses = {
            'ACTS COMPUTER COLLEGE': [
                'Bachelor of Science in Computer Science (BSCS)',
                'Bachelor of Science in Information Technology (BSIT)',
                'Bachelor of Science in Information Management (BSIM)',
                'Bachelor of Science in Business Administration (BSBA)',
                'Bachelor of Science in Secretarial Administration / Office Administration',
            ],
            'AMA COLLEGE': [
                'Bachelor of Science in Information Technology (BSIT)',
                'Bachelor of Science in Computer Science (BSCS)',
                'Bachelor of Science in Computer Engineering (BSCPE)',
                'Bachelor of Science in Electronics Engineering (BSECE)',
                'Bachelor of Science in Accountancy (BSA)',
                'Bachelor of Science in Business Administration (BSBA)',
                'Bachelor of Science in Psychology (BSPsy)',
            ],
            'LAGUNA STATE POLYTECHNIC UNIVERSITY': [
                'BS in Civil Engineering',
                'BS in Mechanical Engineering',
                'BS in Electrical Engineering',
                'BS in Electronics Engineering',
                'BS in Computer Engineering',
                'Bachelor of Secondary Education',
                'Bachelor of Elementary Education',
                'Technical Vocational Teacher Education',
                'Bachelor of Physical Education',
                'BS in Information Technology',
                'BS in Computer Science',
                'BS in Biology',
                'BS in Psychology',
                'BS in Mathematics',
                'BS in Chemistry',
                'BS in Broadcasting',
                'BS in Accountancy',
                'BS in Entrepreneurship',
                'BS in Office Administration',
                'BS in Hospitality Management',
                'BS in Tourism Management',
                'BS in Nursing',
                'BS in Industrial Technology',
            ],
            'LAGUNA UNIVERSITY': [
                'Bachelor of Elementary Education',
                'Bachelor of Secondary Education major in English',
                'Bachelor of Secondary Education major in Math',
                'Bachelor of Secondary Education major in Science',
                'BA in Communication',
                'BA in Psychology',
                'BS in Psychology',
                'BS in Accountancy (BSA)',
                'BS in Accounting Information System (BSAIS)',
                'BS in Entrepreneurship',
                'BS in Tourism Management',
                'BS in Information Technology',
                'BS in Computer Science',
                'BS in Mechanical Engineering',
            ],
            'STI COLLEGE': [
                'BS in Information Technology (BSIT)',
                'BS in Computer Science (BSCS)',
                'BS in Business Administration (BSBA)',
                'BS in Office Management',
            ],
            'PHINMA UNION COLLEGE': [
                'BS in Information Technology',
                'BS in Business Administration (Marketing Management)',
                'BS in Hospitality Management',
                'BS in Tourism Management',
                'BS in Accountancy',
                'BS in Computer Science',
            ],
            'SOUTHBAY MONTESSORI SCHOOL': [
                'Bachelor of Science in Accountancy',
                'Bachelor of Science in Psychology',
                'Bachelor of Science in Social Work',
                'Food and Beverages Services NCII (356 hours)',
                'Housekeeping NCII (436 hours)',
                'Bread and Pastry Production NCII (141 hours)',
                'Computer System Servicing NCII',
            ],
            "PHILIPPINE WOMEN'S UNIVERSITY": [
                'Bachelor of Science in Information Technology (BSIT)',
                'Bachelor of Science in Hospitality Management',
                'Bachelor of Science in Tourism Management',
                'Bachelor of Science in Business Administration',
            ],
        };

        const savedCourse = '{{ old('course') }}';

        function populateDashboardCourses(selectedSchool, selectedCourse) {
            const courseSelect = document.getElementById('dashboard_course');
            courseSelect.innerHTML = '';

            if (!selectedSchool || !schoolCourses[selectedSchool]) {
                courseSelect.innerHTML = '<option value="">-- Select a school first --</option>';
                return;
            }

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = '-- Select a course --';
            courseSelect.appendChild(placeholder);

            schoolCourses[selectedSchool].forEach(function (course) {
                const option = document.createElement('option');
                option.value = course;
                option.textContent = course;
                if (course === selectedCourse) {
                    option.selected = true;
                }
                courseSelect.appendChild(option);
            });
        }

        const schoolSelect = document.getElementById('dashboard_school_name');

        // Populate on page load (handles old() value after validation failure)
        populateDashboardCourses(schoolSelect.value, savedCourse);

        // Re-populate when school changes
        schoolSelect.addEventListener('change', function () {
            populateDashboardCourses(this.value, null);
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

        $stages = [
            'submitted' => ['label' => 'Application submitted', 'icon' => 'bi-send-fill'],
            'pending_verification' => ['label' => 'Policy verification', 'icon' => 'bi-clipboard-check'],
            'pending_mswdo' => ['label' => 'MSWDO assessment', 'icon' => 'bi-people'],
            'exam_scheduled' => ['label' => 'Qualifying exam', 'icon' => 'bi-pencil-square'],
            'oriented' => ['label' => 'Orientation', 'icon' => 'bi-mortarboard'],
            'compliance_pending' => ['label' => 'Compliance', 'icon' => 'bi-recycle'],
            'paid_out' => ['label' => 'Scholarship released', 'icon' => 'bi-wallet2'],
        ];
        $statusStageMap = [
            'submitted' => 0,
            'incomplete' => 0,
            'pending_verification' => 1,
            'pending_mswdo' => 2,
            'exam_scheduled' => 3,
            'exam_passed' => 3,
            'exam_failed' => 3,
            'oriented' => 4,
            'compliance_pending' => 5,
            'compliance_met' => 5,
            'paid_out' => 6,
            'disqualified_policy' => 1,
            'disqualified_policy_verification' => 1,
            'disqualified_mswdo' => 2,
            'disqualified_poverty' => 2,
            'disqualified_exam' => 3,
        ];
        $currentStageIndex = $statusStageMap[$applicant->status] ?? 0;
        $stageKeys = array_keys($stages);

        $isDisqualified = str_starts_with($applicant->status, 'disqualified');
        $latestDisqualification = $applicant->disqualifications->last();
        $existingAppeal = $latestDisqualification ? $latestDisqualification->appeals->last() : null;

        $addressParts = array_filter([$applicant->landmark, $applicant->sitio, $applicant->barangay]);
    @endphp

    <!-- Welcome banner -->
    <div class="alert-brand-success p-3 mb-4 d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i>
        <span class="fw-semibold">Welcome back{{ $applicant->first_name ? ', ' . $applicant->first_name : '' }}!</span>
    </div>

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

    <!-- Profile summary + Requirements progress -->
    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div id="status" class="card-elevated p-4 h-100">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="d-flex gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold fs-4"
                             style="width:64px;height:64px;background: var(--surface-100); color: var(--ink-800); flex-shrink:0;">
                            {{ $initials }}
                        </div>
                        <div>
                            <p class="small text-muted-soft mb-0 fw-semibold" style="letter-spacing:.04em;">Application ID: {{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}</p>
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
                    <div class="col-sm-6">
                        <p class="small text-muted-soft mb-0">Email</p>
                        <p class="mb-0 text-break">{{ $applicant->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="small text-muted-soft mb-0">Contact number</p>
                        <p class="mb-0">{{ $applicant->contact_number ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="pt-3 mt-3 border-top">
                    <p class="small text-muted-soft mb-0">Address</p>
                    <p class="mb-0">{{ $addressParts ? implode(', ', $addressParts) : 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card-elevated p-4 h-100 d-flex flex-column">
                <p class="fw-bold fs-6 mb-3">Requirements Progress</p>
                <div class="d-flex align-items-end justify-content-between mb-2 pb-2 border-bottom">
                    <span class="small text-muted-soft">{{ $submittedReqs }} of {{ $totalReqs }} Requirements Submitted</span>
                    <span class="h4 fw-bold mb-0" style="color: var(--ink-700);">{{ $progressPercent }}%</span>
                </div>
                <div class="progress mb-4" style="height: 8px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $progressPercent }}%; background: var(--ink-700);"></div>
                </div>
                <a href="#requirements" class="btn btn-navy mt-auto">View Checklist</a>
            </div>
        </div>
    </div>

    <!-- Application status tracker -->
    <div id="application-status" class="card-elevated p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clock-history fs-5" style="color: var(--ink-700);"></i>
                <p class="fw-bold mb-0">Application Status</p>
            </div>
            <span class="{{ $currentStatus['class'] }}">{{ $currentStatus['label'] }}</span>
        </div>

        @if ($isDisqualified)
            <div class="alert-brand-danger p-3 mb-4 d-flex gap-2 align-items-start">
                <i class="bi bi-x-octagon-fill fs-5"></i>
                <div class="small">
                    <p class="fw-bold mb-1">Your application was disqualified.</p>
                    <p class="mb-0">
                        Reason: {{ $latestDisqualification->reason ?? 'Not specified' }} ({{ \Carbon\Carbon::parse($latestDisqualification->notice_issued_at)->format('M d, Y') ?? '—' }})
                    </p>
                </div>
            </div>
        @endif

        <div class="status-tracker">
            @foreach ($stages as $key => $stage)
                @php
                    $idx = array_search($key, $stageKeys);
                    $isDone = $idx < $currentStageIndex;
                    $isCurrent = $idx === $currentStageIndex && !$isDisqualified;
                    $isUpcoming = $idx > $currentStageIndex;
                    $isBlocked = $isDisqualified && $idx === $currentStageIndex;
                @endphp
                <div class="status-tracker__step
                    {{ $isDone ? 'status-tracker__step--done' : '' }}
                    {{ $isCurrent ? 'status-tracker__step--current' : '' }}
                    {{ $isUpcoming ? 'status-tracker__step--upcoming' : '' }}
                    {{ $isBlocked ? 'status-tracker__step--blocked' : '' }}">
                    <div class="status-tracker__node">
                        @if ($isDone)
                            <i class="bi bi-check-lg"></i>
                        @elseif ($isBlocked)
                            <i class="bi bi-x-lg"></i>
                        @else
                            <i class="bi {{ $stage['icon'] }}"></i>
                        @endif
                    </div>
                    <div class="status-tracker__body">
                        <p class="status-tracker__label">{{ $stage['label'] }}</p>
                        @if ($isCurrent)
                            <span class="status-tracker__pill status-tracker__pill--current">Current</span>
                        @elseif ($isBlocked)
                            <span class="status-tracker__pill status-tracker__pill--blocked">Stopped</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">
            <a href="{{ route('applicants.show', $applicant) }}" class="btn btn-navy btn-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-person-lines-fill"></i> View Application Details
            </a>
            @if ($latestDisqualification)
                @if ($existingAppeal && $existingAppeal->result === 'pending')
                    <span class="btn btn-outline-navy btn-sm d-inline-flex align-items-center gap-1 disabled">
                        <i class="bi bi-hourglass-split"></i> Appeal pending
                    </span>
                @else
                    <a href="#appeal-form" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1">
                        <i class="bi bi-shield-exclamation"></i> File an appeal
                    </a>
                @endif
            @endif
        </div>

        @if ($latestDisqualification && (! $existingAppeal || $existingAppeal->result !== 'pending'))
        <form id="appeal-form" method="POST" action="{{ route('appeals.store') }}" class="mt-4 pt-3 border-top">
            @csrf
            <input type="hidden" name="disqualification_id" value="{{ $latestDisqualification->id }}">
            <p class="fw-semibold small mb-2">File an appeal</p>
            <textarea name="reconsideration_notes" rows="3" class="form-control mb-2" placeholder="Explain why you believe the disqualification is a mistake..." required></textarea>
            <button type="submit" class="btn btn-navy btn-sm px-3">Submit appeal</button>
        </form>
        @endif
    </div>

    <!-- My benefits: Waste compliance + Payouts -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card-elevated p-4 h-100">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="admin-kpi-icon admin-kpi-icon--green" style="width:38px;height:38px;font-size:1.05rem;flex-shrink:0;">
                        <i class="bi bi-recycle"></i>
                    </span>
                    <div>
                        <p class="fw-bold mb-0">Waste Compliance</p>
                        <p class="small text-muted-soft mb-0">Plastic waste submission per semester</p>
                    </div>
                </div>

                @php
                    $totalComplied = $applicant->wasteCompliance->where('is_compliant', true)->count();
                    $totalKilosSubmitted = $applicant->wasteCompliance->sum('kilos_submitted');
                @endphp

                @if ($applicant->wasteCompliance->count())
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-2">
                            <thead>
                                <tr class="small text-muted-soft">
                                    <th>Semester</th>
                                    <th class="text-center">Required</th>
                                    <th class="text-center">Submitted</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applicant->wasteCompliance->sortByDesc('semester') as $wc)
                                    <tr>
                                        <td class="small">{{ $wc->semester }}</td>
                                        <td class="small text-center">{{ number_format($wc->kilos_required, 1) }} kg</td>
                                        <td class="small text-center">{{ number_format($wc->kilos_submitted, 1) }} kg</td>
                                        <td class="text-end">
                                            @if ($wc->is_compliant)
                                                <span class="badge-soft-gold">Compliant</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger-emphasis">Not compliant</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between small pt-2 border-top">
                        <span class="text-muted-soft">Total kilos submitted</span>
                        <span class="fw-semibold">{{ number_format($totalKilosSubmitted, 1) }} kg</span>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2" style="color: var(--text-500); opacity:.5;"></i>
                        <p class="small text-muted-soft mb-0">No waste compliance records yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-elevated p-4 h-100">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="admin-kpi-icon admin-kpi-icon--blue" style="width:38px;height:38px;font-size:1.05rem;flex-shrink:0;">
                        <i class="bi bi-wallet2"></i>
                    </span>
                    <div>
                        <p class="fw-bold mb-0">Scholarship Payouts</p>
                        <p class="small text-muted-soft mb-0">Financial assistance released</p>
                    </div>
                </div>

                @php $totalPayoutAmount = $applicant->payouts->sum('amount'); @endphp

                @if ($applicant->payouts->count())
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-2">
                            <thead>
                                <tr class="small text-muted-soft">
                                    <th>Amount</th>
                                    <th>Released</th>
                                    <th class="text-end">Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applicant->payouts->sortByDesc('released_at') as $payout)
                                    <tr>
                                        <td class="small fw-semibold">₱{{ number_format($payout->amount, 2) }}</td>
                                        <td class="small">{{ $payout->released_at?->format('M d, Y') ?? '—' }}</td>
                                        <td class="small text-end text-muted-soft">{{ $payout->reference_no ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between small pt-2 border-top">
                        <span class="text-muted-soft">Total assistance received</span>
                        <span class="fw-semibold">₱{{ number_format($totalPayoutAmount, 2) }}</span>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2" style="color: var(--text-500); opacity:.5;"></i>
                        <p class="small text-muted-soft mb-0">No payouts released yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Important Reminders -->
    <div class="card-elevated p-4 mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-exclamation-triangle-fill" style="color: var(--gold-500);"></i>
            <p class="fw-bold mb-0">Important Reminders</p>
        </div>
        <ul class="mb-0 ps-3" style="line-height: 1.9;">
            <li class="small">All applications must be submitted within the deadline.</li>
            <li class="small">Only complete requirements will be processed.</li>
            <li class="small">Applicant will undergo validation, exam, and orientation.</li>
            <li class="small">Scholars must comply with academic and plastic waste submission requirements.</li>
            <li class="small">Non-compliance may result in forfeiture of scholarship benefits.</li>
        </ul>
    </div>

    @if ($applicant->exam_scheduled_at || $applicant->orientation_scheduled_at)
        <!-- Upcoming schedules -->
        <div class="card-elevated p-4 mb-4">
            <p class="fw-bold mb-3">Upcoming Schedules</p>

            @if ($applicant->exam_scheduled_at)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-event fs-5 text-muted-soft"></i>
                        <div>
                            <p class="mb-0 fw-semibold small">Qualifying Exam</p>
                            <p class="small text-muted-soft mb-0">{{ $applicant->exam_scheduled_at->format('F j, Y \a\t g:ia') }}</p>
                        </div>
                    </div>
                    <span class="badge-soft-navy">Scheduled</span>
                </div>
            @endif

            @if ($applicant->orientation_scheduled_at)
                <div class="d-flex align-items-center justify-content-between py-2 flex-wrap gap-2 {{ $applicant->exam_scheduled_at ? 'pt-3' : '' }}">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-mortarboard fs-5 text-muted-soft"></i>
                        <div>
                            <p class="mb-0 fw-semibold small">Orientation</p>
                            <p class="small text-muted-soft mb-0">{{ $applicant->orientation_scheduled_at->format('F j, Y \a\t g:ia') }}</p>
                        </div>
                    </div>
                    <span class="badge-soft-navy">Scheduled</span>
                </div>
            @endif
        </div>
    @endif

    <!-- Announcements -->
    @if (isset($announcements) && $announcements->isNotEmpty())
    <div class="card-elevated p-4 mb-4">
        <p class="fw-bold mb-3">Announcements</p>
        <div class="row g-3">
            @foreach ($announcements as $announcement)
            <div class="col-md-6">
                <div class="card-flat p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <div>
                            <p class="fw-bold mb-0">{{ $announcement->title }}</p>
                            <p class="small text-muted-soft mb-0">{{ $announcement->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="admin-kpi-icon admin-kpi-icon--navy" style="width:36px;height:36px;font-size:1rem;flex-shrink:0;">
                            <i class="bi bi-megaphone"></i>
                        </span>
                    </div>
                    <p class="small mb-0" style="white-space: pre-line;">{{ Str::limit($announcement->body, 140) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Quick actions -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <a href="#requirements" class="btn btn-navy w-100 d-flex align-items-center justify-content-between px-3 py-3 text-decoration-none">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-cloud-upload fs-5"></i> <span class="fw-semibold">Upload Requirement</span></span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="col-md-4">
            <a href="#appeal" class="btn w-100 d-flex align-items-center justify-content-between px-3 py-3 text-decoration-none" style="background: var(--gold-500); color: var(--ink-900); font-weight:600;">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-file-earmark-text fs-5"></i> <span class="fw-semibold">File Appeal</span></span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('support.index') }}" class="btn w-100 d-flex align-items-center justify-content-between px-3 py-3 text-decoration-none" style="background: #1E6B3C; color: #fff; font-weight:600;">
                <span class="d-flex align-items-center gap-2"><i class="bi bi-headset fs-5"></i> <span class="fw-semibold">Contact Support</span></span>
                <i class="bi bi-chevron-right"></i>
            </a>
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
                        <a href="javascript:void(0)" onclick="previewFile('{{ asset('storage/' . $applicant->mswdoAssessment->social_case_study_report_path) }}', 'Social Case Study Report')" class="small fw-semibold" style="color: var(--ink-700);">
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
                        <a href="javascript:void(0)" onclick="previewFile('{{ asset('storage/' . $result->file_path) }}', 'Exam File')" class="small fw-semibold" style="color: var(--ink-700);">
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
    <div id="appeal" class="mb-4">
        @if ($isDisqualified && $latestDisqualification)
            <div class="alert-brand-danger p-4">
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
        @else
            <div class="card-elevated p-4">
                <p class="fw-bold mb-1">Appeals</p>
                <p class="small text-muted-soft mb-0">
                    You don't have any disqualification on record right now, so there's nothing to appeal.
                    If you believe there's an error with your application, please reach out via Contact Support.
                </p>
            </div>
        @endif
    </div>

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
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        @if ($req->file_path)
                            <button type="button" class="btn btn-sm btn-outline-navy d-inline-flex align-items-center gap-1" onclick="previewFile('{{ asset('storage/' . $req->file_path) }}', '{{ $req->requirement->name }}')">
                                <i class="bi bi-eye"></i> View
                            </button>
                        @endif

                        @if ($req->approval_status === 'approved')
                            <span class="badge-soft-gold">Approved</span>
                        @elseif ($req->approval_status === 'rejected')
                            <span class="badge bg-danger-subtle text-danger-emphasis">Not approved — please re-upload</span>
                        @else
                            <span class="badge-soft-navy">Pending review</span>
                        @endif

                        <form method="POST" action="{{ route('requirements.upload', $req) }}" enctype="multipart/form-data" class="d-inline">
                            @csrf
                            <input type="file" name="file" accept=".pdf" id="file-{{ $req->id }}" class="d-none" onchange="validateAndSubmit(this, {{ $req->id }})">
                            <label for="file-{{ $req->id }}" class="btn btn-sm btn-outline-navy d-inline-flex align-items-center gap-1 mb-0" style="cursor:pointer;">
                                <i class="bi bi-arrow-repeat"></i> Replace
                            </label>
                        </form>
                        <form method="POST" action="{{ route('requirements.destroy', $req) }}" onsubmit="return confirm('Remove this uploaded file? You will need to upload it again.');" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1">
                                <i class="bi bi-trash3"></i> Delete
                            </button>
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