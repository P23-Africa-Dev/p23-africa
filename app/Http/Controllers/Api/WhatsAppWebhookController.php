<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppWebhookLog;
use App\Services\WhatsApp\WebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WhatsAppWebhookController extends Controller
{
    public function verify(Request $request, WebhookService $webhookService): Response
    {
        $mode = (string) ($request->query('hub.mode') ?? $request->query('hub_mode') ?? '');
        $verifyToken = (string) ($request->query('hub.verify_token') ?? $request->query('hub_verify_token') ?? '');
        $challenge = (string) ($request->query('hub.challenge') ?? $request->query('hub_challenge') ?? '');

        if ($mode === 'subscribe' && $webhookService->verifyWebhookToken($verifyToken)) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403);
    }

    public function receive(Request $request, WebhookService $webhookService): JsonResponse
    {
        $rawPayload = $request->getContent();
        $payload = $request->all();
        $signatureHeader = $this->resolveSignatureHeader($request);

        $signatureValid = $webhookService->verifySignature($rawPayload, $signatureHeader);

        if (!$signatureValid) {
            $log = WhatsAppWebhookLog::create([
                'event_type' => data_get($payload, 'entry.0.changes.0.field'),
                'payload' => $payload,
                'headers' => $request->headers->all(),
                'signature_valid' => false,
                'status' => 'rejected',
                'error_message' => 'Invalid webhook signature.',
                'processed_at' => now(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid signature.',
                'log_id' => $log->id,
            ], 401);
        }

        $log = $webhookService->handleWebhook($payload, $request->headers->all(), true);

        return response()->json([
            'success' => true,
            'message' => 'Webhook processed.',
            'log_id' => $log->id,
        ]);
    }

    protected function resolveSignatureHeader(Request $request): ?string
    {
        return $request->header('X-Hub-Signature-256')
            ?? $request->header('x-hub-signature-256')
            ?? $request->server('HTTP_X_HUB_SIGNATURE_256')
            ?? $request->server('X_HUB_SIGNATURE_256');
    }
}
