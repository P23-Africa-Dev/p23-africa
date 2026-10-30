<?php

namespace App\Models\Concerns;

use App\Models\ContentTranslation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasContentTranslations
{
    public function contentTranslations(): MorphMany
    {
        return $this->morphMany(ContentTranslation::class, 'translatable');
    }

    public function translated(string $field): mixed
    {
        $locale = app()->getLocale();

        if ($locale === 'en') {
            return $this->getAttribute($field);
        }

        $translations = $this->relationLoaded('contentTranslations')
            ? $this->contentTranslations
            : $this->contentTranslations()->get();

        $value = $translations
            ->first(fn (ContentTranslation $row) => $row->locale === $locale && $row->field === $field)
            ?->value;

        if ($value === null || $value === '') {
            return $this->getAttribute($field);
        }

        return $value;
    }

    public function translationMap(): array
    {
        $map = [];
        $translations = $this->relationLoaded('contentTranslations')
            ? $this->contentTranslations
            : $this->contentTranslations()->get();

        foreach ($translations as $row) {
            $map[$row->locale][$row->field] = $row->value;
        }

        return $map;
    }

    public function syncTranslations(array $input, array $fields): void
    {
        foreach (['fr', 'de', 'nl'] as $locale) {
            foreach ($fields as $field) {
                $value = $input[$locale][$field] ?? null;
                $value = is_string($value) ? trim($value) : null;

                if ($value === null || $value === '') {
                    $this->contentTranslations()
                        ->where('locale', $locale)
                        ->where('field', $field)
                        ->delete();
                    continue;
                }

                $this->contentTranslations()->updateOrCreate(
                    ['locale' => $locale, 'field' => $field],
                    ['value' => $value]
                );
            }
        }
    }
}
