<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile - Iskolar ng Bayan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-2xl mx-auto px-4">

        <div class="flex justify-center items-center gap-3 mb-6">
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/stc-logo.jpg') }}" alt="Santa Cruz Logo" class="h-12">
    </a>
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/iskolar-logo.jpg') }}" alt="Iskolar ng Bayan Logo" class="h-12 bg-white rounded-md px-1">
    </a>
    <a href="{{ route('home') }}" class="inline-block">
        <img src="{{ asset('images/lydo-logo.jpg') }}" alt="LYDO Logo" class="h-12 w-12 rounded-full object-cover">
    </a>
</div>

        <div class="bg-white rounded-xl shadow p-8">
            <h1 class="text-2xl font-bold text-blue-700 mb-2">Complete Your Profile</h1>
            <p class="text-sm text-gray-500 mb-6">Just a few more details to finish your scholarship application.</p>

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('applicants.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block font-medium text-gray-700">School ID</label>
                    <input type="text" name="school_id" value="{{ old('school_id') }}" class="mt-1 w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Grade Average (must be 75% or higher)</label>
                    <input type="number" step="0.01" name="grade_average" value="{{ old('grade_average') }}" class="mt-1 w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block font-medium text-gray-700">Applicant Type</label>
                    <select name="program_type" class="mt-1 w-full border rounded p-2" required>
                        <option value="">-- Select --</option>
                        <option value="current" {{ old('program_type') === 'current' ? 'selected' : '' }}>Current Scholar</option>
                        <option value="aspiring" {{ old('program_type') === 'aspiring' ? 'selected' : '' }}>Aspiring Scholar</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-gray-700 mb-2">Requirements Submitted</label>
                    @foreach ($requirements as $requirement)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="requirement_{{ $requirement->id }}" id="requirement_{{ $requirement->id }}" class="mr-2">
                            <label for="requirement_{{ $requirement->id }}">{{ $requirement->name }}</label>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 w-full">
                    Submit Application
                </button>
            </form>
        </div>
    </div>
</body>
</html>