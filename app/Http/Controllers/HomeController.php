<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Share;
use App\Models\Saving;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure the user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }

        // ✅ Fetch Shares Summary
        $totalShares = Share::where('employees_id', $user->id)->sum('amount');
        $totalEntries = DB::table('shares')
            ->where('employees_id', $user->id)
            ->select(DB::raw('COUNT(DISTINCT CONCAT(covered_year, "-", covered_month)) as total_months'))
            ->value('total_months');

        // ✅ Fetch Savings Summary
        $totalSavingsDisplayed = Saving::where('employees_id', $user->id)->sum('amount');
        $totalSavingsEntries = DB::table('savings')
            ->where('employees_id', $user->id)
            ->select(DB::raw('COUNT(DISTINCT CONCAT(covered_year, "-", covered_month)) as total_months'))
            ->value('total_months');

        // ✅ Return the view with data
        return view('dashboard', compact(
            'totalShares', 
            'totalEntries', 
            'totalSavingsDisplayed',
            'totalSavingsEntries'
        ));
    }
}
