<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\WhatsAppWebhookController;
use App\Jobs\SendWhatsAppMessageJob;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\MessageService;
use App\Services\WhatsApp\WebhookService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
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
    }
}
