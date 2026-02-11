<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;
use App\Models\Share;
use App\Models\Saving;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user)
            return redirect()->route('login');

        // IMPORTANT: shares/savings.employees_id stores this value (ex: ENREMCO-000-004)
        $memberKey = $user->id;

        // SHARES
        $totalShares = DB::table('shares')
            ->where('employees_id', $memberKey)
            ->sum('amount');

        $totalEntries = DB::table('shares')
            ->where('employees_id', $memberKey)
            ->selectRaw('COUNT(DISTINCT CONCAT(covered_year,"-",LPAD(covered_month,2,"0"))) as total_months')
            ->value('total_months');

        $latestShareDate = DB::table('shares')
            ->where('employees_id', $memberKey)
            ->selectRaw('COALESCE(date_remittance, date, date_created) as latest_date')
            ->orderByRaw('COALESCE(date_remittance, date, date_created) DESC')
            ->value('latest_date');

        // SAVINGS
        $totalSavingsDisplayed = DB::table('savings')
            ->where('employees_id', $memberKey)
            ->sum('amount');

        $totalSavingsEntries = DB::table('savings')
            ->where('employees_id', $memberKey)
            ->selectRaw('COUNT(DISTINCT CONCAT(covered_year,"-",LPAD(covered_month,2,"0"))) as total_months')
            ->value('total_months');

        $latestSavingsDate = DB::table('savings')
            ->where('employees_id', $memberKey)
            ->selectRaw('COALESCE(date_remittance, date, date_created) as latest_date')
            ->orderByRaw('COALESCE(date_remittance, date, date_created) DESC')
            ->value('latest_date');

        // OPTIONAL: to prevent "undefined variable" in blade
        $pendingLoan = DB::table('loan_applications')
            ->where(function ($q) use ($user, $memberKey) {
                $q->where('user_id', $user->id)
                    ->orWhere('member_key', $memberKey);
            })
            ->whereIn('status', ['pending', 'for_review', 'for_approval'])
            ->orderByDesc('created_at')
            ->first();

        $recentTransactions = []; // keep empty for now if you don't have this yet

        return view('dashboard', compact(
            'totalShares',
            'totalEntries',
            'latestShareDate',
            'totalSavingsDisplayed',
            'totalSavingsEntries',
            'latestSavingsDate',
            'pendingLoan',
            'recentTransactions'
        ));
    }

    private function bestDateColumn(string $table): string
    {
        foreach (['date_remittance', 'date', 'created_at', 'date_created'] as $col) {
            if (Schema::hasColumn($table, $col))
                return $col;
        }
        return 'created_at'; // fallback (won't be used if column truly missing)
    }

    private function latestDateFromTable(string $table, $memberKey)
    {
        $col = $this->bestDateColumn($table);

        if (!Schema::hasColumn($table, $col))
            return null;

        return DB::table($table)
            ->where('employees_id', $memberKey)
            ->orderByDesc($col)
            ->value($col);
    }
}
