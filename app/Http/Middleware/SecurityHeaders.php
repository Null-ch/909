<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies baseline OWASP-recommended response headers to every request.
 * The CSP allowlists the specific third-party origins this app's layout
 * (resources/views/layouts/app.blade.php) actually loads from — Google
 * Fonts and the Font Awesome CDN — plus 'self' for everything else. If a
 * new external asset host is ever added to a layout, it must be added
 * here too, or the browser will silently block it.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        if (! $response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', implode('; ', [
                "default-src 'self'",
                // 'unsafe-eval' is required by Alpine.js (bundled with Livewire):
                // it evaluates x-data/x-show/@click expression strings via
                // `new Function()`, which the CSP spec classifies as eval.
                // Alpine ships a CSP-safe build that avoids this, but adopting
                // it means pre-compiling every x-data expression — a separate,
                // larger change than this security pass.
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
                "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
                "img-src 'self' data: blob:",
                "connect-src 'self'",
                "frame-ancestors 'none'",
                "base-uri 'self'",
                "form-action 'self'",
            ]));
        }

        return $response;
    }
}
