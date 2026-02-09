<?php

// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_admin) { // Ensure 'is_admin' exists in the users table
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Unauthorized access.');

        if (Auth::check() && Auth::user()->status !== 'active') {
            Auth::logout();
            return redirect('/login')->with('error', 'Your account is not active yet.');
        }
    
        return $next($request);
    }
}
