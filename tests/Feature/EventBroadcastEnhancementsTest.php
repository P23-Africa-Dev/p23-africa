<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use App\Models\WhatsAppAccount;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppTemplate;
use App\Services\WhatsApp\EventWhatsAppService;
use App\Services\WhatsApp\MetaTemplateParameterService;
use App\Services\WhatsApp\MessageService;
use App\Services\WhatsApp\WhatsAppNumberValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class EventBroadcastEnhancementsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'broadcasting.default' => 'log',
            'session.driver' => 'array',
            'whatsapp.enabled' => true,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Role::findOrCreate('admin');
    }

    public function test_audience_count_endpoint_returns_json_for_manual_source(): void
    {
        $organization = Organization::factory()->create();
        $admin = User::factory()->create(['organization_id' => $organization->id]);
        $admin->assignRole('admin');

        WhatsAppAccount::create([
            'business_name' => 'Count Test WA',
            'phone_number' => '233700111222',
            'phone_number_id' => '50001111222',
            'waba_id' => '60001112223333',
            'access_token' => 'count-token',
            'webhook_verify_token' => 'count-verify',
            'status' => 'active',
        ]);

        Http::fake([
            '*contacts*' => Http::response(['contacts' => [['status' => 'valid', 'wa_id' => '2348012345678']]], 200),
            '*' => Http::response([], 200),
        ]);

        $this->actingAs($admin)
            ->post(orgRoute('admin.whatsapp.events.audience-count'), [
                'source_type' => 'manual_contacts',
                'manual_contacts' => "+2348012345678\n+2348098765432",
            ], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonStructure(['success', 'data' => ['total', 'source_type']]);
    }

    public function test_event_whatsapp_service_preview_audience_count_returns_zero_for_empty_manual_source(): void
    {
        $result = app(EventWhatsAppService::class)->previewAudienceCount([
            'source_type' => 'manual_contacts',
            'manual_contacts' => '',
        ]);

        $this->assertSame(0, $result['total']);
        $this->assertSame('manual_contacts', $result['source_type']);
    }

    public function test_audience_count_endpoint_accepts_manual_contacts_when_meta_validation_is_pending(): void
    {
        $organization = Organization::factory()->create();
        $admin = User::factory()->create(['organization_id' => $organization->id]);
        $admin->assignRole('admin');

        WhatsAppAccount::create([
            'business_name' => 'Pending Validation WA',
            'phone_number' => '233700111223',
            'phone_number_id' => '50001111223',
            'waba_id' => '60001112223334',
            'access_token' => 'pending-token',
            'webhook_verify_token' => 'pending-verify',
            'status' => 'active',
        ]);

        // Simulate Meta contacts endpoint being unsupported/unavailable.
        Http::fake([
            '*contacts*' => Http::response(['error' => ['message' => 'Unsupported endpoint']], 400),
            '*' => Http::response([], 200),
        ]);

        $this->actingAs($admin)
            ->post(orgRoute('admin.whatsapp.events.audience-count'), [
                'source_type' => 'manual_contacts',
                'manual_contacts' => '+2348012345678',
            ], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonPath('data.total', 1)
            ->assertJsonPath('data.source_type', 'manual_contacts');
    }

    public function test_send_broadcast_accepts_selected_saved_contacts_with_pending_validation_status(): void
    {
        $organization = Organization::factory()->create();
        $admin = User::factory()->create(['organization_id' => $organization->id]);
        $admin->assignRole('admin');

        $template = WhatsAppTemplate::create([
            'name' => 'saved_contact_test_template',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'components' => [
                    ['type' => 'BODY', 'text' => 'Hello {{1}}'],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $contact = WhatsAppContact::create([
            'name' => 'Pending Contact',
            'phone' => '+2348012345678',
            'is_whatsapp' => null,
            'validation_status' => WhatsAppNumberValidationService::STATUS_PENDING,
            'validation_cache_until' => now()->addHours(4),
            'unsaved_contact' => false,
        ]);

        $response = $this->actingAs($admin)
            ->post(orgRoute('admin.whatsapp.events.send-broadcast'), [
                'source_type' => 'saved_contacts',
                'saved_contact_ids' => [$contact->id],
                'template_id' => $template->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_whatsapp_campaigns', [
            'template_id' => $template->id,
            'source_type' => 'saved_contacts',
            'total_contacts' => 1,
        ]);
    }

    public function test_template_update_ignores_user_provided_status_field(): void
    {
        $organization = Organization::factory()->create();
        $admin = User::factory()->create(['organization_id' => $organization->id]);
        $admin->assignRole('admin');

        $template = WhatsAppTemplate::create([
            'name' => 'meta_status_locked_template',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'components' => [
                    ['type' => 'BODY', 'text' => 'Hello {{1}}'],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $this->actingAs($admin)
            ->put(orgRoute('admin.whatsapp.templates.update', ['template' => $template->id]), [
                'name' => $template->name,
                'category' => $template->category,
                'language' => $template->language,
                'status' => WhatsAppTemplate::STATUS_DRAFT,
                'template_format' => 'json',
                'template_json' => json_encode([
                    'components' => [
                        ['type' => 'BODY', 'text' => 'Updated {{1}}'],
                    ],
                ]),
            ])
            ->assertRedirect();

        $template->refresh();
        $this->assertSame(WhatsAppTemplate::STATUS_APPROVED, $template->status);
    }

    public function test_event_invitation_preview_uses_event_registration_link(): void
    {
        $template = WhatsAppTemplate::create([
            'name' => 'event_invitation',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'components' => [
                    ['type' => 'BODY', 'text' => 'Body'],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $preview = app(MetaTemplateParameterService::class)->buildPreview($template, [
            'name' => 'John Mike',
            'event_title' => 'How to win the Africa Market',
            'event_location' => 'Location/Online',
            'event_registration_link' => 'https://p23africa.com/events/knowing-your-customer-beyond-everybody',
        ]);

        $this->assertStringContainsString('Hello John Mike,', $preview);
        $this->assertStringContainsString('Event: How to win the Africa Market', $preview);
        $this->assertStringContainsString('Location: Location/Online', $preview);
        $this->assertStringContainsString('Register Here: https://p23africa.com/events/knowing-your-customer-beyond-everybody', $preview);
    }

    public function test_event_invitation_payload_maps_header_body_and_button_variables(): void
    {
        $template = WhatsAppTemplate::create([
            'name' => 'event_invitation',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'TEXT',
                        'text' => 'Hello {{1}},',
                    ],
                    [
                        'type' => 'BODY',
                        'text' => 'Event: {{1}} | Location: {{2}} | Register: {{3}}',
                    ],
                    [
                        'type' => 'BUTTONS',
                        'buttons' => [
                            [
                                'type' => 'URL',
                                'text' => 'Register Now',
                                'url' => 'https://p23africa.com/events/{{1}}',
                            ],
                        ],
                    ],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $payload = app(MetaTemplateParameterService::class)->buildPayload($template, [
            'name' => 'John Mike',
            'event_title' => 'How to win the Africa Market',
            'event_location' => 'Location/Online',
            'event_registration_link' => 'https://p23africa.com/events/knowing-your-customer-beyond-everybody',
        ]);

        $components = collect($payload['components'] ?? []);

        $header = $components->firstWhere('type', 'header');
        $body = $components->firstWhere('type', 'body');
        $button = $components->firstWhere('type', 'button');

        $this->assertNotNull($header);
        $this->assertNotNull($body);
        $this->assertNotNull($button);

        $this->assertSame('John Mike', data_get($header, 'parameters.0.text'));
        $this->assertSame('How to win the Africa Market', data_get($body, 'parameters.0.text'));
        $this->assertSame('Location/Online', data_get($body, 'parameters.1.text'));
        $this->assertSame(
            'https://p23africa.com/events/knowing-your-customer-beyond-everybody',
            data_get($body, 'parameters.2.text')
        );
        $this->assertSame('knowing-your-customer-beyond-everybody', data_get($button, 'parameters.0.text'));
    }

    public function test_event_invitation_payload_maps_exact_numbered_header_and_body_format(): void
    {
        $template = WhatsAppTemplate::create([
            'name' => 'event_invitation',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'TEXT',
                        'text' => 'Hello {{1}},',
                    ],
                    [
                        'type' => 'BODY',
                        'text' => "P23 Africa is excited to invite you to join our upcoming event.\n\n📅 Event: {{1}}\n📍 Location: {{2}}\n🔗 Register Here: {{3}}",
                    ],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $payload = app(MetaTemplateParameterService::class)->buildPayload($template, [
            'name' => 'John Mike',
            'event_title' => 'How to win the Africa Market',
            'event_location' => 'Location/Online',
            'event_registration_link' => 'https://p23africa.com/events/knowing-your-customer-beyond-everybody',
        ]);

        $components = collect($payload['components'] ?? []);
        $header = $components->firstWhere('type', 'header');
        $body = $components->firstWhere('type', 'body');

        $this->assertNotNull($header);
        $this->assertNotNull($body);
        $this->assertSame('John Mike', data_get($header, 'parameters.0.text'));
        $this->assertSame('How to win the Africa Market', data_get($body, 'parameters.0.text'));
        $this->assertSame('Location/Online', data_get($body, 'parameters.1.text'));
        $this->assertSame(
            'https://p23africa.com/events/knowing-your-customer-beyond-everybody',
            data_get($body, 'parameters.2.text')
        );
    }

    public function test_submit_to_meta_update_mode_creates_revision_template_name(): void
    {
        $organization = Organization::factory()->create();
        $admin = User::factory()->create(['organization_id' => $organization->id]);
        $admin->assignRole('admin');

        WhatsAppAccount::create([
            'business_name' => 'Template Update WA',
            'phone_number' => '233700111224',
            'phone_number_id' => '50001111224',
            'waba_id' => '60001112223335',
            'access_token' => 'template-update-token',
            'webhook_verify_token' => 'template-update-verify',
            'status' => 'active',
        ]);

        WhatsAppTemplate::create([
            'name' => 'event_invitation',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'components' => [
                    ['type' => 'BODY', 'text' => 'Old body {{1}}'],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        Http::fake([
            '*message_templates*' => Http::response([
                'id' => 'meta-template-revision-id',
                'status' => 'PENDING',
            ], 200),
            '*' => Http::response([], 200),
        ]);

        $this->actingAs($admin)
            ->post(orgRoute('admin.whatsapp.templates.submit-to-meta'), [
                'name' => 'event_invitation',
                'category' => 'UTILITY',
                'language' => 'en',
                'mode' => 'update',
                'components' => json_encode([
                    [
                        'type' => 'BODY',
                        'text' => 'Updated body {{1}}',
                    ],
                ]),
            ], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.mode', 'update')
            ->assertJsonPath('data.base_name', 'event_invitation')
            ->assertJsonPath('data.effective_name', 'event_invitation_v2');

        $this->assertDatabaseHas('whatsapp_templates', [
            'name' => 'event_invitation_v2',
            'language' => 'en',
            'status' => WhatsAppTemplate::STATUS_PENDING,
        ]);
    }

    public function test_versioned_event_invitation_template_still_maps_meta_parameters(): void
    {
        $template = WhatsAppTemplate::create([
            'name' => 'event_invitation_v2',
            'category' => 'marketing',
            'language' => 'en_US',
            'template_format' => 'json',
            'template_json' => [
                'language' => 'en_US',
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'TEXT',
                        'text' => 'Hello {{1}},',
                    ],
                    [
                        'type' => 'BODY',
                        'text' => 'Event: {{1}} | Location: {{2}} | Register Here: {{3}}',
                    ],
                    [
                        'type' => 'BUTTONS',
                        'buttons' => [
                            [
                                'type' => 'URL',
                                'text' => 'Register Now',
                                'url' => 'https://p23africa.com/events/{{1}}',
                            ],
                        ],
                    ],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $payload = app(MetaTemplateParameterService::class)->buildPayload($template, [
            'name' => 'John Mike',
            'event_title' => 'How to win the Africa Market',
            'event_location' => 'Location/Online',
            'event_registration_link' => 'https://p23africa.com/events/knowing-your-customer-beyond-everybody',
        ]);

        $components = collect($payload['components'] ?? []);
        $button = $components->firstWhere('type', 'button');

        $this->assertSame('event_invitation_v2', data_get($payload, 'name'));
        $this->assertSame('en_US', data_get($payload, 'language.code'));
        $this->assertSame('knowing-your-customer-beyond-everybody', data_get($button, 'parameters.0.text'));
    }

    public function test_send_broadcast_rejects_non_approved_template_for_new_campaign(): void
    {
        $organization = Organization::factory()->create();
        $admin = User::factory()->create(['organization_id' => $organization->id]);
        $admin->assignRole('admin');

        $pendingBase = WhatsAppTemplate::create([
            'name' => 'event_invitation',
            'category' => 'marketing',
            'language' => 'en_US',
            'template_format' => 'json',
            'template_json' => [
                'language' => 'en_US',
                'components' => [
                    ['type' => 'BODY', 'text' => 'Base {{1}}'],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_PENDING,
        ]);

        WhatsAppTemplate::create([
            'name' => 'event_invitation_v2',
            'category' => 'marketing',
            'language' => 'en_US',
            'template_format' => 'json',
            'template_json' => [
                'language' => 'en_US',
                'components' => [
                    ['type' => 'BODY', 'text' => 'Revision {{1}}'],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $contact = WhatsAppContact::create([
            'name' => 'Pending Contact',
            'phone' => '+2348012345699',
            'is_whatsapp' => true,
            'validation_status' => WhatsAppNumberValidationService::STATUS_VALID,
            'validation_cache_until' => now()->addHours(4),
            'unsaved_contact' => false,
        ]);

        // Audience validation endpoint fallback can return unsupported in some environments;
        // this test is about template approval guard during campaign creation.
        Http::fake([
            '*contacts*' => Http::response(['error' => ['message' => 'Unsupported endpoint']], 400),
            '*' => Http::response([], 200),
        ]);

        $response = $this->actingAs($admin)
            ->post(orgRoute('admin.whatsapp.events.send-broadcast'), [
                'source_type' => 'saved_contacts',
                'saved_contact_ids' => [$contact->id],
                // Simulate stale UI/campaign payload using base template id.
                'template_id' => $pendingBase->id,
            ]);

        // Controller still validates template_id as approved for new broadcasts.
        // This test confirms the guard remains active and prevents bad queueing.
        $response->assertSessionHasErrors('template_id');
    }

    public function test_message_service_resolves_pending_base_template_to_approved_revision(): void
    {
        $pendingBase = WhatsAppTemplate::create([
            'name' => 'event_invitation',
            'category' => 'marketing',
            'language' => 'en_US',
            'template_format' => 'json',
            'template_json' => ['language' => 'en_US'],
            'status' => WhatsAppTemplate::STATUS_PENDING,
        ]);

        $approvedRevision = WhatsAppTemplate::create([
            'name' => 'event_invitation_v2',
            'category' => 'marketing',
            'language' => 'en_US',
            'template_format' => 'json',
            'template_json' => ['language' => 'en_US'],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $service = app(MessageService::class);
        $method = new \ReflectionMethod($service, 'resolveSendTemplate');
        $method->setAccessible(true);

        /** @var WhatsAppTemplate $resolved */
        $resolved = $method->invoke($service, $pendingBase);

        $this->assertSame($approvedRevision->id, $resolved->id);
        $this->assertSame('event_invitation_v2', $resolved->name);
    }

    public function test_event_contacts_page_uses_admin_route_path_for_audience_pagination_links(): void
    {
        $organization = Organization::factory()->create();
        $admin = User::factory()->create(['organization_id' => $organization->id]);
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)
            ->get(orgRoute('admin.whatsapp.events.contacts', ['audience_page' => 2]));

        $response->assertOk();
        $response->assertSee('/whatsapp/events/contacts', false);
        $response->assertDontSee('href="/\?audience_page=', false);
        $response->assertSee('data-event-audience-filter-form', false);
    }
}
