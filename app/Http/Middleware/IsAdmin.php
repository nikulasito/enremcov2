<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (Auth::check() && $user && $user->isRegularAdmin()) {
            return $next($request);
        }

        if ($user?->isExecAdmin()) {
            return redirect()->route('admin.exec-dashboard')->with('error', 'You do not have admin access.');
        }

        if ($user?->isCreditOfficer()) {
            return redirect()->route('admin.credit-officer.dashboard')->with('error', 'You do not have admin access.');
        }

        return redirect('/dashboard')->with('error', 'You do not have admin access.');
    }
}
