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


        $memberKey = $user->id;
        $userId = $user->id;

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
        $memberKey = $user->employee_ID
            ?? $user->employees_id
            ?? $user->employee_id
            ?? null;

        $loanApplications = DB::table('loan_applications')
            ->where(function ($q) use ($userId, $memberKey) {
                $q->where('user_id', $userId);

                if (!empty($memberKey)) {
                    $q->orWhere('member_key', (string) $memberKey);
                }
            })
            ->orderByDesc('created_at')
            ->get();

        $recentTransactions = []; // keep empty for now if you don't have this yet

        return view('dashboard', compact(
            'totalShares',
            'totalEntries',
            'latestShareDate',
            'totalSavingsDisplayed',
            'totalSavingsEntries',
            'latestSavingsDate',
            'loanApplications',
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
