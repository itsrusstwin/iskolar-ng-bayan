@extends('layouts.student')
@section('title', 'Edit Profile - Iskolar ng Bayan')
@section('content')
<div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 max-w-2xl">
    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-6">Update Your Profile</h1>
    @if ($errors->any())
        <div class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 p-3 rounded mb-4 text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('PUT')
    <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Personal Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
            <input type="text" name="last_name" value="{{ old('last_name', $applicant->last_name) }}"
                class="uppercase-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
            <input type="text" name="first_name" value="{{ old('first_name', $applicant->first_name) }}"
                class="uppercase-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Middle Name</label>
            <input type="text" name="middle_name" value="{{ old('middle_name', $applicant->middle_name) }}"
                class="uppercase-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm">
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contact Number</label>
            <input type="text" name="contact_number" value="{{ old('contact_number', $applicant->contact_number) }}"
                placeholder="e.g. 0917 123 4567"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Application Type</label>
            <select name="program_type" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm" required>
                <option value="">-- Select --</option>
                <option value="new" {{ old('program_type', $applicant->program_type) === 'new' ? 'selected' : '' }}>New</option>
                <option value="renewal" {{ old('program_type', $applicant->program_type) === 'renewal' ? 'selected' : '' }}>Renewal</option>
            </select>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Birth</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($applicant->date_of_birth)->format('Y-m-d')) }}"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sex</label>
            <select name="sex" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm" required>
                <option value="">-- Select --</option>
                <option value="Male" {{ old('sex', $applicant->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('sex', $applicant->sex) === 'Female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Landmark</label>
            <input type="text" name="landmark" value="{{ old('landmark', $applicant->landmark) }}"
                placeholder="e.g. Near the barangay hall"
                class="uppercase-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sitio</label>
            <input type="text" name="sitio" value="{{ old('sitio', $applicant->sitio) }}"
                class="uppercase-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Barangay</label>
            <input type="text" name="barangay" value="{{ old('barangay', $applicant->barangay) }}"
                placeholder="e.g. Barangay Poblacion I"
                class="uppercase-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm" required>
        </div>
    </div>
    <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide pt-2 border-t dark:border-gray-700">Family Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Father's Full Name</label>
            <input type="text" name="father_name" value="{{ old('father_name', $applicant->father_name) }}"
                class="uppercase-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mother's Full Maiden Name</label>
            <input type="text" name="mother_maiden_name" value="{{ old('mother_maiden_name', $applicant->mother_maiden_name) }}"
                class="uppercase-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm">
        </div>
    </div>
    <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide pt-2 border-t dark:border-gray-700">Educational Background</h2>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School Enrolled</label>
        <select name="school_name" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm" required>
            <option value="">-- Select --</option>
            @foreach ([
                'ACTS COMPUTER COLLEGE',
                'AMA COLLEGE',
                'LAGUNA STATE POLYTECHNIC UNIVERSITY',
                'LAGUNA UNIVERSITY',
                'STI COLLEGE',
                'PHINMA UNION COLLEGE',
                'SOUTHBAY MONTESSORI SCHOOL',
                "PHILIPPINE WOMEN'S UNIVERSITY",
            ] as $school)
                <option value="{{ $school }}" {{ old('school_name', $applicant->school_name) === $school ? 'selected' : '' }}>{{ $school }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year</label>
            <select name="year_level" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm" required>
                <option value="">-- Select --</option>
                <option value="1st Year" {{ old('year_level', $applicant->year_level) === '1st Year' ? 'selected' : '' }}>1st Year</option>
                <option value="2nd Year" {{ old('year_level', $applicant->year_level) === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                <option value="3rd Year" {{ old('year_level', $applicant->year_level) === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                <option value="4th Year" {{ old('year_level', $applicant->year_level) === '4th Year' ? 'selected' : '' }}>4th Year</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Course</label>
            <input type="text" name="course" value="{{ old('course', $applicant->course) }}"
                placeholder="e.g. Bachelor of Science in Computer Science"
                class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg px-3 py-2 text-sm" required>
        </div>
    </div>
        <button type="submit" class="bg-blue-600 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-500 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
            Save Changes
        </button>
    </form>
</div>

<style>
    .uppercase-input { text-transform: uppercase; }
</style>
<script>
    document.querySelectorAll('.uppercase-input').forEach(function (input) {
        input.addEventListener('input', function () {
            const cursorPos = this.selectionStart;
            this.value = this.value.toUpperCase();
            this.setSelectionRange(cursorPos, cursorPos);
        });
    });
</script>
@endsection