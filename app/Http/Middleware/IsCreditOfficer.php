<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsCreditOfficer
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user?->isCreditOfficer()) {
            return $next($request);
        }

        if ($user?->isExecAdmin()) {
            return redirect()->route('admin.exec-dashboard')->with('error', 'You do not have credit officer access.');
        }

        if ($user?->isRegularAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have credit officer access.');
        }

        return redirect()->route('dashboard')->with('error', 'You do not have credit officer access.');
    }
}
