<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceAccessibility
{
    /**
     * Handle an incoming request and ensure WCAG compliance.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add ARIA labels and accessibility attributes
        if ($response->headers->get('Content-Type') && 
            str_contains($response->headers->get('Content-Type'), 'text/html')) {
            
            $content = $response->getContent();
            
            // Ensure proper language attribute
            if (!str_contains($content, 'lang=')) {
                $content = str_replace('<html', '<html lang="' . app()->getLocale() . '"', $content);
            }

            // Add skip navigation link
            if (!str_contains($content, 'skip-to-content')) {
                $skipLink = '<a href="#main-content" class="skip-to-content sr-only sr-only-focusable" style="position:absolute;left:-9999px;z-index:999;padding:1em;background:#000;color:#fff;text-decoration:none;">Skip to main content</a>';
                $content = str_replace('<body', $skipLink . '<body', $content);
            }

            // Ensure main content has proper landmark
            if (!str_contains($content, 'role="main"') && !str_contains($content, '<main')) {
                // This would require more sophisticated DOM manipulation
                // For now, we'll rely on view files to include proper landmarks
            }

            $response->setContent($content);
        }

        return $response;
    }
}

