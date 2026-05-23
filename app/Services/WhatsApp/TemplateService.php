<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppTemplate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TemplateService
{
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return WhatsAppTemplate::query()
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createOrUpdate(array $attributes): WhatsAppTemplate
    {
        return WhatsAppTemplate::updateOrCreate(
            [
                'name' => $attributes['name'],
                'language' => $attributes['language'],
            ],
            [
                'category' => $attributes['category'],
                'template_format' => $attributes['template_format'] ?? TemplateRendererService::FORMAT_JSON,
                'template_json' => $attributes['template_json'],
                'rendered_content' => $attributes['rendered_content'] ?? null,
                'preview_content' => $attributes['preview_content'] ?? null,
                'editor_metadata' => $attributes['editor_metadata'] ?? null,
                'status' => $attributes['status'] ?? WhatsAppTemplate::STATUS_DRAFT,
            ]
        );
    }

    public function updateTemplate(WhatsAppTemplate $template, array $attributes): WhatsAppTemplate
    {
        $template->update([
            'name' => $attributes['name'] ?? $template->name,
            'category' => $attributes['category'] ?? $template->category,
            'language' => $attributes['language'] ?? $template->language,
            'template_format' => $attributes['template_format'] ?? $template->template_format,
            'template_json' => $attributes['template_json'] ?? $template->template_json,
            'rendered_content' => $attributes['rendered_content'] ?? $template->rendered_content,
            'preview_content' => $attributes['preview_content'] ?? $template->preview_content,
            'editor_metadata' => $attributes['editor_metadata'] ?? $template->editor_metadata,
            'status' => $attributes['status'] ?? $template->status,
        ]);

        return $template->refresh();
    }

    public function handleTemplateWebhookEvent(array $payload): ?WhatsAppTemplate
    {
        $name = data_get($payload, 'message_template_name')
            ?? data_get($payload, 'name')
            ?? data_get($payload, 'template_name');

        if (!$name) {
            return null;
        }

        $language = data_get($payload, 'message_template_language')
            ?? data_get($payload, 'language')
            ?? 'en';

        $status = data_get($payload, 'event')
            ?? data_get($payload, 'status')
            ?? WhatsAppTemplate::STATUS_PENDING;

        $category = data_get($payload, 'category', 'utility');

        return $this->createOrUpdate([
            'name' => $name,
            'language' => $language,
            'category' => $category,
            'status' => (string) $status,
            'template_format' => TemplateRendererService::FORMAT_JSON,
            'template_json' => $payload,
            'rendered_content' => null,
            'preview_content' => null,
            'editor_metadata' => null,
        ]);
    }
}
