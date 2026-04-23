<?php
/**
 * Purpose: Restrict routes to admin role only
 * Used by: routes/web.php admin route group
 * Dependencies: Auth
 * Main functions: handle()
 * Side effects: Redirects non-admin to home
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403);
        }
        return $next($request);
    }
}
