<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\QuickChatController;
use App\Http\Controllers\Admin\WhatsAppController;
use App\Jobs\SendWhatsAppMessageJob;
use App\Models\WhatsAppAccount;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppTemplate;
use App\Models\User;
use App\Services\WhatsApp\MessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WhatsAppDirectTemplateMessagingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'broadcasting.default' => 'log',
            'session.driver' => 'array',
            'whatsapp.enabled' => true,
            'whatsapp.queue' => true,
        ]);
    }

    public function test_existing_conversation_template_send_with_variables_is_queued(): void
    {
        Queue::fake();

        $admin = $this->createAdminUser();

        $contact = WhatsAppContact::create([
            'name' => 'Existing Contact',
            'phone' => '+2348011111111',
        ]);

        $conversation = WhatsAppConversation::create([
            'contact_id' => $contact->id,
            'status' => 'open',
        ]);

        $template = WhatsAppTemplate::create([
            'name' => 'outreach_welcome',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'parameter_format' => 'NAMED',
                'components' => [
                    [
                        'type' => 'BODY',
                        'text' => 'Hello {{customer_name}}, welcome to {{company_name}}.',
                    ],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $request = Request::create('/admin/whatsapp/conversations/' . $conversation->id . '/messages', 'POST', [
            'type' => 'template',
            'template_id' => $template->id,
            'template_variables' => [
                'customer_name' => 'John',
                'company_name' => 'P23 Africa',
            ],
        ]);
        $request->headers->set('Accept', 'application/json');
        $request->setUserResolver(fn() => $admin);

        $response = app(WhatsAppController::class)->sendMessage($request, $conversation, app(MessageService::class));

        $this->assertSame(200, $response->getStatusCode());

        $message = $conversation->messages()->latest('id')->firstOrFail();
        $this->assertSame('template', $message->type);
        $this->assertSame('queued', $message->status);
        $this->assertSame('John', data_get($message->payload, 'template_variables.customer_name'));
        $this->assertSame('P23 Africa', data_get($message->payload, 'template_variables.company_name'));

        Queue::assertPushed(SendWhatsAppMessageJob::class);
    }

    public function test_existing_conversation_template_send_rejects_missing_required_variables(): void
    {
        Queue::fake();

        $admin = $this->createAdminUser();

        $contact = WhatsAppContact::create([
            'name' => 'Existing Contact',
            'phone' => '+2348022222222',
        ]);

        $conversation = WhatsAppConversation::create([
            'contact_id' => $contact->id,
            'status' => 'open',
        ]);

        $template = WhatsAppTemplate::create([
            'name' => 'outreach_required_var',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'parameter_format' => 'NAMED',
                'components' => [
                    [
                        'type' => 'BODY',
                        'text' => 'Hello {{customer_name}}',
                    ],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $request = Request::create('/admin/whatsapp/conversations/' . $conversation->id . '/messages', 'POST', [
            'type' => 'template',
            'template_id' => $template->id,
            'template_variables' => [],
        ]);
        $request->headers->set('Accept', 'application/json');
        $request->setUserResolver(fn() => $admin);

        $this->expectException(ValidationException::class);

        app(WhatsAppController::class)->sendMessage($request, $conversation, app(MessageService::class));
    }

    public function test_quick_chat_templates_endpoint_returns_only_approved_templates(): void
    {
        $admin = $this->createAdminUser();

        $approved = WhatsAppTemplate::create([
            'name' => 'approved_template',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => ['components' => [['type' => 'BODY', 'text' => 'Hi {{1}}']]],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        WhatsAppTemplate::create([
            'name' => 'pending_template',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => ['components' => [['type' => 'BODY', 'text' => 'Pending']]],
            'status' => WhatsAppTemplate::STATUS_PENDING,
        ]);

        WhatsAppTemplate::create([
            'name' => 'disabled_template',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => ['components' => [['type' => 'BODY', 'text' => 'Disabled']]],
            'status' => WhatsAppTemplate::STATUS_DISABLED,
        ]);

        $request = Request::create('/admin/whatsapp/quick-chat/templates', 'GET');
        $request->headers->set('Accept', 'application/json');
        $request->setUserResolver(fn() => $admin);

        $response = app(QuickChatController::class)->approvedTemplates($request);
        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($payload['success']);
        $this->assertCount(1, $payload['data']['templates']);
        $this->assertSame($approved->id, $payload['data']['templates'][0]['id']);
    }

    public function test_first_outreach_template_send_creates_contact_conversation_and_message(): void
    {
        Queue::fake();

        $admin = $this->createAdminUser();
        $this->createWhatsAppAccount();

        Http::fake([
            'https://graph.facebook.com/*/contacts' => Http::response([
                'contacts' => [
                    [
                        'input' => '2348033333333',
                        'wa_id' => '2348033333333',
                        'status' => 'valid',
                    ],
                ],
            ], 200),
        ]);

        $template = WhatsAppTemplate::create([
            'name' => 'first_outreach_template',
            'category' => 'marketing',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'parameter_format' => 'NAMED',
                'components' => [
                    [
                        'type' => 'BODY',
                        'text' => 'Hello {{customer_name}}, we invite you to {{event_name}}.',
                    ],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $request = Request::create('/admin/whatsapp/quick-chat/start-template', 'POST', [
            'phone_number' => '+2348033333333',
            'country_code' => '234',
            'name' => 'Outreach User',
            'template_id' => $template->id,
            'template_variables' => [
                'customer_name' => 'John',
                'event_name' => 'Business Summit',
            ],
        ]);
        $request->headers->set('Accept', 'application/json');
        $request->setUserResolver(fn() => $admin);

        $response = app(QuickChatController::class)->startTemplate($request);
        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($payload['success']);

        $this->assertDatabaseHas('whatsapp_contacts', [
            'phone' => '+2348033333333',
            'validation_status' => 'valid',
        ]);

        $conversation = WhatsAppConversation::query()->firstOrFail();
        $this->assertSame($conversation->id, $payload['data']['conversation_id']);

        $this->assertDatabaseHas('whatsapp_messages', [
            'id' => $payload['data']['message_id'],
            'conversation_id' => $conversation->id,
            'type' => 'template',
            'status' => 'queued',
        ]);

        Queue::assertPushed(SendWhatsAppMessageJob::class);
    }

    public function test_first_outreach_template_send_does_not_create_conversation_when_number_is_not_whatsapp(): void
    {
        Queue::fake();

        $admin = $this->createAdminUser();
        $this->createWhatsAppAccount();

        Http::fake([
            'https://graph.facebook.com/*/contacts' => Http::response([
                'contacts' => [
                    [
                        'input' => '2348044444444',
                        'wa_id' => null,
                        'status' => 'invalid',
                    ],
                ],
            ], 200),
        ]);

        $template = WhatsAppTemplate::create([
            'name' => 'invalid_outreach_template',
            'category' => 'marketing',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'components' => [
                    [
                        'type' => 'BODY',
                        'text' => 'Hello {{1}}',
                    ],
                ],
            ],
            'status' => WhatsAppTemplate::STATUS_APPROVED,
        ]);

        $request = Request::create('/admin/whatsapp/quick-chat/start-template', 'POST', [
            'phone_number' => '+2348044444444',
            'country_code' => '234',
            'name' => 'Invalid Recipient',
            'template_id' => $template->id,
            'template_variables' => [
                '1' => 'John',
            ],
        ]);
        $request->headers->set('Accept', 'application/json');
        $request->setUserResolver(fn() => $admin);

        $response = app(QuickChatController::class)->startTemplate($request);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertSame(0, WhatsAppConversation::query()->count());
        $this->assertSame(0, WhatsAppContact::query()->count());
        $this->assertSame(0, \App\Models\WhatsAppMessage::query()->count());
        Queue::assertNothingPushed();
    }

    protected function createAdminUser(): User
    {
        Role::findOrCreate('admin');

        $admin = User::factory()->create([
            'organization_id' => null,
        ]);
        $admin->assignRole('admin');

        return $admin;
    }

    protected function createWhatsAppAccount(): WhatsAppAccount
    {
        return WhatsAppAccount::create([
            'business_name' => 'Template Outreach WA',
            'phone_number' => '2348000000000',
            'phone_number_id' => '991122334455',
            'waba_id' => '1122334455667788',
            'access_token' => 'template-outreach-token',
            'webhook_verify_token' => 'verify-token',
            'status' => 'active',
        ]);
    }
}
