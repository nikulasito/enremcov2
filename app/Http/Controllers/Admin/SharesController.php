<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Share;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\SharesTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Imports\SharesImport;
use Illuminate\Support\Facades\Session;

class SharesController extends Controller
{

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);

        $members = User::where('is_admin', '!=', 1)
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $offices = User::where('is_admin', '!=', 1)
            ->whereNotNull('office')
            ->distinct()
            ->orderBy('office')
            ->pluck('office');

        return view('admin.shares', compact('members', 'offices', 'perPage'));
    }

    public function controllerShares(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $search = trim((string) $request->input('search', ''));
        $office = (string) $request->input('office', '');

        $query = User::query()
            ->where('is_admin', '!=', 1)
            ->where('status', 'Active');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_ID', 'like', "%{$search}%")
                    ->orWhere('office', 'like', "%{$search}%");
            });
        }

        if ($office !== '') {
            $query->where('office', $office);
        }

        $members = $query->orderBy('name')->paginate($perPage)->withQueryString();

        $offices = User::where('is_admin', '!=', 1)
            ->where('status', 'Active')
            ->whereNotNull('office')
            ->distinct()
            ->orderBy('office')
            ->pluck('office');

        return view('admin.shares', compact('members', 'offices', 'perPage'));
    }



    public function bulkAddShares(Request $request)
    {
        try {
            $request->validate([
                'member_ids' => 'required|array',
                'amount' => 'required|numeric|min:0.01',
                'date_remittance' => 'required|date',
                'remittance_no' => 'required|string|max:255',
                'covered_month' => 'required|integer|min:1|max:12',
                'covered_year' => 'required|integer|min:1900|max:' . date('Y'),
            ]);

            $duplicates = [];

            // Loop through each selected member and insert into `shares` table
            foreach ($request->member_ids as $memberId) {
                $member = User::find($memberId);

                // Log the current member being processed
                \Log::info('Processing member:', ['memberId' => $memberId, 'memberName' => $member->name]);

                // Check if the remittance already exists for this member
                $existingShare = Share::where('remittance_no', $request->remittance_no)
                    ->where('covered_month', $request->covered_month)
                    ->where('covered_year', $request->covered_year)
                    ->where('employees_id', $member->id)
                    ->first();

                if ($existingShare) {
                    $duplicates[] = [
                        'remittance_no' => $request->remittance_no,
                        'covered_month' => $request->covered_month,
                        'covered_year' => $request->covered_year
                    ];
                    continue; // Skip adding the share for this member
                }

                if ($member) {
                    // Insert into `shares` table including `date_created`
                    Share::create([
                        'employees_id' => $member->id,
                        'name' => $member->name,
                        'date_remittance' => now()->format('Y-m-d'),
                        'amount' => $request->amount,
                        'office' => $member->office ?? 'Unknown',
                        'date_created' => now()->format('Y-m-d'), // ✅ Added
                        'date_remittance' => $request->date_remittance,
                        'remittance_no' => $request->remittance_no,
                        'covered_month' => $request->covered_month,
                        'covered_year' => $request->covered_year
                    ]);
                }
            }

            // Log the final response before sending it
            \Log::info('Response for Bulk Add Shares', ['duplicates' => $duplicates]);

            return response()->json([
                'success' => true,
                'duplicates' => $duplicates // Return duplicates array if it exists
            ]);

        } catch (\Exception $e) {
            \Log::error('Bulk Shares Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateRemittances(Request $request)
    {
        \Log::info('Update Remittances Request:', $request->all());

        $updates = $request->input('updates');
        $changesMade = false;

        foreach ($updates as $data) {
            \Log::info('Processing update for ID: ' . $data['shares_id']);

            $share = Share::where('shares_id', $data['shares_id'])->first();

            if ($share) {
                \Log::info('Original:', $share->toArray());

                $originalData = [
                    'date_remittance' => $share->date_remittance,
                    'remittance_no' => $share->remittance_no,
                    'covered_month' => $share->covered_month,
                    'covered_year' => $share->covered_year,
                    'amount' => $share->amount,
                ];

                $newMonth = (int) date('m', strtotime($data['month_name']));
                $newAmount = (float) $data['amount'];
                $newYear = (int) $data['covered_year'];

                if (
                    $share->date_remittance != $data['date_remittance'] ||
                    $share->remittance_no != $data['remittance_no'] ||
                    (int) $share->covered_month != $newMonth ||
                    (int) $share->covered_year != $newYear ||
                    (float) $share->amount != $newAmount
                ) {
                    $share->date_remittance = $data['date_remittance'];
                    $share->remittance_no = $data['remittance_no'];
                    $share->covered_month = $newMonth;
                    $share->covered_year = $newYear;
                    $share->amount = $newAmount;
                    $share->date_updated = now();
                    $share->save();

                    \Log::info('Updated successfully', $share->toArray());

                    $changesMade = true;
                }
            } else {
                \Log::warning('Share not found for ID: ' . $data['shares_id']);
            }
        }

        if ($changesMade) {
            return response()->json(['success' => true, 'message' => 'Shares updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'No changes made']);
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new SharesTemplateExport, 'shares_template.xlsx');
    }

    public function uploadSharesTemplate(Request $request)
    {
        try {
            Excel::import(new SharesImport, $request->file('file'));
            return back()->with('success', '✅ Shares data uploaded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function importShares(Request $request)
    {
        try {
            Excel::import(new SharesImport, $request->file('import_file'));
            return back()->with('success', '✅ Shares imported successfully!');
        } catch (\Exception $e) {
            return back()->with('import_error', $e->getMessage());
        }
    }

    public function partial(Request $request)
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

        $members = $q->orderBy('name')->paginate($perPage)->withQueryString();
        $members->withPath(route('admin.shares')); // keep paginator links pointing to main page

        $userIds = $members->pluck('id');

        $shareTotals = Share::whereIn('employees_id', $userIds)
            ->select('employees_id', DB::raw('SUM(amount) as total'))
            ->groupBy('employees_id')
            ->pluck('total', 'employees_id');

        $monthsContributedByUser = Share::whereIn('employees_id', $userIds)
            ->whereNotNull('covered_month')
            ->select('employees_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('employees_id')
            ->pluck('cnt', 'employees_id');

        $firstRemittanceByUser = Share::whereIn('employees_id', $userIds)
            ->select('employees_id', DB::raw('MIN(date_remittance) as first'))
            ->groupBy('employees_id')
            ->pluck('first', 'employees_id');

        $latestRemittanceByUser = Share::whereIn('employees_id', $userIds)
            ->select('employees_id', DB::raw('MAX(date_remittance) as latest'))
            ->groupBy('employees_id')
            ->pluck('latest', 'employees_id');

        $latestUpdatedByUser = Share::whereIn('employees_id', $userIds)
            ->select('employees_id', DB::raw('MAX(date_updated) as updated'))
            ->groupBy('employees_id')
            ->pluck('updated', 'employees_id');

        $tbody = view('admin.shares._rows', compact(
            'members',
            'shareTotals',
            'monthsContributedByUser',
            'firstRemittanceByUser',
            'latestRemittanceByUser',
            'latestUpdatedByUser'
        ))->render();

        $pagination = view('admin.shares._pagination', compact('members'))->render();

        return response()->json([
            'tbody' => $tbody,
            'pagination' => $pagination,
        ]);
    }


}