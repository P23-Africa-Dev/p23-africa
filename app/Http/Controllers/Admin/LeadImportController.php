<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadDrive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LeadImportController extends Controller
{
    /**
     * Show the import form with template illustration
     */
    public function showImport()
    {
        $drives = LeadDrive::orderBy('name')->get();
        return view('admin.crm.import', compact('drives'));
    }

    /**
     * Handle the file import
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:10240', // Max 10MB
            'drive_id' => 'nullable|exists:lead_drives,id',
            'new_drive_name' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        try {
            DB::beginTransaction();

            // Determine which drive to use
            $driveId = $request->drive_id;

            // If creating a new drive
            if ($request->filled('new_drive_name')) {
                $newDrive = LeadDrive::create([
                    'name' => $request->new_drive_name,
                    'slug' => Str::slug($request->new_drive_name),
                    'description' => 'Created during import',
                    'color' => $request->new_drive_color ?? '#6c757d',
                    'is_default' => false,
                ]);
                $driveId = $newDrive->id;
            }

            // If no drive selected and no new drive created, use default
            if (!$driveId) {
                $defaultDrive = LeadDrive::getDefault();
                $driveId = $defaultDrive ? $defaultDrive->id : null;
            }

            $data = $this->parseFile($file, $extension);

            if (empty($data)) {
                return back()->with('error', 'The file appears to be empty or could not be parsed.');
            }

            // Validate headers
            $headers = array_map('strtolower', array_map('trim', array_keys($data[0])));
            $requiredHeaders = ['full name', 'email'];

            foreach ($requiredHeaders as $required) {
                if (!in_array($required, $headers)) {
                    return back()->with('error', "Missing required column: '{$required}'. Please check your file format.");
                }
            }

            $imported = 0;
            $skipped = 0;
            $errors = [];
            $warnings = [];
            $emptyNames = 0;
            $emptyEmails = 0;
            $invalidEmails = 0;

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // Account for header row and 0-indexing

                // Normalize keys to lowercase
                $row = array_change_key_case($row, CASE_LOWER);

                // Skip completely empty rows (all fields empty)
                $hasAnyData = false;
                foreach ($row as $value) {
                    if (!empty(trim($value ?? ''))) {
                        $hasAnyData = true;
                        break;
                    }
                }
                if (!$hasAnyData) {
                    continue;
                }

                // Parse full name into first and last name
                $fullName = trim($row['full name'] ?? '');
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';

                // Track empty names (warning only, don't skip)
                if (empty($firstName)) {
                    $emptyNames++;
                    $firstName = 'Unknown';
                }

                // Get and validate email
                $email = trim($row['email'] ?? '');

                // Track empty emails (warning only, don't skip)
                if (empty($email)) {
                    $emptyEmails++;
                    $email = null; // Allow null email
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $invalidEmails++;
                    $warnings[] = "Row {$rowNumber}: Invalid email format '{$email}' : imported without email.";
                    $email = null; // Set to null if invalid format
                }

                // Check if lead with this email already exists (only if email is provided)
                if ($email) {
                    $existingLead = Lead::withTrashed()->where('email', $email)->first();

                    if ($existingLead) {
                        if ($existingLead->trashed()) {
                            // Restore the soft-deleted lead and update it with new data
                            $existingLead->restore();
                            $existingLead->update([
                                'drive_id' => $driveId,
                                'first_name' => $firstName,
                                'last_name' => $lastName,
                                'company' => trim($row['company'] ?? '') ?: $existingLead->company,
                                'employee_count' => trim($row['employee count'] ?? '') ?: $existingLead->employee_count,
                                'country' => trim($row['country'] ?? '') ?: $existingLead->country,
                                'company_linkedin_profile' => trim($row['company linkedin profile'] ?? '') ?: $existingLead->company_linkedin_profile,
                                'ceo_linkedin_profile' => trim($row['ceo linkedin profile'] ?? '') ?: $existingLead->ceo_linkedin_profile,
                                'status' => 'new',
                                'source' => 'noel_waitlist',
                            ]);
                            $imported++;
                            continue;
                        } else {
                            $errors[] = "Row {$rowNumber}: Lead with email '{$email}' already exists.";
                            $skipped++;
                            continue;
                        }
                    }
                }

                // Create the lead
                Lead::create([
                    'drive_id' => $driveId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'company' => trim($row['company'] ?? ''),
                    'employee_count' => trim($row['employee count'] ?? ''),
                    'country' => trim($row['country'] ?? ''),
                    'company_linkedin_profile' => trim($row['company linkedin profile'] ?? ''),
                    'ceo_linkedin_profile' => trim($row['ceo linkedin profile'] ?? ''),
                    'status' => 'new',
                    'source' => 'noel_waitlist',
                    'priority' => 'medium',
                ]);

                $imported++;
            }

            DB::commit();

            // Build warnings for empty fields
            if ($emptyNames > 0) {
                array_unshift($warnings, "⚠️ {$emptyNames} row(s) had empty 'Full Name' (imported as 'Unknown').");
            }
            if ($emptyEmails > 0) {
                array_unshift($warnings, "⚠️ {$emptyEmails} row(s) had empty 'Email' (imported without email).");
            }
            if ($invalidEmails > 0) {
                array_unshift($warnings, "⚠️ {$invalidEmails} row(s) had invalid email format (imported without email).");
            }

            // Combine warnings and errors for display
            $allIssues = array_merge($warnings, $errors);

            // Build appropriate message based on results
            if ($imported === 0 && $skipped === 0) {
                return back()->with('error', 'No leads were imported. The file appears to contain no valid data rows.');
            }

            if ($imported === 0 && $skipped > 0) {
                // All rows were skipped (due to duplicates)
                session()->flash('import_errors', array_slice($allIssues, 0, 10));
                if (count($allIssues) > 10) {
                    session()->flash('more_errors', count($allIssues) - 10);
                }
                return back()->with('error', "Import failed: All {$skipped} rows were skipped due to duplicate emails. Please check the issues below.");
            }

            // At least some leads were imported
            $message = "Successfully imported {$imported} lead" . ($imported > 1 ? 's' : '') . ".";
            if ($skipped > 0) {
                $message .= " {$skipped} row" . ($skipped > 1 ? 's were' : ' was') . " skipped (duplicates).";
            }

            if (!empty($allIssues)) {
                session()->flash('import_errors', array_slice($allIssues, 0, 10)); // Show first 10 issues
                if (count($allIssues) > 10) {
                    session()->flash('more_errors', count($allIssues) - 10);
                }
            }

            return redirect()->route('admin.crm.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lead import failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Failed to import leads. Please check your file format and try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Parse the uploaded file based on extension
     */
    private function parseFile($file, $extension): array
    {
        $data = [];

        if ($extension === 'csv') {
            $data = $this->parseCsv($file);
        } else {
            $data = $this->parseExcel($file);
        }

        return $data;
    }

    /**
     * Parse CSV file
     */
    private function parseCsv($file): array
    {
        $data = [];
        $headers = [];

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $rowNum = 0;
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                if ($rowNum === 0) {
                    // First row is headers
                    $headers = array_map('trim', $row);
                } else {
                    // Data row - combine with headers
                    if (count($row) === count($headers)) {
                        $data[] = array_combine($headers, $row);
                    }
                }
                $rowNum++;
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * Parse Excel file (xlsx, xls)
     */
    private function parseExcel($file): array
    {
        $data = [];

        $spreadsheet = IOFactory::load($file->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        if (empty($rows)) {
            return $data;
        }

        // First row is headers
        $headers = array_map('trim', $rows[0]);

        // Process data rows
        for ($i = 1; $i < count($rows); $i++) {
            if (count($rows[$i]) === count($headers)) {
                $data[] = array_combine($headers, $rows[$i]);
            }
        }

        return $data;
    }

    /**
     * Download sample template
     */
    public function downloadTemplate(Request $request)
    {
        $format = $request->get('format', 'csv');

        $headers = [
            'Full Name',
            'Company',
            'Employee Count',
            'Country',
            'Company LinkedIn Profile',
            'CEO LinkedIn Profile',
            'Email'
        ];

        $sampleData = [
            ['John Doe', 'Acme Corp', '50-100', 'Nigeria', 'https://linkedin.com/company/acme', 'https://linkedin.com/in/johndoe', 'john@acme.com'],
            ['Jane Smith', 'Tech Solutions', '10-50', 'Kenya', 'https://linkedin.com/company/techsol', 'https://linkedin.com/in/janesmith', 'jane@techsol.com'],
        ];

        if ($format === 'csv') {
            $filename = 'noel-leads-template.csv';
            $callback = function () use ($headers, $sampleData) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $headers);
                foreach ($sampleData as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        } else {
            // For Excel, create a simple xlsx
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Add headers
            $sheet->fromArray($headers, null, 'A1');

            // Add sample data
            $sheet->fromArray($sampleData, null, 'A2');

            // Style headers
            $headerStyle = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0']
                ]
            ];
            $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

            // Auto-size columns
            foreach (range('A', 'G') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $filename = 'noel-leads-template.xlsx';
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            $temp = tempnam(sys_get_temp_dir(), 'template');
            $writer->save($temp);

            return response()->download($temp, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        }
    }
}
