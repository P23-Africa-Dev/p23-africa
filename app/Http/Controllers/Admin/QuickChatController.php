<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppTemplate;
use App\Services\WhatsApp\MessageService;
use App\Services\WhatsApp\TemplateComposerService;
use App\Services\WhatsApp\WhatsAppNumberValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use RuntimeException;

class QuickChatController extends Controller
{
    public function __construct(
        protected WhatsAppNumberValidationService $validationService,
        protected MessageService $messageService,
        protected TemplateComposerService $templateComposerService,
    ) {}

    public function approvedTemplates(): JsonResponse
    {
        $this->authorize('whatsapp.view');

        $templates = WhatsAppTemplate::query()
            ->where('status', WhatsAppTemplate::STATUS_APPROVED)
            ->orderBy('name')
            ->get()
            ->map(fn(WhatsAppTemplate $template) => $this->templateComposerService->toClientTemplate($template))
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'templates' => $templates,
            ],
        ]);
    }

    public function validateNumber(Request $request): JsonResponse
    {
        $this->authorize('whatsapp.send');

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
        $this->authorize('whatsapp.send');

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
        $this->authorize('whatsapp.manage');

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

    public function startTemplate(Request $request): JsonResponse
    {
        $this->authorize('whatsapp.send');

        $validated = $request->validate([
            'phone_number' => ['required', 'string', 'max:40'],
            'country_code' => ['nullable', 'string', 'max:8'],
            'name' => ['nullable', 'string', 'max:255'],
            'template_id' => [
                'required',
                'integer',
                Rule::exists('whatsapp_templates', 'id')->where(fn($query) => $query->where('status', WhatsAppTemplate::STATUS_APPROVED)),
            ],
            'template_variables' => ['nullable'],
        ]);

        $validation = $this->validationService->validateWhatsAppNumber(
            (string) $validated['phone_number'],
            (string) ($validated['country_code'] ?? ''),
            true
        );

        $validationStatus = strtolower((string) Arr::get($validation, 'validation_status', WhatsAppNumberValidationService::STATUS_FAILED));
        $isWhatsApp = Arr::get($validation, 'is_whatsapp');

        if ($validationStatus === WhatsAppNumberValidationService::STATUS_INVALID || $isWhatsApp === false) {
            return response()->json([
                'success' => false,
                'message' => 'This number is not registered on WhatsApp.',
            ], 422);
        }

        if ($validationStatus !== WhatsAppNumberValidationService::STATUS_VALID || $isWhatsApp !== true) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to validate recipient. Please retry.',
            ], 422);
        }

        $template = WhatsAppTemplate::query()->findOrFail((int) $validated['template_id']);
        $variables = $this->templateComposerService->normalizeIncomingVariables(Arr::get($validated, 'template_variables'));

        // Validate required variables before creating any records.
        if ($this->templateComposerService->variableType($template) !== 'none') {
            $this->templateComposerService->buildSendComponents($template, $variables);
        }

        try {
            $conversation = $this->validationService->startConversation($validation, $validated['name'] ?? null);
        } catch (RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);
        }

        $message = $this->messageService->queueTemplateMessage(
            $conversation,
            (int) $request->user()->id,
            $template->id,
            null,
            $variables
        );

        return response()->json([
            'success' => true,
            'data' => [
                'conversation_id' => $conversation->id,
                'contact_id' => $conversation->contact_id,
                'message_id' => $message->id,
                'conversation_url' => orgRoute('admin.whatsapp.conversations', ['conversation' => $conversation->id]),
            ],
        ]);
    }
}
