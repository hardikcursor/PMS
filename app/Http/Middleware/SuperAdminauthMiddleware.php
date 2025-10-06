<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminauthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $role
     */
    public function handle(Request $request, Closure $next, string $role = null): Response
    {
        // Check if user is logged in
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Check role if provided
        if ($role && ! $request->user()->hasRole($role)) {
            Auth::logout(); // optional: force logout if wrong role
            return redirect()->route('login')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
