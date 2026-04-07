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
    private const APPROVED_VISIBILITY_DAYS = 5;

    public function index()
    {
        $user = Auth::user();
        if (!$user)
            return redirect()->route('login');

        if ($user->isExecAdmin()) {
            return redirect()->route('admin.exec-dashboard');
        }

        if ($user->isCreditOfficer()) {
            return redirect()->route('admin.credit-officer.dashboard');
        }

        if ($user->isRegularAdmin()) {
            return redirect()->route('admin.dashboard');
        }


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

        $approvedCutoff = Carbon::now()
            ->subDays(self::APPROVED_VISIBILITY_DAYS)
            ->endOfDay();
        $hasReviewedAt = Schema::hasColumn('loan_applications', 'reviewed_at');

        $loanApplications = DB::table('loan_applications')
            ->where(function ($q) use ($userId, $memberKey) {
                $q->where('user_id', $userId);

                if (!empty($memberKey)) {
                    $q->orWhere('member_key', (string) $memberKey);
                }
            })
            ->orderByDesc('created_at')
            ->get()
            ->filter(function ($application) use ($approvedCutoff, $hasReviewedAt) {
                $status = strtolower(trim((string) ($application->status ?? '')));

                if ($status !== 'approved') {
                    return true;
                }

                $approvedAt = null;
                if ($hasReviewedAt && !empty($application->reviewed_at)) {
                    $approvedAt = Carbon::parse($application->reviewed_at);
                } elseif (!empty($application->created_at)) {
                    $approvedAt = Carbon::parse($application->created_at);
                }

                return $approvedAt ? $approvedAt->gt($approvedCutoff) : true;
            })
            ->values();

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
