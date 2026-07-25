<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Exam;
use App\Services\ApplicationWorkflowService;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    protected ApplicationWorkflowService $workflow;

    public function __construct(ApplicationWorkflowService $workflow)
    {
        $this->workflow = $workflow;
    }

    public function store(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'passed' => 'required|boolean',
        ]);

        $applicant->examResults()->create([
            'exam_id' => null,
            'score' => null,
            'passed' => $validated['passed'],
            'posted_at' => now(),
        ]);

        $this->workflow->postExamResult($applicant, (bool) $validated['passed']);

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'Exam result posted.');
    }
}