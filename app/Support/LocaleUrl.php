<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class LocaleUrl
{
    public const LOCALES = ['en', 'fr', 'de', 'nl'];

    public const PREFIXED = ['fr', 'de', 'nl'];

    public static function current(): string
    {
        $locale = app()->getLocale();

        return in_array($locale, self::LOCALES, true) ? $locale : 'en';
    }

    public static function route(string $name, array $parameters = [], bool $absolute = true): string
    {
        $locale = self::current();

        if ($locale !== 'en' && Route::has('locale.' . $name)) {
            return route('locale.' . $name, array_merge(['locale' => $locale], $parameters), $absolute);
        }

        return route($name, $parameters, $absolute);
    }

    public static function to(string $path): string
    {
        $hash = '';
        if (str_contains($path, '#')) {
            [$path, $fragment] = explode('#', $path, 2);
            $hash = '#' . $fragment;
        }

        $path = trim($path, '/');
        $locale = self::current();
        $prefix = in_array($locale, self::PREFIXED, true) ? '/' . $locale : '';

        if ($path === '') {
            return url($prefix === '' ? '/' : $prefix) . $hash;
        }

        return url($prefix . '/' . $path) . $hash;
    }

    public static function switch(string $target): string
    {
        $target = in_array($target, self::LOCALES, true) ? $target : 'en';
        $route = request()->route();
        $url = url('/');

        if ($route && $route->getName()) {
            $name = $route->getName();
            if (str_starts_with($name, 'locale.')) {
                $name = substr($name, strlen('locale.'));
            }

            $parameters = $route->parameters();
            unset($parameters['locale']);

            if ($target === 'en' && Route::has($name)) {
                $url = route($name, $parameters);
            } elseif ($target !== 'en' && Route::has('locale.' . $name)) {
                $url = route('locale.' . $name, array_merge(['locale' => $target], $parameters));
            } else {
                $url = $target === 'en' ? url('/') : url('/' . $target);
            }
        } else {
            $path = trim(request()->path(), '/');
            foreach (self::PREFIXED as $code) {
                if ($path === $code) {
                    $path = '';
                    break;
                }
                if (str_starts_with($path, $code . '/')) {
                    $path = substr($path, strlen($code) + 1);
                    break;
                }
            }

            if ($target === 'en') {
                $url = $path === '' ? url('/') : url('/' . $path);
            } else {
                $url = $path === '' ? url('/' . $target) : url('/' . $target . '/' . $path);
            }
        }

        $query = request()->getQueryString();
        if ($query) {
            $url .= (str_contains($url, '?') ? '&' : '?') . $query;
        }

        return $url;
    }

    public static function alternates(): array
    {
        $links = [];
        foreach (self::LOCALES as $code) {
            $links[$code] = self::switch($code);
        }
        $links['x-default'] = self::switch('en');

        return $links;
    }

    public static function savedChoice(Request $request): ?string
    {
        $choice = $request->cookie('locale_choice');
        if (is_string($choice) && in_array($choice, self::LOCALES, true)) {
            return $choice;
        }

        if ($request->cookie('locale_prompt')) {
            return 'en';
        }

        return null;
    }

    public static function browserLocale(Request $request): ?string
    {
        $header = (string) $request->header('Accept-Language', '');
        if (preg_match('/^\s*([a-z]{2})/i', $header, $matches) !== 1) {
            return null;
        }

        $primary = strtolower($matches[1]);

        return in_array($primary, self::PREFIXED, true) ? $primary : null;
    }

    public static function suggestedLocale(Request $request): ?string
    {
        if (self::savedChoice($request) !== null || self::current() !== 'en') {
            return null;
        }

        return self::browserLocale($request);
    }
}
