@extends('layouts.student')
@section('title', 'Edit Profile - Iskolar ng Bayan')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card-elevated p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold mb-0">Edit Profile</h1>
                <a href="{{ route('profile.show') }}" class="small fw-semibold link-brand d-inline-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Back to profile
                </a>
            </div>

            @if ($errors->any())
                <div class="alert-brand-danger p-3 mb-4 small">
                    @foreach ($errors->all() as $error)
                        <p class="mb-1">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <h2 class="small fw-bold text-uppercase text-muted-soft mb-3">Personal Information</h2>
                <div class="mb-3">
                    <label class="form-label">Application ID</label>
                    <input type="text" value="{{ str_pad($applicant->id, 5, '0', STR_PAD_LEFT) }}" class="form-control bg-surface" readonly>
                </div>
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $applicant->last_name) }}" class="form-control text-uppercase" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $applicant->first_name) }}" class="form-control text-uppercase" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $applicant->middle_name) }}" class="form-control text-uppercase">
                    </div>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" value="{{ old('contact_number', $applicant->contact_number) }}" placeholder="e.g. 0917 123 4567" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Application Type</label>
                        <select name="program_type" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="new" {{ old('program_type', $applicant->program_type) === 'new' ? 'selected' : '' }}>New</option>
                            <option value="renewal" {{ old('program_type', $applicant->program_type) === 'renewal' ? 'selected' : '' }}>Renewal</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <label class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($applicant->date_of_birth)->format('Y-m-d')) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sex</label>
                        <select name="sex" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="Male" {{ old('sex', $applicant->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $applicant->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Landmark</label>
                        <input type="text" name="landmark" value="{{ old('landmark', $applicant->landmark) }}" placeholder="e.g. Near the barangay hall" class="form-control text-uppercase">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sitio</label>
                        <input type="text" name="sitio" value="{{ old('sitio', $applicant->sitio) }}" class="form-control text-uppercase">
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
                                <option value="{{ $brgy }}" {{ old('barangay', $applicant->barangay) === $brgy ? 'selected' : '' }}>{{ $brgy }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <h2 class="small fw-bold text-uppercase text-muted-soft mt-4 mb-3 pt-3 border-top">Family Information</h2>
                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <label class="form-label">Father's Full Name</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $applicant->father_name) }}" class="form-control text-uppercase">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mother's Full Maiden Name</label>
                        <input type="text" name="mother_maiden_name" value="{{ old('mother_maiden_name', $applicant->mother_maiden_name) }}" class="form-control text-uppercase">
                    </div>
                </div>

                <h2 class="small fw-bold text-uppercase text-muted-soft mt-4 mb-3 pt-3 border-top">Educational Background</h2>
                <div class="mb-2">
                    <label class="form-label">School Enrolled</label>
                    <select name="school_name" id="school_name" class="form-select" required>
                        <option value="">-- Select --</option>
                        @foreach ([
                            'ACTS COMPUTER COLLEGE', 'AMA COLLEGE', 'LAGUNA STATE POLYTECHNIC UNIVERSITY',
                            'LAGUNA UNIVERSITY', 'STI COLLEGE', 'PHINMA UNION COLLEGE',
                            'SOUTHBAY MONTESSORI SCHOOL', "PHILIPPINE WOMEN'S UNIVERSITY",
                        ] as $school)
                            <option value="{{ $school }}" {{ old('school_name', $applicant->school_name) === $school ? 'selected' : '' }}>{{ $school }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Year</label>
                        <select name="year_level" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="1st Year" {{ old('year_level', $applicant->year_level) === '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year_level', $applicant->year_level) === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year_level', $applicant->year_level) === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year_level', $applicant->year_level) === '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Full Course</label>
                        <select name="course" id="course" class="form-select" required>
                            <option value="">-- Select a school first --</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-navy px-4 py-2">Save Changes</button>
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

    const savedCourse = @json(old('course', $applicant->course));

    function populateCourses(selectedSchool, selectedCourse) {
        const courseSelect = document.getElementById('course');
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

    const schoolSelect = document.getElementById('school_name');

    // Populate on page load with the saved values
    populateCourses(schoolSelect.value, savedCourse);

    // Re-populate when school changes
    schoolSelect.addEventListener('change', function () {
        populateCourses(this.value, null);
    });
</script>

@endsection