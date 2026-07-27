<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\AuditLog;
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
            'exam_file' => 'nullable|file|mimes:pdf|max:5120', // 5MB, PDF only
            'disqualification_reason' => 'nullable|string|max:1000',
        ], [
            'exam_file.mimes' => 'Only PDF files are allowed for the exam file.',
        ]);

        $filePath = null;
        if ($request->hasFile('exam_file')) {
            $filePath = $request->file('exam_file')->store('exam_results', 'public');
        }

        $examResult = $applicant->examResults()->create([
            'exam_id' => null,
            'score' => null,
            'passed' => $validated['passed'],
            'file_path' => $filePath,
            'posted_at' => now(),
        ]);

        $this->workflow->postExamResult(
            $applicant,
            (bool) $validated['passed'],
            $validated['disqualification_reason'] ?? null
        );

        AuditLog::record(
            'exam_result_posted',
            "Posted qualifying exam result for {$applicant->first_name} {$applicant->last_name} — resulting status: {$applicant->status}",
            $applicant,
            $examResult
        );

        return redirect()
            ->route('applicants.show', $applicant)
            ->with('success', 'Exam result posted.');
    }
}