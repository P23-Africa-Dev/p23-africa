<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadDrive;
use App\Models\LeadStatus;
use App\Models\Organization;
use App\Services\Crm\LeadImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LeadImportController extends Controller
{
    private const MAX_IMPORT_ROWS = 5000;
    private const TEMP_IMPORT_PREFIX = 'imports/temp/';

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
        $this->logImportStep(request(), 'show-import.requested');

        $staleTempPath = (string) session('import_temp_path', '');
        if ($this->isSafeTempImportPath($staleTempPath) && Storage::disk('local')->exists($staleTempPath)) {
            Storage::disk('local')->delete($staleTempPath);
            $this->logImportStep(request(), 'show-import.stale-temp-deleted', [
                'stale_temp_path' => $staleTempPath,
            ]);
        }

        session()->forget([
            'import_trace_id',
            'import_preview_payload',
            'import_temp_path',
            'import_original_name',
            'import_drive_id',
            'import_new_drive_name',
            'import_new_drive_color',
        ]);

        $this->logImportStep(request(), 'show-import.session-cleared');

        $org = $this->currentOrg();
        $drives = $org
            ? LeadDrive::where('organization_id', $org->id)->orderBy('name')->get()
            : LeadDrive::whereNull('organization_id')->orderBy('name')->get();

        return view('admin.crm.import', compact('drives'));
    }

    /**
     * Step 2 — Parse file, auto-map columns, return mapping UI.
     */
    public function preview(Request $request, LeadImportService $importService)
    {
        $request->session()->put('import_trace_id', (string) Str::uuid());
        $this->logImportStep($request, 'preview.request-received', [
            'has_file' => $request->hasFile('file'),
            'drive_id' => $request->input('drive_id'),
            'new_drive_name_present' => filled($request->input('new_drive_name')),
        ]);

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240',
        ]);

        if ($validator->fails()) {
            $this->logImportStep($request, 'preview.validation-failed', [
                'errors' => $validator->errors()->toArray(),
            ], 'warning');

            return back()->withErrors($validator)->withInput();
        }

        $this->logImportStep($request, 'preview.validation-passed');

        $file = $request->file('file');
        $extension = strtolower((string) $file->getClientOriginalExtension());
        if (!$importService->isSupportedImportExtension($extension)) {
            $this->logImportStep($request, 'preview.unsupported-extension', [
                'extension' => $extension,
                'filename' => $file->getClientOriginalName(),
            ], 'warning');

            return back()->withErrors([
                'file' => 'The file field must be a file of type: csv, xlsx, xls, txt.',
            ]);
        }

        $this->logImportStep($request, 'preview.extension-accepted', [
            'extension' => $extension,
            'filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
        ]);

        // --- keep drive selection in session so Step 3 has it ---
        $request->session()->put('import_drive_id', $request->input('drive_id'));
        $request->session()->put('import_new_drive_name', $request->input('new_drive_name'));
        $request->session()->put('import_new_drive_color', $request->input('new_drive_color'));

        $this->logImportStep($request, 'preview.drive-selection-stored', [
            'drive_id' => $request->input('drive_id'),
            'new_drive_name_present' => filled($request->input('new_drive_name')),
        ]);

        Log::info('CRM import preview: upload received', [
            'original_name' => $file->getClientOriginalName(),
            'client_extension' => $extension,
            'client_mime' => $file->getClientMimeType(),
            'detected_mime' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        // Preserve original extension to avoid reader-identification issues on servers
        // that detect XLSX uploads as generic application/octet-stream.
        $tempFilename = Str::random(48) . '.' . $extension;
        $tempPath = $file->storeAs('imports/temp', $tempFilename, 'local');
        $fullPath = Storage::disk('local')->path($tempPath);

        $this->logImportStep($request, 'preview.temp-file-stored', [
            'temp_path' => $tempPath,
            'stored_extension' => pathinfo($tempPath, PATHINFO_EXTENSION),
        ]);

        Log::info('CRM import preview: temp file stored', [
            'temp_path' => $tempPath,
            'stored_extension' => pathinfo($tempPath, PATHINFO_EXTENSION),
        ]);

        try {
            $rows = $this->parseFile($fullPath, $file->getClientOriginalExtension());
        } catch (\Exception $e) {
            $this->logImportStep($request, 'preview.parse-failed', [
                'temp_path' => $tempPath,
                'error' => $e->getMessage(),
            ], 'error');

            Log::error('CRM import preview: parse failed', [
                'temp_path' => $tempPath,
                'full_path' => $fullPath,
                'original_name' => $file->getClientOriginalName(),
                'client_extension' => $extension,
                'file_size' => @filesize($fullPath) ?: null,
                'error' => $e->getMessage(),
            ]);
            Storage::disk('local')->delete($tempPath);
            return back()->with('error', 'Could not read file: ' . $e->getMessage());
        }

        $this->logImportStep($request, 'preview.file-parsed', [
            'row_count_including_header' => count($rows),
        ]);

        if (empty($rows) || count($rows) < 2) {
            Storage::disk('local')->delete($tempPath);
            $this->logImportStep($request, 'preview.no-data-rows', [
                'temp_path' => $tempPath,
            ], 'warning');
            return back()->with('error', 'The file is empty or has no data rows.');
        }

        // First row = headers
        $headers = array_map(fn($h) => trim((string) $h), $rows[0]);
        $dataRows = array_slice($rows, 1);

        $mappableFields = self::getMappableFields();
        $suggestedMapping = $importService->suggestMappings($headers, self::getAutoMappings());

        // Preview first 5 data rows
        $previewRows = array_slice($dataRows, 0, 5);
        $totalRows = count($dataRows);

        if ($totalRows > $this->maxImportRows()) {
            Storage::disk('local')->delete($tempPath);
            $this->logImportStep($request, 'preview.row-limit-exceeded', [
                'total_rows' => $totalRows,
                'max_rows' => $this->maxImportRows(),
            ], 'warning');

            return back()->with('error', "This file contains {$totalRows} rows. The maximum allowed per import is {$this->maxImportRows()} rows. Please split the file and import in batches.");
        }

        // Store temp path in session
        $request->session()->put('import_temp_path', $tempPath);
        $request->session()->put('import_original_name', $file->getClientOriginalName());

        // Pass drive info to mapping view so it can be included as hidden fields
        $driveId = $request->input('drive_id', '');
        $newDriveName = $request->input('new_drive_name', '');
        $newDriveColor = $request->input('new_drive_color', '');

        $request->session()->put('import_preview_payload', [
            'headers' => $headers,
            'suggestedMapping' => $suggestedMapping,
            'mappableFields' => $mappableFields,
            'previewRows' => $previewRows,
            'totalRows' => $totalRows,
            'tempPath' => $tempPath,
            'driveId' => $driveId,
            'newDriveName' => $newDriveName,
            'newDriveColor' => $newDriveColor,
        ]);

        $this->logImportStep($request, 'preview.payload-stored', [
            'headers_count' => count($headers),
            'preview_rows_count' => count($previewRows),
            'total_rows' => $totalRows,
            'temp_path' => $tempPath,
        ]);

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
     * GET fallback for import preview mapping page.
     * Prevents MethodNotAllowed when browser refreshes /crm/import/preview.
     */
    public function showPreview(Request $request)
    {
        $payload = $request->session()->get('import_preview_payload');

        $this->logImportStep($request, 'preview-page.requested', [
            'payload_present' => !empty($payload),
        ]);

        if (empty($payload)) {
            $this->logImportStep($request, 'preview-page.payload-missing', [], 'warning');
            return redirect(orgRoute('admin.crm.import'))
                ->with('error', 'Import preview expired. Please upload your file again.');
        }

        return view('admin.crm.import-mapping', $payload);
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
        return app(LeadImportService::class)->friendlyDbError($e, $leadData);
    }

    /**
     * Extract the affected DB column name from a QueryException, if determinable.
     */
    protected function extractErrorField(\Exception $e): ?string
    {
        return app(LeadImportService::class)->extractErrorField($e);
    }

    /**
     * Step 3 — Process import using user-defined column mappings.
     */
    public function import(Request $request, LeadImportService $importService)
    {
        $this->logImportStep($request, 'import.request-received', [
            'temp_path' => $request->input('temp_path'),
            'column_map_count' => is_array($request->input('column_map')) ? count($request->input('column_map')) : 0,
        ]);

        $validator = Validator::make($request->all(), [
            'column_map'   => 'required|array',
            'temp_path'    => 'required|string',
        ]);

        if ($validator->fails()) {
            $this->logImportStep($request, 'import.validation-failed', [
                'errors' => $validator->errors()->toArray(),
            ], 'warning');

            return back()->withErrors($validator)->withInput();
        }

        $this->logImportStep($request, 'import.validation-passed');

        $org = $this->currentOrg();
        $orgId = $org ? $org->id : null;

        $tempPath = $request->input('temp_path');
        $sessionTempPath = (string) $request->session()->get('import_temp_path', '');

        if (!$this->isSafeTempImportPath($tempPath) || empty($sessionTempPath) || !hash_equals($sessionTempPath, $tempPath)) {
            $this->logImportStep($request, 'import.invalid-session-or-path', [
                'temp_path' => $tempPath,
                'session_temp_path' => $sessionTempPath,
            ], 'warning');

            return redirect(orgRoute('admin.crm.import'))
                ->with('error', 'Invalid import session detected. Please upload the file again.');
        }

        $fieldToColumns = $importService->buildFieldToColumns($request->input('column_map', []));
        if (empty($fieldToColumns)) {
            $this->logImportStep($request, 'import.empty-mapping', [], 'warning');
            return redirect(orgRoute('admin.crm.import.preview-page'))
                ->with('error', 'Map at least one column before starting the import.');
        }

        $this->logImportStep($request, 'import.mapping-built', [
            'mapped_fields' => array_keys($fieldToColumns),
        ]);

        $fullPath = Storage::disk('local')->path($tempPath);

        if (!file_exists($fullPath)) {
            $this->logImportStep($request, 'import.temp-file-missing', [
                'temp_path' => $tempPath,
            ], 'warning');
            return redirect(orgRoute('admin.crm.import'))
                ->with('error', 'Upload session expired. Please upload the file again.');
        }

        // --- resolve drive ---
        $driveId = $this->resolveDrive($request, $orgId);
        $this->logImportStep($request, 'import.drive-resolved', [
            'drive_id' => $driveId,
            'organization_id' => $orgId,
        ]);

        // --- parse file again ---
        $extension = pathinfo($request->session()->get('import_original_name', $tempPath), PATHINFO_EXTENSION);
        try {
            $rows = $this->parseFile($fullPath, $extension);
        } catch (\Exception $e) {
            Storage::disk('local')->delete($tempPath);
            $this->logImportStep($request, 'import.parse-failed', [
                'temp_path' => $tempPath,
                'error' => $e->getMessage(),
            ], 'error');
            return redirect(orgRoute('admin.crm.import'))
                ->with('error', 'Error reading file: ' . $e->getMessage());
        }

        $this->logImportStep($request, 'import.file-parsed', [
            'row_count_including_header' => count($rows),
            'extension' => $extension,
        ]);

        if (empty($rows) || count($rows) < 2) {
            Storage::disk('local')->delete($tempPath);
            $this->logImportStep($request, 'import.no-data-rows', [
                'temp_path' => $tempPath,
            ], 'warning');
            return redirect(orgRoute('admin.crm.import'))
                ->with('error', 'The uploaded file is empty or has no importable data rows.');
        }

        $headers = array_map(fn($h) => trim((string) $h), $rows[0]);
        $dataRows = array_slice($rows, 1);

        if (count($dataRows) > $this->maxImportRows()) {
            Storage::disk('local')->delete($tempPath);
            $this->logImportStep($request, 'import.row-limit-exceeded', [
                'total_rows' => count($dataRows),
                'max_rows' => $this->maxImportRows(),
            ], 'warning');
            return redirect(orgRoute('admin.crm.import'))
                ->with('error', "This file contains " . count($dataRows) . " rows. The maximum allowed per import is {$this->maxImportRows()} rows. Please split the file and import in batches.");
        }

        $defaultStatus = LeadStatus::getDefaultSlug($orgId);
        $importResult = $importService->processMappedRows(
            $dataRows,
            $fieldToColumns,
            $orgId,
            $driveId,
            $defaultStatus,
            fn (array $row, array $mapping) => $this->isDescriptionRow($row, $mapping)
        );

        $imported = $importResult['imported'];
        $skipped = $importResult['skipped'];
        $errorRows = $importResult['error_rows'];
        $descriptionRowsSkipped = $importResult['description_rows_skipped'];
        $errors = $importResult['errors'];

        $this->logImportStep($request, 'import.rows-processed', [
            'imported' => $imported,
            'skipped_duplicates' => $skipped,
            'error_rows' => $errorRows,
            'description_rows_skipped' => $descriptionRowsSkipped,
        ]);

        // Cleanup temp file
        Storage::disk('local')->delete($tempPath);
        $request->session()->forget([
            'import_temp_path',
            'import_original_name',
            'import_drive_id',
            'import_new_drive_name',
            'import_new_drive_color',
            'import_preview_payload',
        ]);

        $this->logImportStep($request, 'import.cleanup-complete', [
            'temp_path' => $tempPath,
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
                $rowIndex = $err['row'] - 2;
                $rawRow = $err['raw'] ?? ($dataRows[$rowIndex] ?? []);
                $parsed = $err['parsed'] ?? [];
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

            $this->logImportStep($request, 'import.sent-to-review', [
                'review_rows' => count($reviewRows),
            ], 'warning');

            return redirect(orgRoute('admin.crm.import.review'))
                ->with('success', $message . " {$errorRows} row(s) need your attention. Review them below.");
        }

        $this->logImportStep($request, 'import.completed-successfully', [
            'imported' => $imported,
            'skipped_duplicates' => $skipped,
        ]);

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

        $this->logImportStep($request, 'review-page.requested', [
            'has_review_rows' => !empty($reviewRows),
            'has_meta' => !empty($meta),
        ]);

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
    public function retry(Request $request, LeadImportService $importService)
    {
        $meta = $request->session()->get('import_review_meta');
        $reviewRows = $request->session()->get('import_review_rows', []);

        $this->logImportStep($request, 'review-retry.request-received', [
            'row_action_count' => is_array($request->input('row_action')) ? count($request->input('row_action')) : 0,
            'existing_review_rows' => count($reviewRows),
        ]);

        if (empty($meta)) {
            $this->logImportStep($request, 'review-retry.meta-missing', [], 'warning');
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
        $existingRowsByNumber = [];
        foreach ($reviewRows as $row) {
            $existingRowsByNumber[(int) ($row['row_number'] ?? 0)] = $row;
        }
        $remainingRowsByNumber = $existingRowsByNumber;

        foreach ($rowActions as $rowNumber => $action) {
            $rowNumber = (int) $rowNumber;

            if ($action === 'skip') {
                $skipped++;
                unset($remainingRowsByNumber[$rowNumber]);
                $this->logImportStep($request, 'review-retry.row-skipped', [
                    'row_number' => $rowNumber,
                ]);
                continue;
            }

            $fields = $rowOverrides[$rowNumber] ?? [];
            $prepared = $importService->buildLeadDataFromManualFields($fields, $orgId, $driveId, $defaultStatus);

            if ($prepared['error']) {
                $message = $prepared['error']['message'];
                $errors[] = "⚠️ Row {$rowNumber}: {$message}";
                $remainingRowsByNumber[$rowNumber] = [
                    'row_number' => $rowNumber,
                    'type' => 'validation',
                    'message' => $message,
                    'affected_field' => $prepared['error']['affected_field'] ?? null,
                    'raw' => array_values($fields),
                    'parsed' => $prepared['data'],
                    'suggestion' => $this->buildFixSuggestion([
                        'type' => 'validation',
                        'message' => $message,
                        'affected_field' => $prepared['error']['affected_field'] ?? null,
                    ], $prepared['data']),
                ];
                $this->logImportStep($request, 'review-retry.row-validation-failed', [
                    'row_number' => $rowNumber,
                    'message' => $message,
                ], 'warning');
                continue;
            }

            try {
                $result = $importService->importPreparedLead($prepared['data'], $orgId);

                if ($result['status'] === 'duplicate') {
                    $skipped++;
                    $message = $result['message'];
                    $errors[] = "🔁 Row {$rowNumber}: {$message}";
                    $remainingRowsByNumber[$rowNumber] = [
                        'row_number' => $rowNumber,
                        'type' => 'duplicate',
                        'message' => $message,
                        'affected_field' => 'email',
                        'raw' => array_values($fields),
                        'parsed' => $prepared['data'],
                        'suggestion' => $this->buildFixSuggestion([
                            'type' => 'duplicate',
                            'message' => $message,
                            'affected_field' => 'email',
                        ], $prepared['data']),
                    ];
                    $this->logImportStep($request, 'review-retry.row-duplicate', [
                        'row_number' => $rowNumber,
                    ], 'warning');
                    continue;
                }

                $imported++;
                unset($remainingRowsByNumber[$rowNumber]);
                $this->logImportStep($request, 'review-retry.row-imported', [
                    'row_number' => $rowNumber,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                $message = $this->friendlyDbError($e, $prepared['data']);
                $errors[] = "⚠️ Row {$rowNumber}: {$message}";
                $remainingRowsByNumber[$rowNumber] = [
                    'row_number' => $rowNumber,
                    'type' => 'database',
                    'message' => $message,
                    'affected_field' => $this->extractErrorField($e),
                    'raw' => array_values($fields),
                    'parsed' => $prepared['data'],
                    'suggestion' => $this->buildFixSuggestion([
                        'type' => 'database',
                        'message' => $message,
                        'affected_field' => $this->extractErrorField($e),
                    ], $prepared['data']),
                ];
                $this->logImportStep($request, 'review-retry.row-db-failed', [
                    'row_number' => $rowNumber,
                    'message' => $message,
                ], 'error');
            } catch (\Exception $e) {
                $message = Str::limit($e->getMessage(), 120);
                $errors[] = "❌ Row {$rowNumber}: {$message}";
                $remainingRowsByNumber[$rowNumber] = [
                    'row_number' => $rowNumber,
                    'type' => 'general',
                    'message' => $message,
                    'affected_field' => null,
                    'raw' => array_values($fields),
                    'parsed' => $prepared['data'],
                    'suggestion' => $this->buildFixSuggestion([
                        'type' => 'general',
                        'message' => $message,
                        'affected_field' => null,
                    ], $prepared['data']),
                ];
                $this->logImportStep($request, 'review-retry.row-general-failed', [
                    'row_number' => $rowNumber,
                    'message' => $message,
                ], 'error');
            }
        }

        $message = "{$imported} row(s) imported from review.";
        if ($skipped > 0) $message .= " {$skipped} row(s) skipped.";

        if (!empty($remainingRowsByNumber)) {
            $remainingRows = array_values($remainingRowsByNumber);
            usort($remainingRows, fn(array $a, array $b) => ($a['row_number'] ?? 0) <=> ($b['row_number'] ?? 0));

            $meta['error_count'] = count($remainingRows);
            $request->session()->put('import_review_rows', $remainingRows);
            $request->session()->put('import_review_meta', $meta);

            $redirect = redirect(orgRoute('admin.crm.import.review'))
                ->with('success', $message . ' Some rows still need correction.');
            if (!empty($errors)) {
                $redirect->with('import_errors', array_slice($errors, 0, 25));
            }

            $this->logImportStep($request, 'review-retry.partial-complete', [
                'imported' => $imported,
                'skipped' => $skipped,
                'remaining_rows' => count($remainingRows),
            ], 'warning');

            return $redirect;
        }

        // Clear review session after all rows are resolved
        $request->session()->forget(['import_review_rows', 'import_review_meta']);

        $redirect = redirect(orgRoute('admin.crm.index'))->with('success', $message);
        if (!empty($errors)) {
            $redirect->with('import_errors', array_slice($errors, 0, 25));
        }

        $this->logImportStep($request, 'review-retry.completed', [
            'imported' => $imported,
            'skipped' => $skipped,
        ]);

        return $redirect;
    }

    /**
     * AJAX: Import or skip a single review-row.
     * POST  crm/import/retry-single
     * Body: { row_number, action: 'import'|'skip', row_data: { field: value, ... } }
     * Returns JSON { ok, message } or { ok: false, error }
     */
    public function retrySingle(Request $request, LeadImportService $importService)
    {
        $meta = $request->session()->get('import_review_meta');

        $this->logImportStep($request, 'review-retry-single.request-received', [
            'row_number' => $request->input('row_number'),
            'action' => $request->input('action', 'import'),
        ]);

        if (empty($meta)) {
            $this->logImportStep($request, 'review-retry-single.meta-missing', [], 'warning');
            return response()->json(['ok' => false, 'error' => 'Review session expired. Start a new import.'], 422);
        }

        $rowNumber = $request->input('row_number');
        $action    = $request->input('action', 'import'); // 'import' or 'skip'
        $fields    = $request->input('row_data', []);

        // --- skip action --------------------------------------------------
        if ($action === 'skip') {
            $this->removeReviewRow($request, $rowNumber);
            $this->logImportStep($request, 'review-retry-single.row-skipped', [
                'row_number' => $rowNumber,
            ]);
            return response()->json(['ok' => true, 'message' => "Row {$rowNumber} skipped."]);
        }

        // --- import action ------------------------------------------------
        $orgId         = $meta['org_id'];
        $driveId       = $meta['drive_id'];
        $defaultStatus = $meta['defaultStatus'];

        $prepared = $importService->buildLeadDataFromManualFields($fields, $orgId, $driveId, $defaultStatus);

        if ($prepared['error']) {
            $this->logImportStep($request, 'review-retry-single.validation-failed', [
                'row_number' => $rowNumber,
                'message' => $prepared['error']['message'],
            ], 'warning');
            return response()->json([
                'ok' => false,
                'error' => $prepared['error']['message'],
            ], 422);
        }

        try {
            $result = $importService->importPreparedLead($prepared['data'], $orgId);

            if ($result['status'] === 'duplicate') {
                $this->logImportStep($request, 'review-retry-single.duplicate', [
                    'row_number' => $rowNumber,
                ], 'warning');
                return response()->json([
                    'ok' => false,
                    'error' => $result['message'],
                ], 422);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            \Illuminate\Support\Facades\Log::error('retrySingle DB error', [
                'row'      => $rowNumber,
                'leadData' => $prepared['data'],
                'sql'      => $e->getMessage(),
            ]);
            return response()->json([
                'ok'    => false,
                'error' => $this->friendlyDbError($e, $prepared['data']),
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

        $this->logImportStep($request, 'review-retry-single.row-imported', [
            'row_number' => $rowNumber,
        ]);

        return response()->json(['ok' => true, 'message' => "Row {$rowNumber} imported successfully!"]);
    }

    /**
     * AJAX: Skip all remaining review rows at once, clear session, return redirect URL.
     */
    public function skipAll(Request $request)
    {
        $meta = $request->session()->get('import_review_meta');
        $count = 0;

        $this->logImportStep($request, 'review-skip-all.request-received');

        if (!empty($meta)) {
            $rows = $request->session()->get('import_review_rows', []);
            $count = count($rows);
        }

        $request->session()->forget(['import_review_rows', 'import_review_meta']);

        $this->logImportStep($request, 'review-skip-all.completed', [
            'rows_skipped' => $count,
        ]);

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
        $before = count($rows);
        $rows = array_values(array_filter($rows, fn($r) => ($r['row_number'] ?? null) != $rowNumber));
        $request->session()->put('import_review_rows', $rows);

        // Update meta count
        $meta = $request->session()->get('import_review_meta', []);
        $meta['error_count'] = count($rows);
        $request->session()->put('import_review_meta', $meta);

        $this->logImportStep($request, 'review-row-removed', [
            'row_number' => $rowNumber,
            'before_count' => $before,
            'after_count' => count($rows),
        ]);
    }

    protected function logImportStep(Request $request, string $step, array $context = [], string $level = 'info'): void
    {
        $traceId = (string) $request->session()->get('import_trace_id', '');
        $baseContext = [
            'step' => $step,
            'trace_id' => $traceId !== '' ? $traceId : null,
            'user_id' => Auth::id(),
            'organization_id' => optional($this->currentOrg())->id,
            'route_name' => optional($request->route())->getName(),
            'method' => $request->method(),
            'path' => $request->path(),
        ];

        $payload = array_merge($baseContext, $context);

        if ($level === 'error') {
            Log::error('CRM import step', $payload);
            return;
        }

        if ($level === 'warning') {
            Log::warning('CRM import step', $payload);
            return;
        }

        Log::info('CRM import step', $payload);
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
        return $this->parseExcel($path, $ext);
    }

    protected function parseCsv(string $path): array
    {
        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            // Detect delimiter
            $firstLine = fgets($handle);
            rewind($handle);
            if ($firstLine === false) {
                fclose($handle);
                return [];
            }

            $delimiter = $this->detectCsvDelimiter($firstLine);

            while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }

        if (!empty($rows[0][0])) {
            $rows[0][0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $rows[0][0]);
        }

        return $rows;
    }

    protected function detectCsvDelimiter(string $firstLine): string
    {
        $candidates = [',', ';', "\t", '|'];
        $winner = ',';
        $winnerCount = -1;

        foreach ($candidates as $candidate) {
            $count = substr_count($firstLine, $candidate);
            if ($count > $winnerCount) {
                $winnerCount = $count;
                $winner = $candidate;
            }
        }

        return $winner;
    }

    protected function maxImportRows(): int
    {
        return max(1, (int) config('crm.import.max_rows', self::MAX_IMPORT_ROWS));
    }

    protected function isSafeTempImportPath(string $tempPath): bool
    {
        if ($tempPath === '') {
            return false;
        }

        $normalised = str_replace('\\', '/', $tempPath);
        if (str_contains($normalised, '..') || !str_starts_with($normalised, self::TEMP_IMPORT_PREFIX)) {
            return false;
        }

        $extension = strtolower((string) pathinfo($normalised, PATHINFO_EXTENSION));
        return app(LeadImportService::class)->isSupportedImportExtension($extension);
    }

    protected function parseExcel(string $path, ?string $extension = null): array
    {
        $reader = null;
        $extension = strtolower((string) $extension);

        $preferredReaderByExtension = [
            'xlsx' => 'Xlsx',
            'xls' => 'Xls',
            'ods' => 'Ods',
            'csv' => 'Csv',
            'txt' => 'Csv',
        ];

        if (!empty($preferredReaderByExtension[$extension])) {
            $preferredReaderType = $preferredReaderByExtension[$extension];
            try {
                $candidate = IOFactory::createReader($preferredReaderType);
                if ($candidate->canRead($path)) {
                    $reader = $candidate;
                    Log::info('CRM import parseExcel: preferred reader selected from extension', [
                        'path' => $path,
                        'extension' => $extension,
                        'reader' => $preferredReaderType,
                    ]);
                }
            } catch (\Throwable $ignored) {
            }
        }

        if (!$reader) {
            try {
                $readerType = IOFactory::identify($path);
                $reader = IOFactory::createReader($readerType);
                Log::info('CRM import parseExcel: reader identified', [
                    'path' => $path,
                    'extension' => $extension,
                    'reader' => $readerType,
                ]);
            } catch (\Throwable $e) {
                Log::warning('CRM import parseExcel: auto-identify failed, trying canRead fallbacks', [
                    'path' => $path,
                    'extension' => $extension,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if (!$reader) {
            foreach (['Xlsx', 'Xls', 'Csv', 'Ods'] as $fallbackType) {
                try {
                    $candidate = IOFactory::createReader($fallbackType);
                    if ($candidate->canRead($path)) {
                        $reader = $candidate;
                        Log::info('CRM import parseExcel: fallback reader selected', [
                            'path' => $path,
                            'extension' => $extension,
                            'reader' => $fallbackType,
                        ]);
                        break;
                    }
                } catch (\Throwable $ignored) {
                }
            }
        }

        if (!$reader) {
            throw new \RuntimeException('Unable to identify a reader for this file');
        }

        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($path);
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
            $drive = LeadDrive::firstOrCreate(
                [
                    'organization_id' => $orgId,
                    'slug' => Str::slug($newDriveName),
                ],
                [
                    'name' => $newDriveName,
                    'color' => $newDriveColor ?: '#0d6efd',
                    'is_default' => false,
                ]
            );

            return $drive->id;
        }

        if ($driveId) {
            $query = LeadDrive::query()->whereKey((int) $driveId);
            $orgId ? $query->where('organization_id', $orgId) : $query->whereNull('organization_id');

            return $query->value('id');
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
