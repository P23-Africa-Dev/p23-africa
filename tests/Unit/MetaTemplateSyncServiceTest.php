<?php

namespace Tests\Unit;

use App\Models\WhatsAppAccount;
use App\Services\WhatsApp\MetaTemplateSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MetaTemplateSyncServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_paginates_and_normalizes_statuses(): void
    {
        $account = WhatsAppAccount::create([
            'business_name' => 'WA Sync',
            'phone_number' => '233500000111',
            'phone_number_id' => '100200300',
            'waba_id' => '9988776655',
            'access_token' => 'wa-token',
            'webhook_verify_token' => 'verify-token',
            'status' => 'active',
        ]);

        Http::fakeSequence()
            ->push([
                'data' => [
                    [
                        'name' => 'welcome_template',
                        'status' => 'APPROVED',
                        'category' => 'UTILITY',
                        'language' => 'en',
                    ],
                    [
                        'name' => 'hold_template',
                        'status' => 'PENDING',
                        'category' => 'MARKETING',
                        'language' => 'en',
                    ],
                ],
                'paging' => [
                    'cursors' => [
                        'after' => 'cursor-1',
                    ],
                ],
            ], 200)
            ->push([
                'data' => [
                    [
                        'name' => 'reject_template',
                        'status' => 'REJECTED',
                        'category' => 'UTILITY',
                        'language' => 'en_GB',
                    ],
                ],
            ], 200);

        $result = app(MetaTemplateSyncService::class)->syncTemplates($account->id, false, false, 100);

        $this->assertSame(2, $result['pages']);
        $this->assertSame(3, $result['fetched']);
        $this->assertSame(3, $result['upserted']);
        $this->assertSame(0, $result['failed']);

        $this->assertDatabaseHas('whatsapp_templates', [
            'name' => 'welcome_template',
            'language' => 'en',
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('whatsapp_templates', [
            'name' => 'hold_template',
            'language' => 'en',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('whatsapp_templates', [
            'name' => 'reject_template',
            'language' => 'en_GB',
            'status' => 'rejected',
        ]);
    }

    public function test_sync_is_idempotent_for_same_name_and_language(): void
    {
        $account = WhatsAppAccount::create([
            'business_name' => 'WA Sync',
            'phone_number' => '233500000222',
            'phone_number_id' => '200300400',
            'waba_id' => '8877665544',
            'access_token' => 'wa-token',
            'webhook_verify_token' => 'verify-token',
            'status' => 'active',
        ]);

        Http::fake([
            '*' => Http::response([
                'data' => [
                    [
                        'name' => 'same_template',
                        'status' => 'PENDING',
                        'category' => 'UTILITY',
                        'language' => 'en',
                    ],
                ],
            ], 200),
        ]);

        app(MetaTemplateSyncService::class)->syncTemplates($account->id, false, false, 100);

        Http::fake([
            '*' => Http::response([
                'data' => [
                    [
                        'name' => 'same_template',
                        'status' => 'APPROVED',
                        'category' => 'UTILITY',
                        'language' => 'en',
                    ],
                ],
            ], 200),
        ]);

        app(MetaTemplateSyncService::class)->syncTemplates($account->id, false, false, 100);

        $this->assertDatabaseCount('whatsapp_templates', 1);

        $template = \App\Models\WhatsAppTemplate::query()
            ->where('name', 'same_template')
            ->where('language', 'en')
            ->firstOrFail();

        $this->assertContains($template->status, ['pending', 'approved']);
    }
}
