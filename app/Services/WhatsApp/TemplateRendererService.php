<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Arr;
use Mews\Purifier\Facades\Purifier;

class TemplateRendererService
{
    public const FORMAT_JSON = 'json';
    public const FORMAT_RICH_TEXT = 'rich_text';
    public const FORMAT_HTML = 'html';

    public function normalizeFormat(?string $format): string
    {
        $format = strtolower(trim((string) $format));

        if (in_array($format, [self::FORMAT_JSON, self::FORMAT_RICH_TEXT, self::FORMAT_HTML], true)) {
            return $format;
        }

        return self::FORMAT_JSON;
    }

    public function render(string $format, mixed $content, array $variables = []): array
    {
        $normalizedFormat = $this->normalizeFormat($format);

        return match ($normalizedFormat) {
            self::FORMAT_RICH_TEXT => $this->renderRichText((string) $content, $variables),
            self::FORMAT_HTML => $this->renderHtml((string) $content, $variables),
            default => $this->renderJson($content, $variables),
        };
    }

    public function renderJson(mixed $content, array $variables = []): array
    {
        $arrayPayload = is_array($content) ? $content : json_decode((string) $content, true);

        if (!is_array($arrayPayload)) {
            $arrayPayload = [
                'components' => [
                    [
                        'type' => 'BODY',
                        'text' => '',
                    ],
                ],
            ];
        }

        $renderedPayload = $this->replaceVariablesInArray($arrayPayload, $variables);
        $bodyText = (string) Arr::get($renderedPayload, 'components.0.text', '');

        return [
            'format' => self::FORMAT_JSON,
            'stored_content' => $arrayPayload,
            'rendered_content' => $renderedPayload,
            'preview_content' => nl2br(e($bodyText)),
            'whatsapp_payload' => $renderedPayload,
        ];
    }

    public function renderRichText(string $content, array $variables = []): array
    {
        $safe = $this->sanitizeHtml($content);
        $renderedHtml = $this->replaceVariablesInString($safe, $variables);
        $images = $this->extractImageLinks($renderedHtml);
        $text = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $renderedHtml)));

        return [
            'format' => self::FORMAT_RICH_TEXT,
            'stored_content' => $safe,
            'rendered_content' => $renderedHtml,
            'preview_content' => $renderedHtml,
            'whatsapp_payload' => [
                'components' => [
                    [
                        'type' => 'BODY',
                        'text' => $text,
                    ],
                ],
                'media' => $images,
            ],
        ];
    }

    public function renderHtml(string $content, array $variables = []): array
    {
        $safe = $this->sanitizeHtml($content);
        $renderedHtml = $this->replaceVariablesInString($safe, $variables);
        $images = $this->extractImageLinks($renderedHtml);
        $text = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $renderedHtml)));

        return [
            'format' => self::FORMAT_HTML,
            'stored_content' => $safe,
            'rendered_content' => $renderedHtml,
            'preview_content' => $renderedHtml,
            'whatsapp_payload' => [
                'components' => [
                    [
                        'type' => 'BODY',
                        'text' => $text,
                    ],
                ],
                'media' => $images,
            ],
        ];
    }

    public function buildPreview(string $format, mixed $content, array $variables = []): string
    {
        return (string) Arr::get($this->render($format, $content, $variables), 'preview_content', '');
    }

    public function sanitizeHtml(string $input): string
    {
        return Purifier::clean($input, 'default');
    }

    private function replaceVariablesInArray(array $payload, array $variables): array
    {
        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = $this->replaceVariablesInArray($value, $variables);
                continue;
            }

            if (is_string($value)) {
                $payload[$key] = $this->replaceVariablesInString($value, $variables);
            }
        }

        return $payload;
    }

    private function replaceVariablesInString(string $content, array $variables): string
    {
        return (string) preg_replace_callback('/\{\{\s*([a-zA-Z0-9_\.]+)\s*\}\}/', function (array $matches) use ($variables) {
            $key = $matches[1] ?? '';
            if ($key === '') {
                return $matches[0];
            }

            return (string) ($variables[$key] ?? $matches[0]);
        }, $content);
    }

    private function extractImageLinks(string $html): array
    {
        $media = [];

        if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches)) {
            foreach ($matches[1] as $src) {
                $url = trim((string) $src);

                if ($url !== '') {
                    $media[] = [
                        'type' => 'image',
                        'url' => $url,
                    ];
                }
            }
        }

        return $media;
    }
}
