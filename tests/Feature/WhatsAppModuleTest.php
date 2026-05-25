<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\WhatsAppController;
use App\Http\Controllers\Admin\QuickChatController;
use App\Http\Controllers\Admin\DeveloperSecurityController;
use App\Jobs\SendEventBroadcastJob;
use App\Jobs\SyncWhatsAppBusinessProfileJob;
use App\Http\Controllers\Api\WhatsAppWebhookController;
use App\Jobs\SendWhatsAppMessageJob;
use App\Models\Event;
use App\Models\Seat;
use App\Models\WhatsAppAccount;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppSettingSecurity;
use App\Models\WhatsappSecurityLog;
use App\Models\User;
use App\Services\WhatsApp\DeveloperPasscodeService;
use App\Services\WhatsApp\ConversationService;
use App\Services\WhatsApp\ConversationMergeService;
use App\Services\WhatsApp\EventAudienceService;
use App\Services\WhatsApp\EventWhatsAppService;
use App\Services\WhatsApp\MessageService;
use App\Services\WhatsApp\MessageStatusSyncService;
use App\Services\WhatsApp\TemplateRendererService;
use App\Services\WhatsApp\WhatsAppBusinessProfileService;
use App\Services\WhatsApp\WhatsAppNumberValidationService;
use App\Services\WhatsApp\WebhookService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
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
            'session.driver' => 'array',
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
            'phone' => '+15551234567',
            'name' => 'John Doe',
        ]);

        $contact = WhatsAppContact::where('phone', '+15551234567')->firstOrFail();
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

        $this->assertSame('15551234567', $conversation->wa_id);
        $this->assertSame('+15551234567', $conversation->normalized_phone);
        $this->assertNotNull($conversation->conversation_key);
    }

    public function test_incoming_reply_reuses_existing_outgoing_conversation(): void
    {
        config(['whatsapp.webhook_secret' => '']);

        $contact = WhatsAppContact::create([
            'name' => 'John Doe',
            'phone' => '+2348012345678',
        ]);

        $conversation = app(ConversationService::class)->findOrCreateConversationForPhone(
            '+2348012345678',
            'John Doe',
            ['unsaved_contact' => false]
        );

        $payload = [
            'entry' => [[
                'changes' => [[
                    'field' => 'messages',
                    'value' => [
                        'contacts' => [[
                            'wa_id' => '2348012345678',
                            'profile' => [
                                'name' => 'John Doe',
                            ],
                        ]],
                        'messages' => [[
                            'from' => '2348012345678',
                            'id' => 'wamid.reply.1',
                            'timestamp' => (string) now()->timestamp,
                            'type' => 'text',
                            'text' => [
                                'body' => 'Reply from WhatsApp',
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
        $this->assertSame(1, WhatsAppConversation::query()->count());

        $conversation->refresh();

        $this->assertSame($contact->id, $conversation->contact_id);
        $this->assertSame('2348012345678', $conversation->wa_id);
        $this->assertSame('+2348012345678', $conversation->normalized_phone);
        $this->assertSame('Reply from WhatsApp', $conversation->last_message);
        $this->assertSame(1, $conversation->unread_count);

        $this->assertDatabaseHas('whatsapp_messages', [
            'conversation_id' => $conversation->id,
            'whatsapp_message_id' => 'wamid.reply.1',
            'sender_type' => 'contact',
        ]);
    }

    public function test_unknown_incoming_number_creates_new_conversation(): void
    {
        config(['whatsapp.webhook_secret' => '']);

        $payload = [
            'entry' => [[
                'changes' => [[
                    'field' => 'messages',
                    'value' => [
                        'contacts' => [[
                            'wa_id' => '447700900123',
                            'profile' => [
                                'name' => 'Unknown User',
                            ],
                        ]],
                        'messages' => [[
                            'from' => '447700900123',
                            'id' => 'wamid.unknown.1',
                            'timestamp' => (string) now()->timestamp,
                            'type' => 'text',
                            'text' => [
                                'body' => 'First inbound message',
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
        $this->assertSame(1, WhatsAppContact::query()->count());
        $this->assertSame(1, WhatsAppConversation::query()->count());

        $conversation = WhatsAppConversation::query()->firstOrFail();

        $this->assertSame('447700900123', $conversation->wa_id);
        $this->assertSame('+447700900123', $conversation->normalized_phone);
        $this->assertSame('First inbound message', $conversation->last_message);
    }

    public function test_existing_contact_without_active_chat_reuses_contact_and_creates_conversation(): void
    {
        config(['whatsapp.webhook_secret' => '']);

        $contact = WhatsAppContact::create([
            'name' => 'Existing Contact',
            'phone' => '+233500000001',
            'company' => 'Acme',
            'notes' => 'Keep linked',
            'unsaved_contact' => false,
        ]);

        $payload = [
            'entry' => [[
                'changes' => [[
                    'field' => 'messages',
                    'value' => [
                        'contacts' => [[
                            'wa_id' => '233500000001',
                            'profile' => [
                                'name' => 'Existing Contact',
                            ],
                        ]],
                        'messages' => [[
                            'from' => '233500000001',
                            'id' => 'wamid.existing.1',
                            'timestamp' => (string) now()->timestamp,
                            'type' => 'text',
                            'text' => [
                                'body' => 'Hello again',
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
        $this->assertSame(1, WhatsAppContact::query()->count());
        $this->assertSame(1, WhatsAppConversation::query()->count());

        $conversation = WhatsAppConversation::query()->firstOrFail();
        $contact->refresh();

        $this->assertSame($contact->id, $conversation->contact_id);
        $this->assertSame('Existing Contact', $contact->name);
        $this->assertSame('Acme', $contact->company);
        $this->assertSame('Keep linked', $contact->notes);
        $this->assertFalse((bool) $contact->unsaved_contact);
    }

    public function test_conversation_merge_service_merges_duplicate_threads(): void
    {
        $contact = WhatsAppContact::create([
            'name' => 'Merge Contact',
            'phone' => '+254700123456',
        ]);

        $olderConversation = WhatsAppConversation::withoutEvents(function () use ($contact) {
            return WhatsAppConversation::create([
                'contact_id' => $contact->id,
                'wa_id' => null,
                'normalized_phone' => null,
                'conversation_key' => 'dup_old_' . uniqid('', true),
                'status' => 'open',
                'last_message' => 'Older thread',
                'unread_count' => 1,
            ]);
        });

        $newerConversation = WhatsAppConversation::withoutEvents(function () use ($contact) {
            return WhatsAppConversation::create([
                'contact_id' => $contact->id,
                'wa_id' => '254700123456',
                'normalized_phone' => '+254700123456',
                'conversation_key' => 'dup_new_' . uniqid('', true),
                'status' => 'open',
                'last_message' => 'Newest thread',
                'unread_count' => 2,
            ]);
        });

        $movedMessage = WhatsAppMessage::create([
            'conversation_id' => $olderConversation->id,
            'sender_type' => 'contact',
            'type' => 'text',
            'body' => 'Old duplicate message',
            'status' => 'received',
        ]);

        app(ConversationMergeService::class)->mergeDuplicates();

        $this->assertDatabaseMissing('whatsapp_conversations', [
            'id' => $olderConversation->id,
        ]);

        $this->assertDatabaseHas('whatsapp_messages', [
            'id' => $movedMessage->id,
            'conversation_id' => $newerConversation->id,
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

    public function test_conversation_history_returns_only_older_messages(): void
    {
        $contact = WhatsAppContact::create([
            'name' => 'History Contact',
            'phone' => '233511111111',
        ]);

        $conversation = WhatsAppConversation::create([
            'contact_id' => $contact->id,
            'status' => 'open',
        ]);

        $messages = collect();

        for ($i = 1; $i <= 12; $i++) {
            $messages->push(WhatsAppMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => $i % 2 === 0 ? 'admin' : 'contact',
                'type' => 'text',
                'body' => 'Message ' . $i,
                'status' => $i % 2 === 0 ? 'sent' : 'received',
                'created_at' => now()->subMinutes(13 - $i),
                'updated_at' => now()->subMinutes(13 - $i),
            ]));
        }

        $oldest = $messages->first();
        $newest = $messages->last();

        $request = Request::create('/admin/whatsapp/conversations/' . $conversation->id . '/history', 'GET', [
            'before_message_id' => $newest->id,
            'limit' => 10,
        ]);
        $request->headers->set('Accept', 'application/json');

        $response = app(WhatsAppController::class)->conversationHistory($request, $conversation);

        $this->assertSame(200, $response->getStatusCode());

        $payload = $response->getData(true);
        $this->assertTrue($payload['success']);
        $this->assertSame($conversation->id, $payload['conversation_id']);
        $this->assertCount(10, $payload['messages']);
        $this->assertSame($messages[1]->id, $payload['messages'][0]['id']);
        $this->assertSame($messages[10]->id, $payload['messages'][9]['id']);
        $this->assertTrue($payload['has_more']);
        $this->assertSame($messages[1]->id, $payload['oldest_message_id']);
        $this->assertNotSame($newest->id, $payload['messages'][9]['id']);
        $this->assertSame($oldest->id, WhatsAppMessage::query()->orderBy('id')->first()->id);
    }

    public function test_clear_conversation_removes_messages_and_resets_thread_state(): void
    {
        $contact = WhatsAppContact::create([
            'name' => 'Clear Contact',
            'phone' => '233577700011',
        ]);

        $conversation = WhatsAppConversation::create([
            'contact_id' => $contact->id,
            'status' => 'open',
            'last_message' => 'To be cleared',
            'unread_count' => 3,
        ]);

        $message = WhatsAppMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'contact',
            'type' => 'text',
            'body' => 'Old chat body',
            'status' => 'received',
        ]);

        $request = Request::create('/admin/whatsapp/conversations/' . $conversation->id . '/clear', 'POST');
        $request->headers->set('Accept', 'application/json');

        $response = app(WhatsAppController::class)->clearConversation($request, $conversation);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertDatabaseMissing('whatsapp_messages', [
            'id' => $message->id,
        ]);
        $this->assertDatabaseHas('whatsapp_conversations', [
            'id' => $conversation->id,
            'last_message' => null,
            'unread_count' => 0,
        ]);
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

    public function test_store_template_supports_rich_text_format(): void
    {
        $request = Request::create('/admin/whatsapp/templates', 'POST', [
            'name' => 'welcome_rich_text',
            'category' => 'utility',
            'language' => 'en',
            'status' => 'draft',
            'template_format' => 'rich_text',
            'rich_text_content' => '<p>Hello {{name}}</p><script>alert(1)</script>',
            'template_json' => null,
            'editor_metadata' => json_encode([
                'source' => 'rich_editor',
            ]),
        ]);

        $response = app(WhatsAppController::class)->storeTemplate(
            $request,
            app(\App\Services\WhatsApp\TemplateService::class),
            app(TemplateRendererService::class)
        );

        $this->assertSame(302, $response->getStatusCode());

        $this->assertDatabaseHas('whatsapp_templates', [
            'name' => 'welcome_rich_text',
            'language' => 'en',
            'template_format' => 'rich_text',
            'status' => 'draft',
        ]);

        $template = \App\Models\WhatsAppTemplate::query()
            ->where('name', 'welcome_rich_text')
            ->firstOrFail();

        $this->assertStringNotContainsString('<script>', (string) $template->rendered_content);
        $this->assertIsArray($template->template_json);
        $this->assertSame('BODY', $template->template_json['components'][0]['type']);
        $this->assertSame('rich_editor', $template->editor_metadata['source'] ?? null);
    }

    public function test_unlock_requires_valid_passcode_and_logs_failure(): void
    {
        Role::findOrCreate('admin');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        WhatsAppSettingSecurity::query()->create([
            'developer_passcode' => Hash::make('StrongPass@2026'),
        ]);

        $session = app('session')->driver();
        $session->start();

        $request = Request::create('/admin/whatsapp/settings/unlock', 'POST', [
            'passcode' => 'wrong-passcode',
        ]);
        $request->setUserResolver(fn() => $admin);
        $request->setLaravelSession($session);
        $request->headers->set('Accept', 'application/json');

        $response = app(DeveloperSecurityController::class)->unlock($request, app(DeveloperPasscodeService::class));

        $this->assertSame(422, $response->getStatusCode());
        $this->assertFalse((bool) $response->getData(true)['success']);
        $this->assertDatabaseHas('whatsapp_security_logs', [
            'user_id' => $admin->id,
            'action' => 'unlock_attempt',
            'status' => 'failure',
        ]);
    }

    public function test_sensitive_data_requires_unlock_and_returns_values_after_unlock(): void
    {
        Role::findOrCreate('admin');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        WhatsAppSettingSecurity::query()->create([
            'developer_passcode' => Hash::make('WhatsAppPass@2026'),
        ]);

        $account = WhatsAppAccount::create([
            'business_name' => 'Secure WA',
            'phone_number' => '233500222333',
            'phone_number_id' => '998877661201',
            'waba_id' => '1122334455662582',
            'access_token' => 'very-secret-token',
            'webhook_verify_token' => 'verify-secret',
            'status' => 'active',
        ]);

        $session = app('session')->driver();
        $session->start();

        $sensitiveBeforeUnlockRequest = Request::create('/admin/whatsapp/settings/sensitive-data', 'GET');
        $sensitiveBeforeUnlockRequest->setUserResolver(fn() => $admin);
        $sensitiveBeforeUnlockRequest->setLaravelSession($session);
        $sensitiveBeforeUnlockRequest->headers->set('Accept', 'application/json');

        $blockedResponse = app(DeveloperSecurityController::class)->sensitiveData(
            $sensitiveBeforeUnlockRequest,
            app(DeveloperPasscodeService::class)
        );

        $this->assertSame(403, $blockedResponse->getStatusCode());

        $unlockRequest = Request::create('/admin/whatsapp/settings/unlock', 'POST', [
            'passcode' => 'WhatsAppPass@2026',
        ]);
        $unlockRequest->setUserResolver(fn() => $admin);
        $unlockRequest->setLaravelSession($session);
        $unlockRequest->headers->set('Accept', 'application/json');

        $unlockResponse = app(DeveloperSecurityController::class)->unlock($unlockRequest, app(DeveloperPasscodeService::class));
        $this->assertSame(200, $unlockResponse->getStatusCode());

        $sensitiveAfterUnlockRequest = Request::create('/admin/whatsapp/settings/sensitive-data', 'GET');
        $sensitiveAfterUnlockRequest->setUserResolver(fn() => $admin);
        $sensitiveAfterUnlockRequest->setLaravelSession($session);
        $sensitiveAfterUnlockRequest->headers->set('Accept', 'application/json');

        $allowedResponse = app(DeveloperSecurityController::class)->sensitiveData(
            $sensitiveAfterUnlockRequest,
            app(DeveloperPasscodeService::class)
        );

        $this->assertSame(200, $allowedResponse->getStatusCode());
        $payload = $allowedResponse->getData(true);

        $this->assertTrue($payload['success']);
        $this->assertSame($account->phone_number_id, $payload['data']['phone_number_id']);
        $this->assertSame('very-secret-token', $payload['data']['access_token']);

        $this->assertDatabaseHas('whatsapp_security_logs', [
            'user_id' => $admin->id,
            'action' => 'unlock_attempt',
            'status' => 'success',
        ]);

        $this->assertTrue(WhatsappSecurityLog::query()->where('action', 'sensitive_data_access')->exists());
    }

    public function test_update_passcode_fails_with_invalid_master_passcode(): void
    {
        Role::findOrCreate('admin');

        $admin = User::factory()->create([
            'organization_id' => null,
        ]);
        $admin->assignRole('admin');

        WhatsAppSettingSecurity::query()->create([
            'developer_passcode' => Hash::make('OldDeveloperPass@2026'),
            'master_passcode_hash' => Hash::make('HappyDevReset401'),
        ]);

        $request = Request::create('/admin/whatsapp/developer-security/passcode', 'POST', [
            'master_passcode' => 'wrong-master',
            'new_passcode' => 'NewDeveloperPass@2026',
            'new_passcode_confirmation' => 'NewDeveloperPass@2026',
        ]);
        $request->setUserResolver(fn() => $admin);
        $request->headers->set('Accept', 'text/html');

        $response = app(DeveloperSecurityController::class)->updatePasscode($request, app(DeveloperPasscodeService::class));

        $this->assertSame(302, $response->getStatusCode());

        $record = WhatsAppSettingSecurity::query()->firstOrFail();
        $this->assertTrue(Hash::check('OldDeveloperPass@2026', $record->developer_passcode));

        $this->assertDatabaseHas('whatsapp_security_logs', [
            'user_id' => $admin->id,
            'action' => 'master_passcode_validation',
            'status' => 'failure',
        ]);
    }

    public function test_update_passcode_succeeds_with_valid_master_passcode(): void
    {
        Role::findOrCreate('admin');

        $admin = User::factory()->create([
            'organization_id' => null,
        ]);
        $admin->assignRole('admin');

        WhatsAppSettingSecurity::query()->create([
            'developer_passcode' => Hash::make('OldDeveloperPass@2026'),
            'master_passcode_hash' => Hash::make('HappyDevReset401'),
        ]);

        $request = Request::create('/admin/whatsapp/developer-security/passcode', 'POST', [
            'master_passcode' => 'HappyDevReset401',
            'new_passcode' => 'NewDeveloperPass@2026',
            'new_passcode_confirmation' => 'NewDeveloperPass@2026',
        ]);
        $request->setUserResolver(fn() => $admin);
        $request->headers->set('Accept', 'text/html');

        $response = app(DeveloperSecurityController::class)->updatePasscode($request, app(DeveloperPasscodeService::class));

        $this->assertSame(302, $response->getStatusCode());

        $record = WhatsAppSettingSecurity::query()->firstOrFail();
        $this->assertTrue(Hash::check('NewDeveloperPass@2026', $record->developer_passcode));

        $this->assertDatabaseHas('whatsapp_security_logs', [
            'user_id' => $admin->id,
            'action' => 'master_passcode_validation',
            'status' => 'success',
        ]);

        $this->assertDatabaseHas('whatsapp_security_logs', [
            'user_id' => $admin->id,
            'action' => 'developer_passcode_update',
            'status' => 'success',
        ]);
    }

    public function test_saving_business_name_queues_meta_profile_sync_and_stores_logo(): void
    {
        Queue::fake();
        Storage::fake('public');

        Role::findOrCreate('admin');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $account = WhatsAppAccount::create([
            'business_name' => 'Old Business',
            'phone_number' => '233500222333',
            'phone_number_id' => '998877661201',
            'waba_id' => '1122334455662582',
            'access_token' => 'very-secret-token',
            'webhook_verify_token' => 'verify-secret',
            'status' => 'active',
        ]);

        $request = Request::create('/admin/whatsapp/settings/account', 'POST', [
            'id' => $account->id,
            'business_name' => 'New Business Name',
            'phone_number' => $account->phone_number,
        ], [], [
            'profile_image' => UploadedFile::fake()->image('logo.png'),
        ]);
        $request->setUserResolver(fn() => $admin);

        $response = app(WhatsAppController::class)->saveAccount($request);

        $this->assertSame(302, $response->getStatusCode());
        $this->assertDatabaseHas('whatsapp_accounts', [
            'id' => $account->id,
            'business_name' => 'New Business Name',
            'display_name_status' => 'pending_approval',
            'profile_sync_status' => 'pending_approval',
        ]);

        Queue::assertPushed(SyncWhatsAppBusinessProfileJob::class, function (SyncWhatsAppBusinessProfileJob $job) use ($account) {
            return $job->accountId === $account->id;
        });
    }

    public function test_whatsapp_business_profile_service_pushes_meta_payload_and_refreshes_status(): void
    {
        config([
            'whatsapp.base_url' => 'https://graph.facebook.com',
            'whatsapp.graph_version' => 'v25.0',
            'app.name' => 'P23 Africa',
            'app.url' => 'https://example.test',
            'mail.from.address' => 'support@example.test',
        ]);

        $account = WhatsAppAccount::create([
            'business_name' => 'Acme Logistics',
            'phone_number' => '233500222333',
            'phone_number_id' => '998877661201',
            'waba_id' => '1122334455662582',
            'access_token' => 'very-secret-token',
            'webhook_verify_token' => 'verify-secret',
            'status' => 'active',
            'profile_image_url' => 'https://cdn.example.test/logo.png',
        ]);

        Http::fake(function ($request) {
            if (str_contains($request->url(), '/whatsapp_business_profile')) {
                return Http::response(['id' => 'meta-profile-123'], 200);
            }

            return Http::response([
                'id' => 'meta-profile-123',
                'verified_name' => 'Acme Logistics',
                'name_status' => 'APPROVED',
            ], 200);
        });

        $service = app(WhatsAppBusinessProfileService::class);
        $response = $service->syncBusinessProfile($account);

        $this->assertSame('meta-profile-123', $response['id']);
        $this->assertDatabaseHas('whatsapp_accounts', [
            'id' => $account->id,
            'display_name_status' => 'approved',
            'profile_sync_status' => 'approved',
            'meta_profile_id' => 'meta-profile-123',
        ]);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), '/whatsapp_business_profile')) {
                return false;
            }

            $payload = $request->data();

            return ($payload['about'] ?? null) !== null
                && ($payload['email'] ?? null) === 'support@example.test'
                && ($payload['websites'][0] ?? null) === 'https://example.test'
                && ($payload['profile_picture_url'] ?? null) === 'https://cdn.example.test/logo.png';
        });
    }

    public function test_number_validation_uses_24_hour_cache_when_available(): void
    {
        WhatsAppContact::create([
            'name' => 'Cached Contact',
            'phone' => '+233500111999',
            'is_whatsapp' => true,
            'validation_status' => 'valid',
            'validated_at' => now()->subMinutes(10),
            'validation_cache_until' => now()->addHours(23),
        ]);

        $result = app(WhatsAppNumberValidationService::class)
            ->validateWhatsAppNumber('+233500111999', '233');

        $this->assertSame('cache', $result['source']);
        $this->assertTrue((bool) $result['is_whatsapp']);
        $this->assertSame('valid', $result['validation_status']);
    }

    public function test_quick_chat_start_and_save_contact_flow_updates_unsaved_contact_state(): void
    {
        Role::findOrCreate('admin');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        WhatsAppAccount::create([
            'business_name' => 'Quick Chat WA',
            'phone_number' => '233500000111',
            'phone_number_id' => '991122334455',
            'waba_id' => '1122334455667788',
            'access_token' => 'quick-chat-token',
            'webhook_verify_token' => 'verify-token',
            'status' => 'active',
        ]);

        Http::fake([
            'https://graph.facebook.com/*/contacts' => Http::response([
                'contacts' => [
                    [
                        'input' => '233500888777',
                        'wa_id' => '233500888777',
                        'status' => 'valid',
                    ],
                ],
            ], 200),
        ]);

        $startRequest = Request::create('/admin/whatsapp/quick-chat/start', 'POST', [
            'phone_number' => '0500888777',
            'country_code' => '233',
            'name' => 'Quick Chat User',
        ]);
        $startRequest->headers->set('Accept', 'application/json');
        $startRequest->setUserResolver(fn() => $admin);

        $startResponse = app(QuickChatController::class)->start($startRequest);
        $this->assertSame(200, $startResponse->getStatusCode());

        $contact = WhatsAppContact::query()->where('phone', '+233500888777')->firstOrFail();
        $this->assertTrue((bool) $contact->unsaved_contact);
        $this->assertSame('valid', $contact->validation_status);

        $saveRequest = Request::create('/admin/whatsapp/quick-chat/save-contact', 'POST', [
            'phone_number' => '+233500888777',
            'country_code' => '233',
            'name' => 'Saved Contact',
            'email' => 'saved@example.test',
            'company' => 'P23 Africa',
            'notes' => 'Saved from quick chat',
        ]);
        $saveRequest->headers->set('Accept', 'application/json');
        $saveRequest->setUserResolver(fn() => $admin);

        $saveResponse = app(QuickChatController::class)->saveContact($saveRequest);
        $this->assertSame(200, $saveResponse->getStatusCode());

        $this->assertDatabaseHas('whatsapp_contacts', [
            'phone' => '+233500888777',
            'name' => 'Saved Contact',
            'email' => 'saved@example.test',
            'company' => 'P23 Africa',
            'unsaved_contact' => 0,
        ]);
    }

    public function test_event_audience_service_extracts_only_valid_deduplicated_whatsapp_attendees(): void
    {
        WhatsAppAccount::create([
            'business_name' => 'Event WA',
            'phone_number' => '233500123456',
            'phone_number_id' => '901122334455',
            'waba_id' => '11223344000099',
            'access_token' => 'event-wa-token',
            'webhook_verify_token' => 'verify-wa-token',
            'status' => 'active',
        ]);

        $event = Event::create([
            'title' => 'Nigerian Business Market',
            'subtitle' => 'Growth Event',
            'description' => 'Event description',
            'link' => 'https://example.test/event',
            'event_date' => now()->addDays(5)->toDateString(),
            'event_time' => '10:30:00',
            'venue' => 'Lagos',
            'display' => 'open_to_all',
            'visibility' => 'public',
        ]);

        Seat::create([
            'event_id' => $event->id,
            'name' => 'Nurudeen',
            'email' => 'nurudeen@example.test',
            'phone' => '+233500111111',
            'organization' => 'P23 Africa',
            'seat_code' => 'P23-1004',
        ]);

        Seat::create([
            'event_id' => $event->id,
            'name' => 'Nurudeen Duplicate',
            'email' => 'nurudeen.dup@example.test',
            'phone' => '233500111111',
            'organization' => 'P23 Africa',
            'seat_code' => 'P23-1005',
        ]);

        Seat::create([
            'event_id' => $event->id,
            'name' => 'Invalid Number',
            'email' => 'invalid@example.test',
            'phone' => '+233500222222',
            'seat_code' => 'P23-1006',
        ]);

        Http::fake(function ($request) {
            if (!str_contains($request->url(), '/contacts')) {
                return Http::response([], 200);
            }

            $target = (string) data_get($request->data(), 'contacts.0', '');

            return Http::response([
                'contacts' => [[
                    'input' => $target,
                    'status' => $target === '233500111111' ? 'valid' : 'invalid',
                ]],
            ], 200);
        });

        $audience = app(EventAudienceService::class)->extractValidatedAudience([
            'event_filter' => 'single',
            'event_id' => $event->id,
        ]);

        $this->assertCount(1, $audience);
        $this->assertSame('+233500111111', $audience->first()['phone']);
        $this->assertSame('Nurudeen', $audience->first()['name']);

        $this->assertDatabaseHas('whatsapp_contacts', [
            'phone' => '+233500111111',
            'validation_status' => 'valid',
        ]);

        $this->assertDatabaseHas('whatsapp_contacts', [
            'phone' => '+233500222222',
            'validation_status' => 'invalid',
        ]);
    }

    public function test_event_whatsapp_service_queues_campaign_and_dispatches_chunk_jobs(): void
    {
        Queue::fake();

        WhatsAppAccount::create([
            'business_name' => 'Event WA',
            'phone_number' => '233500123456',
            'phone_number_id' => '901122334455',
            'waba_id' => '11223344000099',
            'access_token' => 'event-wa-token',
            'webhook_verify_token' => 'verify-wa-token',
            'status' => 'active',
        ]);

        $event = Event::create([
            'title' => 'Startup Expo',
            'subtitle' => 'Expo',
            'description' => 'Expo description',
            'link' => 'https://example.test/expo',
            'event_date' => now()->addDays(3)->toDateString(),
            'event_time' => '09:00:00',
            'venue' => 'Abuja',
            'display' => 'open_to_all',
            'visibility' => 'public',
        ]);

        Seat::create([
            'event_id' => $event->id,
            'name' => 'Amina',
            'email' => 'amina@example.test',
            'phone' => '+2348012345678',
            'organization' => 'Amina Ltd',
            'seat_code' => 'P23-2001',
        ]);

        $template = \App\Models\WhatsAppTemplate::create([
            'name' => 'event_followup',
            'category' => 'utility',
            'language' => 'en',
            'template_format' => 'json',
            'template_json' => [
                'components' => [
                    [
                        'type' => 'BODY',
                        'text' => 'Hello {{name}}, thank you for registering for {{event_name}}. Seat {{seat_code}}.',
                    ],
                ],
            ],
            'status' => 'approved',
        ]);

        Http::fake(function ($request) {
            if (!str_contains($request->url(), '/contacts')) {
                return Http::response([], 200);
            }

            return Http::response([
                'contacts' => [[
                    'input' => '2348012345678',
                    'status' => 'valid',
                ]],
            ], 200);
        });

        $campaign = app(EventWhatsAppService::class)->queueCampaign([
            'source_type' => 'event_contacts',
            'event_filter' => 'single',
            'event_id' => $event->id,
            'template_id' => $template->id,
        ]);

        $this->assertSame('queued', $campaign->status);
        $this->assertSame(1, $campaign->total_contacts);
        $this->assertSame(1, $campaign->valid_contacts);

        $this->assertDatabaseHas('event_whatsapp_recipients', [
            'campaign_id' => $campaign->id,
            'phone' => '+2348012345678',
            'status' => 'queued',
        ]);

        Queue::assertPushed(SendEventBroadcastJob::class, function (SendEventBroadcastJob $job) use ($campaign) {
            return $job->campaignId === $campaign->id
                && count($job->recipientIds) === 1;
        });
    }
}
