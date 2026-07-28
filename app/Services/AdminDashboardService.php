<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\AuditLog;
use App\Models\Payout;
use Illuminate\Support\Collection;

class AdminDashboardService
{
    /** @var array<string, string> */
    public const STATUS_LABELS = [
        'submitted' => 'Application submitted',
        'incomplete' => 'Incomplete requirements',
        'pending_verification' => 'Pending verification',
        'pending_mswdo' => 'Pending MSWDO assessment',
        'exam_scheduled' => 'Exam scheduled',
        'exam_passed' => 'Exam passed',
        'exam_failed' => 'Exam failed',
        'oriented' => 'Orientation complete',
        'compliance_pending' => 'Compliance pending',
        'compliance_met' => 'Compliance met',
        'paid_out' => 'Scholarship released',
        'disqualified_policy' => 'Disqualified (policy)',
        'disqualified_poverty' => 'Disqualified (poverty)',
        'appealed' => 'Appeal submitted',
    ];

    /** @var array<string, string> */
    public const PROGRESS_LABELS = [
        'in_progress' => 'In Progress',
        'qualified' => 'Qualified',
        'released' => 'Scholarship Released',
        'disqualified' => 'Disqualified',
        'appealed' => 'Under Appeal',
    ];

    /** @var array<string, string> */
    public const PROGRAM_LABELS = [
        'new' => 'New Applicant',
        'renewal' => 'Renewal',
        'current' => 'Current Scholar',
        'aspiring' => 'Aspiring Scholar',
    ];

    public function getDashboardData(): array
    {
        $all = Applicant::latest()->get();

        return [
            'stats' => $this->getStats($all),
            'progressChart' => $this->getProgressChartData($all),
            'programChart' => $this->getProgramChartData($all),
            'recentApplicants' => $all->take(10),
            'recentActivity' => AuditLog::with(['user', 'applicant'])->latest()->take(8)->get(),
            'applicantsByStatus' => $all->groupBy('status'),
        ];
    }

    /** @return array<string, mixed> */
    public function getStats(Collection $applicants): array
    {
        $inProgress = $applicants->filter(fn (Applicant $a) => $this->categorizeProgress($a->status) === 'in_progress')->count();
        $qualified = $applicants->filter(fn (Applicant $a) => $this->categorizeProgress($a->status) === 'qualified')->count();
        $released = $applicants->where('status', 'paid_out')->count();
        $disqualified = $applicants->filter(fn (Applicant $a) => $this->categorizeProgress($a->status) === 'disqualified')->count();
        $thisMonth = $applicants->filter(fn (Applicant $a) => $a->created_at?->isCurrentMonth())->count();
        $totalPayout = Payout::sum('amount');

        return [
            'total' => $applicants->count(),
            'in_progress' => $inProgress,
            'qualified' => $qualified,
            'released' => $released,
            'disqualified' => $disqualified,
            'this_month' => $thisMonth,
            'total_payout' => $totalPayout,
        ];
    }

    /** @return array{labels: list<string>, values: list<int>, colors: list<string>} */
    public function getProgressChartData(Collection $applicants): array
    {
        $counts = [
            'in_progress' => 0,
            'qualified' => 0,
            'released' => 0,
            'disqualified' => 0,
            'appealed' => 0,
        ];

        foreach ($applicants as $applicant) {
            $category = $this->categorizeProgress($applicant->status);
            $counts[$category]++;
        }

        $colors = [
            'in_progress' => '#E8A33D',
            'qualified' => '#2c65ac',
            'released' => '#1E6B3C',
            'disqualified' => '#C0392B',
            'appealed' => '#6B7A93',
        ];

        $labels = [];
        $values = [];
        $chartColors = [];

        foreach ($counts as $key => $count) {
            if ($count === 0) {
                continue;
            }
            $labels[] = self::PROGRESS_LABELS[$key];
            $values[] = $count;
            $chartColors[] = $colors[$key];
        }

        if ($labels === []) {
            $labels = ['No applications yet'];
            $values = [1];
            $chartColors = ['#DCE4F0'];
        }

        return compact('labels', 'values', 'colors') + ['chartColors' => $chartColors];
    }

    /** @return array{labels: list<string>, values: list<int>} */
    public function getProgramChartData(Collection $applicants): array
    {
        $grouped = $applicants->groupBy('program_type')->map->count();

        $labels = [];
        $values = [];

        foreach ($grouped as $type => $count) {
            $labels[] = self::PROGRAM_LABELS[$type] ?? ucfirst(str_replace('_', ' ', (string) $type));
            $values[] = $count;
        }

        if ($labels === []) {
            $labels = ['No data'];
            $values = [0];
        }

        return compact('labels', 'values');
    }

    public function categorizeProgress(string $status): string
    {
        if (in_array($status, [
            'submitted', 'incomplete', 'pending_verification',
            'pending_mswdo', 'exam_scheduled', 'compliance_pending',
        ], true)) {
            return 'in_progress';
        }

        if (in_array($status, ['exam_passed', 'oriented', 'compliance_met'], true)) {
            return 'qualified';
        }

        if ($status === 'paid_out') {
            return 'released';
        }

        if ($status === 'appealed') {
            return 'appealed';
        }

        if (str_starts_with($status, 'disqualified') || $status === 'exam_failed') {
            return 'disqualified';
        }

        return 'in_progress';
    }

    public function statusBadgeClass(string $status): string
    {
        return match ($this->categorizeProgress($status)) {
            'qualified' => 'admin-badge-success',
            'released' => 'admin-badge-released',
            'disqualified' => 'admin-badge-danger',
            'appealed' => 'admin-badge-muted',
            default => 'admin-badge-warning',
        };
    }

    public function statusDisplayLabel(string $status): string
    {
        return self::STATUS_LABELS[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
