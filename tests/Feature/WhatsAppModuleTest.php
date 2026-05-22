<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\WhatsAppController;
use App\Http\Controllers\Api\WhatsAppWebhookController;
use App\Jobs\SendWhatsAppMessageJob;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use App\Models\User;
use App\Services\WhatsApp\MessageService;
use App\Services\WhatsApp\MessageStatusSyncService;
use App\Services\WhatsApp\WebhookService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class WhatsAppModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'broadcasting.default' => 'log',
        ]);
    }

    public function test_webhook_verification_returns_challenge_for_valid_token(): void
    {
        config(['whatsapp.verify_token' => 'verify-123']);

        $request = Request::create('/api/whatsapp/webhook', 'GET', [
            'hub.mode' => 'subscribe',
            'hub.verify_token' => 'verify-123',
            'hub.challenge' => 'abc123',
        ]);

        $response = app(WhatsAppWebhookController::class)->verify($request, app(WebhookService::class));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('abc123', $response->getContent());
    }

    public function test_webhook_post_persists_incoming_message_and_conversation(): void
    {
        config(['whatsapp.webhook_secret' => '']);

        $payload = [
            'entry' => [[
                'changes' => [[
                    'field' => 'messages',
                    'value' => [
                        'contacts' => [[
                            'wa_id' => '15551234567',
                            'profile' => [
                                'name' => 'John Doe',
                            ],
                        ]],
                        'messages' => [[
                            'from' => '15551234567',
                            'id' => 'wamid.inbound.1',
                            'timestamp' => (string) now()->timestamp,
                            'type' => 'text',
                            'text' => [
                                'body' => 'Hello from webhook',
                            ],
                        ]],
                    ],
                ]],
            ]],
        ];

        $request = Request::create(
            '/api/whatsapp/webhook',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $request->headers->set('Accept', 'application/json');

        $response = app(WhatsAppWebhookController::class)->receive($request, app(WebhookService::class));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue((bool) $response->getData(true)['success']);

        $this->assertDatabaseHas('whatsapp_contacts', [
            'phone' => '15551234567',
            'name' => 'John Doe',
        ]);

        $contact = WhatsAppContact::where('phone', '15551234567')->firstOrFail();
        $conversation = WhatsAppConversation::where('contact_id', $contact->id)->firstOrFail();

        $this->assertDatabaseHas('whatsapp_messages', [
            'conversation_id' => $conversation->id,
            'whatsapp_message_id' => 'wamid.inbound.1',
            'sender_type' => 'contact',
            'status' => 'received',
        ]);

        $this->assertDatabaseHas('whatsapp_webhook_logs', [
            'event_type' => 'messages',
            'status' => 'processed',
        ]);
    }

    public function test_webhook_status_event_updates_existing_message(): void
    {
        config(['whatsapp.webhook_secret' => '']);

        $contact = WhatsAppContact::create([
            'name' => 'Status Contact',
            'phone' => '233500000000',
        ]);

        $conversation = WhatsAppConversation::create([
            'contact_id' => $contact->id,
            'status' => 'open',
        ]);

        $message = WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'whatsapp_message_id' => 'wamid.outgoing.1',
            'sender_type' => 'admin',
            'type' => 'text',
            'body' => 'Ping',
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        $payload = [
            'entry' => [[
                'changes' => [[
                    'field' => 'messages',
                    'value' => [
                        'statuses' => [[
                            'id' => 'wamid.outgoing.1',
                            'status' => 'read',
                            'timestamp' => (string) now()->timestamp,
                        ]],
                    ],
                ]],
            ]],
        ];

        $request = Request::create(
            '/api/whatsapp/webhook',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $request->headers->set('Accept', 'application/json');

        $response = app(WhatsAppWebhookController::class)->receive($request, app(WebhookService::class));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue((bool) $response->getData(true)['success']);

        $this->assertDatabaseHas('whatsapp_messages', [
            'id' => $message->id,
            'status' => 'read',
        ]);
    }

    public function test_queue_text_message_dispatches_job_when_queue_enabled(): void
    {
        Queue::fake();

        config([
            'whatsapp.enabled' => true,
            'whatsapp.queue' => true,
        ]);

        $contact = WhatsAppContact::create([
            'name' => 'Queue Contact',
            'phone' => '2348012345678',
        ]);

        $conversation = WhatsAppConversation::create([
            'contact_id' => $contact->id,
            'status' => 'open',
        ]);

        $message = app(MessageService::class)->queueTextMessage($conversation, 1, 'Queued message');

        $this->assertDatabaseHas('whatsapp_messages', [
            'id' => $message->id,
            'status' => 'queued',
        ]);

        Queue::assertPushed(SendWhatsAppMessageJob::class);
    }

    public function test_send_message_returns_ajax_json_and_queues_message_without_reload(): void
    {
        Queue::fake();

        config([
            'whatsapp.enabled' => true,
            'whatsapp.queue' => true,
        ]);

        Role::findOrCreate('admin');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $contact = WhatsAppContact::create([
            'name' => 'Ajax Contact',
            'phone' => '233500111222',
        ]);

        $conversation = WhatsAppConversation::create([
            'contact_id' => $contact->id,
            'status' => 'open',
        ]);

        $request = Request::create(
            '/admin/whatsapp/conversations/' . $conversation->id . '/messages',
            'POST',
            [
                'type' => 'text',
                'body' => 'Hello from AJAX',
            ]
        );
        $request->headers->set('Accept', 'application/json');
        $request->setUserResolver(fn() => $admin);

        $response = app(WhatsAppController::class)->sendMessage($request, $conversation, app(MessageService::class));

        $this->assertSame(200, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertTrue($payload['success']);
        $this->assertSame('queued', $payload['data']['status']);
        $this->assertSame('Hello from AJAX', $payload['data']['body']);

        $this->assertDatabaseHas('whatsapp_messages', [
            'conversation_id' => $conversation->id,
            'sender_type' => 'admin',
            'body' => 'Hello from AJAX',
            'status' => 'queued',
        ]);

        Queue::assertPushed(SendWhatsAppMessageJob::class);
    }

    public function test_active_conversation_poll_returns_only_delta_messages(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-22 10:00:00'));

        $contact = WhatsAppContact::create([
            'name' => 'Poll Contact',
            'phone' => '233500333444',
        ]);

        $conversation = WhatsAppConversation::create([
            'contact_id' => $contact->id,
            'status' => 'open',
        ]);

        WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'contact',
            'type' => 'text',
            'body' => 'Old message',
            'status' => 'received',
            'sent_at' => now(),
        ]);

        Carbon::setTestNow(Carbon::parse('2026-05-22 10:05:00'));

        $newMessage = WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'contact',
            'type' => 'text',
            'body' => 'Fresh delta',
            'status' => 'received',
            'sent_at' => now(),
        ]);

        Carbon::setTestNow(Carbon::parse('2026-05-22 10:04:30'));

        $request = Request::create('/admin/whatsapp/poll', 'GET', [
            'conversation_id' => $conversation->id,
            'since' => Carbon::parse('2026-05-22 10:04:00')->toIso8601String(),
        ]);
        $request->headers->set('Accept', 'application/json');

        $response = app(WhatsAppController::class)->poll($request);

        Carbon::setTestNow();

        $this->assertSame(200, $response->getStatusCode());

        $payload = $response->getData(true);
        $this->assertTrue($payload['success']);
        $this->assertSame($conversation->id, $payload['conversation']['id']);
        $this->assertCount(1, $payload['messages']);
        $this->assertSame($newMessage->id, $payload['messages'][0]['id']);
        $this->assertSame('Fresh delta', $payload['messages'][0]['body']);
    }

    public function test_status_poll_returns_only_updated_status_rows(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-22 11:00:00'));

        $contact = WhatsAppContact::create([
            'name' => 'Status Poll Contact',
            'phone' => '233500777999',
        ]);

        $conversation = WhatsAppConversation::create([
            'contact_id' => $contact->id,
            'status' => 'open',
        ]);

        WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'contact',
            'type' => 'text',
            'body' => 'Old queued',
            'status' => 'queued',
            'updated_at' => Carbon::parse('2026-05-22 10:50:00'),
            'created_at' => Carbon::parse('2026-05-22 10:50:00'),
        ]);

        $latest = WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'admin',
            'type' => 'text',
            'body' => 'Delivered now',
            'status' => 'delivered',
            'updated_at' => Carbon::parse('2026-05-22 11:00:30'),
            'created_at' => Carbon::parse('2026-05-22 11:00:00'),
        ]);

        $request = Request::create('/admin/whatsapp/status-poll', 'GET', [
            'conversation_id' => $conversation->id,
            'since' => Carbon::parse('2026-05-22 10:59:00')->toIso8601String(),
        ]);
        $request->headers->set('Accept', 'application/json');

        $response = app(WhatsAppController::class)->statusPoll($request, app(MessageStatusSyncService::class));

        Carbon::setTestNow();

        $this->assertSame(200, $response->getStatusCode());

        $payload = $response->getData(true);
        $this->assertTrue($payload['success']);
        $this->assertSame($conversation->id, $payload['conversation_id']);
        $this->assertCount(1, $payload['statuses']);
        $this->assertSame($latest->id, $payload['statuses'][0]['message_id']);
        $this->assertSame('delivered', $payload['statuses'][0]['status']);
        $this->assertFalse((bool) $latest->fresh()->status_dirty);
    }

    public function test_process_outgoing_message_calls_graph_api_and_updates_status(): void
    {
        config([
            'whatsapp.enabled' => true,
            'whatsapp.queue' => false,
            'whatsapp.phone_number_id' => '123456789',
            'whatsapp.access_token' => 'token-123',
            'whatsapp.base_url' => 'https://graph.facebook.com',
            'whatsapp.graph_version' => 'v23.0',
        ]);

        Http::fake([
            'https://graph.facebook.com/*' => Http::response([
                'messages' => [
                    ['id' => 'wamid.sent.1'],
                ],
            ], 200),
        ]);

        $contact = WhatsAppContact::create([
            'name' => 'Send Contact',
            'phone' => '254700123456',
        ]);

        $conversation = WhatsAppConversation::create([
            'contact_id' => $contact->id,
            'status' => 'open',
        ]);

        $message = WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'admin',
            'type' => 'text',
            'body' => 'Send now',
            'status' => 'queued',
        ]);

        $processed = app(MessageService::class)->processOutgoingMessage($message);

        $this->assertSame('sent', $processed->status);
        $this->assertSame('wamid.sent.1', $processed->whatsapp_message_id);
        $this->assertNotNull($processed->sent_at);
        $this->assertTrue((bool) $processed->status_dirty);
        $this->assertNotNull($processed->status_updated_at);
    }
}
