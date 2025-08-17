<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Default message for access denied.
     */
    private const ACCESS_DENIED_MESSAGE = 'Access denied. You do not have permission to access this area.';

    /**
     * Intercepts the request and checks if the user is an admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = Auth::user();

        // Ensure the user is authenticated and has admin privileges (boolean true)
        if (!$user || !(bool) $user->is_admin) {
            return $this->denyAccess($request); // Deny access if not admin
        }

        // User is admin, allow the request to proceed
        return $next($request);
    }

    /**
     * Returns an appropriate access denied response (JSON or web).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    private function denyAccess(Request $request): mixed
    {
        if ($request->expectsJson()) {
            // Return JSON response for API requests
            return response()->json(['message' => self::ACCESS_DENIED_MESSAGE], 403);
        }

        // Abort with HTTP 403 for web requests
        abort(403, self::ACCESS_DENIED_MESSAGE);
    }
}
