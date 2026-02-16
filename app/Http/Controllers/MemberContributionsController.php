<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Contribution;
use App\Models\Share;
use App\Models\Saving;
use App\Models\LoanDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MemberContributionsController extends Controller
{
    public function index()
    {
        return $this->renderContributionPage('contributions');
    }

    public function sharesPage()
    {
        return $this->renderContributionPage('shares');
    }

    public function savingsPage()
    {
        return $this->renderContributionPage('savings');
    }

    private function renderContributionPage(string $page)
    {
        $user = Auth::user();

        // Ensure the user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }

        // ✅ Get total shares and savings
        $totalShares = Share::where('employees_id', $user->id)->sum('amount');
        $totalSavings = Saving::where('employees_id', $user->id)->sum('amount');

        // ✅ Get contribution details
        $contributions = Contribution::where('employees_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Fetch loan details
        $loans = LoanDetail::where('employee_ID', $user->id)->get();

        // ✅ Fetch **Shares** grouped by year and month
        $shares = DB::table('shares')
            ->select(
                DB::raw('covered_year as year'),
                DB::raw('SUM(CASE WHEN covered_month = 1 THEN amount ELSE 0 END) AS jan'),
                DB::raw('SUM(CASE WHEN covered_month = 2 THEN amount ELSE 0 END) AS feb'),
                DB::raw('SUM(CASE WHEN covered_month = 3 THEN amount ELSE 0 END) AS mar'),
                DB::raw('SUM(CASE WHEN covered_month = 4 THEN amount ELSE 0 END) AS apr'),
                DB::raw('SUM(CASE WHEN covered_month = 5 THEN amount ELSE 0 END) AS may'),
                DB::raw('SUM(CASE WHEN covered_month = 6 THEN amount ELSE 0 END) AS jun'),
                DB::raw('SUM(CASE WHEN covered_month = 7 THEN amount ELSE 0 END) AS jul'),
                DB::raw('SUM(CASE WHEN covered_month = 8 THEN amount ELSE 0 END) AS aug'),
                DB::raw('SUM(CASE WHEN covered_month = 9 THEN amount ELSE 0 END) AS sep'),
                DB::raw('SUM(CASE WHEN covered_month = 10 THEN amount ELSE 0 END) AS oct'),
                DB::raw('SUM(CASE WHEN covered_month = 11 THEN amount ELSE 0 END) AS nov'),
                DB::raw('SUM(CASE WHEN covered_month = 12 THEN amount ELSE 0 END) AS `dec`'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(DISTINCT covered_month) as months_contributed') // ✅ Count months contributed
            )
            ->where('employees_id', $user->id)
            ->groupBy('covered_year')
            ->orderBy('year', 'desc')
            ->get();

        // ✅ Fetch **Savings** grouped by year and month (same logic as shares)
        $savings = DB::table('savings')
            ->select(
                DB::raw('covered_year as year'),
                DB::raw('SUM(CASE WHEN covered_month = 1 THEN amount ELSE 0 END) AS jan'),
                DB::raw('SUM(CASE WHEN covered_month = 2 THEN amount ELSE 0 END) AS feb'),
                DB::raw('SUM(CASE WHEN covered_month = 3 THEN amount ELSE 0 END) AS mar'),
                DB::raw('SUM(CASE WHEN covered_month = 4 THEN amount ELSE 0 END) AS apr'),
                DB::raw('SUM(CASE WHEN covered_month = 5 THEN amount ELSE 0 END) AS may'),
                DB::raw('SUM(CASE WHEN covered_month = 6 THEN amount ELSE 0 END) AS jun'),
                DB::raw('SUM(CASE WHEN covered_month = 7 THEN amount ELSE 0 END) AS jul'),
                DB::raw('SUM(CASE WHEN covered_month = 8 THEN amount ELSE 0 END) AS aug'),
                DB::raw('SUM(CASE WHEN covered_month = 9 THEN amount ELSE 0 END) AS sep'),
                DB::raw('SUM(CASE WHEN covered_month = 10 THEN amount ELSE 0 END) AS oct'),
                DB::raw('SUM(CASE WHEN covered_month = 11 THEN amount ELSE 0 END) AS nov'),
                DB::raw('SUM(CASE WHEN covered_month = 12 THEN amount ELSE 0 END) AS `dec`'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(DISTINCT covered_month) as months_contributed') // ✅ Count months contributed
            )
            ->where('employees_id', $user->id)
            ->groupBy('covered_year')
            ->orderBy('year', 'desc')
            ->get();

        // ✅ Calculate summary values
        $totalDisplayed = $shares->sum('total');
        $totalEntries = $shares->sum('months_contributed');
        $totalSavingsDisplayed = $savings->sum('total');
        $totalSavingsEntries = $savings->sum('months_contributed');

        // ✅ Fetch Withdrawals grouped by year and month
        $withdrawals = collect();
        if (Schema::hasTable('withdrawals')) {
            $withdrawalsQuery = DB::table('withdrawals')
                ->select(
                    DB::raw('COALESCE(withdrawals.covered_year, YEAR(withdrawals.date_of_withdrawal)) as year'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 1 THEN withdrawals.amount_withdrawn ELSE 0 END) AS jan'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 2 THEN withdrawals.amount_withdrawn ELSE 0 END) AS feb'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 3 THEN withdrawals.amount_withdrawn ELSE 0 END) AS mar'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 4 THEN withdrawals.amount_withdrawn ELSE 0 END) AS apr'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 5 THEN withdrawals.amount_withdrawn ELSE 0 END) AS may'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 6 THEN withdrawals.amount_withdrawn ELSE 0 END) AS jun'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 7 THEN withdrawals.amount_withdrawn ELSE 0 END) AS jul'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 8 THEN withdrawals.amount_withdrawn ELSE 0 END) AS aug'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 9 THEN withdrawals.amount_withdrawn ELSE 0 END) AS sep'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 10 THEN withdrawals.amount_withdrawn ELSE 0 END) AS oct'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 11 THEN withdrawals.amount_withdrawn ELSE 0 END) AS nov'),
                    DB::raw('SUM(CASE WHEN COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal)) = 12 THEN withdrawals.amount_withdrawn ELSE 0 END) AS `dec`'),
                    DB::raw('SUM(withdrawals.amount_withdrawn) as total'),
                    DB::raw('COUNT(DISTINCT COALESCE(withdrawals.covered_month, MONTH(withdrawals.date_of_withdrawal))) as months_contributed')
                )
                ->where('withdrawals.employees_id', $user->id);

            // Filter withdrawals by reference_no category per page.
            if ($page === 'shares') {
                $withdrawalsQuery->whereRaw('LOWER(COALESCE(withdrawals.reference_no, "")) LIKE ?', ['%shares%']);
            } elseif ($page === 'savings') {
                $withdrawalsQuery->whereRaw('LOWER(COALESCE(withdrawals.reference_no, "")) LIKE ?', ['%savings%']);
            }

            $withdrawals = $withdrawalsQuery
                ->groupBy(DB::raw('COALESCE(withdrawals.covered_year, YEAR(withdrawals.date_of_withdrawal))'))
                ->orderBy('year', 'desc')
                ->get();
        }

        $totalWithdrawalsDisplayed = $withdrawals->sum('total');
        $totalWithdrawalsEntries = $withdrawals->sum('months_contributed');

        $secondaryRows = $savings;
        $secondaryLabel = 'Savings';
        $secondaryTotalDisplayed = $totalSavingsDisplayed;
        $secondaryTotalEntries = $totalSavingsEntries;
        $secondaryEmptyText = 'No savings contributions available.';

        if (in_array($page, ['shares', 'savings'], true)) {
            $secondaryRows = $withdrawals;
            $secondaryLabel = 'Withdrawals';
            $secondaryTotalDisplayed = $totalWithdrawalsDisplayed;
            $secondaryTotalEntries = $totalWithdrawalsEntries;
            $secondaryEmptyText = 'No withdrawal records available.';
        }

        if ($page === 'savings') {
            $shares = $savings;
            $totalDisplayed = $totalSavingsDisplayed;
            $totalEntries = $totalSavingsEntries;
            $totalShareAmount = $totalSavingsDisplayed;
        }

        // ✅ Return the view with all required data
        return view('member.member_contributions', compact(
            'contributions',
            'totalShares',
            'totalSavings',
            'loans',
            'shares',
            'totalDisplayed',
            'totalEntries',
            'savings',
            'totalSavingsDisplayed',
            'totalSavingsEntries',
            'withdrawals',
            'totalWithdrawalsDisplayed',
            'totalWithdrawalsEntries',
            'secondaryRows',
            'secondaryLabel',
            'secondaryTotalDisplayed',
            'secondaryTotalEntries',
            'secondaryEmptyText',
            'page'
        ));
    }

    public function getContributions($employee_id, $search)
    {
        Log::info("🔍 Fetching contributions for Employee ID: $employee_id, Search Query: $search");

        if (preg_match('/^\d{4}$/', $search)) {
            Log::info("📅 Searching by YEAR: $search");
            $contributions = Share::where('employees_id', $employee_id)
                ->where('covered_year', $search)
                ->select('shares_id', 'date_remittance', 'remittance_no', 'covered_month', 'covered_year', 'amount')
                ->orderBy('covered_month', 'asc')
                ->get();
        } else {
            Log::info("🧾 Searching by REMITTANCE NO: $search");
            $contributions = Share::where('employees_id', $employee_id)
                ->whereRaw("LOWER(remittance_no) LIKE LOWER(?)", ["%$search%"])
                ->select('shares_id', 'date_remittance', 'remittance_no', 'covered_month', 'covered_year', 'amount')
                ->orderBy('covered_month', 'asc')
                ->get();
        }

        Log::info("📊 Total Contributions Found: " . count($contributions));

        if ($contributions->isEmpty()) {
            Log::warning("⚠️ No contributions found for Employee ID: $employee_id, Search: $search");
            return response()->json([
                'success' => false,
                'message' => "No contributions found."
            ]);
        }

        foreach ($contributions as $contribution) {
            $contribution->month_name = date("F", mktime(0, 0, 0, $contribution->covered_month, 1));
        }

        return response()->json([
            'success' => true,
            'contributions' => $contributions
        ]);
    }

    public function getSavingsSearch($employee_id, $search)
    {
        \Log::info("🔍 Searching SAVINGS for Employee ID: $employee_id, Search Query: $search");

        // If the search query is a year
        if (preg_match('/^\d{4}$/', $search)) {
            $savings = Saving::where('employees_id', $employee_id)
                ->where('covered_year', $search)
                ->select('savings_id', 'date_remittance', 'remittance_no', 'covered_month', 'covered_year', 'amount')
                ->orderBy('covered_month', 'asc')
                ->get();
        } else {
            // Search by remittance number
            $savings = Saving::where('employees_id', $employee_id)
                ->whereRaw("LOWER(remittance_no) LIKE LOWER(?)", ["%$search%"])
                ->select('savings_id', 'date_remittance', 'remittance_no', 'covered_month', 'covered_year', 'amount')
                ->orderBy('covered_month', 'asc')
                ->get();
        }

        \Log::info("✅ SAVINGS found: " . $savings->count());

        if ($savings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => "No savings found for query: $search."
            ]);
        }

        foreach ($savings as $saving) {
            $saving->month_name = date("F", mktime(0, 0, 0, $saving->covered_month, 1));
        }

        return response()->json([
            'success' => true,
            'contributions' => $savings
        ]);
    }



    public function getSavings($employee_id, $year)
    {
        Log::info("Fetching savings for Employee ID: $employee_id, Year: $year");

        // Retrieve savings for the given employee and year
        $savings = Saving::where('employees_id', $employee_id)
            ->whereYear('date_remittance', $year)
            ->select('savings_id', 'date_remittance', 'remittance_no', 'covered_month', 'covered_year', 'amount')
            ->orderBy('covered_month', 'asc')
            ->get();

        // Debugging: Log retrieved data
        Log::info("Total Savings Found: " . count($savings));

        if ($savings->isEmpty()) {
            Log::warning("No savings found for Employee ID: $employee_id in Year: $year.");
            return response()->json(['savings' => []]);
        }

        // Convert covered_month (number) to month name
        foreach ($savings as $saving) {
            $saving->month_name = date("F", mktime(0, 0, 0, $saving->covered_month, 1));
        }

        Log::info("API Response: " . json_encode($savings));

        return response()->json(['contributions' => $savings]);
    }
}
