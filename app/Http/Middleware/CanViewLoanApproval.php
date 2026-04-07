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

        $isExecAdmin = $user->isExecAdmin();
        $isRegularAdmin = $user->isRegularAdmin();
        $isCreditOfficer = $user->isCreditOfficer();

        if ($isRegularAdmin || $isExecAdmin || $isCreditOfficer) {
            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Unauthorized access.');
    }
}
