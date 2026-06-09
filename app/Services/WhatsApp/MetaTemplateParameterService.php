<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppTemplate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class MetaTemplateParameterService
{
    public const EVENT_REMINDER_TEMPLATE = 'event_reminder_2';
    public const LATEST_EVENT_REMINDER_TEMPLATE = 'latest_event_reminder';
    public const FINAL_EVENT_REMINDER_TEMPLATE = 'final_event_reminder';
    public const EVENT_INVITATION_TEMPLATE = 'event_invitation';

    public function __construct(
        protected WhatsAppService $whatsAppService,
    ) {}

    public function supportsTemplate(WhatsAppTemplate $template): bool
    {
        $name = $this->resolveTemplateKey((string) $template->name);

        return in_array($name, [
            self::EVENT_REMINDER_TEMPLATE,
            self::LATEST_EVENT_REMINDER_TEMPLATE,
            self::FINAL_EVENT_REMINDER_TEMPLATE,
            self::EVENT_INVITATION_TEMPLATE,
        ], true);
    }

    public function buildPayload(WhatsAppTemplate $template, array $variables): array
    {
        if (! $this->supportsTemplate($template)) {
            throw new RuntimeException('Meta parameter builder does not support template: ' . $template->name);
        }

        $templateKey = $this->resolveTemplateKey((string) $template->name);
        $resolved = $this->normalizeVariables($variables);

        $templateComponents = $this->resolveTemplateComponents($template);
        $parameterFormat = strtoupper(trim((string) Arr::get($template->template_json, 'parameter_format', '')));
        $useNamedParameters = $parameterFormat === 'NAMED';
        $components = $this->buildDynamicComponents($templateKey, $templateComponents, $resolved, $useNamedParameters);

        // Prefer Meta's canonical language code from synced template metadata
        // (e.g. en_US). Generic DB values like "en" can be rejected by Graph.
        $languageCode = trim((string) Arr::get($template->template_json, 'language', (string) $template->language));
        if ($languageCode === '') {
            $languageCode = 'en';
        }
        $languageSource = Arr::has((array) $template->template_json, 'language') ? 'template_json.language' : 'template.language';

        if (empty($components)) {
            Log::warning('Meta template components unresolved; using fallback payload builder', [
                'template' => $template->name,
                'language' => $template->language,
                'template_id' => $template->id,
            ]);
            $components = $this->fallbackComponents($templateKey, $resolved);
        }

        if ((bool) config('whatsapp.debug_logging', false)) {
            Log::debug('Meta template payload built', [
                'template' => $template->name,
                'language' => $languageCode,
                'language_source' => $languageSource,
                'parameter_format' => $parameterFormat,
                'template_id' => $template->id,
                'components_count' => count($components),
                'component_parameter_counts' => array_map(
                    fn(array $component) => count((array) ($component['parameters'] ?? [])),
                    $components
                ),
            ]);
        }

        return [
            'name' => (string) $template->name,
            'language' => [
                'code' => $languageCode,
            ],
            'components' => $components,
        ];
    }

    public function buildPreview(WhatsAppTemplate $template, array $variables): string
    {
        if (! $this->supportsTemplate($template)) {
            throw new RuntimeException('Meta preview builder does not support template: ' . $template->name);
        }

        $resolved = $this->normalizeVariables($variables);
        $templateName = $this->resolveTemplateKey((string) $template->name);

        if ($templateName === self::LATEST_EVENT_REMINDER_TEMPLATE) {
            return trim(implode("\n", [
                'Hello ' . $resolved['name'] . ',',
                'This is a friendly reminder about your upcoming event.',
                '',
                'Event: ' . $resolved['event_title'],
                'Date: ' . $resolved['event_date'],
                'Time: ' . $resolved['event_time'],
                'Location: ' . $resolved['event_location'],
                '',
                'Join Here:',
                $resolved['event_meeting_link'],
                '',
                'We look forward to having you with us.',
                '',
                'If you have any questions or need assistance, simply reply to this message.',
                'Thanks!',
            ]));
        }

        if ($templateName === self::FINAL_EVENT_REMINDER_TEMPLATE) {
            return trim(implode("\n", [
                'Hello ' . $resolved['name'] . ',',
                '',
                'This is a reminder that you are registered for the following event:',
                '',
                'Event: ' . $resolved['event_title'],
                'Date: ' . $resolved['event_date'],
                'Time: ' . $resolved['event_time'],
                'Location: ' . $resolved['event_location'],
                '',
                'Access Link:',
                $resolved['event_meeting_link'],
                '',
                'Please ensure you join a few minutes before the scheduled start time.',
                '',
                'We look forward to your participation.',
                '',
                'Best regards,',
                'P23 Africa',
            ]));
        }

        if ($templateName === self::EVENT_INVITATION_TEMPLATE) {
            return trim(implode("\n", [
                'Hello ' . $resolved['name'] . ',',
                '',
                'P23 Africa is excited to invite you to join our upcoming event. This is a great opportunity to connect, learn, and engage with like-minded professionals and industry leaders.',
                '',
                'Event: ' . $resolved['event_title'],
                'Location: ' . $resolved['event_location'],
                'Register Here: ' . $resolved['event_registration_link'],
                '',
                'Book a seat for FREE today and reserve your spot before seats fill up.',
                'We look forward to seeing you there!',
                '— P23 Africa',
            ]));
        }

        return trim(implode("\n", [
            'Upcoming Event ' . $resolved['event_title'],
            'This is a reminder for your upcoming event.',
            'Event Title: ' . $resolved['event_title'],
            'Date: ' . $resolved['event_date'],
            'Time: ' . $resolved['event_time'],
            'Location: ' . $resolved['event_location'],
            'Meeting Link: ' . $resolved['event_meeting_link'],
            '',
            'If you have any questions, please feel free to reply to this message.',
            '',
            'Event Page: ' . $resolved['event_page_url'],
        ]));
    }

    public function normalizeVariables(array $variables): array
    {
        $eventPageUrl = trim((string) Arr::get($variables, 'event_page_url', ''));
        $eventRegistrationLink = trim((string) Arr::get(
            $variables,
            'event_registration_link',
            $eventPageUrl !== ''
                ? $eventPageUrl
                : Arr::get($variables, 'event_link', Arr::get($variables, 'event_meeting_link', Arr::get($variables, 'meeting_link', '')))
        ));
        $eventSlug = trim((string) Arr::get($variables, 'event_slug', Arr::get($variables, 'event_url_param', '')));

        if ($eventSlug === '' && $eventRegistrationLink !== '') {
            $path = trim((string) parse_url($eventRegistrationLink, PHP_URL_PATH), '/');
            $segments = $path === '' ? [] : explode('/', $path);
            $eventSlug = (string) end($segments);
        }

        return [
            'name' => trim((string) Arr::get($variables, 'name', Arr::get($variables, 'contact_name', 'there'))),
            'event_title' => trim((string) Arr::get($variables, 'event_title', Arr::get($variables, 'event_name', ''))),
            'event_date' => trim((string) Arr::get($variables, 'event_date', '')),
            'event_time' => trim((string) Arr::get($variables, 'event_time', '')),
            'event_location' => trim((string) Arr::get($variables, 'event_location', Arr::get($variables, 'location', ''))),
            'event_meeting_link' => trim((string) Arr::get($variables, 'event_meeting_link', Arr::get($variables, 'event_link', Arr::get($variables, 'meeting_link', '')))),
            'event_registration_link' => $eventRegistrationLink,
            'event_slug' => $eventSlug,
            'event_page_url' => $eventPageUrl !== '' ? $eventPageUrl : $eventRegistrationLink,
        ];
    }

    protected function buildDynamicComponents(string $templateName, array $components, array $resolved, bool $useNamedParameters = false): array
    {
        if (empty($components)) {
            return [];
        }

        $built = [];

        foreach ($components as $component) {
            if (!is_array($component)) {
                continue;
            }

            $type = strtolower(trim((string) Arr::get($component, 'type', '')));

            if ($type === 'header') {
                $tokens = $this->extractPlaceholderTokens((string) Arr::get($component, 'text', ''));
                if (!empty($tokens)) {
                    $built[] = [
                        'type' => 'header',
                        'parameters' => $this->buildTemplateParameters($this->headerValuePool($resolved, $templateName), $tokens, $resolved, $useNamedParameters),
                    ];
                }
                continue;
            }

            if ($type === 'body') {
                $tokens = $this->extractPlaceholderTokens((string) Arr::get($component, 'text', ''));
                if (!empty($tokens)) {
                    $built[] = [
                        'type' => 'body',
                        'parameters' => $this->buildTemplateParameters($this->bodyValuePool($resolved, $templateName), $tokens, $resolved, $useNamedParameters),
                    ];
                }
                continue;
            }

            if ($type === 'button') {
                $tokens = $this->extractPlaceholderTokens((string) Arr::get($component, 'url', Arr::get($component, 'text', '')));
                if (!empty($tokens)) {
                    $built[] = [
                        'type' => 'button',
                        'sub_type' => strtolower((string) Arr::get($component, 'sub_type', 'url')),
                        'index' => (string) Arr::get($component, 'index', '0'),
                        'parameters' => $this->buildTemplateParameters($this->buttonValuePool($resolved, $templateName), $tokens, $resolved, $useNamedParameters),
                    ];
                }
                continue;
            }

            if ($type === 'buttons') {
                $buttons = Arr::get($component, 'buttons', []);
                if (!is_array($buttons)) {
                    continue;
                }

                foreach ($buttons as $index => $button) {
                    if (!is_array($button)) {
                        continue;
                    }

                    $subType = strtolower(trim((string) Arr::get($button, 'type', Arr::get($button, 'sub_type', ''))));
                    $source = (string) Arr::get($button, 'url', Arr::get($button, 'text', ''));
                    $tokens = $this->extractPlaceholderTokens($source);

                    if (empty($tokens)) {
                        continue;
                    }

                    $built[] = [
                        'type' => 'button',
                        'sub_type' => $subType === '' ? 'url' : $subType,
                        'index' => (string) $index,
                        'parameters' => $this->buildTemplateParameters($this->buttonValuePool($resolved, $templateName), $tokens, $resolved, $useNamedParameters),
                    ];
                }
            }
        }

        return $built;
    }

    protected function fallbackComponents(string $templateName, array $resolved): array
    {
        if (strtolower(trim($templateName)) === self::EVENT_INVITATION_TEMPLATE) {
            return [
                [
                    'type' => 'header',
                    'parameters' => [
                        [
                            'type' => 'text',
                            'text' => $resolved['name'],
                        ],
                    ],
                ],
                [
                    'type' => 'body',
                    'parameters' => [
                        [
                            'type' => 'text',
                            'text' => $resolved['event_title'],
                        ],
                        [
                            'type' => 'text',
                            'text' => $resolved['event_location'],
                        ],
                        [
                            'type' => 'text',
                            'text' => $resolved['event_registration_link'],
                        ],
                    ],
                ],
                [
                    'type' => 'button',
                    'sub_type' => 'url',
                    'index' => '0',
                    'parameters' => [
                        [
                            'type' => 'text',
                            'text' => $resolved['event_slug'],
                        ],
                    ],
                ],
            ];
        }

        // Conservative fallback for legacy local templates that do not have
        // synchronized Meta component definitions in template_json.
        return [
            [
                'type' => 'header',
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => $resolved['event_title'],
                    ],
                ],
            ],
            [
                'type' => 'body',
                'parameters' => array_map(fn(string $value) => [
                    'type' => 'text',
                    'text' => $value,
                ], [
                    $resolved['event_title'],
                    $resolved['event_date'],
                    $resolved['event_time'],
                    $resolved['event_location'],
                    $resolved['event_meeting_link'],
                ]),
            ],
            [
                'type' => 'button',
                'sub_type' => 'url',
                'index' => '0',
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => $resolved['event_slug'],
                    ],
                ],
            ],
        ];
    }

    protected function resolveTemplateComponents(WhatsAppTemplate $template): array
    {
        // Always prefer the live Meta definition — local copy may be incomplete
        // (e.g. template_json only has a partial component list from a prior sync).
        // Fall back to local only if Meta is unreachable.
        $metaComponents = $this->fetchLiveComponents($template);
        if (!empty($metaComponents)) {
            return $metaComponents;
        }

        $localComponents = Arr::get($template->template_json, 'components', []);
        if (is_array($localComponents) && !empty($localComponents)) {
            Log::warning('Meta template fetch failed; falling back to local template_json components', [
                'template' => $template->name,
                'language' => $template->language,
                'template_id' => $template->id,
                'local_component_count' => count($localComponents),
            ]);

            return $localComponents;
        }

        return [];
    }

    protected function fetchLiveComponents(WhatsAppTemplate $template): array
    {
        $cursor = null;

        for ($page = 0; $page < 10; $page++) {
            try {
                $response = $this->whatsAppService->fetchMessageTemplatesPage(null, $cursor, 100);
            } catch (\Throwable $exception) {
                Log::warning('Meta template fetch failed during payload build', [
                    'template' => $template->name,
                    'language' => $template->language,
                    'template_id' => $template->id,
                    'error' => $exception->getMessage(),
                ]);

                return [];
            }

            $items = (array) Arr::get($response, 'data', []);

            foreach ($items as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $name = strtolower(trim((string) Arr::get($item, 'name', '')));
                if ($name !== strtolower(trim((string) $template->name))) {
                    continue;
                }

                $metaLanguage = (string) Arr::get($item, 'language', '');
                if (!$this->languagesMatch((string) $template->language, $metaLanguage)) {
                    continue;
                }

                $metaComponents = Arr::get($item, 'components', []);
                if (!is_array($metaComponents) || empty($metaComponents)) {
                    continue;
                }

                // Persist so future sends skip the extra API round-trip.
                $this->persistComponents($template, $item, $metaComponents);

                return $metaComponents;
            }

            $cursor = Arr::get($response, 'paging.cursors.after');
            if (!is_string($cursor) || trim($cursor) === '') {
                break;
            }
        }

        return [];
    }

    protected function persistComponents(WhatsAppTemplate $template, array $metaItem, array $metaComponents): void
    {
        $current = is_array($template->template_json) ? $template->template_json : [];
        $template->template_json = array_merge($current, [
            'components' => $metaComponents,
            'name' => Arr::get($metaItem, 'name'),
            'language' => Arr::get($metaItem, 'language'),
            'parameter_format' => Arr::get($metaItem, 'parameter_format', Arr::get($current, 'parameter_format')),
        ]);

        $metadata = is_array($template->editor_metadata) ? $template->editor_metadata : [];
        $metadata['meta_components_refreshed_at'] = now()->toIso8601String();
        $template->editor_metadata = $metadata;
        $template->save();
    }

    protected function languagesMatch(string $left, string $right): bool
    {
        $normalize = static function (string $value): string {
            return strtolower(str_replace('-', '_', trim($value)));
        };

        $a = $normalize($left);
        $b = $normalize($right);

        if ($a === '' || $b === '') {
            return true;
        }

        if ($a === $b) {
            return true;
        }

        return strtok($a, '_') === strtok($b, '_');
    }

    protected function headerValuePool(array $resolved, string $templateName = ''): array
    {
        if (strtolower(trim($templateName)) === self::EVENT_INVITATION_TEMPLATE) {
            return [$resolved['name']];
        }

        return [
            $resolved['name'],
            $resolved['event_title'],
            $resolved['event_date'],
            $resolved['event_time'],
            $resolved['event_location'],
            $resolved['event_meeting_link'],
            $resolved['event_page_url'],
        ];
    }

    protected function bodyValuePool(array $resolved, string $templateName = ''): array
    {
        if (strtolower(trim($templateName)) === self::EVENT_INVITATION_TEMPLATE) {
            return [
                $resolved['event_title'],
                $resolved['event_location'],
                $resolved['event_registration_link'],
            ];
        }

        return [
            $resolved['name'],
            $resolved['event_title'],
            $resolved['event_title'],
            $resolved['event_date'],
            $resolved['event_time'],
            $resolved['event_location'],
            $resolved['event_meeting_link'],
            $resolved['event_page_url'],
            $resolved['event_slug'],
        ];
    }

    protected function buttonValuePool(array $resolved, string $templateName = ''): array
    {
        if (strtolower(trim($templateName)) === self::EVENT_INVITATION_TEMPLATE) {
            return [
                $resolved['event_slug'],
                $resolved['event_registration_link'],
                $resolved['event_page_url'],
            ];
        }

        return [
            $resolved['event_slug'],
            $resolved['event_page_url'],
            $resolved['event_meeting_link'],
            $resolved['name'],
            $resolved['event_title'],
        ];
    }

    protected function buildTextParameters(array $pool, int $count): array
    {
        $parameters = [];
        $last = trim((string) end($pool));
        if ($last === '') {
            $last = '-';
        }

        for ($index = 0; $index < $count; $index++) {
            $value = trim((string) ($pool[$index] ?? $last));
            if ($value === '') {
                $value = $last;
            }

            $parameters[] = [
                'type' => 'text',
                'text' => $value,
            ];
        }

        return $parameters;
    }

    protected function buildTemplateParameters(array $pool, array $tokens, array $resolved, bool $useNamedParameters): array
    {
        $parameters = [];
        $last = trim((string) end($pool));
        if ($last === '') {
            $last = '-';
        }

        foreach (array_values($tokens) as $index => $token) {
            $token = trim((string) $token);
            $isNumeric = ctype_digit($token);

            if ($isNumeric) {
                $position = max(0, ((int) $token) - 1);
                $value = trim((string) ($pool[$position] ?? $last));
            } else {
                $value = $this->resolveNamedPlaceholderValue($token, $resolved, $pool, $index);
            }

            if ($value === '') {
                $value = $last;
            }

            $parameter = [
                'type' => 'text',
                'text' => $value,
            ];

            if ($useNamedParameters && !$isNumeric) {
                $parameter['parameter_name'] = $token;
            }

            $parameters[] = $parameter;
        }

        return $parameters;
    }

    protected function resolveNamedPlaceholderValue(string $name, array $resolved, array $pool, int $index): string
    {
        $key = strtolower(trim($name));
        $aliases = [
            'event_name' => 'event_title',
            'name' => 'name',
            'contact_name' => 'name',
            'title' => 'event_title',
            'date' => 'event_date',
            'time' => 'event_time',
            'location' => 'event_location',
            'event_link' => 'event_meeting_link',
            'meeting_link' => 'event_meeting_link',
            'link' => 'event_meeting_link',
            'event_registration_link' => 'event_registration_link',
            'registration_link' => 'event_registration_link',
            'register_link' => 'event_registration_link',
            'slug' => 'event_slug',
            'event_url_param' => 'event_slug',
            'url' => 'event_page_url',
            'event_url' => 'event_page_url',
        ];

        $resolvedKey = $aliases[$key] ?? $key;
        $value = trim((string) ($resolved[$resolvedKey] ?? ''));
        if ($value !== '') {
            return $value;
        }

        $fallback = trim((string) ($pool[$index] ?? ''));
        if ($fallback !== '') {
            return $fallback;
        }

        $last = trim((string) end($pool));
        return $last === '' ? '-' : $last;
    }

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

    protected function countPlaceholders(string $text): int
    {
        // Count Meta-style numbered placeholders {{1}}, {{2}}, etc.
        // (the form Meta returns from the Graph API templates endpoint)
        preg_match_all('/\{\{\s*\d+\s*\}\}/', $text, $numbered);
        if (!empty($numbered[0])) {
            return count(array_unique($numbered[0]));
        }

        // Also count named/legacy placeholders {{event_title}}, {{name}}, etc.
        // so templates stored with editor-style variables still get a param count.
        preg_match_all('/\{\{\s*[a-zA-Z_][a-zA-Z0-9_\.]*\s*\}\}/', $text, $named);
        return count(array_unique($named[0]));
    }

    protected function assertRequired(array $variables, array $keys): void
    {
        foreach ($keys as $key) {
            if (trim((string) ($variables[$key] ?? '')) === '') {
                throw new RuntimeException('Missing required Meta template variable: ' . $key);
            }
        }
    }

    protected function resolveTemplateKey(string $name): string
    {
        $normalized = strtolower(trim($name));

        foreach (
            [
                self::EVENT_REMINDER_TEMPLATE,
                self::LATEST_EVENT_REMINDER_TEMPLATE,
                self::FINAL_EVENT_REMINDER_TEMPLATE,
                self::EVENT_INVITATION_TEMPLATE,
            ] as $baseName
        ) {
            if ($normalized === $baseName || preg_match('/^' . preg_quote($baseName, '/') . '_v\d+$/', $normalized)) {
                return $baseName;
            }
        }

        return $normalized;
    }
}
