<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates /admin/* routes. See LARAVEL-DYNAMIZATION-PLAN.md Part 6.1.
 * Redirects to /admin/login if the request is unauthenticated, lacks
 * is_admin, or the account isn't active (pending/suspended).
 */
class CheckAdminRole
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $requiredRole = null): Response
    {
        $user = $request->user();

        if (! $user || ! $user->canAccessAdminPanel()) {
            auth()->logout();

            return redirect()->route('admin.login');
        }

        if ($requiredRole !== null && $user->role !== $requiredRole && ! $user->isSuperAdmin()) {
            abort(403);
        }

        return $next($request);
    }
}
