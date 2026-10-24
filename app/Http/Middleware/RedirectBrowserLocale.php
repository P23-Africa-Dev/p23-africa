<?php

namespace App\Http\Middleware;

use App\Support\LocaleUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBrowserLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        if ($this->isCrawler($request) || LocaleUrl::savedChoice($request) !== null) {
            return $next($request);
        }

        $locale = LocaleUrl::browserLocale($request);
        if ($locale === null) {
            return $next($request);
        }

        $path = trim($request->path(), '/');
        $target = $path === '' ? '/' . $locale : '/' . $locale . '/' . $path;
        $query = $request->getQueryString();
        if ($query) {
            $target .= '?' . $query;
        }

        return redirect($target, 302);
    }

    private function isCrawler(Request $request): bool
    {
        $agent = strtolower((string) $request->userAgent());
        if ($agent === '') {
            return false;
        }

        return preg_match('/bot|crawl|spider|slurp|mediapartners|facebookexternalhit|inspectiontool|preview/', $agent) === 1;
    }
}
