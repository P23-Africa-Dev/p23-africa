<?php

namespace App\Support;

class CaseStudies
{
    public static function all(): array
    {
        return json_decode(file_get_contents(resource_path('data/case-studies.json')), true, 512, JSON_THROW_ON_ERROR);
    }
}
