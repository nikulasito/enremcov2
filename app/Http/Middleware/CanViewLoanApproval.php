<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanViewLoanApproval
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        $isExecAdmin = strtolower((string) ($user->role ?? '')) === 'exec-admin';
        $isRegularAdmin = (bool) ($user->is_admin ?? false) && !$isExecAdmin;

        if ($isRegularAdmin || $isExecAdmin) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Unauthorized access.');
    }
}
