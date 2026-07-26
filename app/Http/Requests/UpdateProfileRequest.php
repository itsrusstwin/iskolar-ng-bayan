<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->applicant !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'contact_number' => 'required|string|max:20',
            'program_type' => 'required|in:new,renewal',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'landmark' => 'nullable|string|max:150',
            'sitio' => 'nullable|string|max:100',
            'barangay' => 'required|string|max:100',
            'father_name' => 'nullable|string|max:150',
            'mother_maiden_name' => 'nullable|string|max:150',
            'school_name' => 'required|in:'.implode(',', StoreApplicantRequest::schoolOptions()),
            'year_level' => 'required|string|max:20',
            'course' => 'required|string|max:150',
        ];
    }

    /**
     * Custom error messages for a few fields where the default phrasing is unclear.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'school_name.in' => 'Please select a valid school from the list.',
            'program_type.in' => 'Application type must be either New or Renewal.',
        ];
    }
}