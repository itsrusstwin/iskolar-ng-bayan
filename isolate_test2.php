<?php
use App\Models\Applicant;

$id = Applicant::withTrashed()->value('id');

if (!$id) {
    echo "No applicants in the database to test with.\n";
    return;
}

echo "Testing with applicant id: {$id}\n";

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

function describe($label, $value) {
    $type = is_object($value) ? get_class($value) : gettype($value);
    $preview = is_object($value) ? (method_exists($value, '__toString') ? (string) $value : '(object)') : var_export($value, true);
    echo str_pad($label, 45) . " => [{$type}] {$preview}\n";
}

echo "\n--- Field types on Applicant #{$id} ---\n";
describe('applicant->date_of_birth', $applicant->date_of_birth);
describe('applicant->exam_scheduled_at', $applicant->exam_scheduled_at);
describe('applicant->orientation_scheduled_at', $applicant->orientation_scheduled_at);
describe('applicant->deleted_at', $applicant->deleted_at);
describe('applicant->created_at', $applicant->created_at);
describe('user->created_at', $applicant->user?->created_at);
describe('user->terms_accepted_at', $applicant->user?->terms_accepted_at);

if ($applicant->verification) {
    describe('verification->created_at', $applicant->verification->created_at);
}
if ($applicant->mswdoAssessment) {
    describe('mswdoAssessment->assessed_at', $applicant->mswdoAssessment->assessed_at);
}
foreach ($applicant->examResults as $i => $r) {
    describe("examResults[$i]->posted_at", $r->posted_at);
}
if ($applicant->orientation) {
    describe('orientation->attended_at', $applicant->orientation->attended_at);
}
foreach ($applicant->requirements as $i => $r) {
    describe("requirements[$i]->submitted_at", $r->submitted_at);
}
foreach ($applicant->payouts as $i => $p) {
    describe("payouts[$i]->released_at", $p->released_at);
}
foreach ($applicant->disqualifications as $i => $d) {
    describe("disqualifications[$i]->notice_issued_at", $d->notice_issued_at);
    foreach ($d->appeals as $j => $a) {
        describe("disqualifications[$i]->appeals[$j]->filed_at", $a->filed_at);
    }
}
foreach ($applicant->auditLogs as $i => $l) {
    describe("auditLogs[$i]->created_at", $l->created_at);
}

echo "\n--- Now attempting to render the view ---\n";

try {
    $dashboard = app(\App\Services\AdminDashboardService::class);
    $html = view('admin.master-list._record', compact('applicant', 'dashboard'))->render();
    echo "SUCCESS — rendered " . strlen($html) . " bytes.\n";
} catch (\Throwable $e) {
    echo "\n=== EXCEPTION ===\n";
    $current = $e;
    $depth = 0;
    while ($current !== null) {
        echo str_repeat('  ', $depth) . get_class($current) . ": " . $current->getMessage() . "\n";
        echo str_repeat('  ', $depth) . "  at " . $current->getFile() . ":" . $current->getLine() . "\n";
        $current = $current->getPrevious();
        $depth++;
    }
}
