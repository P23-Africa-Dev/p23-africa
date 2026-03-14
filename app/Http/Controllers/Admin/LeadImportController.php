<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadDrive;
use App\Models\LeadStatus;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LeadImportController extends Controller
{
    /**
     * All mappable lead fields with human-friendly labels.
     */
    public static function getMappableFields(): array
    {
        return [
            // ── Identity ──────────────────────────────────────────
            'first_name'               => 'First Name',
            'last_name'                => 'Last Name',
            'full_name'                => 'Full Name (splits into First & Last)',
            'email'                    => 'Email Address',
            'phone'                    => 'Phone / Mobile',

            // ── Company ───────────────────────────────────────────
            'company'                  => 'Company / Organisation',
            'job_title'                => 'Job Title / Position',
            'employee_count'           => 'Employee Count / Company Size',
            'year_founded'             => 'Year Founded / Established',
            'industry'                 => 'Industry / Sector',
            'website'                  => 'Website / URL',
            'company_linkedin_profile' => 'Company LinkedIn Profile',
            'ceo_linkedin_profile'     => 'Contact LinkedIn Profile',

            // ── Location ──────────────────────────────────────────
            'country'                  => 'Country',
            'city'                     => 'City / Town',
            'address'                  => 'Street Address',

            // ── Lead Status & Classification ──────────────────────
            'status'                   => 'Lead Status',
            'source'                   => 'Lead Source',
            'priority'                 => 'Priority',

            // ── Deal / Value ──────────────────────────────────────
            'estimated_value'          => 'Estimated Value / Budget / Deal Size',
            'currency'                 => 'Currency Code (e.g. USD, GBP)',
            'interested_services'      => 'Interested Services (comma-separated)',

            // ── Dates & Follow-up ─────────────────────────────────
            'last_contacted_at'        => 'Last Contacted Date',
            'next_follow_up'           => 'Next Follow-up Date',

            // ── Notes & Context ───────────────────────────────────
            'requirements'             => 'Requirements / Needs',
            'notes'                    => 'Notes / Comments',
            'lost_reason'              => 'Lost Reason',
        ];
    }

    /**
     * Fuzzy auto-mapping: normalised column header => lead field.
     * Covers the widest reasonable set of aliases so users' files match automatically.
     */
    protected static function getAutoMappings(): array
    {
        return [
            // ── Identity / Name ──────────────────────────────────
            'first name'               => 'first_name',
            'first_name'               => 'first_name',
            'firstname'                => 'first_name',
            'given name'               => 'first_name',
            'given_name'               => 'first_name',
            'f name'                   => 'first_name',
            'forename'                 => 'first_name',

            'last name'                => 'last_name',
            'last_name'                => 'last_name',
            'lastname'                 => 'last_name',
            'surname'                  => 'last_name',
            'family name'              => 'last_name',
            'family_name'              => 'last_name',
            'l name'                   => 'last_name',

            'full name'                => 'full_name',
            'full_name'                => 'full_name',
            'fullname'                 => 'full_name',
            'name'                     => 'full_name',
            'contact name'             => 'full_name',
            'contact_name'             => 'full_name',
            'contact'                  => 'full_name',
            'person name'              => 'full_name',
            'person'                   => 'full_name',
            'lead name'                => 'full_name',
            'lead_name'                => 'full_name',
            'client name'              => 'full_name',
            'client_name'              => 'full_name',
            'customer name'            => 'full_name',
            'customer_name'            => 'full_name',
            'member name'              => 'full_name',
            'member_name'              => 'full_name',
            'participant name'         => 'full_name',
            'attendee name'            => 'full_name',
            'full name / company name' => 'full_name',
            'name / company'           => 'full_name',
            'founder name'             => 'full_name',
            'ceo name'                 => 'full_name',
            'owner name'               => 'full_name',
            'director name'            => 'full_name',
            'representative'           => 'full_name',

            // ── Email ────────────────────────────────────────────
            'email'                    => 'email',
            'email address'            => 'email',
            'email_address'            => 'email',
            'e-mail'                   => 'email',
            'e mail'                   => 'email',
            'mail'                     => 'email',
            'email id'                 => 'email',
            'email_id'                 => 'email',
            'contact email'            => 'email',
            'work email'               => 'email',
            'business email'           => 'email',
            'primary email'            => 'email',

            // ── Phone ────────────────────────────────────────────
            'phone'                    => 'phone',
            'phone number'             => 'phone',
            'phone_number'             => 'phone',
            'telephone'                => 'phone',
            'tel'                      => 'phone',
            'mobile'                   => 'phone',
            'mobile number'            => 'phone',
            'mobile_number'            => 'phone',
            'cell'                     => 'phone',
            'cell phone'               => 'phone',
            'cellphone'                => 'phone',
            'contact number'           => 'phone',
            'contact_number'           => 'phone',
            'work phone'               => 'phone',
            'business phone'           => 'phone',
            'primary phone'            => 'phone',
            'whatsapp'                 => 'phone',
            'whatsapp number'          => 'phone',

            // ── Company ──────────────────────────────────────────
            'company'                  => 'company',
            'company name'             => 'company',
            'company_name'             => 'company',
            'organisation'             => 'company',
            'organization'             => 'company',
            'organisation name'        => 'company',
            'organization name'        => 'company',
            'org'                      => 'company',
            'org name'                 => 'company',
            'business'                 => 'company',
            'business name'            => 'company',
            'business_name'            => 'company',
            'firm'                     => 'company',
            'firm name'                => 'company',
            'employer'                 => 'company',
            'startup name'             => 'company',
            'brand'                    => 'company',
            'brand name'               => 'company',
            'venture'                  => 'company',
            'venture name'             => 'company',
            'entity'                   => 'company',
            'entity name'              => 'company',

            // ── Job Title / Position ─────────────────────────────
            'job title'                => 'job_title',
            'job_title'                => 'job_title',
            'title'                    => 'job_title',
            'position'                 => 'job_title',
            'role'                     => 'job_title',
            'designation'              => 'job_title',
            'job role'                 => 'job_title',
            'job position'             => 'job_title',
            'work title'               => 'job_title',
            'occupation'               => 'job_title',
            'job'                      => 'job_title',
            'job title / position'     => 'job_title',

            // ── Employee Count / Company Size ────────────────────
            'employee count'           => 'employee_count',
            'employee_count'           => 'employee_count',
            'employees'                => 'employee_count',
            'number of employees'      => 'employee_count',
            'num employees'            => 'employee_count',
            'staff count'              => 'employee_count',
            'staff size'               => 'employee_count',
            'company size'             => 'employee_count',
            'company_size'             => 'employee_count',
            'size'                     => 'employee_count',
            'team size'                => 'employee_count',
            'headcount'                => 'employee_count',
            'head count'               => 'employee_count',
            'workforce'                => 'employee_count',
            'company stage'            => 'employee_count',

            // ── Year Founded ─────────────────────────────────────
            'year founded'             => 'year_founded',
            'year_founded'             => 'year_founded',
            'founded'                  => 'year_founded',
            'established'              => 'year_founded',
            'year established'         => 'year_founded',
            'year_established'         => 'year_founded',
            'founded year'             => 'year_founded',
            'founded_year'             => 'year_founded',
            'established year'         => 'year_founded',
            'date founded'             => 'year_founded',
            'date_founded'             => 'year_founded',
            'date established'         => 'year_founded',
            'inception'                => 'year_founded',
            'inception year'           => 'year_founded',
            'started'                  => 'year_founded',
            'started year'             => 'year_founded',
            'year started'             => 'year_founded',
            'member since'             => 'year_founded',
            'since'                    => 'year_founded',
            'registration date'        => 'year_founded',
            'registration year'        => 'year_founded',

            // ── Industry / Sector ────────────────────────────────
            'industry'                 => 'industry',
            'sector'                   => 'industry',
            'field'                    => 'industry',
            'business type'            => 'industry',
            'business_type'            => 'industry',
            'industry type'            => 'industry',
            'industry / sector'        => 'industry',
            'category'                 => 'industry',
            'business category'        => 'industry',
            'vertical'                 => 'industry',
            'market'                   => 'industry',
            'niche'                    => 'industry',
            'domain'                   => 'industry',
            'area of business'         => 'industry',

            // ── Website ──────────────────────────────────────────
            'website'                  => 'website',
            'web'                      => 'website',
            'url'                      => 'website',
            'site'                     => 'website',
            'company website'          => 'website',
            'company_website'          => 'website',
            'website url'              => 'website',
            'website_url'              => 'website',
            'web address'              => 'website',
            'homepage'                 => 'website',
            'home page'                => 'website',
            'web url'                  => 'website',
            'company url'              => 'website',
            'company_url'              => 'website',
            'link'                     => 'website',

            // ── LinkedIn ─────────────────────────────────────────
            'company linkedin profile' => 'company_linkedin_profile',
            'company_linkedin_profile' => 'company_linkedin_profile',
            'company linkedin'         => 'company_linkedin_profile',
            'company_linkedin'         => 'company_linkedin_profile',
            'linkedin company'         => 'company_linkedin_profile',
            'org linkedin'             => 'company_linkedin_profile',

            'ceo linkedin profile'     => 'ceo_linkedin_profile',
            'ceo_linkedin_profile'     => 'ceo_linkedin_profile',
            'ceo linkedin'             => 'ceo_linkedin_profile',
            'ceo_linkedin'             => 'ceo_linkedin_profile',
            'linkedin'                 => 'ceo_linkedin_profile',
            'linkedin profile'         => 'ceo_linkedin_profile',
            'linkedin url'             => 'ceo_linkedin_profile',
            'linkedin_url'             => 'ceo_linkedin_profile',
            'personal linkedin'        => 'ceo_linkedin_profile',
            'contact linkedin'         => 'ceo_linkedin_profile',
            'linkedin link'            => 'ceo_linkedin_profile',
            'linkedin page'            => 'ceo_linkedin_profile',

            // ── Location ─────────────────────────────────────────
            'country'                  => 'country',
            'location'                 => 'country',
            'region'                   => 'country',
            'nation'                   => 'country',
            'country / region'         => 'country',
            'base location'            => 'country',
            'base_location'            => 'country',
            'headquarters'             => 'country',
            'hq'                       => 'country',
            'hq location'              => 'country',
            'hq_location'              => 'country',
            'based in'                 => 'country',
            'country of operation'     => 'country',
            'operating country'        => 'country',

            'city'                     => 'city',
            'town'                     => 'city',
            'city / town'              => 'city',
            'city/town'                => 'city',
            'metro'                    => 'city',
            'municipality'             => 'city',
            'city name'                => 'city',

            'address'                  => 'address',
            'street'                   => 'address',
            'street address'           => 'address',
            'street_address'           => 'address',
            'full address'             => 'address',
            'full_address'             => 'address',
            'postal address'           => 'address',
            'mailing address'          => 'address',
            'office address'           => 'address',
            'physical address'         => 'address',

            // ── Lead Status & Classification ─────────────────────
            'status'                   => 'status',
            'lead status'              => 'status',
            'lead_status'              => 'status',
            'stage'                    => 'status',
            'deal stage'               => 'status',
            'pipeline stage'           => 'status',
            'pipeline_stage'           => 'status',
            'lead stage'               => 'status',
            'lead_stage'               => 'status',
            'funnel stage'             => 'status',
            'current status'           => 'status',
            'prospect status'          => 'status',
            'lifecycle stage'          => 'status',

            'source'                   => 'source',
            'lead source'              => 'source',
            'lead_source'              => 'source',
            'channel'                  => 'source',
            'acquisition channel'      => 'source',
            'how did you hear'         => 'source',
            'referral source'          => 'source',
            'origin'                   => 'source',
            'marketing source'         => 'source',
            'traffic source'           => 'source',
            'campaign source'          => 'source',

            'priority'                 => 'priority',
            'urgency'                  => 'priority',
            'importance'               => 'priority',
            'lead priority'            => 'priority',
            'lead_priority'            => 'priority',
            'rating'                   => 'priority',
            'lead rating'              => 'priority',
            'lead score'               => 'priority',
            'score'                    => 'priority',
            'hot/warm/cold'            => 'priority',

            // ── Deal / Value ─────────────────────────────────────
            'estimated value'          => 'estimated_value',
            'estimated_value'          => 'estimated_value',
            'value'                    => 'estimated_value',
            'deal value'               => 'estimated_value',
            'deal_value'               => 'estimated_value',
            'budget'                   => 'estimated_value',
            'revenue'                  => 'estimated_value',
            'amount'                   => 'estimated_value',
            'deal size'                => 'estimated_value',
            'deal_size'                => 'estimated_value',
            'contract value'           => 'estimated_value',
            'contract_value'           => 'estimated_value',
            'annual revenue'           => 'estimated_value',
            'annual_revenue'           => 'estimated_value',
            'arr'                      => 'estimated_value',
            'mrr'                      => 'estimated_value',
            'potential value'          => 'estimated_value',
            'opportunity value'        => 'estimated_value',
            'opportunity_value'        => 'estimated_value',
            'projected value'          => 'estimated_value',
            'expected revenue'         => 'estimated_value',
            'turnover'                 => 'estimated_value',
            'investment'               => 'estimated_value',
            'investment amount'        => 'estimated_value',
            'funding'                  => 'estimated_value',
            'funding amount'           => 'estimated_value',

            // ── Currency ─────────────────────────────────────────
            'currency'                 => 'currency',
            'currency code'            => 'currency',
            'currency_code'            => 'currency',
            'deal currency'            => 'currency',
            'payment currency'         => 'currency',

            // ── Interested Services ──────────────────────────────
            'interested services'      => 'interested_services',
            'interested_services'      => 'interested_services',
            'services'                 => 'interested_services',
            'service interest'         => 'interested_services',
            'service_interest'         => 'interested_services',
            'products'                 => 'interested_services',
            'product interest'         => 'interested_services',
            'product_interest'         => 'interested_services',
            'interests'                => 'interested_services',
            'areas of interest'        => 'interested_services',
            'areas_of_interest'        => 'interested_services',
            'interest areas'           => 'interested_services',
            'service needs'            => 'interested_services',
            'product needs'            => 'interested_services',
            'solutions'                => 'interested_services',
            'solution interest'        => 'interested_services',
            'what are you interested in' => 'interested_services',
            'interested in'            => 'interested_services',
            'offerings'                => 'interested_services',

            // ── Dates ────────────────────────────────────────────
            'last contacted'           => 'last_contacted_at',
            'last contacted at'        => 'last_contacted_at',
            'last_contacted_at'        => 'last_contacted_at',
            'last contact date'        => 'last_contacted_at',
            'last_contact_date'        => 'last_contacted_at',
            'date contacted'           => 'last_contacted_at',
            'contacted date'           => 'last_contacted_at',
            'last interaction'         => 'last_contacted_at',
            'last_interaction'         => 'last_contacted_at',
            'last touch'               => 'last_contacted_at',
            'last outreach'            => 'last_contacted_at',
            'last activity'            => 'last_contacted_at',
            'last_activity'            => 'last_contacted_at',

            'next follow up'           => 'next_follow_up',
            'next_follow_up'           => 'next_follow_up',
            'follow up date'           => 'next_follow_up',
            'follow_up_date'           => 'next_follow_up',
            'follow-up date'           => 'next_follow_up',
            'follow up'                => 'next_follow_up',
            'follow-up'                => 'next_follow_up',
            'next contact'             => 'next_follow_up',
            'next contact date'        => 'next_follow_up',
            'next_contact_date'        => 'next_follow_up',
            'reminder date'            => 'next_follow_up',
            'reminder'                 => 'next_follow_up',
            'callback date'            => 'next_follow_up',
            'scheduled date'           => 'next_follow_up',
            'next action date'         => 'next_follow_up',
            'next_action_date'         => 'next_follow_up',

            // ── Notes & Context ──────────────────────────────────
            'notes'                    => 'notes',
            'note'                     => 'notes',
            'comments'                 => 'notes',
            'comment'                  => 'notes',
            'description'              => 'notes',
            'remarks'                  => 'notes',
            'remark'                   => 'notes',
            'additional info'          => 'notes',
            'additional information'   => 'notes',
            'details'                  => 'notes',
            'info'                     => 'notes',
            'observation'              => 'notes',
            'observations'             => 'notes',
            'background'               => 'notes',
            'context'                  => 'notes',
            'summary'                  => 'notes',
            'bio'                      => 'notes',
            'about'                    => 'notes',

            'requirements'             => 'requirements',
            'requirement'              => 'requirements',
            'needs'                    => 'requirements',
            'pain points'              => 'requirements',
            'pain_points'              => 'requirements',
            'challenges'               => 'requirements',
            'goals'                    => 'requirements',
            'objectives'               => 'requirements',
            'use case'                 => 'requirements',
            'use_case'                 => 'requirements',
            'project requirements'     => 'requirements',
            'business needs'           => 'requirements',
            'specifications'           => 'requirements',

            // ── Lost Reason ──────────────────────────────────────
            'lost reason'              => 'lost_reason',
            'lost_reason'              => 'lost_reason',
            'reason lost'              => 'lost_reason',
            'loss reason'              => 'lost_reason',
            'loss_reason'              => 'lost_reason',
            'closed lost reason'       => 'lost_reason',
            'closed_lost_reason'       => 'lost_reason',
            'why lost'                 => 'lost_reason',
            'reason for loss'          => 'lost_reason',
            'rejection reason'         => 'lost_reason',
            'disqualification reason'  => 'lost_reason',
        ];
    }

    /**
     * Resolve current organization (org-account or super-admin).
     */
    protected function currentOrg(): ?Organization
    {
        $org = request()->route('organization');
        if ($org instanceof Organization) return $org;

        $user = Auth::user();
        if ($user && $user->organization_id) {
            return Organization::find($user->organization_id);
        }
        return null;
    }

    /**
     * Step 1 — Show the upload form.
     */
    public function showImport()
    {
        $org = $this->currentOrg();
        $drives = $org
            ? LeadDrive::where('organization_id', $org->id)->orderBy('name')->get()
            : LeadDrive::whereNull('organization_id')->orderBy('name')->get();

        return view('admin.crm.import', compact('drives'));
    }

    /**
     * Step 2 — Parse file, auto-map columns, return mapping UI.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls,txt|max:10240',
        ]);

        // --- keep drive selection in session so Step 3 has it ---
        $request->session()->put('import_drive_id', $request->input('drive_id'));
        $request->session()->put('import_new_drive_name', $request->input('new_drive_name'));
        $request->session()->put('import_new_drive_color', $request->input('new_drive_color'));

        $file = $request->file('file');

        // Store temp file in the 'local' disk (root = storage/app/private)
        $tempPath = $file->store('imports/temp', 'local');
        $fullPath = Storage::disk('local')->path($tempPath);

        try {
            $rows = $this->parseFile($fullPath, $file->getClientOriginalExtension());
        } catch (\Exception $e) {
            Storage::disk('local')->delete($tempPath);
            return back()->with('error', 'Could not read file: ' . $e->getMessage());
        }

        if (empty($rows) || count($rows) < 2) {
            Storage::disk('local')->delete($tempPath);
            return back()->with('error', 'The file is empty or has no data rows.');
        }

        // First row = headers
        $headers = array_map(fn($h) => trim((string) $h), $rows[0]);
        $dataRows = array_slice($rows, 1);

        // Auto-map headers
        $autoMappings = self::getAutoMappings();
        $mappableFields = self::getMappableFields();
        $suggestedMapping = [];

        foreach ($headers as $index => $header) {
            // Normalise: lowercase, collapse whitespace/underscores/hyphens into space, strip parenthetical hints
            $normalised = strtolower(trim(preg_replace('/[\s_\-]+/', ' ', $header)));
            // Strip parenthetical suffixes like "(e.g. Startup)" or "(YYYY-MM-DD)"
            $normalised = trim(preg_replace('/\s*\(.*?\)\s*$/', '', $normalised));

            // 1. Exact match
            if (isset($autoMappings[$normalised])) {
                $suggestedMapping[$index] = $autoMappings[$normalised];
                continue;
            }

            // 2. Partial / contains match — check if any alias is contained in the header
            $found = '';
            $bestLen = 0;
            foreach ($autoMappings as $alias => $field) {
                // Only match aliases that are ≥3 chars to avoid false positives
                if (strlen($alias) >= 3 && str_contains($normalised, $alias) && strlen($alias) > $bestLen) {
                    $found = $field;
                    $bestLen = strlen($alias);
                }
            }
            $suggestedMapping[$index] = $found;
        }

        // Preview first 5 data rows
        $previewRows = array_slice($dataRows, 0, 5);
        $totalRows = count($dataRows);

        // Store temp path in session
        $request->session()->put('import_temp_path', $tempPath);
        $request->session()->put('import_original_name', $file->getClientOriginalName());

        // Pass drive info to mapping view so it can be included as hidden fields
        $driveId = $request->input('drive_id', '');
        $newDriveName = $request->input('new_drive_name', '');
        $newDriveColor = $request->input('new_drive_color', '');

        return view('admin.crm.import-mapping', compact(
            'headers',
            'suggestedMapping',
            'mappableFields',
            'previewRows',
            'totalRows',
            'tempPath',
            'driveId',
            'newDriveName',
            'newDriveColor'
        ));
    }

    /**
     * Detect if a row looks like a header / description row rather than real data.
     * Returns true if the row should be skipped.
     */
    protected function isDescriptionRow(array $row, array $fieldToColumns): bool
    {
        // Gather only the values that are actually mapped to fields
        $mappedValues = [];
        foreach ($fieldToColumns as $field => $colIndices) {
            foreach ($colIndices as $colIndex) {
                $val = isset($row[$colIndex]) ? strtolower(trim((string) $row[$colIndex])) : '';
                if ($val !== '') {
                    $mappedValues[] = $val;
                }
            }
        }

        if (empty($mappedValues)) return true; // empty row

        // Common template / description patterns
        $descriptionPatterns = [
            '/^e\.?g\.?\b/i',           // starts with "e.g."
            '/\(e\.?g\.?\b/i',          // contains "(e.g."
            '/^enter\s/i',              // "Enter your..."
            '/^type\s/i',               // "Type the..."
            '/^your\s/i',               // "Your name"
            '/yyyy/i',                  // date format hints like "YYYY-MM-DD"
            '/mm[\/-]dd/i',             // "MM/DD" format hints
            '/full\s*name\s*[\/\|]/i',  // "Full Name / Company Name"
            '/\bsample\b/i',           // "sample"
            '/\bexample\b/i',          // "example"
            '/\bplaceholder\b/i',      // "placeholder"
        ];

        $descriptionHits = 0;
        foreach ($mappedValues as $val) {
            foreach ($descriptionPatterns as $pattern) {
                if (preg_match($pattern, $val)) {
                    $descriptionHits++;
                    break;
                }
            }
        }

        // If more than half of mapped values look like descriptions, skip the row
        return $descriptionHits > 0 && $descriptionHits >= (count($mappedValues) / 2);
    }

    /**
     * Parse a user-friendly message from a database exception.
     */
    protected function friendlyDbError(\Exception $e, array $leadData): string
    {
        $msg = $e->getMessage();

        // Data truncation errors (e.g. ENUM mismatch, wrong date format)
        if (str_contains($msg, 'Data truncated') || str_contains($msg, '1265')) {
            if (preg_match("/column '(\w+)'/", $msg, $m)) {
                $column = $m[1];
                $value = $leadData[$column] ?? 'unknown';
                $friendly = str_replace('_', ' ', $column);
                return "Invalid value \"{$value}\" for {$friendly}. Please check the format.";
            }
            return 'A value was in the wrong format and could not be saved.';
        }

        // Incorrect integer / numeric value
        if (str_contains($msg, 'Incorrect integer') || str_contains($msg, '1366')) {
            if (preg_match("/column '(\w+)'/", $msg, $m)) {
                $column = $m[1];
                $friendly = str_replace('_', ' ', $column);
                return "Non-numeric value provided for {$friendly}.";
            }
            return 'A numeric field received a non-numeric value.';
        }

        // Too long for column
        if (str_contains($msg, 'too long') || str_contains($msg, '1406')) {
            if (preg_match("/column '(\w+)'/", $msg, $m)) {
                $friendly = str_replace('_', ' ', $m[1]);
                return "Value too long for {$friendly}. Please shorten it.";
            }
            return 'A value was too long for its field.';
        }

        // Duplicate entry
        if (str_contains($msg, 'Duplicate entry') || str_contains($msg, '1062')) {
            // Composite unique (org_id + email) — extract email portion
            if (str_contains($msg, 'leads_org_email_unique') && preg_match("/Duplicate entry '(?:\d+-)?(.*?)' for key/", $msg, $m)) {
                return "A lead with email \"{$m[1]}\" already exists in your organisation.";
            }
            if (preg_match("/Duplicate entry '(.+?)' for key/", $msg, $m)) {
                return "Duplicate value \"{$m[1]}\" — this record already exists.";
            }
            return 'This record already exists (duplicate).';
        }

        // Cannot be null
        if (str_contains($msg, 'cannot be null') || str_contains($msg, '1048')) {
            if (preg_match("/Column '(\w+)'/", $msg, $m)) {
                $friendly = str_replace('_', ' ', $m[1]);
                return "The {$friendly} field is required and cannot be empty.";
            }
            return 'A required field was left empty.';
        }

        // Out of range
        if (str_contains($msg, 'Out of range') || str_contains($msg, '1264')) {
            if (preg_match("/column '(\w+)'/", $msg, $m)) {
                $friendly = str_replace('_', ' ', $m[1]);
                return "Value out of range for {$friendly}. Please use a smaller number.";
            }
            return 'A numeric value was out of the allowed range.';
        }

        // Generic SQLSTATE fallback — try to extract the column name at least
        if (str_contains($msg, 'SQLSTATE')) {
            if (preg_match("/column '(\w+)'/i", $msg, $m)) {
                $column  = $m[1];
                $value   = $leadData[$column] ?? null;
                $friendly = str_replace('_', ' ', $column);
                $hint = $value !== null ? " (value: \"{$value}\")" : '';
                return "Database error on {$friendly}{$hint}. Please check the value.";
            }
            return 'Database error — please check your data for invalid values. Check the log for details.';
        }

        return Str::limit($msg, 120);
    }

    /**
     * Extract the affected DB column name from a QueryException, if determinable.
     */
    protected function extractErrorField(\Exception $e): ?string
    {
        $msg = $e->getMessage();
        if (preg_match("/column '(\w+)'/i", $msg, $m)) {
            return $m[1]; // raw column name e.g. "year_founded"
        }
        return null;
    }

    /**
     * Step 3 — Process import using user-defined column mappings.
     */
    public function import(Request $request)
    {
        $request->validate([
            'column_map'   => 'required|array',
            'temp_path'    => 'required|string',
        ]);

        $org = $this->currentOrg();
        $orgId = $org ? $org->id : null;

        $tempPath = $request->input('temp_path');
        $fullPath = Storage::disk('local')->path($tempPath);

        if (!file_exists($fullPath)) {
            return redirect(orgRoute('admin.crm.import'))
                ->with('error', 'Upload session expired. Please upload the file again.');
        }

        // --- resolve drive ---
        $driveId = $this->resolveDrive($request, $orgId);

        // --- parse file again ---
        $extension = pathinfo($request->session()->get('import_original_name', $tempPath), PATHINFO_EXTENSION);
        try {
            $rows = $this->parseFile($fullPath, $extension);
        } catch (\Exception $e) {
            Storage::disk('local')->delete($tempPath);
            return redirect(orgRoute('admin.crm.import'))
                ->with('error', 'Error reading file: ' . $e->getMessage());
        }

        $headers = array_map(fn($h) => trim((string) $h), $rows[0]);
        $dataRows = array_slice($rows, 1);

        // Column mapping from user
        $columnMap = $request->input('column_map', []); // index => field_name

        // Invert: field_name => [column indices]
        $fieldToColumns = [];
        foreach ($columnMap as $colIndex => $fieldName) {
            if ($fieldName && $fieldName !== '' && $fieldName !== 'skip') {
                $fieldToColumns[$fieldName][] = (int) $colIndex;
            }
        }

        $imported = 0;
        $skipped = 0;
        $errorRows = 0;
        $descriptionRowsSkipped = 0;
        $errors = [];

        $defaultStatus = LeadStatus::getDefaultSlug($orgId);

        foreach ($dataRows as $rowIndex => $row) {
            $rowNumber = $rowIndex + 2; // +1 for 0-index, +1 for header

            // Skip entirely empty rows
            $nonEmpty = array_filter($row, fn($v) => $v !== null && trim((string) $v) !== '');
            if (empty($nonEmpty)) {
                continue;
            }

            // Skip header / description / template rows
            if ($this->isDescriptionRow($row, $fieldToColumns)) {
                $descriptionRowsSkipped++;
                continue;
            }

            try {
                $leadData = $this->buildLeadData($row, $fieldToColumns, $orgId, $driveId, $defaultStatus);

                // Duplicate check: same org + email (include soft-deleted — DB index still blocks them)
                if (!empty($leadData['email'])) {
                    $dupQuery = Lead::withTrashed()->where('email', $leadData['email']);
                    if ($orgId) {
                        $dupQuery->where('organization_id', $orgId);
                    } else {
                        $dupQuery->whereNull('organization_id');
                    }
                    $existingLead = $dupQuery->first();

                    if ($existingLead) {
                        if ($existingLead->trashed()) {
                            // Restore the soft-deleted lead and update with fresh data
                            $existingLead->restore();
                            $existingLead->update($leadData);
                            $imported++;
                            continue;
                        }
                        // Active duplicate — skip
                        $skipped++;
                        $errors[] = [
                            'row'     => $rowNumber,
                            'type'    => 'duplicate',
                            'message' => "Duplicate email \"{$leadData['email']}\" — skipped.",
                        ];
                        continue;
                    }
                }

                Lead::create($leadData);
                $imported++;
            } catch (\Illuminate\Database\QueryException $e) {
                $errorRows++;
                $leadData = $leadData ?? [];
                $errors[] = [
                    'row'            => $rowNumber,
                    'type'           => 'database',
                    'message'        => $this->friendlyDbError($e, $leadData),
                    'affected_field' => $this->extractErrorField($e),
                ];
            } catch (\Exception $e) {
                $errorRows++;
                $errors[] = [
                    'row'            => $rowNumber,
                    'type'           => 'general',
                    'message'        => Str::limit($e->getMessage(), 120),
                    'affected_field' => null,
                ];
            }
        }

        // Cleanup temp file
        Storage::disk('local')->delete($tempPath);
        $request->session()->forget([
            'import_temp_path',
            'import_original_name',
            'import_drive_id',
            'import_new_drive_name',
            'import_new_drive_color',
        ]);

        // Build success message
        $message = "{$imported} lead(s) imported successfully.";
        if ($skipped > 0) {
            $message .= " {$skipped} duplicate(s) skipped.";
        }
        if ($descriptionRowsSkipped > 0) {
            $message .= " {$descriptionRowsSkipped} description/template row(s) auto-skipped.";
        }

        // If there are error rows, send user to review page instead of index
        if (!empty($errors)) {
            // Store everything needed for the review page in session
            // We need to capture the raw row data for each error row so the user can edit it
            $reviewRows = [];
            foreach ($errors as $err) {
                $rowIndex = $err['row'] - 2; // reverse the +2 offset used during processing
                $rawRow = $dataRows[$rowIndex] ?? [];
                // Build current parsed values so the review form is pre-populated
                try {
                    $parsed = $this->buildLeadData($rawRow, $fieldToColumns, $orgId, $driveId, $defaultStatus);
                } catch (\Exception $e2) {
                    $parsed = [];
                }
                $reviewRows[] = [
                    'row_number'     => $err['row'],
                    'type'           => $err['type'],
                    'message'        => $err['message'],
                    'affected_field' => $err['affected_field'] ?? null,
                    'raw'            => $rawRow,
                    'parsed'         => $parsed,
                    'suggestion'     => $this->buildFixSuggestion($err, $parsed),
                ];
            }

            $request->session()->put('import_review_rows', $reviewRows);
            $request->session()->put('import_review_meta', [
                'imported'   => $imported,
                'skipped'    => $skipped,
                'error_count' => count($errors),
                'org_id'     => $orgId,
                'drive_id'   => $driveId,
                'fieldToColumns' => $fieldToColumns,
                'headers'    => $headers,
                'defaultStatus' => $defaultStatus,
            ]);

            return redirect(orgRoute('admin.crm.import.review'))
                ->with('success', $message . " {$errorRows} row(s) need your attention. Review them below.");
        }

        return redirect(orgRoute('admin.crm.index'))->with('success', $message);
    }

    /**
     * Build a human-readable fix suggestion for a given error + parsed data.
     */
    protected function buildFixSuggestion(array $err, array $parsed): string
    {
        $type  = $err['type']           ?? 'general';
        $msg   = $err['message']        ?? '';
        $field = $err['affected_field'] ?? null;

        if ($type === 'duplicate') {
            $email = $parsed['email'] ?? '';
            return "The email \"{$email}\" already exists in your organisation. Change the email address to a unique one, or skip this row.";
        }

        // Field-specific suggestions
        if ($field === 'year_founded' || str_contains($msg, 'year founded')) {
            $bad = $parsed['year_founded'] ?? '';
            return "\"" . htmlspecialchars((string)$bad) . "\" is not a valid year. Enter only a 4-digit year (e.g. 2018). The highlighted field is shown in red below.";
        }

        if ($field && str_contains($msg, 'too long')) {
            $label = ucwords(str_replace('_', ' ', $field));
            return "\"{$label}\" value is too long. Shorten it to fit the maximum allowed length. The field is highlighted below.";
        }

        if ($field && (str_contains($msg, 'Non-numeric') || str_contains($msg, 'Incorrect integer'))) {
            $label = ucwords(str_replace('_', ' ', $field));
            $bad   = $parsed[$field] ?? '';
            return "\"{$label}\" must be a plain number. Got \"{$bad}\" — remove currency symbols, commas and letters. The field is highlighted below.";
        }

        if ($field) {
            $label = ucwords(str_replace('_', ' ', $field));
            return "The \"{$label}\" field has an invalid value. Edit it in the highlighted field below and try again.";
        }

        return 'Edit the values below to correct this row, then click "Fix & Import".';
    }

    /**
     * Step 4 — Show the error-review page with editable failed rows.
     */
    public function showReview(Request $request)
    {
        $reviewRows = $request->session()->get('import_review_rows');
        $meta       = $request->session()->get('import_review_meta');

        if (empty($reviewRows) || empty($meta)) {
            return redirect(orgRoute('admin.crm.import'))
                ->with('error', 'No import errors to review. Please start a new import.');
        }

        $mappableFields = self::getMappableFields();

        return view('admin.crm.import-review', compact('reviewRows', 'meta', 'mappableFields'));
    }

    /**
     * Step 4 — Retry: accept per-row corrections and import only the fixed rows.
     */
    public function retry(Request $request)
    {
        $meta = $request->session()->get('import_review_meta');

        if (empty($meta)) {
            return redirect(orgRoute('admin.crm.import'))
                ->with('error', 'Review session expired. Please start a new import.');
        }

        $orgId         = $meta['org_id'];
        $driveId       = $meta['drive_id'];
        $defaultStatus = $meta['defaultStatus'];

        $rowActions  = $request->input('row_action', []);   // row_number => 'skip'|'import'
        $rowOverrides = $request->input('row_data', []);    // row_number => [field => value, ...]

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($rowActions as $rowNumber => $action) {
            if ($action === 'skip') {
                $skipped++;
                continue;
            }

            // Build lead data from the user-edited fields
            $fields = $rowOverrides[$rowNumber] ?? [];

            // Remove empty strings so defaults kick in
            $fields = array_filter($fields, fn($v) => trim((string) $v) !== '');

            // Apply type transformations identical to buildLeadData
            $leadData = [
                'organization_id' => $orgId,
                'drive_id'        => $driveId,
                'status'          => $defaultStatus,
                'source'          => 'other',
                'priority'        => 'medium',
            ];

            foreach ($fields as $field => $value) {
                $value = trim((string) $value);
                if ($value === '') continue;

                try {
                    switch ($field) {
                        case 'full_name':
                            $parts = explode(' ', $value, 2);
                            $leadData['first_name'] = $parts[0];
                            $leadData['last_name']  = $parts[1] ?? '';
                            break;
                        case 'year_founded':
                            if (preg_match('/\b((?:19|20)\d{2})\b/', $value, $m)) {
                                $leadData['year_founded'] = (int) $m[1];
                            }
                            break;
                        case 'currency':
                            $code = strtoupper(preg_replace('/[^a-zA-Z]/', '', $value));
                            if (strlen($code) === 3) $leadData['currency'] = $code;
                            break;
                        case 'estimated_value':
                            $numeric = preg_replace('/[^0-9.]/', '', $value);
                            if (is_numeric($numeric)) $leadData['estimated_value'] = (float) $numeric;
                            break;
                        case 'interested_services':
                            $services = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $value))));
                            if (!empty($services)) $leadData['interested_services'] = $services;
                            break;
                        case 'last_contacted_at':
                        case 'next_follow_up':
                            try {
                                $leadData[$field] = \Carbon\Carbon::parse($value)->toDateTimeString();
                            } catch (\Exception $ignored) {
                            }
                            break;
                        case 'status':
                            $statuses = array_keys(Lead::getStatuses($orgId));
                            $n = strtolower(trim(str_replace([' ', '-'], '_', $value)));
                            $leadData['status'] = in_array($n, $statuses) ? $n : $defaultStatus;
                            break;
                        case 'source':
                            $sources = array_keys(Lead::getSources($orgId));
                            $n = strtolower(str_replace([' ', '-'], '_', $value));
                            $leadData['source'] = in_array($n, $sources) ? $n : 'other';
                            break;
                        case 'priority':
                            $priorities = array_keys(Lead::getPriorities());
                            $n = strtolower(trim($value));
                            $leadData['priority'] = in_array($n, $priorities) ? $n : 'medium';
                            break;
                        default:
                            $leadData[$field] = $value;
                            break;
                    }
                } catch (\Exception $ignored) {
                }
            }

            if (empty($leadData['first_name'])) {
                $leadData['first_name'] = 'Unknown';
            }
            if (!empty($leadData['email']) && !filter_var($leadData['email'], FILTER_VALIDATE_EMAIL)) {
                unset($leadData['email']);
            }

            // Sanitise: keep only fields the Lead model actually accepts
            $fillable = (new Lead)->getFillable();
            $leadData = array_intersect_key($leadData, array_flip($fillable));

            // Ensure status / source / priority are safe ENUM values
            $safeStatuses   = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost', 'unqualified'];
            $safeSources    = ['website', 'referral', 'social_media', 'email_campaign', 'cold_call', 'event', 'advertisement', 'noel_waitlist', 'other'];
            $safePriorities = ['low', 'medium', 'high', 'urgent'];

            if (!empty($leadData['status']) && !in_array($leadData['status'], $safeStatuses)) {
                $leadData['status'] = 'new';
            }
            if (!empty($leadData['source']) && !in_array($leadData['source'], $safeSources)) {
                $leadData['source'] = 'other';
            }
            if (!empty($leadData['priority']) && !in_array($leadData['priority'], $safePriorities)) {
                $leadData['priority'] = 'medium';
            }

            // Duplicate check (include soft-deleted — DB index still blocks them)
            if (!empty($leadData['email'])) {
                $dupQuery = Lead::withTrashed()->where('email', $leadData['email']);
                $orgId ? $dupQuery->where('organization_id', $orgId) : $dupQuery->whereNull('organization_id');
                $existingLead = $dupQuery->first();

                if ($existingLead) {
                    if ($existingLead->trashed()) {
                        // Restore the soft-deleted lead and update with fresh data
                        $existingLead->restore();
                        $existingLead->update($leadData);
                        $imported++;
                        continue;
                    }
                    $skipped++;
                    $errors[] = "🔁 Row {$rowNumber}: Duplicate email \"{$leadData['email']}\" — skipped.";
                    continue;
                }
            }

            try {
                Lead::create($leadData);
                $imported++;
            } catch (\Illuminate\Database\QueryException $e) {
                $errors[] = "⚠️ Row {$rowNumber}: " . $this->friendlyDbError($e, $leadData);
            } catch (\Exception $e) {
                $errors[] = "❌ Row {$rowNumber}: " . Str::limit($e->getMessage(), 120);
            }
        }

        // Clear review session
        $request->session()->forget(['import_review_rows', 'import_review_meta']);

        $message = "{$imported} row(s) imported from review.";
        if ($skipped > 0) $message .= " {$skipped} row(s) skipped.";

        $redirect = redirect(orgRoute('admin.crm.index'))->with('success', $message);
        if (!empty($errors)) {
            $redirect->with('import_errors', array_slice($errors, 0, 25));
        }
        return $redirect;
    }

    /**
     * AJAX: Import or skip a single review-row.
     * POST  crm/import/retry-single
     * Body: { row_number, action: 'import'|'skip', row_data: { field: value, ... } }
     * Returns JSON { ok, message } or { ok: false, error }
     */
    public function retrySingle(Request $request)
    {
        $meta = $request->session()->get('import_review_meta');

        if (empty($meta)) {
            return response()->json(['ok' => false, 'error' => 'Review session expired. Start a new import.'], 422);
        }

        $rowNumber = $request->input('row_number');
        $action    = $request->input('action', 'import'); // 'import' or 'skip'
        $fields    = $request->input('row_data', []);

        // --- skip action --------------------------------------------------
        if ($action === 'skip') {
            $this->removeReviewRow($request, $rowNumber);
            return response()->json(['ok' => true, 'message' => "Row {$rowNumber} skipped."]);
        }

        // --- import action ------------------------------------------------
        $orgId         = $meta['org_id'];
        $driveId       = $meta['drive_id'];
        $defaultStatus = $meta['defaultStatus'];

        // Remove empty strings so defaults kick in
        $fields = array_filter($fields, fn($v) => trim((string) $v) !== '');

        $leadData = [
            'organization_id' => $orgId,
            'drive_id'        => $driveId,
            'status'          => $defaultStatus,
            'source'          => 'other',
            'priority'        => 'medium',
        ];

        foreach ($fields as $field => $value) {
            $value = trim((string) $value);
            if ($value === '') continue;

            try {
                switch ($field) {
                    case 'full_name':
                        $parts = explode(' ', $value, 2);
                        $leadData['first_name'] = $parts[0];
                        $leadData['last_name']  = $parts[1] ?? '';
                        break;
                    case 'year_founded':
                        if (preg_match('/\b((?:19|20)\d{2})\b/', $value, $m)) {
                            $leadData['year_founded'] = (int) $m[1];
                        }
                        break;
                    case 'currency':
                        $code = strtoupper(preg_replace('/[^a-zA-Z]/', '', $value));
                        if (strlen($code) === 3) $leadData['currency'] = $code;
                        break;
                    case 'estimated_value':
                        $numeric = preg_replace('/[^0-9.]/', '', $value);
                        if (is_numeric($numeric)) $leadData['estimated_value'] = (float) $numeric;
                        break;
                    case 'interested_services':
                        $services = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $value))));
                        if (!empty($services)) $leadData['interested_services'] = $services;
                        break;
                    case 'last_contacted_at':
                    case 'next_follow_up':
                        try {
                            $leadData[$field] = \Carbon\Carbon::parse($value)->toDateTimeString();
                        } catch (\Exception $ignored) {
                        }
                        break;
                    case 'status':
                        $statuses = array_keys(Lead::getStatuses($orgId));
                        $n = strtolower(trim(str_replace([' ', '-'], '_', $value)));
                        $leadData['status'] = in_array($n, $statuses) ? $n : $defaultStatus;
                        break;
                    case 'source':
                        $sources = array_keys(Lead::getSources($orgId));
                        $n = strtolower(str_replace([' ', '-'], '_', $value));
                        $leadData['source'] = in_array($n, $sources) ? $n : 'other';
                        break;
                    case 'priority':
                        $priorities = array_keys(Lead::getPriorities());
                        $n = strtolower(trim($value));
                        $leadData['priority'] = in_array($n, $priorities) ? $n : 'medium';
                        break;
                    default:
                        $leadData[$field] = $value;
                        break;
                }
            } catch (\Exception $ignored) {
            }
        }

        if (empty($leadData['first_name'])) {
            $leadData['first_name'] = 'Unknown';
        }
        if (!empty($leadData['email']) && !filter_var($leadData['email'], FILTER_VALIDATE_EMAIL)) {
            unset($leadData['email']);
        }

        // Sanitise: keep only fields the Lead model actually accepts
        $fillable = (new Lead)->getFillable();
        $leadData = array_intersect_key($leadData, array_flip($fillable));

        // Ensure status / source / priority are safe ENUM values
        $safeStatuses   = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost', 'unqualified'];
        $safeSources    = ['website', 'referral', 'social_media', 'email_campaign', 'cold_call', 'event', 'advertisement', 'noel_waitlist', 'other'];
        $safePriorities = ['low', 'medium', 'high', 'urgent'];

        if (!empty($leadData['status']) && !in_array($leadData['status'], $safeStatuses)) {
            $leadData['status'] = 'new';
        }
        if (!empty($leadData['source']) && !in_array($leadData['source'], $safeSources)) {
            $leadData['source'] = 'other';
        }
        if (!empty($leadData['priority']) && !in_array($leadData['priority'], $safePriorities)) {
            $leadData['priority'] = 'medium';
        }

        // Duplicate check (include soft-deleted — DB index still blocks them)
        if (!empty($leadData['email'])) {
            $dupQuery = Lead::withTrashed()->where('email', $leadData['email']);
            $orgId ? $dupQuery->where('organization_id', $orgId) : $dupQuery->whereNull('organization_id');
            $existingLead = $dupQuery->first();

            if ($existingLead) {
                if ($existingLead->trashed()) {
                    // Restore the soft-deleted lead and update with fresh data
                    $existingLead->restore();
                    $existingLead->update($leadData);

                    $this->removeReviewRow($request, $rowNumber);
                    return response()->json(['ok' => true, 'message' => "Row {$rowNumber} imported successfully (restored previously deleted lead)."]);
                }

                return response()->json([
                    'ok'    => false,
                    'error' => "Duplicate email \"{$leadData['email']}\" already exists in your organisation.",
                ], 422);
            }
        }

        try {
            Lead::create($leadData);
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\Log::error('retrySingle DB error', [
                'row'      => $rowNumber,
                'leadData' => $leadData,
                'sql'      => $e->getMessage(),
            ]);
            return response()->json([
                'ok'    => false,
                'error' => $this->friendlyDbError($e, $leadData),
            ], 422);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('retrySingle error', [
                'row' => $rowNumber,
                'msg' => $e->getMessage(),
            ]);
            return response()->json([
                'ok'    => false,
                'error' => \Illuminate\Support\Str::limit($e->getMessage(), 120),
            ], 422);
        }

        // Remove the row from session so page stays in sync
        $this->removeReviewRow($request, $rowNumber);

        return response()->json(['ok' => true, 'message' => "Row {$rowNumber} imported successfully!"]);
    }

    /**
     * AJAX: Skip all remaining review rows at once, clear session, return redirect URL.
     */
    public function skipAll(Request $request)
    {
        $meta = $request->session()->get('import_review_meta');
        $count = 0;

        if (!empty($meta)) {
            $rows = $request->session()->get('import_review_rows', []);
            $count = count($rows);
        }

        $request->session()->forget(['import_review_rows', 'import_review_meta']);

        return response()->json([
            'ok'       => true,
            'message'  => "{$count} row(s) skipped.",
            'redirect' => orgRoute('admin.crm.index'),
        ]);
    }

    /**
     * Remove a single row from the review session data.
     */
    protected function removeReviewRow(Request $request, $rowNumber): void
    {
        $rows = $request->session()->get('import_review_rows', []);
        $rows = array_values(array_filter($rows, fn($r) => ($r['row_number'] ?? null) != $rowNumber));
        $request->session()->put('import_review_rows', $rows);

        // Update meta count
        $meta = $request->session()->get('import_review_meta', []);
        $meta['error_count'] = count($rows);
        $request->session()->put('import_review_meta', $meta);
    }

    /**
     * Download a sample template.
     */
    public function downloadTemplate(Request $request)
    {
        $format = strtolower($request->query('format', 'xlsx'));

        $headers = [
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Company',
            'Job Title',
            'Employee Count',
            'Year Founded',
            'Industry',
            'Website',
            'Company LinkedIn Profile',
            'CEO LinkedIn Profile',
            'Country',
            'City',
            'Address',
            'Source',
            'Priority',
            'Estimated Value',
            'Notes',
        ];

        $sampleRows = [
            [
                'John',
                'Doe',
                'john@acme.com',
                '+2341234567',
                'Acme Corporation',
                'CEO',
                '50-100',
                '2015',
                'Technology',
                'https://acme.com',
                'https://linkedin.com/company/acme',
                'https://linkedin.com/in/johndoe',
                'Nigeria',
                'Lagos',
                '123 Main St',
                'website',
                'medium',
                '5000',
                'Interested in our services'
            ],
            [
                'Jane',
                'Smith',
                'jane@techsolutions.co.ke',
                '+2547654321',
                'Tech Solutions Ltd',
                'CTO',
                '10-50',
                '2020',
                'Finance',
                'https://techsolutions.co.ke',
                'https://linkedin.com/company/techsol',
                'https://linkedin.com/in/janesmith',
                'Kenya',
                'Nairobi',
                '456 Tech Ave',
                'referral',
                'high',
                '15000',
                'Referred by partner'
            ],
        ];

        if ($format === 'csv') {
            $callback = function () use ($headers, $sampleRows) {
                $output = fopen('php://output', 'w');
                fputcsv($output, $headers);
                foreach ($sampleRows as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
            };
            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="lead-import-template.csv"',
            ]);
        }

        // XLSX
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Leads Template');

        // Headers row
        foreach ($headers as $colIndex => $header) {
            $cell = $sheet->getCellByColumnAndRow($colIndex + 1, 1);
            $cell->setValue($header);
            $cell->getStyle()->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn($colIndex + 1)->setAutoSize(true);
        }

        // Sample data
        foreach ($sampleRows as $rIdx => $row) {
            foreach ($row as $cIdx => $value) {
                $sheet->getCellByColumnAndRow($cIdx + 1, $rIdx + 2)->setValue($value);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'lead_template_');
        $writer->save($tempFile);

        return response()->download($tempFile, 'lead-import-template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    // ────────────────────────────────────────────────────────
    //  Private helpers
    // ────────────────────────────────────────────────────────

    /**
     * Parse a CSV or Excel file into an array of rows (each row is an array of cell values).
     */
    protected function parseFile(string $path, string $extension): array
    {
        $ext = strtolower($extension);
        if ($ext === 'csv' || $ext === 'txt') {
            return $this->parseCsv($path);
        }
        return $this->parseExcel($path);
    }

    protected function parseCsv(string $path): array
    {
        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            // Detect delimiter
            $firstLine = fgets($handle);
            rewind($handle);
            $delimiter = (substr_count($firstLine, "\t") > substr_count($firstLine, ',')) ? "\t" : ',';

            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }
        return $rows;
    }

    protected function parseExcel(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = [];
        foreach ($sheet->getRowIterator() as $row) {
            $cells = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
            $rows[] = $cells;
        }
        return $rows;
    }

    /**
     * Resolve or create the drive for the import.
     */
    protected function resolveDrive(Request $request, ?int $orgId): ?int
    {
        // Check request inputs first (passed as hidden fields from mapping form)
        $driveId = $request->input('drive_id');
        $newDriveName = $request->input('new_drive_name');
        $newDriveColor = $request->input('new_drive_color');

        // Fallback to session (set in preview step)
        if (!$driveId && !$newDriveName) {
            $driveId = $request->session()->get('import_drive_id');
            $newDriveName = $request->session()->get('import_new_drive_name');
            $newDriveColor = $request->session()->get('import_new_drive_color');
        }

        if (!empty($newDriveName)) {
            $drive = LeadDrive::create([
                'organization_id' => $orgId,
                'name' => $newDriveName,
                'slug' => Str::slug($newDriveName),
                'color' => $newDriveColor ?: '#0d6efd',
                'is_default' => false,
            ]);
            return $drive->id;
        }

        if ($driveId) {
            return (int) $driveId;
        }

        // Fallback: default drive
        $default = $orgId
            ? LeadDrive::where('organization_id', $orgId)->where('is_default', true)->first()
            : LeadDrive::whereNull('organization_id')->where('is_default', true)->first();

        return $default?->id;
    }

    /**
     * Build a lead data array from a row using the user-defined column mapping.
     */
    protected function buildLeadData(array $row, array $fieldToColumns, ?int $orgId, ?int $driveId, string $defaultStatus): array
    {
        $data = [
            'organization_id' => $orgId,
            'drive_id'        => $driveId,
            'status'          => $defaultStatus,
            'source'          => 'other',
            'priority'        => 'medium',
        ];

        // Extract values per field
        foreach ($fieldToColumns as $field => $colIndices) {
            // Take the first mapped column for each field
            $colIndex = $colIndices[0];
            $value = isset($row[$colIndex]) ? trim((string) $row[$colIndex]) : '';

            if ($value === '') continue;

            try {
                switch ($field) {
                    case 'full_name':
                        $parts = explode(' ', $value, 2);
                        $data['first_name'] = $parts[0];
                        $data['last_name'] = $parts[1] ?? '';
                        break;

                    case 'first_name':
                    case 'last_name':
                    case 'email':
                    case 'phone':
                    case 'company':
                    case 'job_title':
                    case 'industry':
                    case 'website':
                    case 'company_linkedin_profile':
                    case 'ceo_linkedin_profile':
                    case 'country':
                    case 'city':
                    case 'address':
                    case 'notes':
                    case 'requirements':
                    case 'lost_reason':
                        $data[$field] = $value;
                        break;

                    case 'employee_count':
                        // Strip non-numeric and allow ranges like "50-100" or "50+"
                        $data['employee_count'] = $value;
                        break;

                    case 'year_founded':
                        // DB column is YEAR type — extract 4-digit year
                        if (preg_match('/\b((?:19|20)\d{2})\b/', $value, $m)) {
                            $data['year_founded'] = (int) $m[1];
                        }
                        // If no 4-digit year found, silently skip to avoid SQL error
                        break;

                    case 'currency':
                        // Store as uppercase 3-letter code
                        $code = strtoupper(preg_replace('/[^a-zA-Z]/', '', $value));
                        if (strlen($code) === 3) {
                            $data['currency'] = $code;
                        }
                        break;

                    case 'status':
                        // Normalise to slug and validate against org statuses
                        $statuses = array_keys(Lead::getStatuses($orgId));
                        $normalised = strtolower(trim(str_replace([' ', '-'], '_', $value)));
                        $data['status'] = in_array($normalised, $statuses) ? $normalised : $defaultStatus;
                        break;

                    case 'interested_services':
                        // Accept comma-separated or semicolon-separated list
                        $services = preg_split('/[,;]+/', $value);
                        $services = array_values(array_filter(array_map('trim', $services)));
                        if (!empty($services)) {
                            $data['interested_services'] = $services;
                        }
                        break;

                    case 'last_contacted_at':
                    case 'next_follow_up':
                        // Accept common date formats
                        try {
                            $date = \Carbon\Carbon::parse($value);
                            $data[$field] = $date->toDateTimeString();
                        } catch (\Exception $e) {
                            // Skip unparseable dates
                        }
                        break;

                    case 'source':
                        $sources = array_keys(Lead::getSources($orgId));
                        $normalised = strtolower(str_replace([' ', '-'], '_', $value));
                        $data['source'] = in_array($normalised, $sources) ? $normalised : 'other';
                        break;

                    case 'priority':
                        $priorities = array_keys(Lead::getPriorities());
                        $normalised = strtolower(trim($value));
                        $data['priority'] = in_array($normalised, $priorities) ? $normalised : 'medium';
                        break;

                    case 'estimated_value':
                        $numeric = preg_replace('/[^0-9.]/', '', $value);
                        if (is_numeric($numeric)) {
                            $data['estimated_value'] = (float) $numeric;
                        }
                        break;
                }
            } catch (\Exception $e) {
                // If any individual field fails to process, skip it silently
                // rather than killing the entire row
                Log::warning("Import: field '{$field}' processing failed", [
                    'value' => $value,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Ensure at least a first name
        if (empty($data['first_name'])) {
            $data['first_name'] = 'Unknown';
        }

        // Validate email format
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            unset($data['email']);
        }

        // Ensure status / source / priority are safe ENUM values
        $safeStatuses   = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost', 'unqualified'];
        $safeSources    = ['website', 'referral', 'social_media', 'email_campaign', 'cold_call', 'event', 'advertisement', 'noel_waitlist', 'other'];
        $safePriorities = ['low', 'medium', 'high', 'urgent'];

        if (!empty($data['status']) && !in_array($data['status'], $safeStatuses)) {
            $data['status'] = 'new';
        }
        if (!empty($data['source']) && !in_array($data['source'], $safeSources)) {
            $data['source'] = 'other';
        }
        if (!empty($data['priority']) && !in_array($data['priority'], $safePriorities)) {
            $data['priority'] = 'medium';
        }

        return $data;
    }
}
