<?php
use App\Models\Applicant;

$id = Applicant::withTrashed()->value('id');

if (!$id) {
    echo "No applicants in the database to test with.\n";
    return;
}

echo "Testing with applicant id: {$id}\n";
$start = microtime(true);

$applicant = Applicant::withTrashed()
    ->with([
        'user' => fn ($q) => $q->withTrashed(),
        'requirements.requirement',
        'verification',
        'mswdoAssessment',
        'examResults.exam',
        'orientation',
        'wasteCompliance',
        'payouts',
        'disqualifications.appeals',
        'auditLogs.user' => fn ($q) => $q->withTrashed(),
    ])
    ->findOrFail($id);

$afterQuery = microtime(true);
echo "Query finished in " . round($afterQuery - $start, 3) . "s\n";

$dashboard = app(\App\Services\AdminDashboardService::class);
$html = view('admin.master-list._record', compact('applicant', 'dashboard'))->render();

$afterRender = microtime(true);
echo "View rendered in " . round($afterRender - $afterQuery, 3) . "s\n";
echo "Total: " . round($afterRender - $start, 3) . "s\n";
echo "Output length: " . strlen($html) . " bytes\n";
