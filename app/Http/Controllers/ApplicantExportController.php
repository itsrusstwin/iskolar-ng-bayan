<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Two-step applicant export: (1) pick applicants, (2) pick fields,
 * then download an Excel-compatible CSV.
 */
class ApplicantExportController extends Controller
{
    /**
     * Field key => human label shown to the admin in the picker.
     *
     * @var array<string, string>
     */
    private const FIELD_OPTIONS = [
        'first_name' => 'First Name',
        'middle_name' => 'Middle Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'contact_number' => 'Contact Number',
        'sex' => 'Sex',
        'date_of_birth' => 'Date of Birth',
        'school_name' => 'School',
        'course' => 'Course',
        'year_level' => 'Year Level',
        'program_type' => 'Program Type',
        'status' => 'Status',
        'barangay' => 'Barangay',
        'sitio' => 'Sitio',
        'landmark' => 'Landmark',
        'father_name' => "Father's Name",
        'mother_maiden_name' => "Mother's Maiden Name",
    ];

    public function select()
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $applicants = Applicant::with('user')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $service = app(AdminDashboardService::class);
        $dashboardStatusClasses = collect($applicants->pluck('status')->unique())
            ->mapWithKeys(fn ($status) => [$status => $service->statusBadgeClass($status)])
            ->all();
        $dashboardStatusLabels = AdminDashboardService::STATUS_LABELS;

        $schools = $applicants->pluck('school_name')->filter()->unique()->sort()->values();
        $courses = $applicants->pluck('course')->filter()->unique()->sort()->values();
        $yearLevels = $applicants->pluck('year_level')->filter()->unique()->sort()->values();

        return view('admin.export.select', compact(
            'applicants',
            'dashboardStatusClasses',
            'dashboardStatusLabels',
            'schools',
            'courses',
            'yearLevels',
        ));
    }

    /**
     * Fetch the given applicants keeping the order the admin clicked them,
     * so click order becomes the export order.
     */
    private function orderedApplicants(array $ids)
    {
        $found = Applicant::with('user')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $found->get($id))
            ->filter()
            ->values();
    }

    public function fields(Request $request)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $ids = $request->input('ids', []);
        $ids = array_values(array_filter((array) $ids));

        if (count($ids) === 0) {
            return redirect()
                ->route('admin.export.applicants')
                ->with('error', 'Please select at least one applicant.');
        }

        $applicants = $this->orderedApplicants($ids);

        $fieldOptions = self::FIELD_OPTIONS;

        // Pre-resolve every selectable field for each applicant so the live
        // preview shows exactly what the spreadsheet will contain.
        $service = app(AdminDashboardService::class);
        $preview = $applicants->map(function (Applicant $a) use ($service) {
            $row = [];
            foreach (self::FIELD_OPTIONS as $key => $label) {
                $row[$key] = $this->valueFor($a, $key, $service);
            }
            return $row;
        });

        return view('admin.export.fields', compact('ids', 'applicants', 'fieldOptions', 'preview'));
    }

    /**
     * Per-field column widths (in characters) for a tidy spreadsheet.
     *
     * @var array<string, int>
     */
    private const FIELD_WIDTHS = [
        'first_name' => 15,
        'middle_name' => 15,
        'last_name' => 15,
        'email' => 32,
        'contact_number' => 16,
        'sex' => 8,
        'date_of_birth' => 14,
        'school_name' => 28,
        'course' => 42,
        'year_level' => 12,
        'program_type' => 18,
        'status' => 24,
        'barangay' => 22,
        'sitio' => 18,
        'landmark' => 26,
        'father_name' => 24,
        'mother_maiden_name' => 26,
    ];

    public function download(Request $request)
    {
        abort_unless(Auth::user()?->role === 'admin', 403);

        $ids = array_values(array_filter((array) $request->input('ids', [])));
        $fields = array_values(array_filter((array) $request->input('fields', [])));

        if (count($ids) === 0 || count($fields) === 0) {
            return redirect()
                ->route('admin.export.applicants')
                ->with('error', 'Please select applicants and at least one field.');
        }

        // Keep only known fields, preserving the order the admin picked.
        $fields = array_values(array_intersect($fields, array_keys(self::FIELD_OPTIONS)));

        if (count($fields) === 0) {
            return redirect()
                ->route('admin.export.applicants')
                ->with('error', 'No valid fields selected.');
        }

        $applicants = $this->orderedApplicants($ids);

        $service = app(AdminDashboardService::class);

        $rows = [];
        foreach ($applicants as $applicant) {
            $row = [];
            foreach ($fields as $field) {
                $row[] = $this->valueFor($applicant, $field, $service);
            }
            $rows[] = $row;
        }

        $filename = 'applicants_' . now()->format('Y-m-d_His') . '.xls';

        $content = $this->buildSpreadsheetXml(
            title: 'ISKOLAR NG BAYAN APPLICANTS',
            subtitle: 'Generated ' . now()->format('F j, Y \a\t g:i A') . '  ·  ' . count($rows) . ' applicant(s)',
            headers: array_map(fn ($field) => self::FIELD_OPTIONS[$field], $fields),
            widths: array_map(fn ($field) => $this->charsToPoints(self::FIELD_WIDTHS[$field] ?? 18), $fields),
            rows: $rows,
        );

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => strlen($content),
        ]);
    }

    /**
     * Build a SpreadsheetML 2003 (.xls) document with a styled title,
     * navy header row, alternating rows, borders and proper column widths.
     *
     * @param array<int, string> $headers
     * @param array<int, int> $widths
     * @param array<int, array<int, string>> $rows
     */
    private function buildSpreadsheetXml(string $title, string $subtitle, array $headers, array $widths, array $rows): string
    {
        $colCount = count($headers);
        $mergeAcross = max(0, $colCount - 1);

        $e = fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

        $columns = '';
        foreach ($widths as $width) {
            $columns .= '        <Column ss:Width="' . $width . '"/>' . "\n";
        }

        $titleRow = '      <Row ss:Height="34">' . "\n"
            . '        <Cell ss:MergeAcross="' . $mergeAcross . '" ss:StyleID="Title"><Data ss:Type="String">' . $e($title) . '</Data></Cell>' . "\n"
            . '      </Row>' . "\n";

        $subtitleRow = '      <Row ss:Height="20">' . "\n"
            . '        <Cell ss:MergeAcross="' . $mergeAcross . '" ss:StyleID="Subtitle"><Data ss:Type="String">' . $e($subtitle) . '</Data></Cell>' . "\n"
            . '      </Row>' . "\n";

        $spacerRow = '      <Row ss:Height="10" ss:StyleID="Spacer"/>' . "\n";

        $headerCells = '';
        foreach ($headers as $header) {
            $headerCells .= '        <Cell ss:StyleID="Header"><Data ss:Type="String">' . $e(strtoupper($header)) . '</Data></Cell>' . "\n";
        }
        $headerRow = '      <Row ss:Height="26">' . "\n" . $headerCells . '      </Row>' . "\n";

        $dataRows = '';
        foreach ($rows as $i => $row) {
            $style = ($i % 2 === 0) ? 'Cell' : 'AltCell';
            $cells = '';
            foreach ($row as $value) {
                $cells .= '        <Cell ss:StyleID="' . $style . '"><Data ss:Type="String">' . $e($value) . '</Data></Cell>' . "\n";
            }
            $dataRows .= '      <Row ss:Height="20">' . "\n" . $cells . '      </Row>' . "\n";
        }

        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<?mso-application progid="Excel.Sheet"?>' . "\n"
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n"
            . ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n"
            . ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n"
            . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n"
            . ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n"
            . '  <Styles>' . "\n"
            . '    <Style ss:ID="Default" ss:Name="Normal">' . "\n"
            . '      <Alignment ss:Vertical="Center"/>' . "\n"
            . '      <Font ss:FontName="Calibri" ss:Size="11"/>' . "\n"
            . '    </Style>' . "\n"
            . '    <Style ss:ID="Title">' . "\n"
            . '      <Alignment ss:Vertical="Center"/>' . "\n"
            . '      <Font ss:FontName="Calibri" ss:Size="18" ss:Bold="1" ss:Color="#0A2647"/>' . "\n"
            . '    </Style>' . "\n"
            . '    <Style ss:ID="Subtitle">' . "\n"
            . '      <Font ss:FontName="Calibri" ss:Size="10" ss:Color="#6B7A90" ss:Italic="1"/>' . "\n"
            . '    </Style>' . "\n"
            . '    <Style ss:ID="Spacer">' . "\n"
            . '      <Font ss:FontName="Calibri" ss:Size="6"/>' . "\n"
            . '    </Style>' . "\n"
            . '    <Style ss:ID="Header">' . "\n"
            . '      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>' . "\n"
            . '      <Font ss:FontName="Calibri" ss:Size="11" ss:Bold="1" ss:Color="#FFFFFF"/>' . "\n"
            . '      <Interior ss:Color="#0A2647" ss:Pattern="Solid"/>' . "\n"
            . '      <Borders>' . "\n"
            . '        <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#0A2647"/>' . "\n"
            . '      </Borders>' . "\n"
            . '    </Style>' . "\n"
            . '    <Style ss:ID="Cell">' . "\n"
            . '      <Alignment ss:Vertical="Center"/>' . "\n"
            . '      <Borders>' . "\n"
            . '        <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E3E9F2"/>' . "\n"
            . '      </Borders>' . "\n"
            . '    </Style>' . "\n"
            . '    <Style ss:ID="AltCell">' . "\n"
            . '      <Alignment ss:Vertical="Center"/>' . "\n"
            . '      <Interior ss:Color="#F4F7FB" ss:Pattern="Solid"/>' . "\n"
            . '      <Borders>' . "\n"
            . '        <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E3E9F2"/>' . "\n"
            . '      </Borders>' . "\n"
            . '    </Style>' . "\n"
            . '  </Styles>' . "\n"
            . '  <Worksheet ss:Name="Applicants">' . "\n"
            . '    <Table ss:ExpandedColumnCount="' . $colCount . '" ss:ExpandedRowCount="' . (count($rows) + 4) . '">' . "\n"
            . $columns
            . $titleRow
            . $subtitleRow
            . $spacerRow
            . $headerRow
            . $dataRows
            . '    </Table>' . "\n"
            . '  </Worksheet>' . "\n"
            . '</Workbook>' . "\n";
    }

    /**
     * Resolve a single field value for an applicant.
     */
    private function valueFor(Applicant $applicant, string $field, AdminDashboardService $service): string
    {
        switch ($field) {
            case 'email':
                return $applicant->user?->email ?? '';
            case 'date_of_birth':
                return $applicant->date_of_birth?->format('Y-m-d') ?? '';
            case 'status':
                return $service->statusDisplayLabel($applicant->status);
            case 'program_type':
                return AdminDashboardService::PROGRAM_LABELS[$applicant->program_type] ?? ucfirst(str_replace('_', ' ', $applicant->program_type));
            default:
                return $applicant->{$field} ?? '';
        }
    }

    /**
     * Convert a column width in characters to points, matching how Excel
     * sizes columns: pixels = chars * 7 + 5, points = pixels * 0.75.
     */
    private function charsToPoints(int $chars): int
    {
        return (int) round(($chars * 7 + 5) * 0.75);
    }
}
