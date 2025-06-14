<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles  <-- Changed from $permission to $roles for clarity
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check if user is authenticated first
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
        }

        // Now we know Auth::user() is not null
        $user = Auth::user();

        // 2. Iterate through the required roles and check if the user has any of them
        foreach ($roles as $role) {
            // Use the hasRole() method from your User model
            if ($user->hasRole($role)) {
                return $next($request); // User has at least one of the required roles, allow access
            }
        }

        // If the loop finishes, it means the user does not have any of the required roles
        abort(403, 'You do not have permission to access this page.');
    }
}