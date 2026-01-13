<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect()->route('web.login');
        }

        $userRole = auth()->user()->role;

        if (!$userRole || !in_array($userRole->name, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Required role: ' . implode(', ', $roles)], 403);
            }
            abort(403, 'Unauthorized. You do not have the required role to access this page.');
        }

        return $next($request);
    }
}
