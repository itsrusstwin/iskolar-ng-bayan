<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\Disqualification;
use App\Notifications\ApplicationStatusChanged;

class ApplicationWorkflowService
{
    /**
     * Human-readable labels for each ApplicationStatus value, used in
     * notification emails. Keep this in sync with the $statusLabels
     * arrays used across the blade views.
     */
    protected function labelFor(string $status): string
    {
        return match ($status) {
            'submitted' => 'Application submitted',
            'pending_mswdo' => 'Pending MSWDO assessment',
            'exam_scheduled' => 'Exam scheduled',
            'exam_passed' => 'Exam passed',
            'oriented' => 'Orientation complete',
            'compliance_pending' => 'Waste compliance pending',
            'compliance_met' => 'Compliance met',
            'paid_out' => 'Scholarship released',
            'disqualified_policy_verification' => 'Disqualified — policy verification',
            'disqualified_mswdo' => 'Disqualified — MSWDO assessment',
            'disqualified_exam' => 'Disqualified — qualifying exam',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    protected function notify(Applicant $a, ?string $detail = null): void
    {
        $a->user?->notify(new ApplicationStatusChanged($this->labelFor($a->status), $detail));
    }

    public function submitRequirements(Applicant $a, array $submittedIds)
    {
        // Step 2-3 logic (optional, handled in controller currently)
    }

    /**
     * @param string|null $customReason Optional admin-provided reason, used only if this
     *                                  verification results in a disqualification.
     */
    public function verifyPolicy(Applicant $a, ?string $customReason = null): void
    {
        $v = $a->verification;
        if ($v->in_4ps || $v->in_spes || !$v->one_scholar_per_family_ok) {
            $this->disqualify($a, 'policy_verification', $customReason ?: 'Fails eligibility policy');
            return;
        }
        $a->update(['status' => 'pending_mswdo']);
        $this->notify($a);
    }

    /**
     * @param string|null $customReason Optional admin-provided reason, used only if the
     *                                  applicant is not qualified.
     */
    public function assessPoverty(Applicant $a, bool $qualified, ?string $customReason = null): void
    {
        if ($qualified) {
            $a->update(['status' => 'exam_scheduled']);
            $this->notify($a);
            return;
        }
        $this->disqualify($a, 'mswdo', $customReason ?: 'Did not meet poverty threshold');
    }

    /**
     * @param string|null $customReason Optional admin-provided reason, used only if the
     *                                  applicant failed the exam.
     */
    public function postExamResult(Applicant $a, bool $passed, ?string $customReason = null): void
    {
        if ($passed) {
            $a->update(['status' => 'exam_passed']);
            $this->notify($a);
            return;
        }
        $this->disqualify($a, 'exam', $customReason ?: 'Failed qualifying exam');
    }

    public function completeOrientation(Applicant $a)
    {
        $a->update(['status' => 'oriented']);
        $this->notify($a);
    }

    public function recordWasteCompliance(Applicant $a, float $kilos)
    {
        $a->update(['status' => $kilos >= 10 ? 'compliance_met' : 'compliance_pending']);
        $this->notify($a);
    }

    public function releasePayout(Applicant $a, float $amount)
    {
        $a->update(['status' => 'paid_out']);
        $this->notify($a, 'Amount released: ' . number_format($amount, 2));
    }

    protected function disqualify(Applicant $a, string $stage, string $reason)
    {
        $a->disqualifications()->create([
            'stage' => $stage,
            'reason' => $reason,
            'notice_issued_at' => now(),
        ]);
        $a->update(['status' => "disqualified_{$stage}"]);
        $this->notify($a, $reason . '. You may file an appeal from your dashboard if you believe this is a mistake.');
    }

    /**
     * Called when an admin approves an appeal: puts the applicant back into
     * the stage they would have reached had they not been disqualified.
     */
    public function reinstateFromAppeal(Disqualification $dq): void
    {
        $applicant = $dq->applicant;

        $nextStatus = match ($dq->stage) {
            'policy_verification' => 'pending_mswdo',
            'mswdo' => 'exam_scheduled',
            'exam' => 'exam_scheduled', // give them another shot at the exam
            default => 'submitted',
        };

        $applicant->update(['status' => $nextStatus]);
        $this->notify($applicant, 'Your appeal was approved and your application has been reinstated.');
    }
}