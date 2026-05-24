<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WhatsApp\MessageService;
use App\Services\WhatsApp\WhatsAppNumberValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class QuickChatController extends Controller
{
    public function __construct(
        protected WhatsAppNumberValidationService $validationService,
        protected MessageService $messageService,
    ) {}

    public function validateNumber(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'max:40'],
            'country_code' => ['nullable', 'string', 'max:8'],
            'force' => ['nullable', 'boolean'],
        ]);

        $result = $this->validationService->validateWhatsAppNumber(
            (string) $validated['phone_number'],
            (string) ($validated['country_code'] ?? ''),
            (bool) ($validated['force'] ?? false)
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    public function start(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'max:40'],
            'country_code' => ['nullable', 'string', 'max:8'],
            'name' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:4096'],
        ]);

        $result = $this->validationService->validateWhatsAppNumber(
            (string) $validated['phone_number'],
            (string) ($validated['country_code'] ?? ''),
        );

        try {
            $conversation = $this->validationService->startConversation($result, $validated['name'] ?? null);
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }

        $initialMessage = trim((string) ($validated['message'] ?? ''));
        if ($initialMessage !== '' && $request->user()) {
            $this->messageService->queueTextMessage($conversation, (int) $request->user()->id, $initialMessage);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'conversation_id' => $conversation->id,
                'contact_id' => $conversation->contact_id,
                'phone' => $conversation->contact?->phone,
                'name' => $conversation->contact?->name,
                'validation' => $result,
                'conversation_url' => orgRoute('admin.whatsapp.conversations', ['conversation' => $conversation->id]),
            ],
        ]);
    }

    public function saveContact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'max:40'],
            'country_code' => ['nullable', 'string', 'max:8'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $result = $this->validationService->validateWhatsAppNumber(
            (string) $validated['phone_number'],
            (string) ($validated['country_code'] ?? ''),
        );

        $contact = $this->validationService->saveContact($result, [
            'name' => $validated['name'],
            'country_code' => $validated['country_code'] ?? null,
            'email' => $validated['email'] ?? null,
            'company' => $validated['company'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'contact_id' => $contact->id,
                'name' => $contact->name,
                'phone' => $contact->phone,
                'validation_status' => $contact->validation_status,
            ],
        ]);
    }
}
