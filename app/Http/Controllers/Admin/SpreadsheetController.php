<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spreadsheet;
use App\Models\SpreadsheetPasscode;
use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet as PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SpreadsheetController extends Controller
{
    // ─── Helpers ──────────────────────────────────────────────────────

    protected function currentOrgId(): ?int
    {
        $user = auth()->user();
        if ($user && $user->hasRole('admin|supervisor') && $user->organization_id) {
            return (int) $user->organization_id;
        }
        return null;
    }

    protected function requireOrg(): int
    {
        $id = $this->currentOrgId();
        if (!$id) {
            abort(403, 'This feature is only available to organization users.');
        }
        return $id;
    }

    protected function isSupervisorOrAdmin(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->hasRole('supervisor'));
    }

    protected function isStaff(): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('staff');
    }

    // ═════════════════════════════════════════════════════════════════
    //  CRUD
    // ═════════════════════════════════════════════════════════════════

    /**
     * List all spreadsheets for the organisation.
     */
    public function index()
    {
        $orgId = $this->requireOrg();

        $spreadsheets = Spreadsheet::where('organization_id', $orgId)
            ->with(['creator', 'lastEditor'])
            ->orderByDesc('updated_at')
            ->get();

        $hasPasscode = SpreadsheetPasscode::where('organization_id', $orgId)->exists();

        return view('admin.spreadsheets.index', compact('spreadsheets', 'hasPasscode'));
    }

    /**
     * Show upload form.
     */
    public function create()
    {
        $this->requireOrg();

        if (!$this->isSupervisorOrAdmin()) {
            abort(403, 'Only supervisors and admins can upload spreadsheets.');
        }

        return view('admin.spreadsheets.create');
    }

    /**
     * Store a newly uploaded spreadsheet (from XLSX).
     */
    public function store(Request $request)
    {
        $orgId = $this->requireOrg();

        if (!$this->isSupervisorOrAdmin()) {
            abort(403, 'Only supervisors and admins can upload spreadsheets.');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file'        => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ]);

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();

            // Store original file
            $path = $file->store("spreadsheets/{$orgId}", 'local');

            // Parse to Luckysheet-compatible JSON
            $sheetData = $this->parseXlsxToLuckysheet($file->getRealPath());

            $spreadsheet = Spreadsheet::create([
                'organization_id'   => $orgId,
                'name'              => $request->input('name'),
                'description'       => $request->input('description'),
                'original_filename' => $originalName,
                'file_path'         => $path,
                'sheet_data'        => json_encode($sheetData),
                'created_by'        => Auth::id(),
                'last_edited_by'    => Auth::id(),
                'last_edited_at'    => now(),
            ]);

            return orgRedirect('admin.spreadsheets.show', ['spreadsheet' => $spreadsheet->id])
                ->with('success', 'Spreadsheet uploaded successfully!');
        } catch (\Exception $e) {
            Log::error('Spreadsheet upload error: ' . $e->getMessage());
            return back()->withErrors(['file' => 'Failed to process the spreadsheet. Please try again.'])->withInput();
        }
    }

    /**
     * Show / edit a spreadsheet (Luckysheet editor).
     */
    public function show(Spreadsheet $spreadsheet)
    {
        $orgId = $this->requireOrg();
        if ($spreadsheet->organization_id !== $orgId) {
            abort(403);
        }

        $user = Auth::user();
        $needsPasscode = $this->isStaff();
        $isSupervisor  = $this->isSupervisorOrAdmin();

        // For supervisors, also load passcode management data
        $passcodeRecord = null;
        $plainPasscode  = session('spreadsheet_passcode_plain'); // set after generate
        if ($isSupervisor) {
            $passcodeRecord = SpreadsheetPasscode::forOrg($orgId);
        }

        return view('admin.spreadsheets.editor', compact(
            'spreadsheet',
            'needsPasscode',
            'isSupervisor',
            'passcodeRecord',
            'plainPasscode'
        ));
    }

    /**
     * Return sheet JSON data via AJAX.
     */
    public function getData(Spreadsheet $spreadsheet)
    {
        $orgId = $this->requireOrg();
        if ($spreadsheet->organization_id !== $orgId) {
            return response()->json(['success' => false, 'error' => 'Unauthorized.'], 403);
        }

        try {
            // ── 1. Return stored sheet_data from DB if already parsed ─────
            if (!empty($spreadsheet->sheet_data)) {
                $decoded = json_decode($spreadsheet->sheet_data, true);
                if (json_last_error() === JSON_ERROR_NONE && !empty($decoded)) {
                    return response()->json(['success' => true, 'data' => $decoded]);
                }
            }

            // ── 2. Fall back: re-parse original file ──────────────────────
            if (empty($spreadsheet->file_path)) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No file path stored for this spreadsheet. Please re-upload the file.',
                ], 404);
            }

            $filePath = Storage::disk('local')->path($spreadsheet->file_path);

            // Try public_path fallback for legacy/seeded records
            if (!file_exists($filePath) && !empty($spreadsheet->original_filename)) {
                $publicFallback = public_path('documents/' . $spreadsheet->original_filename);
                if (file_exists($publicFallback)) {
                    $filePath = $publicFallback;
                }
            }

            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Spreadsheet file not found on this server. Please re-upload.',
                ], 404);
            }

            if (!class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class)) {
                return response()->json([
                    'success' => false,
                    'error'   => 'PhpSpreadsheet is not installed. Run: composer require phpoffice/phpspreadsheet',
                ], 500);
            }

            @ini_set('memory_limit', '512M');
            $data = $this->parseXlsxToLuckysheet($filePath);

            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'error'   => 'File was parsed but returned no sheet data.',
                ], 422);
            }

            // Persist so next load is instant
            $spreadsheet->update(['sheet_data' => json_encode($data)]);

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            Log::error('Spreadsheet getData error', [
                'spreadsheet_id' => $spreadsheet->id,
                'error'          => $e->getMessage(),
                'file'           => $e->getFile(),
                'line'           => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'error'   => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save edited sheet data (AJAX).
     */
    public function save(Request $request, Spreadsheet $spreadsheet)
    {
        $orgId = $this->requireOrg();
        if ($spreadsheet->organization_id !== $orgId) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        // Staff must verify passcode first (check session flag)
        if ($this->isStaff() && !session('spreadsheet_edit_verified_' . $spreadsheet->id)) {
            return response()->json(['success' => false, 'error' => 'Passcode required. Please enter the edit passcode before saving.'], 403);
        }

        $request->validate([
            'sheet_data' => 'required',
        ]);

        try {
            $spreadsheet->update([
                'sheet_data'     => is_string($request->input('sheet_data'))
                    ? $request->input('sheet_data')
                    : json_encode($request->input('sheet_data')),
                'last_edited_by' => Auth::id(),
                'last_edited_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Saved!']);
        } catch (\Exception $e) {
            Log::error('Spreadsheet save error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a spreadsheet (supervisor / admin only).
     */
    public function destroy(Spreadsheet $spreadsheet)
    {
        $orgId = $this->requireOrg();
        if ($spreadsheet->organization_id !== $orgId) {
            abort(403);
        }
        if (!$this->isSupervisorOrAdmin()) {
            abort(403, 'Only supervisors and admins can delete spreadsheets.');
        }

        // Delete stored file
        if ($spreadsheet->file_path && Storage::disk('local')->exists($spreadsheet->file_path)) {
            Storage::disk('local')->delete($spreadsheet->file_path);
        }

        $spreadsheet->delete();

        return orgRedirect('admin.spreadsheets.index')
            ->with('success', 'Spreadsheet deleted.');
    }

    /**
     * Export spreadsheet back to XLSX for download.
     */
    public function export(Spreadsheet $spreadsheet)
    {
        $orgId = $this->requireOrg();
        if ($spreadsheet->organization_id !== $orgId) {
            abort(403);
        }

        try {
            $data = json_decode($spreadsheet->sheet_data, true) ?? [];
            $xlsx = $this->luckysheetToXlsx($data);

            $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $spreadsheet->name) . '.xlsx';

            $temp = tempnam(sys_get_temp_dir(), 'ss_');
            $writer = new Xlsx($xlsx);
            $writer->save($temp);

            return response()->download($temp, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Spreadsheet export error: ' . $e->getMessage());
            return back()->with('error', 'Failed to export spreadsheet.');
        }
    }

    // ═════════════════════════════════════════════════════════════════
    //  PASSCODE
    // ═════════════════════════════════════════════════════════════════

    /**
     * Staff: verify passcode via AJAX.
     */
    public function verifyPasscode(Request $request, Spreadsheet $spreadsheet)
    {
        $orgId = $this->requireOrg();
        $request->validate(['passcode' => 'required|string']);

        $record = SpreadsheetPasscode::forOrg($orgId);
        if (!$record) {
            return response()->json(['error' => 'No passcode has been set. Ask a supervisor to create one.'], 422);
        }

        if ($record->verify($request->input('passcode'))) {
            session(['spreadsheet_edit_verified_' . $spreadsheet->id => true]);
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Invalid passcode.'], 422);
    }

    /**
     * Staff: request passcode from supervisors (sends notification).
     */
    public function requestPasscode(Request $request, Spreadsheet $spreadsheet)
    {
        $orgId = $this->requireOrg();
        $user  = Auth::user();

        // Notify all supervisors + admins in the org
        $supervisors = User::where('organization_id', $orgId)
            ->whereHas('roles', fn($q) => $q->whereIn('name', ['supervisor', 'admin']))
            ->get();

        $url = orgRoute('admin.spreadsheets.show', ['spreadsheet' => $spreadsheet->id]);

        foreach ($supervisors as $sup) {
            $sup->notify(new AdminNotification(
                'spreadsheet_passcode_request',
                'Passcode Requested',
                "{$user->name} is requesting the spreadsheet edit passcode for \"{$spreadsheet->name}\".",
                $url,
                'bi bi-key'
            ));
        }

        return response()->json(['success' => true, 'message' => 'Request sent to supervisors.']);
    }

    /**
     * Supervisor: create / regenerate passcode.
     */
    public function generatePasscode(Request $request)
    {
        $orgId = $this->requireOrg();
        if (!$this->isSupervisorOrAdmin()) {
            abort(403);
        }

        $result = SpreadsheetPasscode::generateForOrg($orgId, Auth::id());

        // Store plain passcode in session so it can be shown once
        session()->flash('spreadsheet_passcode_plain', $result['plain']);

        return response()->json([
            'success'  => true,
            'passcode' => $result['plain'],
            'message'  => 'Passcode generated successfully.',
        ]);
    }

    /**
     * Supervisor: view current passcode info (cannot see the hash; must regenerate to see plain).
     */
    public function passcodeInfo()
    {
        $orgId = $this->requireOrg();
        if (!$this->isSupervisorOrAdmin()) {
            abort(403);
        }

        $record = SpreadsheetPasscode::forOrg($orgId);

        return response()->json([
            'exists'        => (bool) $record,
            'created_by'    => $record?->createdByUser?->name ?? 'Unknown',
            'regenerated_at' => $record?->regenerated_at?->diffForHumans() ?? null,
        ]);
    }

    // ═════════════════════════════════════════════════════════════════
    //  XLSX ↔ Luckysheet Conversion
    // ═════════════════════════════════════════════════════════════════

    /**
     * Parse an XLSX file into Luckysheet-compatible JSON array.
     */
    protected function parseXlsxToLuckysheet(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(false);
        $workbook = $reader->load($filePath);

        $luckySheets = [];

        foreach ($workbook->getSheetNames() as $sheetIndex => $sheetName) {
            $sheet = $workbook->getSheet($sheetIndex);
            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn();
            $highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);

            $celldata = [];
            $colWidths = [];
            $rowHeights = [];

            // Merge cells
            $mergeCells = [];
            foreach ($sheet->getMergeCells() as $mergeRange) {
                $rangeParts = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::rangeBoundaries($mergeRange);
                $mergeCells[] = [
                    'r'  => $rangeParts[0][1] - 1,
                    'c'  => $rangeParts[0][0] - 1,
                    'rs' => $rangeParts[1][1] - $rangeParts[0][1] + 1,
                    'cs' => $rangeParts[1][0] - $rangeParts[0][0] + 1,
                ];
            }

            // Convert merge cells to config format
            $mergeConfig = [];
            foreach ($mergeCells as $mc) {
                $key = $mc['r'] . '_' . $mc['c'];
                $mergeConfig[$key] = $mc;
            }

            for ($row = 1; $row <= $highestRow; $row++) {
                // Row height
                $rh = $sheet->getRowDimension($row)->getRowHeight();
                if ($rh > 0) {
                    $rowHeights[$row - 1] = (int) $rh;
                }

                for ($col = 1; $col <= $highestColIndex; $col++) {
                    $cell = $sheet->getCellByColumnAndRow($col, $row);
                    $value = $cell->getValue();

                    if ($value === null && $cell->getStyle()->getFill()->getFillType() === 'none') {
                        continue;
                    }

                    $cellObj = [
                        'r' => $row - 1,
                        'c' => $col - 1,
                        'v' => $this->buildCellValue($cell, $sheet),
                    ];

                    $celldata[] = $cellObj;
                }
            }

            // Column widths
            for ($col = 1; $col <= $highestColIndex; $col++) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $cw = $sheet->getColumnDimension($colLetter)->getWidth();
                if ($cw > 0) {
                    $colWidths[$col - 1] = (int) ($cw * 7.5); // approx px
                }
            }

            $config = [];
            if (!empty($mergeConfig)) $config['merge'] = $mergeConfig;
            if (!empty($rowHeights))  $config['rowlen'] = $rowHeights;
            if (!empty($colWidths))   $config['columnlen'] = $colWidths;

            $luckySheets[] = [
                'name'      => $sheetName,
                'index'     => $sheetIndex,
                'order'     => $sheetIndex,
                'status'    => $sheetIndex === 0 ? 1 : 0,
                'celldata'  => $celldata,
                'config'    => $config,
                'row'       => $highestRow,
                'column'    => $highestColIndex,
            ];
        }

        return $luckySheets;
    }

    /**
     * Build a Luckysheet cell value object from a PhpSpreadsheet cell.
     */
    protected function buildCellValue($cell, $sheet): array
    {
        $value = $cell->getValue();
        $calculated = $cell->getCalculatedValue();
        $style = $cell->getStyle();

        $v = [];

        // Value
        if (is_object($value) && $value instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
            $v['v'] = $value->getPlainText();
            $v['m'] = $value->getPlainText();
        } else {
            $v['v'] = $calculated;
            $v['m'] = (string) $calculated;
        }

        // Formula
        if (is_string($value) && str_starts_with($value, '=')) {
            $v['f'] = $value;
        }

        // Font styling
        $font = $style->getFont();
        if ($font->getBold()) $v['bl'] = 1;
        if ($font->getItalic()) $v['it'] = 1;
        if ($font->getUnderline() !== 'none') $v['un'] = 1;

        $fontSize = $font->getSize();
        if ($fontSize && $fontSize != 11) $v['fs'] = $fontSize;

        $fontColor = $font->getColor()->getRGB();
        if ($fontColor && $fontColor !== '000000') $v['fc'] = '#' . $fontColor;

        $fontName = $font->getName();
        if ($fontName && $fontName !== 'Calibri') $v['ff'] = $fontName;

        // Background color
        $fill = $style->getFill();
        if ($fill->getFillType() !== 'none') {
            $bgColor = $fill->getStartColor()->getRGB();
            if ($bgColor && $bgColor !== 'FFFFFF' && $bgColor !== '000000') {
                $v['bg'] = '#' . $bgColor;
            }
        }

        // Alignment
        $alignment = $style->getAlignment();
        $hMap = ['left' => 0, 'center' => 1, 'right' => 2];
        $vMap = ['top' => 0, 'center' => 1, 'bottom' => 2];
        $h = $alignment->getHorizontal();
        $vAlign = $alignment->getVertical();
        if ($h && isset($hMap[$h])) $v['ht'] = $hMap[$h];
        if ($vAlign && isset($vMap[$vAlign])) $v['vt'] = $vMap[$vAlign];

        // Text wrap
        if ($alignment->getWrapText()) $v['tb'] = 2;

        return $v;
    }

    /**
     * Convert Luckysheet JSON back to PhpSpreadsheet object.
     */
    protected function luckysheetToXlsx(array $sheets): PhpSpreadsheet
    {
        $workbook = new PhpSpreadsheet();
        $workbook->removeSheetByIndex(0);

        foreach ($sheets as $sheetData) {
            $ws = $workbook->createSheet();
            $ws->setTitle($sheetData['name'] ?? 'Sheet');

            $celldata = $sheetData['celldata'] ?? [];
            $config   = $sheetData['config'] ?? [];

            foreach ($celldata as $cd) {
                $row = ($cd['r'] ?? 0) + 1;
                $col = ($cd['c'] ?? 0) + 1;
                $v   = $cd['v'] ?? [];

                if (is_scalar($v)) {
                    $ws->setCellValueByColumnAndRow($col, $row, $v);
                    continue;
                }

                // Formula
                if (!empty($v['f'])) {
                    $ws->setCellValueByColumnAndRow($col, $row, $v['f']);
                } else {
                    $ws->setCellValueByColumnAndRow($col, $row, $v['v'] ?? '');
                }

                // Styling
                $cellRef = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
                $style   = $ws->getStyle($cellRef);

                if (!empty($v['bl'])) $style->getFont()->setBold(true);
                if (!empty($v['it'])) $style->getFont()->setItalic(true);
                if (!empty($v['un'])) $style->getFont()->setUnderline(true);
                if (!empty($v['fs'])) $style->getFont()->setSize($v['fs']);
                if (!empty($v['fc'])) $style->getFont()->getColor()->setRGB(ltrim($v['fc'], '#'));
                if (!empty($v['ff'])) $style->getFont()->setName($v['ff']);
                if (!empty($v['bg'])) {
                    $style->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $style->getFill()->getStartColor()->setRGB(ltrim($v['bg'], '#'));
                }
            }

            // Merge cells
            if (!empty($config['merge'])) {
                foreach ($config['merge'] as $mc) {
                    $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($mc['c'] + 1);
                    $endCol   = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($mc['c'] + $mc['cs']);
                    $ws->mergeCells("{$startCol}" . ($mc['r'] + 1) . ":{$endCol}" . ($mc['r'] + $mc['rs']));
                }
            }

            // Column widths
            if (!empty($config['columnlen'])) {
                foreach ($config['columnlen'] as $ci => $w) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($ci + 1);
                    $ws->getColumnDimension($colLetter)->setWidth($w / 7.5);
                }
            }

            // Row heights
            if (!empty($config['rowlen'])) {
                foreach ($config['rowlen'] as $ri => $h) {
                    $ws->getRowDimension($ri + 1)->setRowHeight($h);
                }
            }
        }

        return $workbook;
    }
}
