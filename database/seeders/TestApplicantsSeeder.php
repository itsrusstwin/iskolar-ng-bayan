<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\Appeal;
use App\Models\ApplicantRequirement;
use App\Models\AuditLog;
use App\Models\Disqualification;
use App\Models\ExamResult;
use App\Models\MswdoAssessment;
use App\Models\Orientation;
use App\Models\Payout;
use App\Models\ProgramVerification;
use App\Models\Requirement;
use App\Models\SupportMessage;
use App\Models\User;
use App\Models\WasteCompliance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TestApplicantsSeeder extends Seeder
{
/** Shared demo login password for every test student account. */
    protected const PASSWORD = 'Test1234!';

    /** Official barangay options in the Santa Cruz, Laguna application form. */
    protected const BARANGAYS = [
        'Brgy. Alipit', 'Brgy. Bagumbayan', 'Brgy. I (Poblacion)', 'Brgy. II (Poblacion)',
        'Brgy. III (Poblacion)', 'Brgy. IV (Poblacion)', 'Brgy. V (Poblacion)',
        'Brgy. Bubukal', 'Brgy. Calios', 'Brgy. Duhat', 'Brgy. Gatid',
        'Brgy. Jasaan', 'Brgy. Labuin', 'Brgy. Malinao', 'Brgy. Oogong',
        'Brgy. Pagsawitan', 'Brgy. Palasan', 'Brgy. Patimbao',
        'Brgy. San Jose', 'Brgy. San Juan', 'Brgy. San Pablo Norte',
        'Brgy. San Pablo Sur', 'Brgy. Santisima Cruz',
        'Brgy. Santo Angel Central', 'Brgy. Santo Angel Norte', 'Brgy. Santo Angel Sur',
    ];

    /** Official school options in the application form drop-down. */
    protected const SCHOOLS = [
        'ACTS COMPUTER COLLEGE', 'AMA COLLEGE', 'LAGUNA STATE POLYTECHNIC UNIVERSITY',
        'LAGUNA UNIVERSITY', 'STI COLLEGE', 'PHINMA UNION COLLEGE',
        'SOUTHBAY MONTESSORI SCHOOL', "PHILIPPINE WOMEN'S UNIVERSITY",
    ];

    /** Official course options per school (subset of the form's mapping). */
    protected const COURSES = [
        'ACTS COMPUTER COLLEGE' => [
            'BACHELOR OF SCIENCE IN COMPUTER SCIENCE (BSCS)',
            'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
            'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION (BSBA)',
            'BACHELOR OF SCIENCE IN OFFICE ADMINISTRATION (BSOA)',
        ],
        'AMA COLLEGE' => [
            'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
            'BACHELOR OF SCIENCE IN COMPUTER ENGINEERING (BSCPE)',
            'BACHELOR OF SCIENCE IN ACCOUNTANCY (BSA)',
            'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION (BSBA)',
        ],
        'LAGUNA STATE POLYTECHNIC UNIVERSITY' => [
            'BACHELOR OF SCIENCE IN NURSING (BSN)',
            'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
            'BACHELOR OF SECONDARY EDUCATION (BSED)',
            'BACHELOR OF SCIENCE IN CRIMINOLOGY (BSCRIM)',
        ],
        'LAGUNA UNIVERSITY' => [
            'BACHELOR OF SECONDARY EDUCATION MAJOR IN ENGLISH (BSED-ENGLISH)',
            'BACHELOR OF SCIENCE IN ACCOUNTANCY (BSA)',
            'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
            'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT (BSTM)',
        ],
        'STI COLLEGE' => [
            'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)',
            'BACHELOR OF SCIENCE IN COMPUTER SCIENCE (BSCS)',
            'BACHELOR OF SCIENCE IN MANAGEMENT ACCOUNTING (BSMA)',
            'BACHELOR OF MULTIMEDIA ARTS (BMMA)',
        ],
        'PHINMA UNION COLLEGE' => [
            'BACHELOR OF SCIENCE IN CRIMINOLOGY (BSCRIM)',
            'BACHELOR OF SCIENCE IN ACCOUNTANCY (BSA)',
            'BACHELOR OF SECONDARY EDUCATION MAJOR IN ENGLISH (BSED-ENGLISH)',
            'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT (BSHM)',
        ],
        'SOUTHBAY MONTESSORI SCHOOL' => [
            'BACHELOR OF SCIENCE IN ACCOUNTANCY',
            'BACHELOR OF SCIENCE IN PSYCHOLOGY',
            'BACHELOR OF SCIENCE IN SOCIAL WORK',
        ],
        "PHILIPPINE WOMEN'S UNIVERSITY" => [
            'BACHELOR OF SCIENCE IN OFFICE ADMINISTRATION (BSOA)',
            'BACHELOR OF ELEMENTARY EDUCATION (BEED)',
            'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT (BSHM)',
            'BACHELOR OF SCIENCE IN TOURISM MANAGEMENT (BSTM)',
        ],
    ];

    public function run(): void
    {
        if (User::query()->whereLike('email', 'demo.%@iskolar.test')->exists()) {
            $this->command?->warn('Test applicants already seeded â€” skipping.');

            return;
        }

        $requirements = Requirement::pluck('id')->values()->all();
        $admin = User::query()->where('role', 'admin')->first();

        // name, email, program, status, stage-related records
        $cases = [
            [
                'first_name' => 'Ana', 'last_name' => 'Santos', 'program' => 'new',
                'status' => 'submitted', 'terms' => true, 'online' => true,
                'steps' => ['audit' => 'application_submitted|Application submitted for scholarship.'],
            ],
            [
                'first_name' => 'Bella', 'last_name' => 'Reyes', 'program' => 'new',
                'status' => 'incomplete', 'terms' => true,
                'reqs' => 'partial',
                'steps' => ['audit' => 'requirement_submitted|Submitted 1 of 3 requirements â€” flagged incomplete.'],
            ],
            [
                'first_name' => 'Carla', 'last_name' => 'Mendoza', 'program' => 'new',
                'status' => 'pending_verification', 'terms' => true,
                'reqs' => 'all',
                'steps' => ['audit' => 'application_reviewed|All requirements submitted, awaiting policy verification.'],
            ],
            [
                'first_name' => 'Daniel', 'last_name' => 'Cruz', 'program' => 'new',
                'status' => 'pending_mswdo', 'terms' => true,
                'reqs' => 'all',
                'verification' => ['in_spes' => false, 'in_4ps' => false, 'one_ok' => true, 'disq' => false],
                'steps' => ['audit' => 'verification_passed|Policy verification passed â€” pending MSWDO assessment.'],
            ],
            [
                'first_name' => 'Elena', 'last_name' => 'Garcia', 'program' => 'new',
                'status' => 'exam_scheduled', 'terms' => true,
                'reqs' => 'all',
                'exam_at' => '+10 days',
                'verification' => ['in_spes' => false, 'in_4ps' => false, 'one_ok' => true, 'disq' => false],
                'mswdo' => ['qualified' => true, 'slip' => 'RS-2026-0417'],
                'steps' => ['audit' => 'exam_scheduled|Qualifying exam scheduled.'],
            ],
            [
                'first_name' => 'Francisco', 'last_name' => 'Ramos', 'program' => 'new',
                'status' => 'exam_passed', 'terms' => true,
                'reqs' => 'all',
                'verification' => ['in_spes' => false, 'in_4ps' => false, 'one_ok' => true, 'disq' => false],
                'mswdo' => ['qualified' => true, 'slip' => 'RS-2026-0432'],
                'exam' => ['passed' => true, 'score' => 88, 'days_ago' => 6],
                'steps' => ['audit' => 'exam_result_posted|Qualifying exam passed (score 88).'],
            ],
            [
                'first_name' => 'Grace', 'last_name' => 'Aquino', 'program' => 'new',
                'status' => 'oriented', 'terms' => true,
                'reqs' => 'all',
                'verification' => ['in_spes' => false, 'in_4ps' => false, 'one_ok' => true, 'disq' => false],
                'mswdo' => ['qualified' => true, 'slip' => 'RS-2026-0441'],
                'exam' => ['passed' => true, 'score' => 91, 'days_ago' => 14],
                'orientation' => ['attended' => true, 'signed' => true, 'days_ago' => 4],
                'steps' => ['audit' => 'orientation_completed|Orientation attended and acknowledgement signed.'],
            ],
            [
                'first_name' => 'Henry', 'last_name' => 'Villanueva', 'program' => 'new',
                'status' => 'compliance_pending', 'terms' => true,
                'reqs' => 'all',
                'verification' => ['in_spes' => false, 'in_4ps' => false, 'one_ok' => true, 'disq' => false],
                'mswdo' => ['qualified' => true, 'slip' => 'RS-2026-0455'],
                'exam' => ['passed' => true, 'score' => 84, 'days_ago' => 21],
                'orientation' => ['attended' => true, 'signed' => true, 'days_ago' => 10],
                'waste' => [['sem' => '1st Sem AY 2026-27', 'req' => 15, 'sub' => 9, 'ok' => false]],
                'steps' => ['audit' => 'compliance_reviewed|First semester compliance pending â€” 9/15 kg submitted.'],
            ],
            [
                'first_name' => 'Isabel', 'last_name' => 'Domingo', 'program' => 'renewal',
                'status' => 'paid_out', 'terms' => true, 'online' => true,
                'reqs' => 'all',
                'verification' => ['in_spes' => false, 'in_4ps' => false, 'one_ok' => true, 'disq' => false],
                'mswdo' => ['qualified' => true, 'slip' => 'RS-2026-0462'],
                'exam' => ['passed' => true, 'score' => 95, 'days_ago' => 30],
                'orientation' => ['attended' => true, 'signed' => true, 'days_ago' => 20],
                'waste' => [
                    ['sem' => '1st Sem AY 2025-26', 'req' => 15, 'sub' => 18, 'ok' => true],
                    ['sem' => '2nd Sem AY 2025-26', 'req' => 15, 'sub' => 16, 'ok' => true],
                ],
                'payouts' => [
                    ['amount' => 5000, 'ref' => 'ISO-2026-0001', 'days_ago' => 50],
                    ['amount' => 5000, 'ref' => 'ISO-2026-0002', 'days_ago' => 12],
                ],
                'support' => true,
                'steps' => [
                    'payout_recorded|Second semester stipend released â€” P5,000.00.',
                    'compliance_reviewed|Waste compliance validated for 2 semesters.',
                ],
            ],
            [
                'first_name' => 'Juan', 'last_name' => 'Fernandez', 'program' => 'new',
                'status' => 'disqualified_policy', 'terms' => true,
                'reqs' => 'all',
                'verification' => ['in_spes' => false, 'in_4ps' => true, 'one_ok' => false, 'disq' => true],
                'disqualify' => ['stage' => 'policy', 'reason' => 'Another household member is already a 4Ps beneficiary.', 'days_ago' => 5],
                'steps' => ['audit' => 'disqualification_issued|Disqualified during policy verification.'],
            ],
            [
                'first_name' => 'Karen', 'last_name' => 'Salazar', 'program' => 'new',
                'status' => 'disqualified_poverty', 'terms' => true,
                'reqs' => 'all',
                'verification' => ['in_spes' => false, 'in_4ps' => false, 'one_ok' => true, 'disq' => false],
                'mswdo' => ['qualified' => false, 'slip' => 'RS-2026-0470', 'days_ago' => 4],
                'disqualify' => ['stage' => 'mswdo', 'reason' => 'Family income exceeds the threshold for the scholarship program.', 'days_ago' => 2],
                'steps' => ['audit' => 'disqualification_issued|Disqualified after MSWDO assessment.'],
            ],
            [
                'first_name' => 'Leo', 'last_name' => 'Navarro', 'program' => 'new',
                'status' => 'appealed', 'terms' => true,
                'reqs' => 'all',
                'verification' => ['in_spes' => false, 'in_4ps' => false, 'one_ok' => false, 'disq' => true, 'remarks' => 'Duplicate scholar in the same household.'],
                'disqualify' => ['stage' => 'policy', 'reason' => 'Duplicate scholar in the same household.', 'days_ago' => 9, 'appeal' => ['notes' => 'The existing scholar graduated this term; requesting reconsideration.', 'days_ago' => 6]],
                'steps' => ['audit' => 'appeal_filed|Appeal for reconsideration filed.'],
            ],
            [
                'first_name' => 'Mia', 'last_name' => 'Morales', 'program' => 'new',
                'status' => 'exam_failed', 'terms' => true,
                'reqs' => 'all',
                'verification' => ['in_spes' => false, 'in_4ps' => false, 'one_ok' => true, 'disq' => false],
                'mswdo' => ['qualified' => true, 'slip' => 'RS-2026-0481'],
                'exam' => ['passed' => false, 'score' => 42, 'days_ago' => 3],
                'steps' => ['audit' => 'exam_result_posted|Qualifying exam failed (score 42).'],
            ],
[
                'first_name' => 'Noah', 'last_name' => 'Dela Cruz', 'program' => 'new',
                'status' => 'submitted', 'slug' => 'archived', 'terms' => true, 'archived' => true,
                'steps' => ['audit' => 'student_account_archived|Student account archived — retained for reference.'],
            ],
        ];

        foreach ($cases as $index => $case) {
            $this->createDemoApplicant($case, $requirements, $index);
        }

        // A short support thread on the released applicant so the Support inbox has content.
        $demo = User::query()->where('email', 'demo.paid_out@iskolar.test')->first();
        if ($demo && $admin && ! SupportMessage::where('user_id', $demo->id)->exists()) {
            SupportMessage::create(['user_id' => $demo->id, 'sender_id' => $demo->id, 'message' => 'Hi, when is the release for the second semester?', 'is_read' => true]);
            SupportMessage::create(['user_id' => $demo->id, 'sender_id' => $admin->id, 'message' => 'Hi Isabel! Releases are issued after the waste compliance validation. We\'ll notify you once approved.', 'is_read' => true]);
        }

        $this->command?->info('Test applicants created. Login password for all: ' . self::PASSWORD);
    }

    protected function createDemoApplicant(array $case, array $requirements, int $index): void
    {
$program = $case['program'];
        $slugSource = $case['slug'] ?? $case['status'] ?? 'submitted';
        $slug = substr(strtolower(str_replace(' ', '-', $slugSource)), 0, 32);
        $email = "demo.{$slug}@iskolar.test";
        if (User::query()->where('email', $email)->exists()) {
            return; // idempotent per stage
        }

$createdAt = Carbon::now()->subDays(15 - $index);
        $name = strtoupper($case['first_name'] . ' ' . $case['last_name']);

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => self::PASSWORD,
            'role' => 'applicant',
            'terms_accepted_at' => ! empty($case['terms']) ? $createdAt->copy()->subDay() : null,
            'last_seen_at' => ! empty($case['online']) ? Carbon::now()->subMinutes(2) : null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        $barangay = self::BARANGAYS[$index % count(self::BARANGAYS)];
        $school = self::SCHOOLS[$index % count(self::SCHOOLS)];
        $course = self::COURSES[$school][$index % count(self::COURSES[$school])];
        $yearLevel = (($index % 4) + 1);

        $applicant = Applicant::create([
            'user_id' => $user->id,
            'first_name' => strtoupper($case['first_name']),
            'middle_name' => strtoupper($case['middle_name'] ?? 'TEST'),
            'last_name' => strtoupper($case['last_name']),
            'program_type' => $program,
            'status' => $case['status'],
            'contact_number' => '0917 ' . str_pad((string) rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
            'date_of_birth' => Carbon::now()->subYears(rand(17, 22))->subMonths(rand(1, 11)),
            'sex' => random_int(0, 1) ? 'Female' : 'Male',
            'barangay' => $barangay,
            'sitio' => 'SITIO ' . chr(65 + ($index % 5)),
            'landmark' => 'NEAR BARANGAY HALL',
            'father_name' => strtoupper('JUAN ' . $case['last_name']),
            'mother_maiden_name' => strtoupper('MARIA SANTOS'),
            'course' => $course,
            'year_level' => ['1st Year', '2nd Year', '3rd Year', '4th Year'][$yearLevel - 1],
            'school_name' => $school,
            'province' => 'LAGUNA',
            'city_municipality' => 'SANTA CRUZ',
            'exam_scheduled_at' => ! empty($case['exam_at']) ? Carbon::now()->parse($case['exam_at']) : null,
            'orientation_scheduled_at' => ! empty($case['orientation_at']) ? Carbon::now()->parse($case['orientation_at']) : null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        // Requirements
        $reqState = $case['reqs'] ?? null;
        foreach ($requirements as $i => $reqId) {
            $submit = $reqState === 'all' || ($reqState === 'partial' && $i === 0);
            if ($submit) {
                ApplicantRequirement::create([
                    'applicant_id' => $applicant->id,
                    'requirement_id' => $reqId,
                    'is_submitted' => true,
                    'approval_status' => $case['status'] === 'incomplete' ? 'pending' : 'approved',
                    'submitted_at' => $createdAt->copy()->addDay(),
                ]);
            }
        }

        // Policy verification
        if (! empty($case['verification'])) {
            ProgramVerification::create([
                'applicant_id' => $applicant->id,
                'in_spes' => $case['verification']['in_spes'],
                'in_4ps' => $case['verification']['in_4ps'],
                'one_scholar_per_family_ok' => $case['verification']['one_ok'],
                'is_disqualified' => $case['verification']['disq'],
                'remarks' => $case['verification']['remarks'] ?? null,
            ]);
        }

        // MSWDO assessment
        if (! empty($case['mswdo'])) {
            MswdoAssessment::create([
                'applicant_id' => $applicant->id,
                'referral_slip_no' => $case['mswdo']['slip'],
                'is_qualified' => $case['mswdo']['qualified'],
                'assessed_at' => Carbon::now()->subDays($case['mswdo']['days_ago'] ?? 8),
            ]);
        }

        // Exam result
        if (! empty($case['exam'])) {
            ExamResult::create([
                'applicant_id' => $applicant->id,
                'exam_id' => null,
                'score' => $case['exam']['score'],
                'passed' => $case['exam']['passed'],
                'posted_at' => Carbon::now()->subDays($case['exam']['days_ago']),
            ]);
        }

        // Orientation
        if (! empty($case['orientation'])) {
            Orientation::create([
                'applicant_id' => $applicant->id,
                'attended' => $case['orientation']['attended'],
                'signed_acknowledgement' => $case['orientation']['signed'],
                'attended_at' => Carbon::now()->subDays($case['orientation']['days_ago']),
            ]);
        }

        // Waste compliance
        foreach ($case['waste'] ?? [] as $w) {
            WasteCompliance::create([
                'applicant_id' => $applicant->id,
                'semester' => $w['sem'],
                'kilos_required' => $w['req'],
                'kilos_submitted' => $w['sub'],
                'is_compliant' => $w['ok'],
            ]);
        }

        // Payouts
        foreach ($case['payouts'] ?? [] as $p) {
            Payout::create([
                'applicant_id' => $applicant->id,
                'amount' => $p['amount'],
                'reference_no' => $p['ref'],
                'released_at' => Carbon::now()->subDays($p['days_ago']),
            ]);
        }

        // Disqualification + appeal
        if (! empty($case['disqualify'])) {
            $dq = Disqualification::create([
                'applicant_id' => $applicant->id,
                'stage' => $case['disqualify']['stage'],
                'reason' => $case['disqualify']['reason'],
                'notice_issued_at' => Carbon::now()->subDays($case['disqualify']['days_ago']),
            ]);
            if (! empty($case['disqualify']['appeal'])) {
                Appeal::create([
                    'disqualification_id' => $dq->id,
                    'filed_at' => Carbon::now()->subDays($case['disqualify']['appeal']['days_ago']),
                    'reconsideration_notes' => $case['disqualify']['appeal']['notes'],
                    'result' => 'pending',
                ]);
            }
        }

        // Audit trail
        foreach ($case['steps'] as $step) {
            [$action, $description] = explode('|', $step, 2);
            AuditLog::record($action, $description, $applicant);
        }

        // Archive it (soft delete) so it demonstrates the Master List archive
        if (! empty($case['archived'])) {
            $applicant->delete();
            $user->delete();
        }
    }
}
