<?php

namespace Tests\Unit;

use App\Services\WhatsApp\MetaTemplateBuilderService;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MetaTemplateBuilderServiceTest extends TestCase
{
    public function test_normalize_template_name_converts_to_meta_compatible_value(): void
    {
        $service = new MetaTemplateBuilderService();

        $normalized = $service->normalizeTemplateName('  Welcome Promo 2026!  ');

        $this->assertSame('welcome_promo_2026', $normalized);
    }

    public function test_build_simple_template_supports_named_variables_and_buttons(): void
    {
        $service = new MetaTemplateBuilderService();

        $payload = $service->buildSimpleTemplate([
            'variable_type' => 'named',
            'header_type' => 'text',
            'header_text' => 'Hello {{name}}',
            'body_text' => 'Event {{event_name}} is on {{event_date}}.',
            'footer_text' => 'See you there',
            'buttons' => [
                [
                    'type' => 'visit_website',
                    'label' => 'Register',
                    'value' => 'https://example.com/register/{{seat_code}}',
                ],
            ],
        ]);

        $this->assertSame('NAMED', $payload['parameter_format']);
        $this->assertCount(4, $payload['components']);
        $this->assertSame('BUTTONS', $payload['components'][3]['type']);
        $this->assertSame('URL', $payload['components'][3]['buttons'][0]['type']);
    }

    public function test_validate_template_json_rejects_named_tokens_in_number_mode(): void
    {
        $service = new MetaTemplateBuilderService();

        $this->expectException(ValidationException::class);

        $service->validateAndNormalizeTemplateJson([
            'components' => [
                [
                    'type' => 'BODY',
                    'text' => 'Hello {{name}}',
                ],
            ],
        ], 'number');
    }

    public function test_normalize_buttons_rejects_duplicate_labels(): void
    {
        $service = new MetaTemplateBuilderService();

        $this->expectException(ValidationException::class);

        $service->normalizeButtons([
            [
                'type' => 'quick_reply',
                'label' => 'Join now',
                'value' => '',
            ],
            [
                'type' => 'visit_website',
                'label' => 'Join now',
                'value' => 'https://example.com',
            ],
        ]);
    }
}
