<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // Check against provided roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect appropriately if unauthorized
        if ($user->role === 'affiliate') {
            return redirect()->route('affiliate.dashboard');
        }

        if (in_array($user->role, ['super_admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        }

        // Default redirect to home
        return redirect('/');
    }
}
