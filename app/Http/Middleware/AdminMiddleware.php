<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->hasRole('admin')) {
            return $next($request);
        } else {
            if (str_contains($request->url(), 'admin')) {
                return redirect()->route('login');
            }
            return redirect()->route('login');
        }
    }
}
