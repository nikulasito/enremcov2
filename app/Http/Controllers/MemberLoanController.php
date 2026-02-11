<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoanApplication;
use App\Notifications\NewLoanApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MemberLoanController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'loan_type' => ['required', 'in:regular,educational,appliance,grocery'],
            'loan_amount' => ['required', 'numeric', 'min:1'],

            'comaker1_name' => ['required', 'string', 'max:255'],
            'comaker2_name' => ['required', 'string', 'max:255'],

            'comaker1_user_id' => ['required', 'integer', 'exists:users,id'],
            'comaker2_user_id' => ['required', 'integer', 'exists:users,id', 'different:comaker1_user_id'],

            'comaker1_position' => ['nullable', 'string', 'max:255'],
            'comaker2_position' => ['nullable', 'string', 'max:255'],
        ]);

        $user = auth()->user();

        $memberKey = $user->employee_ID ?? $user->employees_id ?? $user->employee_id ?? (string) $user->id;

        // 1) SAVE as pending
        $loan = LoanApplication::create([
            'user_id' => $user->id,
            'application_no' => null, // set after create
            'full_name' => $data['full_name'],
            'member_key' => $memberKey,
            'address' => $data['address'] ?? null,

            'loan_type' => $data['loan_type'],
            'loan_amount' => $data['loan_amount'],

            'comaker1_user_id' => $data['comaker1_user_id'],
            'comaker1_name' => $data['comaker1_name'],
            'comaker1_position' => $data['comaker1_position'] ?? null,

            'comaker2_user_id' => $data['comaker2_user_id'],
            'comaker2_name' => $data['comaker2_name'],
            'comaker2_position' => $data['comaker2_position'] ?? null,

            'status' => 'pending',
        ]);

        // Create readable ref like APP-202602111234-0001
        $loan->application_no = 'APP-' . now()->format('YmdHis') . '-' . str_pad((string) $loan->id, 4, '0', STR_PAD_LEFT);
        $loan->save();

        // 2) NOTIFY ADMINS
        // Adjust this admin query to match your schema (is_admin/role/etc.)
        $admins = User::query()
            ->when(Schema::hasColumn('users', 'is_admin'), fn($q) => $q->where('is_admin', 1))
            ->when(Schema::hasColumn('users', 'role'), fn($q) => $q->orWhere('role', 'admin'))
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewLoanApplicationNotification($loan));
        }

        // 3) Return with modal data
        return redirect()
            ->route('member.loans.apply')
            ->with('loan_submitted', [
                'application_no' => $loan->application_no,
                'loan_type' => $loan->loan_type,
                'loan_amount' => $loan->loan_amount,
            ]);
    }

    public function searchComakers(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $userId = auth()->id();

        // ✅ Only use columns that actually exist
        $hasEmployeeID = Schema::hasColumn('users', 'employee_ID');
        $hasEmployeesId = Schema::hasColumn('users', 'employees_id');
        $hasEmployeeId = Schema::hasColumn('users', 'employee_id');
        $hasPosition = Schema::hasColumn('users', 'position');

        $query = User::query();

        if ($userId) {
            $query->where('id', '!=', $userId);
        }

        $query->where(function ($sub) use ($q, $hasEmployeeID, $hasEmployeesId, $hasEmployeeId) {
            $sub->where('name', 'like', "%{$q}%");

            if ($hasEmployeeID)
                $sub->orWhere('employee_ID', 'like', "%{$q}%");
            if ($hasEmployeesId)
                $sub->orWhere('employees_id', 'like', "%{$q}%");
            if ($hasEmployeeId)
                $sub->orWhere('employee_id', 'like', "%{$q}%");
        });

        // ✅ Select position only if it exists; otherwise return empty string
        $select = ['id', 'name'];
        if ($hasPosition) {
            $select[] = 'position';
        } else {
            $select[] = DB::raw("'' as position");
        }

        $results = $query
            ->select($select)
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'position' => $u->position ?? '',
            ]);

        return response()->json($results);
    }
}
