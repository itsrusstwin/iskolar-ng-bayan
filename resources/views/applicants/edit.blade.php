@extends('layouts.student')
@section('title', 'Edit Profile - Iskolar ng Bayan')

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card-elevated p-4 p-md-5">
            <h1 class="h3 fw-bold mb-4">Update Your Profile</h1>

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
                    <select name="school_name" class="form-select" required>
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
                        <input type="text" name="course" value="{{ old('course', $applicant->course) }}" placeholder="e.g. Bachelor of Science in Computer Science" class="form-control" required>
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
</script>

@endsection