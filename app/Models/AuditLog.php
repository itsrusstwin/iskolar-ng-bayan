<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * A single accountability record: who (user_id) did what (action/description)
 * to which applicant/record (applicant_id, subject), and when (created_at).
 *
 * Use AuditLog::record(...) from a controller right after an admin decision
 * is saved — see RequirementReviewController, PolicyVerificationController,
 * MswdoAssessmentController, ExamResultController, OrientationController,
 * WasteComplianceController, PayoutController, AppealController, and
 * StudentAccountController for examples.
 */
class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'applicant_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Record one audit entry, attributed to the currently authenticated user.
     *
     * @param string $action A short machine-readable code, e.g. 'exam_result_posted'.
     * @param string $description A human-readable sentence describing what happened.
     * @param Applicant|null $applicant The applicant this action relates to, if any.
     * @param Model|null $subject The specific record affected (e.g. an ExamResult), if any.
     */
    public static function record(string $action, string $description, ?Applicant $applicant = null, ?Model $subject = null): self
    {
        return static::create([
            'user_id' => Auth::id(),
            'applicant_id' => $applicant?->id,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->id,
        ]);
    }
}