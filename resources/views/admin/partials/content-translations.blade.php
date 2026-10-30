@php
    $locales = ['fr' => 'French', 'de' => 'German', 'nl' => 'Dutch'];
    $existing = isset($model) ? $model->translationMap() : [];
    $longFields = $longFields ?? ['content_1', 'content_2', 'description'];
@endphp
<fieldset class="border rounded p-3 mt-4 mb-4">
    <legend class="float-none w-auto px-2">Translations</legend>
    <p class="text-muted">Optional. An empty field keeps the English text on that language page.</p>
    @foreach ($locales as $code => $label)
        <h4 class="h6 mt-3">{{ $label }}</h4>
        @foreach ($translationFields as $field => $fieldLabel)
            <label class="form-label" for="translation-{{ $code }}-{{ $field }}">{{ $fieldLabel }} ({{ $label }})</label>
            @if (in_array($field, $longFields, true))
                <textarea id="translation-{{ $code }}-{{ $field }}" name="translations[{{ $code }}][{{ $field }}]" class="form-control mb-3" rows="4">{{ old('translations.'.$code.'.'.$field, $existing[$code][$field] ?? '') }}</textarea>
            @else
                <input id="translation-{{ $code }}-{{ $field }}" type="text" name="translations[{{ $code }}][{{ $field }}]" class="form-control mb-3" value="{{ old('translations.'.$code.'.'.$field, $existing[$code][$field] ?? '') }}">
            @endif
        @endforeach
    @endforeach
</fieldset>
