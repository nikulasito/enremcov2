<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Exports\WithdrawTemplateExport;
use App\Imports\WithdrawImport;
use Maatwebsite\Excel\Facades\Excel;

class WithdrawController extends Controller
{

    public function downloadTemplate()
    {
        return Excel::download(new WithdrawTemplateExport, 'withdraw_template.xlsx');
    }

    public function uploadWithdrawTemplate(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv,xls']);
        Excel::import(new WithdrawImport, $request->file('file'));
        return back()->with('success', '✅ Withdrawals uploaded successfully.');
    }


    public function controllerWithdraw(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search = trim((string) $request->input('search', ''));
        $office = trim((string) $request->input('office', ''));

        $q = User::query()
            ->where('status', 'Active')
            ->where('is_admin', '!=', 1);

        if ($office !== '') {
            $q->where('office', $office);
        }

        if ($search !== '') {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_ID', 'like', "%{$search}%")
                    ->orWhere('office', 'like', "%{$search}%");
            });
        }

        // ✅ define members (fixes Undefined variable $members too)
        $members = $q->orderBy('name')->paginate($perPage)->appends($request->except('page'));

        $offices = User::where('status', 'Active')->pluck('office')->unique()->values();

        $userIds = $members->pluck('id');

        $withdrawTotals = Withdraw::whereIn('employees_id', $userIds)
            ->select('employees_id', DB::raw('SUM(amount_withdrawn) as total'))
            ->groupBy('employees_id')
            ->pluck('total', 'employees_id');

        $latestWithdrawByUser = Withdraw::whereIn('employees_id', $userIds)
            ->select('employees_id', DB::raw('MAX(date_of_withdrawal) as latest'))
            ->groupBy('employees_id')
            ->pluck('latest', 'employees_id');

        return view('admin.withdraw', compact(
            'members',
            'offices',
            'perPage',
            'withdrawTotals',
            'latestWithdrawByUser'
        ));
    }

    // Bulk add withdrawals (multi-select members, one date/amount/reference)
    public function bulkAddWithdraw(Request $request)
    {
        try {
            $request->validate([
                'member_ids' => 'required|array|min:1',
                'amount_withdrawn' => 'required|numeric|min:0.01',
                'date_of_withdrawal' => 'required|date',
                'reference_no' => 'nullable|string|max:255',
                'covered_month' => 'nullable|integer|min:1|max:12',
                'covered_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'remarks' => 'nullable|string|max:1000',
            ]);

            foreach ($request->member_ids as $memberId) {
                $member = User::find($memberId);
                if (!$member) {
                    continue;
                }

                Withdraw::create([
                    'employees_id' => $member->id,
                    'name' => $member->name,
                    'office' => $member->office ?? 'Unknown',
                    'date_of_withdrawal' => $request->date_of_withdrawal,
                    'amount_withdrawn' => $request->amount_withdrawn,
                    'reference_no' => $request->reference_no,
                    'covered_month' => $request->covered_month,
                    'covered_year' => $request->covered_year,
                    'remarks' => $request->remarks,
                    'date_created' => now()->format('Y-m-d'),
                ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Bulk Withdraw Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // (Optional) fetch by year or ref for modal tables
    public function getWithdrawals($employeeId, $search)
    {
        $query = Withdraw::where('employees_id', $employeeId)->orderBy('date_of_withdrawal', 'desc');

        $search = trim($search);

        if (preg_match('/^\d{4}$/', $search)) {
            // Search by YEAR from date_of_withdrawal
            $query->whereYear('date_of_withdrawal', (int) $search);
        } else {
            // Fallback: search by reference number
            $query->where('reference_no', 'LIKE', "%{$search}%");
        }


        $rows = $query->get()->map(function ($w) {
            $monthName = $w->covered_month ? date('F', mktime(0, 0, 0, $w->covered_month, 1)) : null;
            return [
                'withdrawals_id' => $w->withdrawals_id,
                'date_of_withdrawal' => $w->date_of_withdrawal,
                'reference_no' => $w->reference_no,
                'month_name' => $monthName,
                'covered_year' => $w->covered_year,
                'amount_withdrawn' => $w->amount_withdrawn,
                'remarks' => $w->remarks,
            ];
        });

        return response()->json([
            'success' => true,
            'withdrawals' => $rows,
        ]);
    }


    // (Optional) inline update from modal table
    public function updateWithdrawals(Request $request)
    {
        $updates = $request->input('updates', []);
        $changed = false;

        foreach ($updates as $row) {
            $w = Withdraw::where('withdrawals_id', $row['withdrawals_id'] ?? null)->first();
            if (!$w) {
                continue;
            }

            $newMonth = isset($row['month_name']) ? (int) date('m', strtotime($row['month_name'])) : $w->covered_month;

            if (
                $w->date_of_withdrawal != $row['date_of_withdrawal'] ||
                $w->reference_no != ($row['reference_no'] ?? $w->reference_no) ||
                (int) $w->covered_year != (int) ($row['covered_year'] ?? $w->covered_year) ||
                (int) $w->covered_month != (int) $newMonth ||
                (float) $w->amount_withdrawn != (float) $row['amount_withdrawn'] ||
                (string) ($w->remarks ?? '') !== (string) ($row['remarks'] ?? '')
            ) {
                $w->date_of_withdrawal = $row['date_of_withdrawal'];
                $w->reference_no = $row['reference_no'] ?? null;
                $w->covered_year = $row['covered_year'] ?? null;
                $w->covered_month = $newMonth ?? null;
                $w->amount_withdrawn = $row['amount_withdrawn'];
                $w->remarks = $row['remarks'] ?? null;
                $w->date_updated = now();
                $w->save();
                $changed = true;
            }
        }

        return response()->json([
            'success' => $changed,
            'message' => $changed ? 'Withdrawals updated successfully' : 'No changes made',
        ]);
    }

    public function partial(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search = trim((string) $request->input('search', ''));
        $office = trim((string) $request->input('office', ''));

        $q = \App\Models\User::query()
            ->where('status', 'Active')
            ->where('is_admin', '!=', 1);

        if ($office !== '') {
            $q->where('office', $office);
        }

        if ($search !== '') {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_ID', 'like', "%{$search}%")
                    ->orWhere('office', 'like', "%{$search}%");
            });
        }

        $members = $q->orderBy('name')->paginate($perPage)->appends($request->except('page'));

        $userIds = $members->pluck('id');

        $withdrawTotals = \App\Models\Withdraw::whereIn('employees_id', $userIds)
            ->select('employees_id', \DB::raw('SUM(amount_withdrawn) as total'))
            ->groupBy('employees_id')
            ->pluck('total', 'employees_id');

        $latestWithdrawByUser = \App\Models\Withdraw::whereIn('employees_id', $userIds)
            ->select('employees_id', \DB::raw('MAX(date_of_withdrawal) as latest'))
            ->groupBy('employees_id')
            ->pluck('latest', 'employees_id');

        $tbody = view('admin.withdraw._rows', compact(
            'members',
            'withdrawTotals',
            'latestWithdrawByUser'
        ))->render();

        $pagination = view('admin.withdraw._pagination', compact('members'))->render();

        return response()->json([
            'tbody' => $tbody,
            'pagination' => $pagination,
        ]);
    }

}
