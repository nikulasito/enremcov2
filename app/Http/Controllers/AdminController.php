<?php

// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;   // for Brevo API call
use Illuminate\Support\Facades\Log;    // for Log::error / w
use App\Models\User;
use App\Models\LoanDetail;
use App\Models\LoanPayment;
use Illuminate\Support\Facades\Schema;


class AdminController extends Controller
{
    // Dashboard Method
    public function dashboard()
    {
        $totalUsers = User::where('is_admin', '!=', 1)->count();

        $totalActiveMembers = User::where('is_admin', '!=', 1)
            ->whereIn('status', ['Active', 'active'])
            ->count();

        $pendingMemberships = User::where('is_admin', '!=', 1)
            ->whereIn('status', ['Inactive', 'inactive', 'Pending', 'pending'])
            ->count();

        $newMembersLast30Days = User::where('is_admin', '!=', 1)
            ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
            ->count();

        $totalShares = \App\Models\Share::sum('amount');
        $totalSavings = \App\Models\Saving::sum('amount');

        $pendingLoans = (Schema::hasTable('loan_details') && Schema::hasColumn('loan_details', 'date_approved'))
            ? \App\Models\LoanDetail::whereNull('date_approved')->count()
            : 0;

        $recentMembershipRequests = User::where('is_admin', '!=', 1)
            ->whereIn('status', ['Inactive', 'inactive', 'Pending', 'pending'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $recentLoanEntries = \App\Models\LoanDetail::query()
            ->when(
                Schema::hasTable('loan_details') && Schema::hasColumn('loan_details', 'created_at'),
                fn($q) => $q->orderByDesc('created_at'),
                fn($q) => $q->orderByDesc('loan_id')
            )
            ->limit(5)
            ->get();

        // Map borrower names safely (depending on your users table employee id column)
        $borrowerNames = collect();
        $loanEmployeeIDs = $recentLoanEntries->pluck('employee_ID')->filter()->unique()->values();

        if ($loanEmployeeIDs->isNotEmpty()) {
            $empColumn = null;
            foreach (['employee_ID', 'employees_id', 'employee_id'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $empColumn = $col;
                    break;
                }
            }

            if ($empColumn) {
                $borrowerNames = User::whereIn($empColumn, $loanEmployeeIDs)->pluck('name', $empColumn);
            }
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalActiveMembers',
            'pendingMemberships',
            'newMembersLast30Days',
            'totalShares',
            'totalSavings',
            'pendingLoans',
            'recentMembershipRequests',
            'recentLoanEntries',
            'borrowerNames'
        ));
    }




    public function index()
    {
        return view('admin.dashboard'); // Ensure the 'admin/dashboard.blade.php' file exists
    }

    // View Members Method
    public function viewMembers(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 50, 100], true) ? $perPage : 10;

        $search = trim((string) $request->get('search', ''));
        $status = trim((string) $request->get('status', ''));
        $office = trim((string) $request->get('office', ''));

        // Detect employee id column safely (your app has mixed naming in places)
        $empCol = null;
        foreach (['employee_ID', 'employees_id', 'employee_id'] as $col) {
            if (Schema::hasColumn('users', $col)) {
                $empCol = $col;
                break;
            }
        }

        // Status sets
        $activeSet = ['Active', 'active'];
        $inactiveSet = ['Inactive', 'inactive'];

        // Treat "Pending" as both Pending + Awaiting Approval (matches your newMembers())
        $pendingSet = ['Pending', 'pending', 'Awaiting Approval', 'awaiting approval', 'Awaiting approval'];

        // Base query
        $query = User::query()
            ->where('is_admin', '!=', 1)
            ->whereIn('status', array_merge($activeSet, $inactiveSet, $pendingSet));

        // Search
        if ($search !== '') {
            $query->where(function ($q) use ($search, $empCol) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('office', 'like', "%{$search}%");

                if ($empCol) {
                    $q->orWhere($empCol, 'like', "%{$search}%");
                }
            });
        }

        // Status filter (normalized)
        if ($status !== '') {
            $s = strtolower($status);

            if ($s === 'active') {
                $query->whereIn('status', $activeSet);
            } elseif ($s === 'inactive') {
                $query->whereIn('status', $inactiveSet);
            } elseif ($s === 'pending') {
                $query->whereIn('status', $pendingSet);
            } else {
                // fallback exact match
                $query->where('status', $status);
            }
        }

        // Office filter
        if ($office !== '') {
            $query->where('office', $office);
        }

        $members = $query
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        // Offices list (keep consistent with current status filter)
        $officesQuery = User::query()
            ->where('is_admin', '!=', 1)
            ->whereIn('status', array_merge($activeSet, $inactiveSet, $pendingSet));

        if ($status !== '') {
            $s = strtolower($status);

            if ($s === 'active') {
                $officesQuery->whereIn('status', $activeSet);
            } elseif ($s === 'inactive') {
                $officesQuery->whereIn('status', $inactiveSet);
            } elseif ($s === 'pending') {
                $officesQuery->whereIn('status', $pendingSet);
            } else {
                $officesQuery->where('status', $status);
            }
        }

        $offices = $officesQuery
            ->whereNotNull('office')
            ->select('office')
            ->distinct()
            ->orderBy('office')
            ->pluck('office');

        // IMPORTANT:
        // Your new Tailwind page uses fetch() and parses HTML,
        // so DO NOT return JSON for ajax here.
        return view('admin.members', compact('members', 'perPage', 'offices'));
    }




    public function approveMember(Request $request, $id)
    {
        $member = User::findOrFail($id);

        // Optional validation (unobtrusive): ensure approved_at is a valid date if provided
        $request->validate([
            'approved_at' => 'nullable|date',
            'approve_notes' => 'nullable|string|max:1000',
        ]);

        // Use provided approved_at or default to now()
        $approvedAt = $request->input('approved_at')
            ? Carbon::parse($request->input('approved_at'))->startOfDay()
            : now();

        // 1) Update status & date_approved & token (keep your naming: date_approved)
        $member->status = 'Active';
        $member->date_approved = $approvedAt;
        $member->email_verification_token = Str::random(64);

        // Optional notes
        if ($request->filled('approve_notes')) {
            // Ensure you have a column to store notes (approve_notes). If not, remove this.
            $member->approve_notes = $request->input('approve_notes');
        }

        $member->save();

        // 2) Render your Blade email (MATCH the filename you created)
        try {
            $html = view('emails.member-approved', compact('member'))->render();
        } catch (\Throwable $e) {
            Log::error('Failed to render member-approved email view', ['err' => $e->getMessage()]);
            return back()->with('error', 'Failed to prepare approval email.');
        }

        // 3) Build Brevo payload
        $payload = [
            'sender' => [
                'name' => env('MAIL_FROM_NAME', 'ENREMCO'),
                'email' => env('MAIL_FROM_ADDRESS', 'denr10enremco@gmail.com'),
            ],
            'to' => [['email' => $member->email, 'name' => $member->name]],
            'subject' => 'Member approved and verified',
            'htmlContent' => $html,
        ];

        // 4) OPTIONAL: attach PDF (try in-memory dompdf; fallback: skip cleanly)
        try {
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                // If your pdf view relies on the approval date make sure it reads $member->date_approved
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.member', compact('member'));
                $pdfContent = base64_encode($pdf->output());
                $payload['attachment'] = [
                    [
                        'name' => "member-{$member->id}.pdf",
                        'content' => $pdfContent,
                    ]
                ];
            } else {
                Log::warning('PDF attachment skipped: barryvdh/laravel-dompdf not installed.');
            }
        } catch (\Throwable $e) {
            Log::error('PDF generation failed; sending email without attachment.', ['err' => $e->getMessage()]);
            // Continue without attachment
        }

        // 5) Send via Brevo HTTP API
        try {
            $resp = Http::withHeaders([
                'api-key' => env('BREVO_API_KEY'),
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', $payload);

            if (!$resp->successful()) {
                Log::error('Brevo API send failed', ['status' => $resp->status(), 'body' => $resp->body()]);
                return back()->with('error', 'Email send failed.');
            }
        } catch (\Throwable $e) {
            Log::error('Brevo API request failed', ['err' => $e->getMessage()]);
            return back()->with('error', 'Email send failed.');
        }

        return back()->with('success', 'Member approved and verification email sent.');
    }


    public function disapproveMember(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $member = User::findOrFail($id);

        // Build HTML from Blade
        $html = view('emails.member-disapproved', [
            'member' => $member,
            'reason' => $request->reason,
        ])->render();

        // Send via Brevo HTTP API (no SMTP)
        try {
            $apiKey = env('BREVO_API_KEY');
            if (empty($apiKey)) {
                Log::error('BREVO_API_KEY missing; cannot send disapproval email.');
                return back()->with('error', 'Disapproved, but email failed to send (missing API key).');
            }

            $payload = [
                'sender' => [
                    'name' => env('MAIL_FROM_NAME', 'ENREMCO'),
                    'email' => env('MAIL_FROM_ADDRESS', 'support@enremco.com'),
                ],
                'to' => [['email' => $member->email, 'name' => $member->name]],
                'subject' => 'Registration Disapproved',
                'htmlContent' => $html,
            ];

            $resp = Http::timeout(20)->withHeaders([
                'api-key' => $apiKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', $payload);

            if (!$resp->successful()) {
                Log::error('Brevo disapprove send failed', ['status' => $resp->status(), 'body' => $resp->body()]);
                return back()->with('error', 'Disapproved, but email failed to send.');
            }
        } catch (\Throwable $e) {
            Log::error('Disapprove email exception', ['err' => $e->getMessage()]);
            return back()->with('error', 'Disapproved, but email failed to send.');
        }

        // Delete the member only after attempting to notify
        $member->delete();

        return redirect()
            ->route('admin.new-members')
            ->with('success', 'Member disapproved and email sent.');
    }


    public function newMembers(Request $request)
    {
        // Fetch members with status "Awaiting Approval"
        // $newMembers = User::where('status', 'Awaiting Approval')->get();
        $perPage = $request->input('per_page', 10); // default is 10
        $newMembers = User::where('status', 'Awaiting Approval')->paginate($perPage);
        // return view('admin.new-members', compact('newMembers'));
        return view('admin.new-members', [
            'newMembers' => $newMembers,
            'perPage' => $perPage,
        ]);

    }



    public function editMember($id)
    {
        $member = User::findOrFail($id);

        $offices = User::where('is_admin', 0)
            ->select('office')->distinct()->orderBy('office')->pluck('office');

        return view('admin.partials.edit-member', compact('member', 'offices'));
    }


    public function updateMember(Request $request, $id)
    {
        $request->validate([
            'shares' => 'sometimes|nullable|numeric|min:0',
            'savings' => 'sometimes|nullable|numeric|min:0',
            'membership_date' => 'sometimes|nullable|date',
            'status' => 'sometimes|nullable|in:Active,Inactive',
        ]);

        $member = User::findOrFail($id);
        $previousStatus = $member->status;

        if ($request->has('shares'))
            $member->shares = $request->shares;
        if ($request->has('savings'))
            $member->savings = $request->savings;
        if ($request->has('membership_date'))
            $member->membership_date = $request->membership_date;

        if ($request->has('status')) {
            $newStatus = $request->status;

            if ($newStatus === 'Inactive') {
                $member->date_inactive = now();
            } elseif ($newStatus === 'Active' && $previousStatus !== 'Active') {
                $member->date_reactive = now();
            }

            $member->status = $newStatus;
        }

        $member->save();

        // ✅ If AJAX/fetch, return JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $member->status,
                'date_inactive' => $member->date_inactive ? \Carbon\Carbon::parse($member->date_inactive)->format('M d, Y') : null,
                'date_reactive' => $member->date_reactive ? \Carbon\Carbon::parse($member->date_reactive)->format('M d, Y') : null,
            ]);
        }

        // ✅ Normal form submit fallback
        return redirect()->route('admin.members')->with('success', 'Member updated successfully.');
    }



    public function deleteMember($id)
    {
        // Find the member by ID
        $member = User::findOrFail($id);

        // Optionally, delete the profile photo
        if ($member->photo && \Storage::exists('public/' . $member->photo)) {
            \Storage::delete('public/' . $member->photo);
        }

        // Delete the member
        $member->delete();

        // Redirect back with success message
        return redirect()->route('admin.members')->with('success', 'Member deleted successfully.');
    }

    public function inactivateMember($id)
    {
        // Find the member by ID
        $member = User::findOrFail($id);

        // Update the status to "Inactive"
        $member->status = 'Inactive';
        $member->date_inactive = now();
        $member->save();

        // Redirect back with a success message
        return redirect()->route('admin.members')->with('success', $member->name . ' has been marked as Inactive.');
    }

    public function markAsActive($id)
    {
        $member = User::findOrFail($id);
        $member->status = 'Active';
        $member->date_reactive = now(); // Assuming you want to set the date of reactivation
        $member->save();

        return redirect()->route('admin.members')->with('success', 'Member marked as active.');
    }

    public function loans()
    {
        $loans = LoanDetail::with('user')
            ->withSum('loanPayments as total_payments_sum', 'total_payments')
            ->addSelect([
                'latest_outstanding_balance' => LoanPayment::select('outstanding_balance')
                    ->whereColumn('loan_payments.loan_id', 'loan_details.loan_id')
                    ->orderByDesc('date_of_remittance')
                    ->limit(1)
            ])
            ->get();
        return view('admin.loans', compact('loans'));
    }

    public function getUserDetails($id)
    {
        $user = User::where('employee_id', $id)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($user);
    }

}
