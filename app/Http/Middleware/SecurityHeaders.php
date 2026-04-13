<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders {
    public function handle( Request $request, Closure $next ): Response {
        $response = $next( $request );

        $csp = "default-src 'self'; " .
            "base-uri 'self'; " .
            "form-action 'self'; " .
            "frame-ancestors 'self'; " .
            "img-src 'self' data: blob:; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.tailwindcss.com; " .
            "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.tailwindcss.com; " .
            "connect-src 'self';";

        $response->headers->set( 'Content-Security-Policy', $csp );
        $response->headers->set( 'Referrer-Policy', 'strict-origin-when-cross-origin' );
        $response->headers->set( 'X-Content-Type-Options', 'nosniff' );
        $response->headers->set( 'X-Frame-Options', 'SAMEORIGIN' );
        $response->headers->set( 'Permissions-Policy', 'camera=(), microphone=(), geolocation=(), fullscreen=(self)' );

        if ( $request->isSecure() ) {
            $response->headers->set( 'Strict-Transport-Security', 'max-age=15552000; includeSubDomains' );
        }

        return $response;
    }
}
