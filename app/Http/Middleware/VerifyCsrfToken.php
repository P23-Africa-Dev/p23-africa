<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add routes that should bypass CSRF (use sparingly!)
        // Example: 'webhook/*', 'api/*'
        // DO NOT add your form routes here
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next)
    {
        // Add custom logging for debugging (remove in production)
        if ($request->isMethod('post') && !$this->isReading($request)) {
            $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
            
            if (!$token) {
                Log::warning('CSRF token missing from request', [
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'referer' => $request->header('referer')
                ]);
            }
        }

        return parent::handle($request, $next);
    }

    /**
     * Determine if the HTTP request uses a 'read' verb.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isReading($request)
    {
        return in_array($request->method(), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Handle token mismatch exception with better logging
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Session\TokenMismatchException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function tokensMatch($request)
    {
        $token = $this->getTokenFromRequest($request);
        
        $match = parent::tokensMatch($request);
        
        if (!$match) {
            Log::error('CSRF token mismatch', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'session_token' => substr($request->session()->token(), 0, 10) . '...',
                'request_token' => $token ? substr($token, 0, 10) . '...' : 'null',
                'user_agent' => $request->userAgent()
            ]);
        }
        
        return $match;
    }
}