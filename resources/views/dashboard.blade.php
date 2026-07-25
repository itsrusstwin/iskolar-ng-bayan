@extends('layouts.student')
    @section('title', 'My Dashboard - Iskolar ng Bayan')

    @section('content')

    @if (!$applicant)

        {{-- Profile not yet completed --}}
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 max-w-2xl mx-auto">
            <h1 class="text-xl font-semibold text-blue-700 dark:text-blue-400 mb-2">Complete Your Profile</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Just a few more details to finish your scholarship application.</p>

            @if ($errors->any())
                <div class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 p-4 rounded mb-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('applicants.store') }}" class="space-y-5">
        @csrf

        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Personal Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}"
                    class="uppercase-input mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2" required>
            </div>
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}"
                    class="uppercase-input mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2" required>
            </div>
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Middle Name</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                    class="uppercase-input mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                    placeholder="e.g. 0917 123 4567"
                    class="mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2" required>
            </div>
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Application Type</label>
                <select name="program_type" class="mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2" required>
                    <option value="">-- Select --</option>
                    <option value="new" {{ old('program_type') === 'new' ? 'selected' : '' }}>New</option>
                    <option value="renewal" {{ old('program_type') === 'renewal' ? 'selected' : '' }}>Renewal</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                    class="mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2" required>
            </div>
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Sex</label>
                <select name="sex" class="mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2" required>
                    <option value="">-- Select --</option>
                    <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Landmark</label>
                <input type="text" name="landmark" value="{{ old('landmark') }}"
                    placeholder="e.g. Near the barangay hall"
                    class="uppercase-input mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2">
            </div>
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Sitio</label>
                <input type="text" name="sitio" value="{{ old('sitio') }}"
                    class="uppercase-input mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2">
            </div>
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Barangay</label>
                <input type="text" name="barangay" value="{{ old('barangay') }}"
                    placeholder="e.g. Barangay Poblacion I"
                    class="uppercase-input mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2" required>
            </div>
        </div>

        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide pt-2 border-t dark:border-gray-700">Family Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Father's Full Name</label>
                <input type="text" name="father_name" value="{{ old('father_name') }}"
                    class="uppercase-input mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2">
            </div>
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Mother's Full Maiden Name</label>
                <input type="text" name="mother_maiden_name" value="{{ old('mother_maiden_name') }}"
                    class="uppercase-input mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2">
            </div>
        </div>

        <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide pt-2 border-t dark:border-gray-700">Educational Background</h2>

        <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300">School Enrolled</label>
            <select name="school_name" class="mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2" required>
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
                    <option value="{{ $school }}" {{ old('school_name') === $school ? 'selected' : '' }}>{{ $school }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Year</label>
                <select name="year_level" class="mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2" required>
                    <option value="">-- Select --</option>
                    <option value="1st Year" {{ old('year_level') === '1st Year' ? 'selected' : '' }}>1st Year</option>
                    <option value="2nd Year" {{ old('year_level') === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                    <option value="3rd Year" {{ old('year_level') === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                    <option value="4th Year" {{ old('year_level') === '4th Year' ? 'selected' : '' }}>4th Year</option>
                </select>
            </div>
            <div>
                <label class="block font-medium text-gray-700 dark:text-gray-300">Full Course</label>
                <input type="text" name="course" value="{{ old('course') }}"
                    placeholder="e.g. Bachelor of Science in Computer Science"
                    class="mt-1 w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded p-2" required>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 w-full">
            Submit Application
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

    @else

        {{-- Full profile view (same as before) --}}
        @php
        $uploadableRequirements = $applicant->requirements->filter(function ($req) {
            return !str_contains(strtolower($req->requirement->name), 'brown envelope');
        });
        $totalReqs = $uploadableRequirements->count();
        $submittedReqs = $uploadableRequirements->where('is_submitted', true)->count();
        $progressPercent = $totalReqs > 0 ? round(($submittedReqs / $totalReqs) * 100) : 0;
        
            $statusLabels = [
                'submitted' => ['label' => 'Application submitted', 'bg' => 'bg-amber-100 dark:bg-amber-900/40', 'text' => 'text-amber-700 dark:text-amber-300'],
                'pending_mswdo' => ['label' => 'Pending MSWDO assessment', 'bg' => 'bg-amber-100 dark:bg-amber-900/40', 'text' => 'text-amber-700 dark:text-amber-300'],
                'exam_scheduled' => ['label' => 'Exam scheduled', 'bg' => 'bg-blue-100 dark:bg-blue-900/40', 'text' => 'text-blue-700 dark:text-blue-300'],
                'exam_passed' => ['label' => 'Exam passed', 'bg' => 'bg-green-100 dark:bg-green-900/40', 'text' => 'text-green-700 dark:text-green-300'],
                'oriented' => ['label' => 'Orientation complete', 'bg' => 'bg-green-100 dark:bg-green-900/40', 'text' => 'text-green-700 dark:text-green-300'],
                'compliance_met' => ['label' => 'Compliance met', 'bg' => 'bg-green-100 dark:bg-green-900/40', 'text' => 'text-green-700 dark:text-green-300'],
                'paid_out' => ['label' => 'Scholarship released', 'bg' => 'bg-green-100 dark:bg-green-900/40', 'text' => 'text-green-700 dark:text-green-300'],
            ];
            $currentStatus = $statusLabels[$applicant->status] ?? ['label' => ucfirst(str_replace('_', ' ', $applicant->status)), 'bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-700 dark:text-gray-300'];

            $initials = strtoupper(substr($applicant->first_name, 0, 1) . substr($applicant->last_name, 0, 1));
        @endphp

        @if ($applicant->status === 'exam_passed')
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-5 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center shrink-0">
                        <i class="ti ti-check text-lg text-green-700 dark:text-green-300"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-green-800 dark:text-green-300">Congratulations! Your scholarship registration is approved.</p>
                        <p class="text-sm text-green-700 dark:text-green-400">You passed the qualifying exam. Please watch out for the orientation schedule.</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($applicant->orientation && $applicant->orientation->attended)
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center shrink-0">
                        <i class="ti ti-shield-check text-lg text-blue-700 dark:text-blue-300"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-blue-800 dark:text-blue-300">You are officially a Scholar of the Municipality of Santa Cruz.</p>
                        <p class="text-sm text-blue-700 dark:text-blue-400">Orientation attended and acknowledged. Keep track of your remaining requirements below.</p>
                    </div>
                </div>
            </div>
        @endif

        <div id="status" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 mb-5">
            <div class="flex justify-between items-start">
                <div class="flex gap-4">
                    <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-900/40 flex items-center justify-center text-xl font-medium text-blue-700 dark:text-blue-300">
                        {{ $initials }}
                    </div>
                    <div>
                        <p class="font-medium text-lg mb-0.5 text-gray-900 dark:text-gray-100">{{ $applicant->first_name }} {{ $applicant->last_name }}</p>
                        @if ($applicant->course || $applicant->year_level)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                                {{ $applicant->course }}{{ $applicant->course && $applicant->year_level ? ', ' : '' }}{{ $applicant->year_level }}
                            </p>
                        @endif
                        @if ($applicant->school_name)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $applicant->school_name }}</p>
                        @endif
                    </div>
                </div>
                <span class="{{ $currentStatus['bg'] }} {{ $currentStatus['text'] }} text-xs font-medium px-3 py-1.5 rounded-full">
                    {{ $currentStatus['label'] }}
                </span>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-700 mt-4 pt-3.5 grid grid-cols-1 md:grid-cols-3 gap-3.5 text-sm">
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Email</span><br>
                    <span class="text-gray-800 dark:text-gray-200">{{ $applicant->user->email ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Contact number</span><br>
                    <span class="text-gray-800 dark:text-gray-200">{{ $applicant->contact_number ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-gray-500 dark:text-gray-400">Address</span><br>
                    <span class="text-gray-800 dark:text-gray-200">
                        @php
                            $addressParts = array_filter([
                                $applicant->landmark,
                                $applicant->sitio,
                                $applicant->barangay,
                            ]);
                        @endphp
                        {{ $addressParts ? implode(', ', $addressParts) : 'N/A' }}
                    </span>
                </div>
            </div>

           
        </div>

        <div id="requirements" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
            <div class="flex justify-between items-center mb-1">
                <p class="font-medium text-sm text-gray-900 dark:text-gray-100">Requirements checklist</p>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $submittedReqs }} of {{ $totalReqs }} submitted</span>
            </div>
            <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full my-2.5 mb-4 overflow-hidden">
                <div class="h-full bg-blue-600" style="width: {{ $progressPercent }}%"></div>
            </div>

            @foreach ($applicant->requirements as $req)
            @php $isPersonalSubmission = str_contains(strtolower($req->requirement->name), 'brown envelope'); @endphp
            <div class="flex items-center justify-between py-3 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <i class="ti ti-file-text text-lg text-gray-500 dark:text-gray-400"></i>
                    <div>
                        <p class="text-sm mb-0 text-gray-900 dark:text-gray-100">{{ $req->requirement->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-0">
                            {{ $isPersonalSubmission ? 'To be submitted personally at the office' : 'PDF only, max 5MB' }}
                        </p>
                    </div>
                </div>

                @if ($isPersonalSubmission)
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-medium px-2.5 py-1 rounded-full">
                        Submit in person
                    </span>
                @elseif ($req->is_submitted)
                    <div class="flex items-center gap-2.5">
                        @if ($req->file_path)
                            <a href="{{ asset('storage/' . $req->file_path) }}" target="_blank"
                               class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View</a>
                        @endif

                        @if ($req->approval_status === 'approved')
                            <span class="bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 text-xs font-medium px-2.5 py-1 rounded-full">Approved</span>
                        @elseif ($req->approval_status === 'rejected')
                            <span class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 text-xs font-medium px-2.5 py-1 rounded-full">Not approved — please re-upload</span>
                        @else
                            <span class="bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-xs font-medium px-2.5 py-1 rounded-full">Pending review</span>
                        @endif

                        <form method="POST" action="{{ route('requirements.upload', $req) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" accept=".pdf" id="file-{{ $req->id }}" class="hidden" onchange="validateAndSubmit(this, {{ $req->id }})">
                            <label for="file-{{ $req->id }}" class="text-xs text-gray-500 dark:text-gray-400 hover:underline cursor-pointer">Replace</label>
                        </form>
                        <form method="POST" action="{{ route('requirements.destroy', $req) }}"
                              onsubmit="return confirm('Remove this uploaded file? You will need to upload it again.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>
                    </div>
                @else
                    <form method="POST" action="{{ route('requirements.upload', $req) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".pdf" id="file-{{ $req->id }}" class="hidden" onchange="validateAndSubmit(this, {{ $req->id }})">
                        <label for="file-{{ $req->id }}" class="bg-blue-600 text-white text-xs font-medium px-3.5 py-1.5 rounded-lg flex items-center gap-1.5 cursor-pointer">
                            <i class="ti ti-upload text-sm"></i> Upload
                        </label>
                    </form>
                @endif
                <p id="file-error-{{ $req->id }}" class="hidden text-xs text-red-600 dark:text-red-400 mt-1 w-full"></p>
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
                    errorEl.classList.remove('hidden');
                    input.value = '';
                    return;
                }

                errorEl.classList.add('hidden');
                input.form.submit();
            }
        </script>

    @endif

    @endsection