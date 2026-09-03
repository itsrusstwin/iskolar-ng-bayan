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

                <p class="section-eyebrow mb-3 mt-2">Personal Information</p>
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

                <p class="section-eyebrow mt-4 mb-3 pt-3 border-top">Family Information</p>
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

                <p class="section-eyebrow mt-4 mb-3 pt-3 border-top">Educational Background</p>
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
                            <option value="1st Year" {{ old('year_level', $applicant->year_level) === '1st Year' ? 'selected' : '' }}>1ST YEAR</option>
                            <option value="2nd Year" {{ old('year_level', $applicant->year_level) === '2nd Year' ? 'selected' : '' }}>2ND YEAR</option>
                            <option value="3rd Year" {{ old('year_level', $applicant->year_level) === '3rd Year' ? 'selected' : '' }}>3RD YEAR</option>
                            <option value="4th Year" {{ old('year_level', $applicant->year_level) === '4th Year' ? 'selected' : '' }}>4TH YEAR</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Full Course</label>
                        <select name="course" id="course" class="form-select" required>
                            <option value="">-- Select a school first --</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-navy px-4 py-2 d-inline-flex align-items-center gap-2">
                    <i class="bi bi-check2-circle"></i> Save Changes
                </button>
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
    'BACHELOR OF SCIENCE IN COMPUTER SCIENCE (BSCS)',
    'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
    'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION (BSBA)',
    'BACHELOR OF SCIENCE IN ENTREPRENUERSHIP (BSENTREP)',
    'BACHELOR OF SCIENCE IN OFFICE ADMINISTRATION (BSOA)',
    'BACHELOR OF TECHNICAL-VOCATIONAL TEACHER EDUCATION (BTVTED)',
    'BACHELOR OF SCIENCE IN ACCOUNTING INFORMATION SYSTEM (BSAIS)',
],
'AMA COLLEGE': [
    'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
    'BACHELOR OF SCIENCE IN COMPUTER SCIENCE (BSCS)',
    'BACHELOR OF SCIENCE IN COMPUTER ENGINEERING (BSCPE)',
    'BACHELOR OF SCIENCE IN ELECTRONICS ENGINEERING (BSECE)',
    'BACHELOR OF SCIENCE IN ACCOUNTANCY (BSA)',
    'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION (BSBA)',
    'BACHELOR OF SCIENCE IN PSYCHOLOGY (BSPSY)',
],
'LAGUNA STATE POLYTECHNIC UNIVERSITY': [
    'BACHELOR OF SCIENCE IN NURSING (BSN)',
    'BACHELOR OF SCIENCE IN BIOLOGY (BS BIOLOGY)',
    'BACHELOR OF SCIENCE IN CHEMISTRY (BS CHEMISTRY)',
    'BACHELOR OF SCIENCE IN MATHEMATICS (BS MATHEMATICS)',
    'BACHELOR OF SCIENCE IN PSYCHOLOGY (BS PSYCHOLOGY)',
    'BACHELOR OF SCIENCE IN CIVIL ENGINEERING (BSCE)',
    'BACHELOR OF SCIENCE IN COMPUTER ENGINEERING (BSCPE)',
    'BACHELOR OF SCIENCE IN ELECTRICAL ENGINEERING (BSEE)',
    'BACHELOR OF SCIENCE IN ELECTRONICS ENGINEERING (BSECE)',
    'BACHELOR OF SCIENCE IN MECHANICAL ENGINEERING (BSME)',
    'BACHELOR OF SCIENCE IN COMPUTER SCIENCE (BSCS)',
    'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
    'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT (BSHM)',
    'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT (BSTM)',
    'BACHELOR OF SCIENCE IN INDUSTRIAL TECHNOLOGY MAJOR IN AUTOMOTIVE TECHNOLOGY (BSINDTECH-AUTO)',
    'BACHELOR OF SCIENCE IN INDUSTRIAL TECHNOLOGY MAJOR IN ELECTRICAL TECHNOLOGY (BSINDTECH-ELEC)',
    'BACHELOR OF SCIENCE IN INDUSTRIAL TECHNOLOGY MAJOR IN ELECTRONICS TECHNOLOGY (BSINDTECH-ELECTRONICS)',
    'BACHELOR OF SCIENCE IN INDUSTRIAL TECHNOLOGY MAJOR IN FOOD TECHNOLOGY (BSINDTECH-FOOD TECH)',
    'BACHELOR OF SCIENCE IN INDUSTRIAL TECHNOLOGY MAJOR IN GARMENTS/FASHION AND APPAREL TECHNOLOGY (BSINDTECH-GFAT)',
    'BACHELOR OF SCIENCE IN INDUSTRIAL TECHNOLOGY MAJOR IN DRAFTING TECHNOLOGY (BSINDTECH-DRAFTING)',
    'BACHELOR OF SCIENCE IN INDUSTRIAL TECHNOLOGY MAJOR IN REFRIGERATION AND AIR CONDITIONING TECHNOLOGY (BSINDTECH-RAC)',
    'BACHELOR OF ELEMENTARY EDUCATION (BEED)',
    'BACHELOR OF PHYSICAL EDUCATION (BPED)',
    'BACHELOR OF SECONDARY EDUCATION (BSED)',
    'BACHELOR OF TECHNICAL TEACHER EDUCATION (BTTE)',
    'BACHELOR OF TECHNOLOGY AND LIVELIHOOD EDUCATION (BTLED)',
    'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP (BSENTREP)',
    'BACHELOR OF SCIENCE IN OFFICE ADMINISTRATION (BSOA)',
    'BACHELOR OF SCIENCE IN CRIMINOLOGY (BSCRIM)',
],
'LAGUNA UNIVERSITY': [
    'BACHELOR OF ELEMENTARY EDUCATION (BEED)',
    'BACHELOR OF SECONDARY EDUCATION MAJOR IN ENGLISH (BSED-ENGLISH)',
    'BACHELOR OF SECONDARY EDUCATION MAJOR IN MATHEMATICS (BSED-MATH)',
    'BACHELOR OF SECONDARY EDUCATION MAJOR IN SCIENCE (BSED-SCIENCE)',
    'BACHELOR OF ARTS IN COMMUNICATION (BA COMMUNICATION)',
    'BACHELOR OF ARTS IN PSYCHOLOGY (AB PSYCHOLOGY)',
    'BACHELOR OF SCIENCE IN PSYCHOLOGY (BSPYCH)',
    'BACHELOR OF SCIENCE IN ACCOUNTANCY (BSA)',
    'BACHELOR OF SCIENCE IN ACCOUNTING INFORMATION SYSTEM (BSAIS)',
    'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP (BSENTREP)',
    'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT (BSTM)',
    'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
    'BACHELOR OF SCIENCE IN COMPUTER SCIENCE (BSCS)',
    'BACHELOR OF SCIENCE IN MECHANICAL ENGINEERING (BSME)',
],
'STI COLLEGE': [
    'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
    'BACHELOR OF SCIENCE IN COMPUTER SCIENCE (BSCS)',
    'BACHELOR OF SCIENCE IN INFORMATION SYSTEMS (BSSI)',
    'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION (BSBA)',
    'BACHELOR OF SCIENCE IN ACCOUNTING INFORMATION SYSTEM (BSAIS)',
    'BACHELOR OF SCIENCE IN MANAGEMENT ACCOUNTING (BSMA)',
    'BACHELOR OF SCIENCE IN RETAIL TECHNOLOGY AND CONSUMER SCIENCE (BSRTCS)',
    'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT (BSHM)',
    'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT (BSTM)',
    'BACHELOR OF SCIENCE IN COMPUTER ENGINEERING (BSCPE)',
    'BACHELOR OF ARTS IN COMMUNICATION (BACOMM)',
    'BACHELOR OF MULTIMEDIA ARTS (BMMA)',
    'BACHELOR OF ARTS IN PSYCHOLOGY (AB PSY)',
],
'PHINMA UNION COLLEGE': [
    'BACHELOR OF SCIENCE IN CRIMINOLOGY (BSCRIM)',
    'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT (BSTM)',
    'BACHELOR OF SCIENCE IN PSYCHOLOGY (BSPYCH)',
    'BACHELOR OF SCIENCE IN ACCOUNTANCY (BSA)',
    'BACHELOR OF SCIENCE IN MANAGEMENT ACCOUNTING (BSMA)',
    'BACHELOR OF SECONDARY EDUCATION MAJOR IN ENGLISH (BSED-ENGLISH)',
    'BACHELOR OF SECONDARY EDUCATION MAJOR IN FILIPINO (BSED-FILIPINO)',
    'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN MARKETING MANAGEMENT (BSBA-MM)',
    'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN FINANCIAL MANAGEMENT (BSBA-FM)',
    'BACHELOR OF ELEMENTARY EDUCATION (BEED)',
    'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT (BSHM)',
    'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
],
'SOUTHBAY MONTESSORI SCHOOL': [
    'BACHELOR OF SCIENCE IN ACCOUNTANCY',
    'BACHELOR OF SCIENCE IN PSYCHOLOGY',
    'BACHELOR OF SCIENCE IN SOCIAL WORK',
],
"PHILIPPINE WOMEN'S UNIVERSITY": [
    'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION (SPECIALIZATION: OPERATIONS MANAGEMENT) (BSBA-OM)',
    'BACHELOR OF SCIENCE IN OFFICE ADMINISTRATION (BSOA)',
    'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN FINANCIAL MANAGEMENT (BSBA-FM)',
    'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN MARKETING MANAGEMENT (BSBA-MM)',
    'BACHELOR OF ELEMENTARY EDUCATION (BEED)',
    'BACHELOR OF SECONDARY EDUCATION MAJOR IN ENGLISH (BSED-ENGLISH)',
    'BACHELOR OF FINE ARTS MAJOR IN VISUAL COMMUNICATION (BFA)',
    'BACHELOR OF SCIENCE IN HOTEL AND RESTAURANT MANAGEMENT (BSHRM)',
    'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT (BSHM)',
    'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT (BSTM)',
    'BACHELOR OF SCIENCE IN INFORMATION SYSTEMS (BSIS)',
    'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
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