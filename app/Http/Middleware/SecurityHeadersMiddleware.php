<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = $this->generateNonce();

        /** @var Response $response */
        $response = $next($request);

        $csp = implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "object-src 'none'",
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com",
            "script-src-elem 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com",
            "script-src-attr 'unsafe-inline'",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com",
            "style-src-elem 'self' 'nonce-{$nonce}' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com",
            "style-src-attr 'unsafe-inline'",
            "font-src 'self' data: https://cdnjs.cloudflare.com https://fonts.gstatic.com",
            "img-src 'self' data: blob: https:",
            "media-src 'self' blob: https:",
            "frame-src 'self' https://www.youtube.com https://maps.google.com https://www.google.com",
            "connect-src 'self'",
        ]);

        $this->injectNonceIntoHtml($response, $nonce);

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-site');
        $response->headers->set('Origin-Agent-Cluster', '?1');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->set('X-DNS-Prefetch-Control', 'off');

        if ($request->isSecure() || $request->header('X-Forwarded-Proto') === 'https') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    protected function generateNonce(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(16)), '+/', '-_'), '=');
    }

    protected function injectNonceIntoHtml(Response $response, string $nonce): void
    {
        $contentType = (string) $response->headers->get('Content-Type', '');

        if (stripos($contentType, 'text/html') === false) {
            return;
        }

        $content = $response->getContent();

        if (! is_string($content) || $content === '') {
            return;
        }

        $escapedNonce = htmlspecialchars($nonce, ENT_QUOTES, 'UTF-8');

        $content = preg_replace(
            '/<script\b(?![^>]*\bnonce=)/i',
            '<script nonce="'.$escapedNonce.'"',
            $content
        );

        $content = preg_replace(
            '/<style\b(?![^>]*\bnonce=)/i',
            '<style nonce="'.$escapedNonce.'"',
            $content
        );

        if (stripos($content, 'name="csp-nonce"') === false) {
            $content = preg_replace(
                '/<\/head>/i',
                '    <meta name="csp-nonce" content="'.$escapedNonce.'">'."\n</head>",
                $content,
                1
            );
        }

        $response->setContent($content);
    }
}
