<?php

namespace App\Http\Middleware;

use App\Models\UserActivityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity {
    /**
     * Handle an incoming request.
     */
    public function handle( Request $request, Closure $next ): Response {
        $response = $next( $request );

        $user = $request->user();
        if ( ! $user ) {
            return $response;
        }

        $method = strtoupper( $request->method() );
        if ( in_array( $method, ['HEAD', 'OPTIONS'], true ) ) {
            return $response;
        }

        UserActivityLog::create( [
            'user_id' => $user->id,
            'event' => 'visit',
            'method' => $method,
            'path' => '/' . ltrim( $request->path(), '/' ),
            'route_name' => optional( $request->route() )->getName(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ] );

        return $response;
    }
}
