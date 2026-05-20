<?php

namespace App\Jobs;

use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\MessageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public array $backoff = [30, 90, 180, 300];

    public function __construct(
        public int $messageId,
        public ?int $accountId = null,
    ) {}

    public function handle(MessageService $messageService): void
    {
        $message = WhatsAppMessage::find($this->messageId);

        if (!$message) {
            return;
        }

        if ($message->status !== WhatsAppMessage::STATUS_QUEUED) {
            return;
        }

        $messageService->processOutgoingMessage($message, $this->accountId);
    }

    public function failed(Throwable $exception): void
    {
        $message = WhatsAppMessage::find($this->messageId);

        if (!$message) {
            return;
        }

        $message->update([
            'status' => WhatsAppMessage::STATUS_FAILED,
            'payload' => array_merge($message->payload ?? [], [
                'error' => $exception->getMessage(),
            ]),
        ]);
    }
}
