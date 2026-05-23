<?php

namespace Tests\Unit;

use App\Services\WhatsApp\TemplateRendererService;
use Tests\TestCase;

class TemplateRendererServiceTest extends TestCase
{
    public function test_render_json_replaces_variables_for_preview(): void
    {
        $service = app(TemplateRendererService::class);

        $payload = [
            'components' => [
                [
                    'type' => 'BODY',
                    'text' => 'Hello {{name}} from {{company}}',
                ],
            ],
        ];

        $result = $service->renderJson($payload, [
            'name' => 'Ada',
            'company' => 'P23 Africa',
        ]);

        $this->assertSame('Hello Ada from P23 Africa', $result['rendered_content']['components'][0]['text']);
        $this->assertStringContainsString('Hello Ada from P23 Africa', $result['preview_content']);
    }

    public function test_render_html_sanitizes_unsafe_tags(): void
    {
        $service = app(TemplateRendererService::class);

        $html = '<h1>Welcome</h1><script>alert(1)</script><p>Hello {{name}}</p>';

        $result = $service->renderHtml($html, [
            'name' => 'Ada',
        ]);

        $this->assertStringNotContainsString('<script>', $result['stored_content']);
        $this->assertStringContainsString('Welcome', $result['preview_content']);
        $this->assertStringContainsString('Hello Ada', strip_tags($result['preview_content']));
    }
}
