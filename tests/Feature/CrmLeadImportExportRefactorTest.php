<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\LeadImportController;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadDrive;
use App\Models\LeadEmail;
use App\Models\Organization;
use App\Models\User;
use App\Services\Crm\LeadExportService;
use App\Services\Crm\LeadImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CrmLeadImportExportRefactorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('supervisor', 'web');
        Role::findOrCreate('staff', 'web');
    }

    public function test_import_service_skips_existing_and_in_file_duplicates(): void
    {
        $service = app(LeadImportService::class);
        $organization = $this->createOrganization('Import Org');
        $drive = $this->createDrive($organization, 'Growth');

        Lead::create([
            'organization_id' => $organization->id,
            'drive_id' => $drive->id,
            'first_name' => 'Existing',
            'email' => 'existing@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'medium',
        ]);

        $fieldToColumns = $service->buildFieldToColumns([
            0 => 'email',
            1 => 'first_name',
            2 => 'company',
        ]);

        $result = $service->processMappedRows(
            [
                ['fresh@example.com', 'Fresh', 'Acme'],
                ['existing@example.com', 'Duplicate Existing', 'Beta'],
                ['fresh@example.com', 'Duplicate In File', 'Gamma'],
            ],
            $fieldToColumns,
            $organization->id,
            $drive->id,
            'new',
            fn() => false,
        );

        $this->assertSame(1, $result['imported']);
        $this->assertSame(2, $result['skipped']);
        $this->assertCount(2, $result['errors']);
        $this->assertDatabaseHas('leads', [
            'organization_id' => $organization->id,
            'email' => 'fresh@example.com',
            'first_name' => 'Fresh',
        ]);
    }

    public function test_import_service_accepts_supported_file_extensions(): void
    {
        $service = app(LeadImportService::class);

        $this->assertTrue($service->isSupportedImportExtension('xlsx'));
        $this->assertTrue($service->isSupportedImportExtension('XLS'));
        $this->assertTrue($service->isSupportedImportExtension('csv'));
        $this->assertFalse($service->isSupportedImportExtension('exe'));
    }

    public function test_import_preview_can_parse_real_xlsx_upload(): void
    {
        $filePath = tempnam(sys_get_temp_dir(), 'crm-import-');
        $xlsxPath = $filePath . '.xlsx';
        @rename($filePath, $xlsxPath);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            ['First Name', 'Email', 'Company'],
            ['Jane', 'jane@example.com', 'Acme Ltd'],
        ]);
        (new Xlsx($spreadsheet))->save($xlsxPath);

        $uploaded = new UploadedFile(
            $xlsxPath,
            'lead-import-template.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $request = Request::create('/admin/crm/import/preview', 'POST', []);
        $request->files->set('file', $uploaded);

        $session = app('session')->driver();
        $session->start();
        $request->setLaravelSession($session);

        $response = app(LeadImportController::class)->preview($request, app(LeadImportService::class));

        $this->assertSame('admin.crm.import-mapping', $response->name());
        $this->assertArrayHasKey('headers', $response->getData());
        $this->assertArrayHasKey('previewRows', $response->getData());
        $this->assertSame('First Name', $response->getData()['headers'][0]);
        $this->assertSame('jane@example.com', $response->getData()['previewRows'][0][1]);

        @unlink($xlsxPath);
    }

    public function test_import_preview_can_parse_xlsx_even_when_detected_as_octet_stream(): void
    {
        $filePath = tempnam(sys_get_temp_dir(), 'crm-import-octet-');
        $xlsxPath = $filePath . '.xlsx';
        @rename($filePath, $xlsxPath);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            ['First Name', 'Email', 'Company'],
            ['John', 'john@example.com', 'Octet Co'],
        ]);
        (new Xlsx($spreadsheet))->save($xlsxPath);

        $uploaded = new UploadedFile(
            $xlsxPath,
            'lead-import-template.xlsx',
            'application/octet-stream',
            null,
            true
        );

        $request = Request::create('/admin/crm/import/preview', 'POST', []);
        $request->files->set('file', $uploaded);

        $session = app('session')->driver();
        $session->start();
        $request->setLaravelSession($session);

        $response = app(LeadImportController::class)->preview($request, app(LeadImportService::class));

        $this->assertSame('admin.crm.import-mapping', $response->name());
        $this->assertArrayHasKey('tempPath', $response->getData());
        $this->assertStringEndsWith('.xlsx', $response->getData()['tempPath']);
        $this->assertSame('john@example.com', $response->getData()['previewRows'][0][1]);

        @unlink($xlsxPath);
    }

    public function test_import_preview_get_route_renders_mapping_when_session_payload_exists(): void
    {
        $session = app('session')->driver();
        $session->start();
        $session->put('import_preview_payload', [
            'headers' => ['First Name', 'Email'],
            'suggestedMapping' => [0 => 'first_name', 1 => 'email'],
            'mappableFields' => ['first_name' => 'First Name', 'email' => 'Email Address'],
            'previewRows' => [['Jane', 'jane@example.com']],
            'totalRows' => 1,
            'tempPath' => 'imports/temp/example.xlsx',
            'driveId' => '',
            'newDriveName' => '',
            'newDriveColor' => '',
        ]);

        $request = Request::create('/admin/crm/import/preview', 'GET');
        $request->setLaravelSession($session);

        $response = app(LeadImportController::class)->showPreview($request);

        $this->assertSame('admin.crm.import-mapping', $response->name());
        $this->assertArrayHasKey('headers', $response->getData());
        $this->assertSame('First Name', $response->getData()['headers'][0]);
    }

    public function test_import_preview_get_route_redirects_to_import_when_session_is_missing(): void
    {
        $session = app('session')->driver();
        $session->start();

        $request = Request::create('/admin/crm/import/preview', 'GET');
        $request->setLaravelSession($session);

        $response = app(LeadImportController::class)->showPreview($request);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/crm/import', $response->getTargetUrl());
    }

    public function test_import_service_restores_soft_deleted_lead_with_matching_email(): void
    {
        $service = app(LeadImportService::class);
        $organization = $this->createOrganization('Restore Org');
        $drive = $this->createDrive($organization, 'Retention');

        $lead = Lead::create([
            'organization_id' => $organization->id,
            'drive_id' => $drive->id,
            'first_name' => 'Old',
            'company' => 'Legacy Co',
            'email' => 'restore@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'medium',
        ]);
        $lead->delete();

        $fieldToColumns = $service->buildFieldToColumns([
            0 => 'email',
            1 => 'first_name',
            2 => 'company',
        ]);

        $result = $service->processMappedRows(
            [
                ['restore@example.com', 'Restored', 'Modern Co'],
            ],
            $fieldToColumns,
            $organization->id,
            $drive->id,
            'new',
            fn() => false,
        );

        $this->assertSame(1, $result['imported']);
        $this->assertSame(0, $result['skipped']);
        $this->assertFalse($lead->fresh()->trashed());
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'first_name' => 'Restored',
            'company' => 'Modern Co',
            'email' => 'restore@example.com',
        ]);
    }

    public function test_import_service_returns_validation_error_for_invalid_email(): void
    {
        $service = app(LeadImportService::class);
        $organization = $this->createOrganization('Validation Org');
        $drive = $this->createDrive($organization, 'Validation');

        $fieldToColumns = $service->buildFieldToColumns([
            0 => 'email',
            1 => 'company',
        ]);

        $result = $service->processMappedRows(
            [
                ['not-an-email', 'Broken Row'],
            ],
            $fieldToColumns,
            $organization->id,
            $drive->id,
            'new',
            fn() => false,
        );

        $this->assertSame(0, $result['imported']);
        $this->assertSame(1, $result['error_rows']);
        $this->assertSame('validation', $result['errors'][0]['type']);
        $this->assertSame('email', $result['errors'][0]['affected_field']);
    }

    public function test_import_service_attributes_created_by_and_source_type_for_file_imports(): void
    {
        $service = app(LeadImportService::class);
        $organization = $this->createOrganization('Attribution Org');
        $drive = $this->createDrive($organization, 'Attribution');
        $staff = User::factory()->create(['organization_id' => $organization->id]);

        $fieldToColumns = $service->buildFieldToColumns([
            0 => 'email',
            1 => 'first_name',
        ]);

        $result = $service->processMappedRows(
            [
                ['attributed@example.com', 'Attributed'],
            ],
            $fieldToColumns,
            $organization->id,
            $drive->id,
            'new',
            fn() => false,
            $staff->id,
        );

        $this->assertSame(1, $result['imported']);
        $this->assertDatabaseHas('leads', [
            'organization_id' => $organization->id,
            'created_by' => $staff->id,
            'source_type' => 'file_import',
            'email' => 'attributed@example.com',
        ]);
    }

    public function test_import_process_rejects_tampered_temp_path(): void
    {
        Storage::disk('local')->put('imports/temp/valid-import.csv', "First Name,Email\nJane,jane@example.com\n");
        Storage::disk('local')->put('imports/temp/other-import.csv', "First Name,Email\nJohn,john@example.com\n");

        $session = app('session')->driver();
        $session->start();
        $session->put('import_temp_path', 'imports/temp/valid-import.csv');
        $session->put('import_original_name', 'valid-import.csv');

        $request = Request::create('/admin/crm/import/process', 'POST', [
            'temp_path' => 'imports/temp/other-import.csv',
            'column_map' => [
                0 => 'first_name',
                1 => 'email',
            ],
        ]);
        $request->setLaravelSession($session);

        $response = app(LeadImportController::class)->import($request, app(LeadImportService::class));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/crm/import', $response->getTargetUrl());
    }

    public function test_import_process_requires_at_least_one_mapped_column(): void
    {
        Storage::disk('local')->put('imports/temp/empty-map.csv', "First Name,Email\nJane,jane@example.com\n");

        $session = app('session')->driver();
        $session->start();
        $session->put('import_temp_path', 'imports/temp/empty-map.csv');
        $session->put('import_original_name', 'empty-map.csv');
        $session->put('import_preview_payload', [
            'headers' => ['First Name', 'Email'],
            'suggestedMapping' => [0 => 'first_name', 1 => 'email'],
            'mappableFields' => ['first_name' => 'First Name', 'email' => 'Email Address'],
            'previewRows' => [['Jane', 'jane@example.com']],
            'totalRows' => 1,
            'tempPath' => 'imports/temp/empty-map.csv',
            'driveId' => '',
            'newDriveName' => '',
            'newDriveColor' => '',
        ]);

        $request = Request::create('/admin/crm/import/process', 'POST', [
            'temp_path' => 'imports/temp/empty-map.csv',
            'column_map' => [
                0 => 'skip',
                1 => 'skip',
            ],
        ]);
        $request->setLaravelSession($session);

        $response = app(LeadImportController::class)->import($request, app(LeadImportService::class));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/crm/import/preview', $response->getTargetUrl());
    }

    public function test_import_process_rejects_files_exceeding_row_limit(): void
    {
        $rows = ["First Name,Email"];
        for ($i = 1; $i <= 5001; $i++) {
            $rows[] = "User {$i},user{$i}@example.com";
        }
        Storage::disk('local')->put('imports/temp/too-many-rows.csv', implode("\n", $rows));

        $session = app('session')->driver();
        $session->start();
        $session->put('import_temp_path', 'imports/temp/too-many-rows.csv');
        $session->put('import_original_name', 'too-many-rows.csv');

        $request = Request::create('/admin/crm/import/process', 'POST', [
            'temp_path' => 'imports/temp/too-many-rows.csv',
            'column_map' => [
                0 => 'first_name',
                1 => 'email',
            ],
        ]);
        $request->setLaravelSession($session);

        $response = app(LeadImportController::class)->import($request, app(LeadImportService::class));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/crm/import', $response->getTargetUrl());
        $this->assertSame(0, Lead::count());
    }

    public function test_retry_keeps_unresolved_rows_in_review_session(): void
    {
        $session = app('session')->driver();
        $session->start();
        $session->put('import_review_meta', [
            'org_id' => null,
            'drive_id' => null,
            'defaultStatus' => 'new',
            'error_count' => 1,
        ]);
        $session->put('import_review_rows', [
            [
                'row_number' => 3,
                'type' => 'validation',
                'message' => 'Invalid email',
                'affected_field' => 'email',
                'raw' => ['Bad', 'not-an-email'],
                'parsed' => ['first_name' => 'Bad', 'email' => 'not-an-email'],
                'suggestion' => 'Fix email',
            ],
        ]);

        $request = Request::create('/admin/crm/import/retry', 'POST', [
            'row_action' => [
                3 => 'import',
            ],
            'row_data' => [
                3 => [
                    'first_name' => 'Bad',
                    'email' => 'still-not-an-email',
                ],
            ],
        ]);
        $request->setLaravelSession($session);

        $response = app(LeadImportController::class)->retry($request, app(LeadImportService::class));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertStringContainsString('/admin/crm/import/review', $response->getTargetUrl());

        $remaining = $session->get('import_review_rows', []);
        $this->assertCount(1, $remaining);
        $this->assertSame(3, $remaining[0]['row_number']);
    }

    public function test_export_uses_org_scope_and_applies_drive_and_priority_filters(): void
    {
        $organization = $this->createOrganization('Export Org');
        $otherOrganization = $this->createOrganization('Other Export Org');
        $matchingDrive = $this->createDrive($organization, 'Matching');
        $otherDrive = $this->createDrive($organization, 'Other');
        $foreignDrive = $this->createDrive($otherOrganization, 'Foreign');
        $user = User::factory()->create(['organization_id' => $organization->id]);

        Lead::create([
            'organization_id' => $organization->id,
            'drive_id' => $matchingDrive->id,
            'first_name' => 'Exported',
            'email' => 'exported@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'high',
        ]);

        Lead::create([
            'organization_id' => $organization->id,
            'drive_id' => $otherDrive->id,
            'first_name' => 'WrongDrive',
            'email' => 'wrong-drive@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'high',
        ]);

        Lead::create([
            'organization_id' => $organization->id,
            'drive_id' => $matchingDrive->id,
            'first_name' => 'WrongPriority',
            'email' => 'wrong-priority@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'low',
        ]);

        Lead::create([
            'organization_id' => $otherOrganization->id,
            'drive_id' => $foreignDrive->id,
            'first_name' => 'Foreign',
            'email' => 'foreign@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'high',
        ]);

        $this->actingAs($user);

        $response = app(LeadController::class)->export(
            Request::create('/admin/crm/export', 'GET', [
                'drive' => $matchingDrive->id,
                'priority' => 'high',
            ]),
            app(LeadExportService::class),
        );

        ob_start();
        $response->sendContent();
        $csv = ob_get_clean();

        $this->assertStringContainsString('exported@example.com', $csv);
        $this->assertStringNotContainsString('wrong-drive@example.com', $csv);
        $this->assertStringNotContainsString('wrong-priority@example.com', $csv);
        $this->assertStringNotContainsString('foreign@example.com', $csv);
    }

    public function test_export_includes_extended_crm_columns_and_values(): void
    {
        $organization = $this->createOrganization('Extended Export Org');
        $drive = $this->createDrive($organization, 'LinkedIn Pipeline');
        $user = User::factory()->create(['organization_id' => $organization->id]);

        Lead::create([
            'organization_id' => $organization->id,
            'drive_id' => $drive->id,
            'first_name' => 'Ada',
            'last_name' => 'Founder',
            'email' => 'ada@example.com',
            'phone' => '+2348000000000',
            'company' => 'Founders Hub',
            'job_title' => 'CEO',
            'employee_count' => '11-50',
            'year_founded' => 2020,
            'industry' => 'Technology',
            'website' => 'https://foundershub.test',
            'company_linkedin_profile' => 'https://linkedin.com/company/foundershub',
            'ceo_linkedin_profile' => 'https://linkedin.com/in/ada-founder',
            'country' => 'Nigeria',
            'city' => 'Lagos',
            'address' => '42 Startup Avenue',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'high',
            'estimated_value' => 15000,
            'currency' => 'USD',
            'interested_services' => ['Consulting', 'Web Development'],
            'requirements' => 'Needs CRM implementation',
            'notes' => 'Met at conference',
            'lost_reason' => 'Budget delayed',
        ]);

        $this->actingAs($user);

        $response = app(LeadController::class)->export(
            Request::create('/admin/crm/export', 'GET'),
            app(LeadExportService::class),
        );

        ob_start();
        $response->sendContent();
        $csv = ob_get_clean();

        $this->assertStringContainsString('Company LinkedIn Profile', $csv);
        $this->assertStringContainsString('Contact LinkedIn Profile', $csv);
        $this->assertStringContainsString('Interested Services', $csv);
        $this->assertStringContainsString('Requirements', $csv);
        $this->assertStringContainsString('Lost Reason', $csv);
        $this->assertStringContainsString('https://linkedin.com/company/foundershub', $csv);
        $this->assertStringContainsString('https://linkedin.com/in/ada-founder', $csv);
        $this->assertStringContainsString('Consulting, Web Development', $csv);
        $this->assertStringContainsString('Needs CRM implementation', $csv);
        $this->assertStringContainsString('Budget delayed', $csv);
    }

    public function test_legacy_admin_can_export_specified_organization_only(): void
    {
        $targetOrganization = $this->createOrganization('Target Export Org');
        $otherOrganization = $this->createOrganization('Other Export Org');

        $targetDrive = $this->createDrive($targetOrganization, 'Target Pipeline');
        $otherDrive = $this->createDrive($otherOrganization, 'Other Pipeline');

        $legacyAdmin = User::factory()->create(['organization_id' => null]);
        $legacyAdmin->assignRole('admin');

        Lead::create([
            'organization_id' => $targetOrganization->id,
            'drive_id' => $targetDrive->id,
            'first_name' => 'Target Lead',
            'email' => 'target-export@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'high',
        ]);

        Lead::create([
            'organization_id' => $otherOrganization->id,
            'drive_id' => $otherDrive->id,
            'first_name' => 'Other Lead',
            'email' => 'other-export@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'high',
        ]);

        $this->actingAs($legacyAdmin);

        $response = app(LeadController::class)->export(
            Request::create('/admin/crm/export', 'GET', [
                'organization_id' => $targetOrganization->id,
            ]),
            app(LeadExportService::class),
        );

        ob_start();
        $response->sendContent();
        $csv = ob_get_clean();

        $this->assertStringContainsString('target-export@example.com', $csv);
        $this->assertStringNotContainsString('other-export@example.com', $csv);
    }

    public function test_non_legacy_admin_cannot_export_other_organization(): void
    {
        $adminOrganization = $this->createOrganization('Scoped Admin Org');
        $otherOrganization = $this->createOrganization('Blocked Export Org');

        $orgAdmin = User::factory()->create(['organization_id' => $adminOrganization->id]);
        $orgAdmin->assignRole('admin');

        $this->actingAs($orgAdmin);

        try {
            app(LeadController::class)->export(
                Request::create('/admin/crm/export', 'GET', [
                    'organization_id' => $otherOrganization->id,
                ]),
                app(LeadExportService::class),
            );

            $this->fail('Expected a 403 exception when exporting another organization.');
        } catch (HttpException $exception) {
            $this->assertSame(403, $exception->getStatusCode());
        }
    }

    public function test_migration_export_json_includes_relationship_mappings_and_histories(): void
    {
        $organization = $this->createOrganization('Migration Org');
        $drive = $this->createDrive($organization, 'Migration Pipeline');

        $admin = User::factory()->create(['organization_id' => $organization->id]);
        $admin->assignRole('admin');

        $assignee = User::factory()->create(['organization_id' => $organization->id]);
        $assignee->assignRole('staff');

        $lead = Lead::create([
            'organization_id' => $organization->id,
            'drive_id' => $drive->id,
            'created_by' => $admin->id,
            'assigned_to' => $assignee->id,
            'first_name' => 'Migrate',
            'last_name' => 'Lead',
            'email' => 'migrate@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'high',
            'notes' => 'Important migration lead',
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => $admin->id,
            'type' => 'status_change',
            'title' => 'Status changed',
            'description' => 'Updated from contacted to new',
            'old_value' => 'contacted',
            'new_value' => 'new',
            'metadata' => ['reason' => 'retry'],
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => $assignee->id,
            'type' => 'follow_up',
            'title' => 'Follow up scheduled',
            'description' => 'Call next week',
            'scheduled_at' => now()->addDays(7),
            'is_completed' => false,
        ]);

        LeadEmail::create([
            'lead_id' => $lead->id,
            'user_id' => $admin->id,
            'from_email' => 'admin@example.com',
            'to_email' => 'migrate@example.com',
            'subject' => 'Welcome',
            'body' => 'Welcome body',
            'direction' => 'outgoing',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $this->actingAs($admin);

        $response = app(LeadController::class)->export(
            Request::create('/admin/crm/export', 'GET', [
                'organization_id' => $organization->id,
                'format' => 'migration_json',
            ]),
            app(LeadExportService::class),
        );

        ob_start();
        $response->sendContent();
        $json = ob_get_clean();
        $payload = json_decode($json, true);

        $this->assertIsArray($payload);
        $this->assertSame('crm-leads-migration.v1', $payload['schema_version']);
        $this->assertSame($organization->id, $payload['organization']['id']);
        $this->assertSame(1, $payload['metadata']['lead_count']);
        $this->assertNotEmpty($payload['mappings']['statuses']);
        $this->assertCount(1, $payload['leads']);
        $this->assertSame('migrate@example.com', $payload['leads'][0]['lead']['email']);
        $this->assertSame('Migration Pipeline', $payload['leads'][0]['pipeline']['name']);
        $this->assertSame($assignee->id, $payload['leads'][0]['assigned_user']['id']);
        $this->assertCount(1, $payload['leads'][0]['status_history']);
        $this->assertCount(1, $payload['leads'][0]['tasks_reminders']);
        $this->assertCount(1, $payload['leads'][0]['emails']);
    }

    public function test_migration_export_json_is_organization_scoped(): void
    {
        $organization = $this->createOrganization('Migration Scoped Org');
        $otherOrganization = $this->createOrganization('Migration Other Org');

        $drive = $this->createDrive($organization, 'Scoped Pipeline');
        $otherDrive = $this->createDrive($otherOrganization, 'Other Pipeline');

        $admin = User::factory()->create(['organization_id' => $organization->id]);
        $admin->assignRole('admin');

        Lead::create([
            'organization_id' => $organization->id,
            'drive_id' => $drive->id,
            'first_name' => 'Scoped',
            'email' => 'scoped@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'medium',
        ]);

        Lead::create([
            'organization_id' => $otherOrganization->id,
            'drive_id' => $otherDrive->id,
            'first_name' => 'Foreign',
            'email' => 'foreign-migration@example.com',
            'status' => 'new',
            'source' => 'website',
            'priority' => 'medium',
        ]);

        $this->actingAs($admin);

        $response = app(LeadController::class)->export(
            Request::create('/admin/crm/export', 'GET', [
                'organization_id' => $organization->id,
                'format' => 'migration_json',
            ]),
            app(LeadExportService::class),
        );

        ob_start();
        $response->sendContent();
        $json = ob_get_clean();
        $payload = json_decode($json, true);

        $this->assertCount(1, $payload['leads']);
        $this->assertSame('scoped@example.com', $payload['leads'][0]['lead']['email']);
    }

    public function test_migration_export_requires_specific_organization_context(): void
    {
        $legacyAdmin = User::factory()->create(['organization_id' => null]);
        $legacyAdmin->assignRole('admin');

        $this->actingAs($legacyAdmin);

        try {
            app(LeadController::class)->export(
                Request::create('/admin/crm/export', 'GET', [
                    'format' => 'migration_json',
                ]),
                app(LeadExportService::class),
            );

            $this->fail('Expected a 422 exception when organization_id is omitted for migration export.');
        } catch (HttpException $exception) {
            $this->assertSame(422, $exception->getStatusCode());
        }
    }

    public function test_index_exposes_export_organizations_for_legacy_admin(): void
    {
        $this->createOrganization('Org Alpha');
        $this->createOrganization('Org Beta');

        $legacyAdmin = User::factory()->create(['organization_id' => null]);
        $legacyAdmin->assignRole('admin');

        $this->actingAs($legacyAdmin);

        $response = app(LeadController::class)->index(Request::create('/admin/crm', 'GET'));

        $this->assertSame('admin.crm.index', $response->name());
        $this->assertTrue($response->getData()['canCrossOrgExport']);
        $this->assertCount(2, $response->getData()['exportOrganizations']);
    }

    public function test_index_hides_cross_org_export_options_for_non_legacy_admin(): void
    {
        $organization = $this->createOrganization('Scoped Org');

        $admin = User::factory()->create(['organization_id' => $organization->id]);
        $admin->assignRole('admin');

        $this->actingAs($admin);

        $response = app(LeadController::class)->index(Request::create('/admin/crm', 'GET'));

        $this->assertSame('admin.crm.index', $response->name());
        $this->assertFalse($response->getData()['canCrossOrgExport']);
        $this->assertCount(0, $response->getData()['exportOrganizations']);
    }

    private function createOrganization(string $name): Organization
    {
        return Organization::create([
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::lower((string) Str::uuid())),
        ]);
    }

    private function createDrive(Organization $organization, string $name): LeadDrive
    {
        return LeadDrive::create([
            'organization_id' => $organization->id,
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::lower((string) Str::uuid())),
            'color' => '#0d6efd',
            'is_default' => false,
        ]);
    }
}
