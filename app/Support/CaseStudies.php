<?php

namespace App\Support;

class CaseStudies
{
    public static function all(): array
    {
        $studies = json_decode(file_get_contents(resource_path('data/case-studies.json')), true, 512, JSON_THROW_ON_ERROR);

        return array_map(fn(array $study) => self::translate($study), $studies);
    }

    private static function translate(array $study): array
    {
        if (app()->getLocale() === 'en') {
            return $study;
        }

        $translated = trans('case_studies.' . $study['slug']);
        if (!is_array($translated)) {
            return $study;
        }

        foreach (['title', 'client', 'sector', 'market', 'service', 'result', 'timeline', 'summary', 'challenge', 'image_alt', 'validation', 'outcome', 'cta_heading', 'cta_description'] as $field) {
            if (!empty($translated[$field]) && is_string($translated[$field])) {
                $study[$field] = $translated[$field];
            }
        }

        if (!empty($translated['process']) && is_array($study['process'] ?? null)) {
            foreach ($study['process'] as $index => $step) {
                foreach (['title', 'description'] as $field) {
                    if (!empty($translated['process'][$index][$field])) {
                        $study['process'][$index][$field] = $translated['process'][$index][$field];
                    }
                }
            }
        }

        if (!empty($translated['metrics']) && is_array($study['metrics'] ?? null)) {
            foreach ($study['metrics'] as $index => $metric) {
                if (!empty($translated['metrics'][$index]['label'])) {
                    $study['metrics'][$index]['label'] = $translated['metrics'][$index]['label'];
                }
            }
        }

        return $study;
    }
}
