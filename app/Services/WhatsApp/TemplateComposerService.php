<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppTemplate;
use Illuminate\Validation\ValidationException;
use Throwable;

class TemplateComposerService
{
    /**
     * @var array<string, array<string, mixed>>|null
     */
    protected ?array $liveTemplateIndex = null;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function extractVariables(WhatsAppTemplate $template): array
    {
        $components = $this->templateComponents($template);
        $tokens = [];

        foreach ($components as $component) {
            if (!is_array($component)) {
                continue;
            }

            $type = strtoupper(trim((string) ($component['type'] ?? '')));

            if (in_array($type, ['HEADER', 'BODY', 'FOOTER'], true)) {
                $componentTokens = $this->extractPlaceholderTokens((string) ($component['text'] ?? ''));
                if (empty($componentTokens)) {
                    $componentTokens = $this->extractTokensFromComponentExamples($component);
                }

                $tokens = array_merge($tokens, $componentTokens);
            }

            if ($type === 'BUTTON') {
                $source = (string) ($component['url'] ?? $component['text'] ?? '');
                $componentTokens = $this->extractPlaceholderTokens($source);
                if (empty($componentTokens)) {
                    $componentTokens = $this->extractTokensFromComponentExamples($component);
                }

                $tokens = array_merge($tokens, $componentTokens);
            }

            if ($type === 'BUTTONS') {
                $buttons = $component['buttons'] ?? [];
                if (!is_array($buttons)) {
                    continue;
                }

                foreach ($buttons as $button) {
                    if (!is_array($button)) {
                        continue;
                    }

                    $source = (string) ($button['url'] ?? $button['text'] ?? '');
                    $buttonTokens = $this->extractPlaceholderTokens($source);
                    if (empty($buttonTokens)) {
                        $buttonTokens = $this->extractTokensFromNamedParamExamples($button['example'] ?? []);
                    }

                    $tokens = array_merge($tokens, $buttonTokens);
                }
            }
        }

        if (empty($tokens)) {
            $tokens = array_merge($tokens, $this->extractTokensFromFallbackTemplateContent($template));
            $tokens = array_merge($tokens, $this->extractTokensFromNamedParamExamples($template->template_json ?? []));
        }

        $tokens = array_values(array_unique(array_map('strval', $tokens)));

        return array_map(function (string $token) {
            $label = ctype_digit($token)
                ? 'Variable ' . $token
                : ucwords(str_replace('_', ' ', $token));

            return [
                'key' => $token,
                'label' => $label,
                'required' => true,
            ];
        }, $tokens);
    }

    public function variableType(WhatsAppTemplate $template): string
    {
        $variables = $this->extractVariables($template);
        if (empty($variables)) {
            return 'none';
        }

        $tokens = array_map(static fn(array $item) => (string) ($item['key'] ?? ''), $variables);

        return $this->variableTypeFromTokens($template, $tokens);
    }

    /**
     * @param array<string, mixed> $variables
     */
    public function renderPreview(WhatsAppTemplate $template, array $variables): string
    {
        $components = $this->templateComponents($template);
        $resolved = $this->resolveValues($template, $variables, false);

        $lines = [];

        foreach ($components as $component) {
            if (!is_array($component)) {
                continue;
            }

            $type = strtoupper(trim((string) ($component['type'] ?? '')));
            if (!in_array($type, ['HEADER', 'BODY', 'FOOTER'], true)) {
                continue;
            }

            $text = trim((string) ($component['text'] ?? ''));
            if ($text === '') {
                continue;
            }

            $lines[] = $this->replacePlaceholders($text, $resolved, false);
        }

        if (empty($lines)) {
            $fallback = $this->fallbackPreviewText($template, $resolved);
            if ($fallback !== '') {
                return $fallback;
            }
        }

        return trim(implode("\n", $lines));
    }

    /**
     * @param array<string, mixed> $variables
     * @return array<int, array<string, mixed>>
     */
    public function buildSendComponents(WhatsAppTemplate $template, array $variables): array
    {
        $components = $this->templateComponents($template);
        $resolved = $this->resolveValues($template, $variables, true);

        if (empty($resolved)) {
            return [];
        }

        $useNamedParameters = strtoupper(trim((string) data_get($template->template_json, 'parameter_format', ''))) === 'NAMED';
        $built = [];

        foreach ($components as $component) {
            if (!is_array($component)) {
                continue;
            }

            $type = strtoupper(trim((string) ($component['type'] ?? '')));

            if (in_array($type, ['HEADER', 'BODY'], true)) {
                $tokens = $this->extractPlaceholderTokens((string) ($component['text'] ?? ''));
                if (empty($tokens)) {
                    $tokens = $this->extractTokensFromComponentExamples($component);
                }

                if (empty($tokens)) {
                    continue;
                }

                $built[] = [
                    'type' => strtolower($type),
                    'parameters' => $this->buildParameters($tokens, $resolved, $useNamedParameters),
                ];
                continue;
            }

            if ($type === 'BUTTON') {
                $source = (string) ($component['url'] ?? $component['text'] ?? '');
                $tokens = $this->extractPlaceholderTokens($source);
                if (empty($tokens)) {
                    $tokens = $this->extractTokensFromComponentExamples($component);
                }

                if (empty($tokens)) {
                    continue;
                }

                $built[] = [
                    'type' => 'button',
                    'sub_type' => strtolower(trim((string) ($component['sub_type'] ?? 'url'))),
                    'index' => (string) ($component['index'] ?? '0'),
                    'parameters' => $this->buildParameters($tokens, $resolved, $useNamedParameters),
                ];
                continue;
            }

            if ($type === 'BUTTONS') {
                $buttons = $component['buttons'] ?? [];
                if (!is_array($buttons)) {
                    continue;
                }

                foreach ($buttons as $index => $button) {
                    if (!is_array($button)) {
                        continue;
                    }

                    $source = (string) ($button['url'] ?? $button['text'] ?? '');
                    $tokens = $this->extractPlaceholderTokens($source);
                    if (empty($tokens)) {
                        $tokens = $this->extractTokensFromNamedParamExamples($button['example'] ?? []);
                    }

                    if (empty($tokens)) {
                        continue;
                    }

                    $buttonType = strtoupper(trim((string) ($button['type'] ?? 'URL')));
                    $subType = match ($buttonType) {
                        'QUICK_REPLY' => 'quick_reply',
                        'PHONE_NUMBER' => 'phone_number',
                        'COPY_CODE' => 'copy_code',
                        'FLOW' => 'flow',
                        default => 'url',
                    };

                    $built[] = [
                        'type' => 'button',
                        'sub_type' => $subType,
                        'index' => (string) $index,
                        'parameters' => $this->buildParameters($tokens, $resolved, $useNamedParameters),
                    ];
                }
            }
        }

        return $built;
    }

    /**
     * @param array<string, mixed>|string|null $input
     * @return array<string, string>
     */
    public function normalizeIncomingVariables(array|string|null $input): array
    {
        if (is_string($input)) {
            $decoded = json_decode($input, true);
            $input = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($input)) {
            return [];
        }

        $normalized = [];

        foreach ($input as $key => $value) {
            if (is_array($value) || is_object($value)) {
                continue;
            }

            $stringKey = trim((string) $key);
            if ($stringKey === '') {
                continue;
            }

            $normalized[$stringKey] = trim((string) $value);
        }

        return $normalized;
    }

    public function toClientTemplate(WhatsAppTemplate $template): array
    {
        $variables = $this->extractVariables($template);

        return [
            'id' => $template->id,
            'name' => $template->name,
            'category' => $template->category,
            'status' => $template->status,
            'language' => $template->language,
            'updated_at' => optional($template->updated_at)->toIso8601String(),
            'variable_type' => $this->variableType($template),
            'variables' => $variables,
            'preview_template' => $this->renderPreview($template, []),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function templateComponents(WhatsAppTemplate $template): array
    {
        $json = $template->template_json;
        if (!is_array($json)) {
            return [];
        }

        $components = data_get($json, 'components');
        if (!is_array($components) || empty($components)) {
            if (array_is_list($json)) {
                $components = $json;
            } else {
                $components = $this->componentsFromSimpleSections($json);
            }
        }

        if ((!is_array($components) || empty($components)) && $template->exists) {
            $components = $this->fetchLiveComponents($template);
        }

        return $this->normalizeComponents($components);
    }

    /**
     * @param array<int, string> $tokens
     */
    protected function variableTypeFromTokens(WhatsAppTemplate $template, array $tokens): string
    {
        if (empty($tokens)) {
            return 'none';
        }

        $parameterFormat = strtoupper(trim((string) data_get($template->template_json, 'parameter_format', '')));
        if ($parameterFormat === 'NAMED') {
            return 'named';
        }

        foreach ($tokens as $token) {
            if (!ctype_digit((string) $token)) {
                return 'named';
            }
        }

        return 'number';
    }

    /**
     * @param array<string, string> $resolved
     * @return array<int, array<string, string>>
     */
    protected function buildParameters(array $tokens, array $resolved, bool $useNamedParameters): array
    {
        $parameters = [];

        foreach ($tokens as $token) {
            $value = $resolved[$token] ?? '';
            $parameter = [
                'type' => 'text',
                'text' => $value,
            ];

            if ($useNamedParameters && !ctype_digit($token)) {
                $parameter['parameter_name'] = $token;
            }

            $parameters[] = $parameter;
        }

        return $parameters;
    }

    /**
     * @param array<string, mixed> $variables
     * @return array<string, string>
     */
    protected function resolveValues(WhatsAppTemplate $template, array $variables, bool $strict): array
    {
        $normalized = $this->normalizeIncomingVariables($variables);
        $lowercase = [];
        foreach ($normalized as $key => $value) {
            $lowercase[strtolower($key)] = $value;
        }

        $definitions = $this->extractVariables($template);
        $resolved = [];

        foreach ($definitions as $definition) {
            $token = (string) ($definition['key'] ?? '');
            if ($token === '') {
                continue;
            }

            $value = '';

            if (ctype_digit($token)) {
                $value = $normalized[$token]
                    ?? $normalized[(string) ((int) $token)]
                    ?? (array_is_list($variables) ? trim((string) ($variables[max(0, ((int) $token) - 1)] ?? '')) : '');
            } else {
                $value = $normalized[$token] ?? $lowercase[strtolower($token)] ?? '';
            }

            $value = trim((string) $value);

            if ($strict && $value === '') {
                $label = (string) ($definition['label'] ?? $token);
                throw ValidationException::withMessages([
                    'template_variables.' . $token => 'Template variable "' . $label . '" is required.',
                ]);
            }

            $resolved[$token] = $value !== '' ? $value : '{{' . $token . '}}';
        }

        return $resolved;
    }

    /**
     * @param array<string, string> $resolved
     */
    protected function replacePlaceholders(string $text, array $resolved, bool $strict): string
    {
        return (string) preg_replace_callback('/\{\{\s*([^\}]+?)\s*\}\}/', function (array $matches) use ($resolved, $strict) {
            $token = trim((string) ($matches[1] ?? ''));
            if ($token === '') {
                return (string) ($matches[0] ?? '');
            }

            if (array_key_exists($token, $resolved)) {
                return (string) $resolved[$token];
            }

            if ($strict) {
                return '';
            }

            return (string) ($matches[0] ?? '');
        }, $text);
    }

    /**
     * @return array<int, string>
     */
    protected function extractPlaceholderTokens(string $text): array
    {
        preg_match_all('/\{\{\s*([^\}]+?)\s*\}\}/', $text, $matches);

        $tokens = [];

        foreach ((array) ($matches[1] ?? []) as $token) {
            $clean = trim((string) $token);
            if ($clean === '') {
                continue;
            }

            if (!in_array($clean, $tokens, true)) {
                $tokens[] = $clean;
            }
        }

        return $tokens;
    }

    /**
     * @param array<int|string, mixed> $components
     * @return array<int, array<string, mixed>>
     */
    protected function normalizeComponents(array $components): array
    {
        if (!array_is_list($components)) {
            $mapped = [];

            foreach ($components as $key => $value) {
                if (is_array($value)) {
                    if (!isset($value['type']) && is_string($key)) {
                        $value['type'] = strtoupper(trim($key));
                    }

                    $mapped[] = $value;
                    continue;
                }

                if (is_string($value) && is_string($key)) {
                    $type = strtoupper(trim($key));
                    if (in_array($type, ['HEADER', 'BODY', 'FOOTER'], true)) {
                        $mapped[] = [
                            'type' => $type,
                            'text' => $value,
                        ];
                    }
                }
            }

            $components = $mapped;
        }

        $normalized = [];

        foreach ($components as $component) {
            if (!is_array($component)) {
                continue;
            }

            $type = strtoupper(trim((string) ($component['type'] ?? '')));
            if ($type === '') {
                continue;
            }

            $component['type'] = $type;
            $normalized[] = $component;
        }

        return $normalized;
    }

    /**
     * @param array<string, mixed> $json
     * @return array<int, array<string, mixed>>
     */
    protected function componentsFromSimpleSections(array $json): array
    {
        $sections = [];

        foreach (['header' => 'HEADER', 'body' => 'BODY', 'footer' => 'FOOTER'] as $key => $type) {
            $value = $json[$key] ?? null;

            if (is_string($value) && trim($value) !== '') {
                $sections[] = [
                    'type' => $type,
                    'text' => $value,
                ];
                continue;
            }

            if (is_array($value)) {
                $section = $value;
                $section['type'] = $section['type'] ?? $type;
                $sections[] = $section;
            }
        }

        $buttons = $json['buttons'] ?? null;
        if (is_array($buttons) && !empty($buttons)) {
            if (array_is_list($buttons)) {
                $sections[] = [
                    'type' => 'BUTTONS',
                    'buttons' => $buttons,
                ];
            } else {
                $buttonSection = $buttons;
                $buttonSection['type'] = $buttonSection['type'] ?? 'BUTTONS';
                $sections[] = $buttonSection;
            }
        }

        return $sections;
    }

    /**
     * @param array<string, mixed> $component
     * @return array<int, string>
     */
    protected function extractTokensFromComponentExamples(array $component): array
    {
        return $this->extractTokensFromNamedParamExamples($component['example'] ?? []);
    }

    /**
     * @param mixed $source
     * @return array<int, string>
     */
    protected function extractTokensFromNamedParamExamples(mixed $source): array
    {
        $tokens = [];
        $this->collectNamedParamTokens($source, $tokens);

        return $tokens;
    }

    /**
     * @param mixed $node
     * @param array<int, string> $tokens
     */
    protected function collectNamedParamTokens(mixed $node, array &$tokens): void
    {
        if (!is_array($node)) {
            return;
        }

        foreach ($node as $key => $value) {
            $keyName = strtolower(trim((string) $key));
            if (in_array($keyName, ['param_name', 'parameter_name'], true) && is_string($value)) {
                $candidate = trim($value);
                if ($candidate !== '' && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $candidate) === 1 && !in_array($candidate, $tokens, true)) {
                    $tokens[] = $candidate;
                }

                continue;
            }

            $this->collectNamedParamTokens($value, $tokens);
        }
    }

    /**
     * @return array<int, string>
     */
    protected function extractTokensFromFallbackTemplateContent(WhatsAppTemplate $template): array
    {
        $sources = [];

        if (is_string($template->preview_content) && trim($template->preview_content) !== '') {
            $sources[] = $this->toPlainText($template->preview_content);
        }

        $rendered = $template->rendered_content;
        if (is_string($rendered) && trim($rendered) !== '') {
            $decoded = json_decode($rendered, true);
            if (is_array($decoded)) {
                $sources = array_merge($sources, $this->collectTextFragments($decoded));
            } else {
                $sources[] = $this->toPlainText($rendered);
            }
        }

        $tokens = [];
        foreach ($sources as $source) {
            $tokens = array_merge($tokens, $this->extractPlaceholderTokens((string) $source));
        }

        return array_values(array_unique($tokens));
    }

    /**
     * @param array<string, string> $resolved
     */
    protected function fallbackPreviewText(WhatsAppTemplate $template, array $resolved): string
    {
        $candidates = [];

        if (is_string($template->preview_content) && trim($template->preview_content) !== '') {
            $candidates[] = $this->toPlainText($template->preview_content);
        }

        $rendered = $template->rendered_content;
        if (is_string($rendered) && trim($rendered) !== '') {
            $decoded = json_decode($rendered, true);
            if (is_array($decoded)) {
                $candidates = array_merge($candidates, $this->collectTextFragments($decoded));
            } else {
                $candidates[] = $this->toPlainText($rendered);
            }
        }

        foreach ($candidates as $candidate) {
            $text = trim($this->replacePlaceholders((string) $candidate, $resolved, false));
            if ($text !== '') {
                return $text;
            }
        }

        return '';
    }

    /**
     * @param array<int|string, mixed> $payload
     * @return array<int, string>
     */
    protected function collectTextFragments(array $payload): array
    {
        $fragments = [];

        foreach ($payload as $key => $value) {
            if (is_array($value)) {
                $fragments = array_merge($fragments, $this->collectTextFragments($value));
                continue;
            }

            if (!is_string($value)) {
                continue;
            }

            $keyName = strtolower(trim((string) $key));
            if (!in_array($keyName, ['text', 'body', 'header', 'footer', 'content', 'preview', 'preview_content'], true)) {
                continue;
            }

            $plain = $this->toPlainText($value);
            if ($plain !== '') {
                $fragments[] = $plain;
            }
        }

        return array_values(array_unique($fragments));
    }

    protected function toPlainText(string $value): string
    {
        $withBreaks = str_ireplace(['<br>', '<br/>', '<br />'], "\n", $value);

        return trim(html_entity_decode(strip_tags($withBreaks), ENT_QUOTES | ENT_HTML5));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function fetchLiveComponents(WhatsAppTemplate $template): array
    {
        $index = $this->loadLiveTemplateIndex();
        if (empty($index)) {
            return [];
        }

        $name = strtolower(trim((string) $template->name));
        $language = $this->normalizeLanguage((string) $template->language);
        $baseLanguage = strtok($language, '_') ?: $language;

        $candidates = [];
        if ($name !== '' && $language !== '') {
            $candidates[] = $name . '|' . $language;
        }
        if ($name !== '' && $baseLanguage !== '') {
            $candidates[] = $name . '|' . $baseLanguage;
        }

        foreach ($candidates as $candidate) {
            $item = $index[$candidate] ?? null;
            if (!is_array($item)) {
                continue;
            }

            $components = $item['components'] ?? [];
            if (!is_array($components) || empty($components)) {
                continue;
            }

            if ($template->exists) {
                $current = is_array($template->template_json) ? $template->template_json : [];
                if (array_is_list($current)) {
                    $current = ['components' => $current];
                }

                $template->template_json = array_merge($current, [
                    'components' => $components,
                    'name' => $item['name'] ?? $template->name,
                    'language' => $item['language'] ?? $template->language,
                    'parameter_format' => $item['parameter_format'] ?? data_get($current, 'parameter_format'),
                ]);
                $template->save();
            }

            return $components;
        }

        return [];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function loadLiveTemplateIndex(): array
    {
        if (is_array($this->liveTemplateIndex)) {
            return $this->liveTemplateIndex;
        }

        $this->liveTemplateIndex = [];

        try {
            $service = app(WhatsAppService::class);
            $cursor = null;

            for ($page = 0; $page < 10; $page++) {
                $response = $service->fetchMessageTemplatesPage(null, $cursor, 100);
                $items = data_get($response, 'data', []);
                if (!is_array($items) || empty($items)) {
                    break;
                }

                foreach ($items as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $name = strtolower(trim((string) ($item['name'] ?? '')));
                    if ($name === '') {
                        continue;
                    }

                    $language = $this->normalizeLanguage((string) ($item['language'] ?? ''));
                    $baseLanguage = strtok($language, '_') ?: $language;

                    if ($language !== '') {
                        $this->liveTemplateIndex[$name . '|' . $language] = $item;
                    }

                    if ($baseLanguage !== '') {
                        $this->liveTemplateIndex[$name . '|' . $baseLanguage] = $item;
                    }
                }

                $cursor = data_get($response, 'paging.cursors.after');
                if (!is_string($cursor) || trim($cursor) === '') {
                    break;
                }
            }
        } catch (Throwable) {
            // Swallow network/API failures and gracefully use local payload only.
        }

        return $this->liveTemplateIndex;
    }

    protected function normalizeLanguage(string $language): string
    {
        return strtolower(str_replace('-', '_', trim($language)));
    }
}
