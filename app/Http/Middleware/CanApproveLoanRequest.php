<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanApproveLoanRequest
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('/login');
        }

        $isExecAdmin = strtolower((string) ($user->role ?? '')) === 'exec-admin';

        if ($isExecAdmin) {
            return $next($request);
        }

        return redirect('/admin/loan-requests')->with('error', 'Only exec-admin can approve loan requests.');
    }
}
