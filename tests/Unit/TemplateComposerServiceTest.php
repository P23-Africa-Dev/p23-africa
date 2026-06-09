<?php

namespace Tests\Unit;

use App\Models\WhatsAppTemplate;
use App\Services\WhatsApp\TemplateComposerService;
use Tests\TestCase;

class TemplateComposerServiceTest extends TestCase
{
    public function test_extracts_named_variable_from_simple_section_shape(): void
    {
        $template = new WhatsAppTemplate([
            'name' => 'consultation_outreach',
            'language' => 'en',
            'template_json' => [
                'header' => 'Hi {{name}},',
                'body' => "I'm Donald from P23 Africa.",
                'footer' => 'P23 Africa',
            ],
        ]);

        $service = app(TemplateComposerService::class);
        $variables = $service->extractVariables($template);

        $this->assertCount(1, $variables);
        $this->assertSame('name', $variables[0]['key']);
    }

    public function test_extracts_named_variable_from_component_map_shape(): void
    {
        $template = new WhatsAppTemplate([
            'name' => 'outreach',
            'language' => 'en',
            'template_json' => [
                'components' => [
                    'HEADER' => [
                        'text' => 'Hi {{name}},',
                    ],
                    'BODY' => [
                        'text' => 'Body text here',
                    ],
                ],
            ],
        ]);

        $service = app(TemplateComposerService::class);
        $variables = $service->extractVariables($template);

        $this->assertNotEmpty($variables);
        $this->assertSame('name', $variables[0]['key']);
    }

    public function test_render_preview_uses_simple_sections_and_replaces_variables(): void
    {
        $template = new WhatsAppTemplate([
            'name' => 'consultation_outreach',
            'language' => 'en',
            'template_json' => [
                'header' => 'Hi {{name}},',
                'body' => 'Would you be available for a brief chat?',
                'footer' => 'P23 Africa',
            ],
        ]);

        $service = app(TemplateComposerService::class);
        $preview = $service->renderPreview($template, ['name' => 'Michael']);

        $this->assertStringContainsString('Hi Michael,', $preview);
        $this->assertStringContainsString('Would you be available for a brief chat?', $preview);
        $this->assertStringContainsString('P23 Africa', $preview);
    }

    public function test_render_preview_falls_back_to_preview_content_when_components_missing(): void
    {
        $template = new WhatsAppTemplate([
            'name' => 'outreach_preview_fallback',
            'language' => 'en',
            'template_json' => [],
            'preview_content' => 'Hi {{name}},<br>I am Donald from P23 Africa.',
        ]);

        $service = app(TemplateComposerService::class);
        $preview = $service->renderPreview($template, ['name' => 'Sarah']);

        $this->assertStringContainsString('Hi Sarah,', $preview);
        $this->assertStringContainsString('I am Donald from P23 Africa.', $preview);
    }
}
