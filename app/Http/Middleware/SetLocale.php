<?php

namespace App\Http\Middleware;

use App\Support\LocaleUrl;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if (is_string($locale) && in_array($locale, LocaleUrl::PREFIXED, true)) {
            App::setLocale($locale);
            URL::defaults(['locale' => $locale]);
            $request->route()?->forgetParameter('locale');
        } else {
            App::setLocale('en');
        }

        return $next($request);
    }
}
