<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class MetaTemplateBuilderService
{
    public const VARIABLE_TYPE_NUMBER = 'number';
    public const VARIABLE_TYPE_NAMED = 'named';

    private const HEADER_MAX = 60;
    private const BODY_MAX = 1024;
    private const FOOTER_MAX = 60;
    private const TEMPLATE_NAME_MAX = 512;
    private const BUTTON_LABEL_MAX = 25;

    /**
     * Normalize to Meta-friendly template names.
     */
    public function normalizeTemplateName(string $name): string
    {
        $normalized = strtolower(trim($name));
        $normalized = preg_replace('/[^a-z0-9]+/', '_', $normalized) ?? '';
        $normalized = preg_replace('/_+/', '_', $normalized) ?? '';
        $normalized = trim($normalized, '_');

        if ($normalized === '') {
            return '';
        }

        if (ctype_digit(substr($normalized, 0, 1))) {
            $normalized = 'template_' . $normalized;
        }

        return substr($normalized, 0, self::TEMPLATE_NAME_MAX);
    }

    public function normalizeCategory(string $category): string
    {
        $value = strtoupper(trim($category));

        return match ($value) {
            'MARKETING' => 'marketing',
            'AUTHENTICATION' => 'authentication',
            default => 'utility',
        };
    }

    public function normalizeVariableType(?string $variableType): string
    {
        $type = strtolower(trim((string) $variableType));

        if (in_array($type, [self::VARIABLE_TYPE_NUMBER, self::VARIABLE_TYPE_NAMED], true)) {
            return $type;
        }

        return self::VARIABLE_TYPE_NUMBER;
    }

    /**
     * Build Meta template JSON from visual/simple builder inputs.
     *
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    public function buildSimpleTemplate(array $input): array
    {
        $variableType = $this->normalizeVariableType((string) Arr::get($input, 'variable_type', self::VARIABLE_TYPE_NUMBER));
        $headerType = strtolower(trim((string) Arr::get($input, 'header_type', 'none')));
        $headerText = trim((string) Arr::get($input, 'header_text', ''));
        $headerMediaFormat = strtoupper(trim((string) Arr::get($input, 'header_media_format', '')));
        $bodyText = trim((string) Arr::get($input, 'body_text', ''));
        $footerText = trim((string) Arr::get($input, 'footer_text', ''));
        $buttons = Arr::get($input, 'buttons', []);

        if ($bodyText === '') {
            throw ValidationException::withMessages([
                'simple_body_text' => 'Body content is required.',
            ]);
        }

        if (mb_strlen($bodyText) > self::BODY_MAX) {
            throw ValidationException::withMessages([
                'simple_body_text' => 'Body cannot exceed ' . self::BODY_MAX . ' characters.',
            ]);
        }

        $components = [];

        if ($headerType === 'text' && $headerText !== '') {
            if (mb_strlen($headerText) > self::HEADER_MAX) {
                throw ValidationException::withMessages([
                    'simple_header_text' => 'Header text cannot exceed ' . self::HEADER_MAX . ' characters.',
                ]);
            }

            $components[] = [
                'type' => 'HEADER',
                'format' => 'TEXT',
                'text' => $headerText,
            ];
        }

        if (in_array($headerType, ['image', 'video', 'document'], true)) {
            $format = match ($headerType) {
                'image' => 'IMAGE',
                'video' => 'VIDEO',
                default => 'DOCUMENT',
            };

            if ($headerMediaFormat !== '' && in_array($headerMediaFormat, ['IMAGE', 'VIDEO', 'DOCUMENT'], true)) {
                $format = $headerMediaFormat;
            }

            $components[] = [
                'type' => 'HEADER',
                'format' => $format,
            ];
        }

        $components[] = [
            'type' => 'BODY',
            'text' => $bodyText,
        ];

        if ($footerText !== '') {
            if (mb_strlen($footerText) > self::FOOTER_MAX) {
                throw ValidationException::withMessages([
                    'simple_footer_text' => 'Footer text cannot exceed ' . self::FOOTER_MAX . ' characters.',
                ]);
            }

            $components[] = [
                'type' => 'FOOTER',
                'text' => $footerText,
            ];
        }

        $normalizedButtons = $this->normalizeButtons($buttons);
        if (!empty($normalizedButtons)) {
            $components[] = [
                'type' => 'BUTTONS',
                'buttons' => $normalizedButtons,
            ];
        }

        $payload = [
            'parameter_format' => strtoupper($variableType === self::VARIABLE_TYPE_NAMED ? 'NAMED' : 'POSITIONAL'),
            'components' => $components,
        ];

        return $this->validateAndNormalizeTemplateJson($payload, $variableType);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function validateAndNormalizeTemplateJson(array $payload, string $variableType): array
    {
        $components = Arr::get($payload, 'components', []);

        if (!is_array($components) || empty($components)) {
            throw ValidationException::withMessages([
                'template_json' => 'Template JSON must include at least one component.',
            ]);
        }

        $variableType = $this->normalizeVariableType($variableType);
        $hasBody = false;
        $normalized = [];

        foreach ($components as $index => $component) {
            if (!is_array($component)) {
                throw ValidationException::withMessages([
                    'template_json' => 'Each component must be an object. Error at index ' . $index . '.',
                ]);
            }

            $type = strtoupper(trim((string) Arr::get($component, 'type', '')));
            if ($type === '') {
                throw ValidationException::withMessages([
                    'template_json' => 'Component type is required. Error at index ' . $index . '.',
                ]);
            }

            if ($type === 'BODY') {
                $hasBody = true;
                $text = trim((string) Arr::get($component, 'text', ''));
                if ($text === '') {
                    throw ValidationException::withMessages([
                        'template_json' => 'BODY component text is required.',
                    ]);
                }
                if (mb_strlen($text) > self::BODY_MAX) {
                    throw ValidationException::withMessages([
                        'template_json' => 'BODY component text exceeds Meta limit of ' . self::BODY_MAX . ' characters.',
                    ]);
                }
                $this->validatePlaceholderTokens($text, $variableType, 'BODY');
                $component['text'] = $text;
            }

            if ($type === 'HEADER') {
                $format = strtoupper(trim((string) Arr::get($component, 'format', 'TEXT')));
                $component['format'] = $format;

                if ($format === 'TEXT') {
                    $text = trim((string) Arr::get($component, 'text', ''));
                    if ($text === '') {
                        throw ValidationException::withMessages([
                            'template_json' => 'HEADER text is required when format is TEXT.',
                        ]);
                    }
                    if (mb_strlen($text) > self::HEADER_MAX) {
                        throw ValidationException::withMessages([
                            'template_json' => 'HEADER text exceeds Meta limit of ' . self::HEADER_MAX . ' characters.',
                        ]);
                    }
                    $this->validatePlaceholderTokens($text, $variableType, 'HEADER');
                    $component['text'] = $text;
                }
            }

            if ($type === 'FOOTER') {
                $text = trim((string) Arr::get($component, 'text', ''));
                if (mb_strlen($text) > self::FOOTER_MAX) {
                    throw ValidationException::withMessages([
                        'template_json' => 'FOOTER text exceeds Meta limit of ' . self::FOOTER_MAX . ' characters.',
                    ]);
                }
                $component['text'] = $text;
            }

            if ($type === 'BUTTONS') {
                $buttons = Arr::get($component, 'buttons', []);
                if (!is_array($buttons)) {
                    throw ValidationException::withMessages([
                        'template_json' => 'BUTTONS component must contain a buttons array.',
                    ]);
                }

                $firstButtonType = strtoupper(trim((string) Arr::get($buttons[0] ?? [], 'type', '')));
                if (in_array($firstButtonType, ['QUICK_REPLY', 'URL', 'PHONE_NUMBER', 'COPY_CODE', 'FLOW'], true)) {
                    $component['buttons'] = $this->normalizeMetaButtons($buttons);
                } else {
                    $component['buttons'] = $this->normalizeButtons($buttons);
                }
            }

            $component['type'] = $type;
            $normalized[] = $component;
        }

        if (!$hasBody) {
            throw ValidationException::withMessages([
                'template_json' => 'Template JSON must include one BODY component.',
            ]);
        }

        return [
            'parameter_format' => strtoupper($variableType === self::VARIABLE_TYPE_NAMED ? 'NAMED' : 'POSITIONAL'),
            'components' => $normalized,
        ];
    }

    /**
     * @param mixed $buttons
     * @return array<int, array<string, string>>
     */
    public function normalizeButtons(mixed $buttons): array
    {
        if (!is_array($buttons)) {
            return [];
        }

        $normalized = [];
        $labels = [];

        foreach ($buttons as $index => $button) {
            if (!is_array($button)) {
                continue;
            }

            $type = strtolower(trim((string) Arr::get($button, 'type', '')));
            $label = trim((string) Arr::get($button, 'label', ''));
            $value = trim((string) Arr::get($button, 'value', ''));

            if ($type === '' || $label === '') {
                continue;
            }

            if (mb_strlen($label) > self::BUTTON_LABEL_MAX) {
                throw ValidationException::withMessages([
                    'buttons_config' => 'Button label at position ' . ($index + 1) . ' exceeds ' . self::BUTTON_LABEL_MAX . ' characters.',
                ]);
            }

            $labelKey = mb_strtolower($label);
            if (isset($labels[$labelKey])) {
                throw ValidationException::withMessages([
                    'buttons_config' => 'Duplicate button label "' . $label . '" is not allowed.',
                ]);
            }
            $labels[$labelKey] = true;

            $item = match ($type) {
                'quick_reply' => [
                    'type' => 'QUICK_REPLY',
                    'text' => $label,
                ],
                'visit_website' => $this->normalizeUrlButton($label, $value),
                'call_phone' => $this->normalizePhoneButton($label, $value),
                'copy_coupon' => $this->normalizeCopyCodeButton($label, $value),
                'flow' => $this->normalizeFlowButton($label, $value),
                default => null,
            };

            if (is_array($item)) {
                $normalized[] = $item;
            }
        }

        if (count($normalized) > 10) {
            throw ValidationException::withMessages([
                'buttons_config' => 'Meta supports up to 10 buttons per template.',
            ]);
        }

        return array_values($normalized);
    }

    public function validatePlaceholderTokens(string $text, string $variableType, string $field): void
    {
        preg_match_all('/\{\{\s*([^\}]+?)\s*\}\}/', $text, $matches);
        $tokens = array_values(array_unique(array_map(static fn($token) => trim((string) $token), (array) ($matches[1] ?? []))));

        if (empty($tokens)) {
            return;
        }

        if ($variableType === self::VARIABLE_TYPE_NUMBER) {
            foreach ($tokens as $token) {
                if (!preg_match('/^\d+$/', $token)) {
                    throw ValidationException::withMessages([
                        'template_json' => $field . ' uses named variables while Variable Type is Number.',
                    ]);
                }
            }

            return;
        }

        foreach ($tokens as $token) {
            if (!preg_match('/^[a-z][a-z0-9_]{0,30}$/', $token)) {
                throw ValidationException::withMessages([
                    'template_json' => $field . ' has invalid named variable {{' . $token . '}}. Use lowercase letters, numbers, and underscore only.',
                ]);
            }
        }
    }

    private function normalizeUrlButton(string $label, string $value): array
    {
        if ($value === '') {
            throw ValidationException::withMessages([
                'buttons_config' => 'Visit Website button URL is required.',
            ]);
        }

        $urlForValidation = preg_replace('/\{\{\s*[^\}]+\s*\}\}/', 'sample', $value) ?? $value;
        if (!preg_match('/^https?:\/\//i', $urlForValidation) || filter_var($urlForValidation, FILTER_VALIDATE_URL) === false) {
            throw ValidationException::withMessages([
                'buttons_config' => 'Visit Website button URL is invalid: ' . $value,
            ]);
        }

        return [
            'type' => 'URL',
            'text' => $label,
            'url' => $value,
        ];
    }

    private function normalizePhoneButton(string $label, string $value): array
    {
        if (!preg_match('/^\+[1-9]\d{6,15}$/', $value)) {
            throw ValidationException::withMessages([
                'buttons_config' => 'Call Phone button requires valid international phone format, e.g. +2348012345678.',
            ]);
        }

        return [
            'type' => 'PHONE_NUMBER',
            'text' => $label,
            'phone_number' => $value,
        ];
    }

    private function normalizeCopyCodeButton(string $label, string $value): array
    {
        if ($value === '' || mb_strlen($value) > 30) {
            throw ValidationException::withMessages([
                'buttons_config' => 'Copy Coupon button requires a code up to 30 characters.',
            ]);
        }

        return [
            'type' => 'COPY_CODE',
            'text' => $label,
            'coupon_code' => $value,
        ];
    }

    private function normalizeFlowButton(string $label, string $value): array
    {
        if ($value === '') {
            throw ValidationException::withMessages([
                'buttons_config' => 'Flow button requires a flow id or flow name.',
            ]);
        }

        return [
            'type' => 'FLOW',
            'text' => $label,
            'flow_id' => $value,
        ];
    }

    /**
     * @param array<int, array<string, mixed>> $buttons
     * @return array<int, array<string, string>>
     */
    private function normalizeMetaButtons(array $buttons): array
    {
        $legacy = [];

        foreach ($buttons as $button) {
            if (!is_array($button)) {
                continue;
            }

            $type = strtoupper(trim((string) Arr::get($button, 'type', '')));

            if ($type === 'QUICK_REPLY') {
                $legacy[] = [
                    'type' => 'quick_reply',
                    'label' => (string) Arr::get($button, 'text', ''),
                    'value' => '',
                ];
                continue;
            }

            if ($type === 'URL') {
                $legacy[] = [
                    'type' => 'visit_website',
                    'label' => (string) Arr::get($button, 'text', ''),
                    'value' => (string) Arr::get($button, 'url', ''),
                ];
                continue;
            }

            if ($type === 'PHONE_NUMBER') {
                $legacy[] = [
                    'type' => 'call_phone',
                    'label' => (string) Arr::get($button, 'text', ''),
                    'value' => (string) Arr::get($button, 'phone_number', ''),
                ];
                continue;
            }

            if ($type === 'COPY_CODE') {
                $legacy[] = [
                    'type' => 'copy_coupon',
                    'label' => (string) Arr::get($button, 'text', ''),
                    'value' => (string) Arr::get($button, 'coupon_code', ''),
                ];
                continue;
            }

            if ($type === 'FLOW') {
                $legacy[] = [
                    'type' => 'flow',
                    'label' => (string) Arr::get($button, 'text', ''),
                    'value' => (string) (Arr::get($button, 'flow_id', Arr::get($button, 'flow_name', ''))),
                ];
            }
        }

        return $this->normalizeButtons($legacy);
    }
}
