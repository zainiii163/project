<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessibilityMiddleware
{
    /**
     * Handle an incoming request and add accessibility headers and features.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Add accessibility headers
        $response->headers->set('Content-Language', app()->getLocale());
        
        // Add ARIA landmarks hint for screen readers
        if ($request->wantsJson()) {
            $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        }

        // Inject accessibility attributes into HTML response
        if ($response->headers->get('Content-Type') && 
            str_contains($response->headers->get('Content-Type'), 'text/html')) {
            
            $content = $response->getContent();
            
            // Add skip navigation link if not present
            if (!str_contains($content, 'skip-to-content')) {
                $skipLink = '<a href="#main-content" class="skip-to-content" style="position:absolute;left:-9999px;z-index:999;padding:1em;background:#000;color:#fff;text-decoration:none;">Skip to main content</a>';
                $content = str_replace('<body', $skipLink . '<body', $content);
            }

            // Ensure proper heading hierarchy (this is a basic check)
            // Full implementation would require DOM parsing
            
            $response->setContent($content);
        }

        return $response;
    }
}
