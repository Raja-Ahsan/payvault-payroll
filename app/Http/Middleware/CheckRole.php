<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (! $request->user() || ! $request->user()->hasRole($role)) {
            // Redirect or return a response if the user does not have the role
            return redirect('home');
        }
        return $next($request);
    }
}
