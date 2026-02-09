<x-admin-v2-layout title="ENREMCO - Loan Details" pageTitle="Loan Details"
    pageSubtitle="Create, view, and update member loan records" :showSearch="false">
    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-black text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div
            class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-black text-red-700 whitespace-pre-line">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-black text-red-700">
            <ul class="list-disc pl-5 space-y-1 font-semibold">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Templates --}}
    <div class="mb-8 grid grid-cols-1 lg:grid-cols-2 gap-4">
        <a href="{{ url('/download/loans-template') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-black text-[#112119] hover:brightness-110">
            Download Loans Template
        </a>

        <form action="{{ route('admin.upload-loans-template') }}" method="POST" enctype="multipart/form-data"
            class="rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] p-4">
            @csrf
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="file" name="file" required=""
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                <button type="submit"
                    class="shrink-0 rounded-lg bg-primary px-5 py-2.5 text-sm font-black text-[#112119] hover:brightness-110">
                    Upload Loans Template
                </button>
            </div>
        </form>

    </div>

    {{-- Add Loan Form --}}
    <div class="mb-8 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] shadow-sm">
        <div class="p-6 border-b border-[#dce5e0] dark:border-[#2a3a32]">
            <h4 class="text-lg font-black">Add Loan</h4>
            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Fill in loan details and submit.</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.store-loan') }}" class="space-y-6">
                @csrf

                {{-- Member --}}
                <div
                    class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-[#f6f8f7] dark:bg-[#0d1a14]/50 p-6">
                    <h5 class="text-sm font-black uppercase tracking-wider text-[#111814] dark:text-white mb-4">Member
                    </h5>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Employee ID
                            </label>
                            <input type="text" name="employee_id" id="employee_id"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] px-4 py-2.5 text-sm"
                                placeholder="Format: ENREMCO-XXX-XXX" required>
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Employee Name
                            </label>
                            <input type="text" name="employee_name" id="employee_name"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] px-4 py-2.5 text-sm"
                                readonly>
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Office
                            </label>
                            <input type="text" name="office" id="office"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] px-4 py-2.5 text-sm"
                                readonly>
                        </div>
                    </div>
                </div>

                {{-- Co-makers --}}
                <div class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] p-6">
                    <h5 class="text-sm font-black uppercase tracking-wider text-[#111814] dark:text-white mb-4">
                        Co-makers</h5>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Co-maker 1 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Co-maker 1
                                </label>
                                <input type="text" name="co_maker_name" id="co_maker_name"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                                <div id="co_maker_suggestions"
                                    class="absolute z-50 mt-1 hidden w-full overflow-hidden rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] shadow-lg">
                                </div>
                                <p id="co_maker_error" class="mt-1 hidden text-xs font-bold text-red-600">Co-maker not
                                    found!</p>
                            </div>

                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Position
                                </label>
                                <input type="text" name="co_maker_position" id="co_maker_position"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                            </div>
                        </div>

                        {{-- Co-maker 2 --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Co-maker 2
                                </label>
                                <input type="text" name="co_maker2_name" id="co_maker2_name"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                                <div id="co_maker2_suggestions"
                                    class="absolute z-50 mt-1 hidden w-full overflow-hidden rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] shadow-lg">
                                </div>
                                <p id="co_maker2_error" class="mt-1 hidden text-xs font-bold text-red-600">Co-maker not
                                    found!</p>
                            </div>

                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Position
                                </label>
                                <input type="text" name="co_maker2_position" id="co_maker2_position"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Loan info --}}
                <div class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] p-6">
                    <h5 class="text-sm font-black uppercase tracking-wider text-[#111814] dark:text-white mb-4">Loan
                        Info</h5>

                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div class="md:col-span-1">
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Loan Type
                            </label>
                            <select name="loan_type"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                                <option value="Regular Loan">Regular Loan</option>
                                <option value="Educational Loan">Educational Loan</option>
                                <option value="Appliance Loan">Appliance Loan</option>
                                <option value="Grocery Loan">Grocery Loan</option>
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Loan Amount
                            </label>
                            <input type="text" id="loan_amount" name="loan_amount"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>
                        </div>

                        <div class="md:col-span-1">
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Date Applied
                            </label>
                            <input type="date" id="date_applied" name="date_applied"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>
                        </div>

                        <div class="md:col-span-1">
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Interest Rate %
                            </label>
                            <input type="number" id="interest_rate" name="interest_rate"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>
                        </div>

                        <div class="md:col-span-1">
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Term
                            </label>
                            <input type="number" id="terms" name="terms"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>
                        </div>

                        <div class="md:col-span-1">
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Monthly Payment
                            </label>
                            <input type="text" id="monthly_payment" name="monthly_payment"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>
                        </div>
                    </div>
                </div>

                {{-- Deductions --}}
                <div class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] p-6">
                    <h5 class="text-sm font-black uppercase tracking-wider text-[#111814] dark:text-white mb-4">
                        Deductions</h5>

                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Total
                                Deduction</label>
                            <input type="text" name="total_deduction" id="total_deduction"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-[#f6f8f7] dark:bg-[#0d1a14]/50 px-4 py-2.5 text-sm"
                                readonly>

                            <label
                                class="mt-4 mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Balance</label>
                            <input type="text" name="old_balance" id="old_balance"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Total
                                Net</label>
                            <input type="text" name="total_net" id="total_net"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-[#f6f8f7] dark:bg-[#0d1a14]/50 px-4 py-2.5 text-sm"
                                readonly>

                            <label
                                class="mt-4 mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Interest</label>
                            <input type="text" name="interest" id="interest"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Date
                                Approved</label>
                            <input type="date" id="date_approved" name="date_approved"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>

                            <label
                                class="mt-4 mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Petty
                                Cash Loan</label>
                            <input type="text" name="petty_cash_loan" id="petty_cash_loan"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>
                        </div>

                        <div>
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">LPP</label>
                            <input type="text" name="lpp" id="lpp"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>

                            <label
                                class="mt-4 mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Handling
                                Fee</label>
                            <input type="text" name="handling_fee" id="handling_fee"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>
                        </div>

                        <div class="md:col-span-2">
                            <label
                                class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Remarks</label>
                            <select name="remarks" id="remarks"
                                class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm"
                                required>
                                <option value="New Loan">New Loan</option>
                                <option value="Re-Loan">Re-Loan</option>
                            </select>

                            <button type="submit"
                                class="mt-4 w-full rounded-xl bg-[#112119] dark:bg-white text-white dark:text-[#112119] px-6 py-3 text-sm font-black hover:opacity-90 transition">
                                Add Loan
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- Loans Table --}}
    <div
        class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-[#dce5e0] dark:border-[#2a3a32]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h4 class="text-lg font-black">Loans List</h4>
                    <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Search and manage existing loans.</p>
                </div>

                <div class="relative w-full md:w-96">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#638875]">
                        search
                    </span>
                    <input type="text" id="search_loans"
                        class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 pl-10 pr-4 text-sm"
                        placeholder="Search by Employee Name, Loan ID, or Office...">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-b border-[#dce5e0] dark:border-[#2a3a32]">
                    <tr>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            No.</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Loan ID</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Employee ID</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Employee</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Office</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Loan Type</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Loan Amount</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Total Payments</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Balance</th>

                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Date Approved</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Action</th>
                    </tr>
                </thead>

                <tbody id="loanTableBody" class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32]">
                    @foreach ($loans as $loan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-black text-primary">{{ $loan->loan_id }}</td>
                            <td class="px-6 py-4">{{ $loan->employee_ID }}</td>
                            <td class="px-6 py-4 font-black">{{ optional($loan->user)->name ?? 'NA' }}</td>
                            <td class="px-6 py-4">{{ optional($loan->user)->office ?? 'NA' }}</td>
                            <td class="px-6 py-4">{{ $loan->loan_type }}</td>
                            <td class="px-6 py-4 font-black">{{ number_format($loan->loan_amount, 2) }}</td>
                            <td class="px-6 py-4 font-black">
                                {{ number_format($loan->total_paid ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 font-black">
                                {{ number_format($loan->computed_balance ?? ($loan->loan_amount ?? 0), 2) }}
                            </td>

                            <td class="px-6 py-4">{{ $loan->date_approved }}</td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2 min-w-[160px]">
                                    <button type="button"
                                        class="view-loan-btn view-loan-btn inline-flex items-center justify-center rounded-xl bg-[#112119] dark:bg-white text-white dark:text-[#112119] px-4 py-2 text-xs font-black hover:opacity-90 transition"
                                        data-open-modal="viewLoanModal" data-loan-id="{{ $loan->loan_id }}"
                                        data-employee-id="{{ $loan->employee_ID }}"
                                        data-employee-name="{{ optional($loan->user)->name ?? 'NA' }}"
                                        data-office="{{ optional($loan->user)->office ?? 'NA' }}"
                                        data-loan-type="{{ $loan->loan_type }}"
                                        data-loan-amount="{{ number_format($loan->loan_amount, 2) }}"
                                        data-terms="{{ $loan->terms }}" data-interest-rate="{{ $loan->interest_rate }}"
                                        data-monthly-payment="{{ number_format($loan->monthly_payment, 2) }}"
                                        data-total-deduction="{{ number_format($loan->total_deduction, 2) }}"
                                        data-total-net="{{ number_format($loan->total_net, 2) }}"
                                        data-total-payments="{{ number_format($loan->total_paid ?? 0, 2) }}"
                                        data-balance="{{ number_format($loan->computed_balance ?? ($loan->loan_amount ?? 0), 2) }}"
                                        data-date-approved="{{ $loan->date_approved }}" data-remarks="{{ $loan->remarks }}">
                                        View Details
                                    </button>

                                    <button type="button"
                                        class="update-loan-btn inline-flex items-center justify-center rounded-xl bg-primary text-[#112119] px-4 py-2 text-xs font-black hover:brightness-110 transition"
                                        data-open-modal="updateLoanModal" data-loan-id="{{ $loan->loan_id }}"
                                        data-employee-id="{{ $loan->employee_ID }}"
                                        data-employee-name="{{ optional($loan->user)->name ?? 'NA' }}"
                                        data-office="{{ optional($loan->user)->office ?? 'NA' }}"
                                        data-loan-type="{{ $loan->loan_type }}" data-loan-amount="{{ $loan->loan_amount }}"
                                        data-terms="{{ $loan->terms }}" data-interest-rate="{{ $loan->interest_rate }}"
                                        data-monthly-payment="{{ $loan->monthly_payment }}"
                                        data-total-deduction="{{ $loan->total_deduction }}"
                                        data-total-net="{{ $loan->total_net }}"
                                        data-date-approved="{{ $loan->date_approved }}" data-remarks="{{ $loan->remarks }}"
                                        data-total-payments="{{ number_format($loan->total_payments_sum ?? 0, 2, '.', '') }}"
                                        data-balance="{{ number_format($loan->latest_outstanding_balance ?? $loan->loan_amount, 2, '.', '') }}"
                                        data-no-of-payments="{{ optional($loan->latestPayment)->total_payments_count ?? 0 }}"
                                        data-latest-payment="{{ optional($loan->latestPayment)->latest_remittance ?? '' }}">
                                        Update
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    {{-- ========================= Tailwind Modal: Update Loan ========================= --}}
    <div id="updateLoanModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm" data-close-modal="updateLoanModal"></div>

        <div class="relative min-h-screen w-full flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-[#112119] w-full max-w-2xl rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] flex flex-col max-h-[90vh]">
                <div
                    class="px-8 py-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#111814] dark:text-white uppercase tracking-tight">
                            Update Loan
                        </h2>
                        <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Edit loan details</p>
                    </div>
                    <button type="button" class="text-[#638875] hover:text-red-500 transition-colors"
                        data-close-modal="updateLoanModal">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    <form id="updateLoanForm" method="POST">
                        @csrf
                        <input type="hidden" name="loan_id" id="update_loan_id">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Employee
                                    ID</label>
                                <input type="text" id="update_employee_id"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-[#f6f8f7] dark:bg-[#0d1a14]/50 px-4 py-2.5 text-sm"
                                    readonly>
                            </div>

                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Employee
                                    Name</label>
                                <input type="text" id="update_employee_name"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-[#f6f8f7] dark:bg-[#0d1a14]/50 px-4 py-2.5 text-sm"
                                    readonly>
                            </div>

                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Loan
                                    Type</label>
                                <select id="update_loan_type" name="loan_type"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                                    <option value="Regular Loan">Regular Loan</option>
                                    <option value="Educational Loan">Educational Loan</option>
                                    <option value="Appliance Loan">Appliance Loan</option>
                                    <option value="Grocery Loan">Grocery Loan</option>
                                </select>
                            </div>

                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Loan
                                    Amount</label>
                                <input type="text" id="update_loan_amount" name="loan_amount"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                            </div>

                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Monthly
                                    Payment</label>
                                <input type="text" name="update_monthly_payment" id="update_monthly_payment"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                            </div>

                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">No.
                                    of Total Payments</label>
                                <input type="number" name="no_of_payments" id="update_no_of_payments"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                            </div>

                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Total
                                    Payments</label>
                                <input type="text" name="total_payments" id="update_total_payments"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                            </div>

                            <div>
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Latest
                                    Payment</label>
                                <input type="date" name="latest_payment" id="update_latest_payment"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label
                                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Remarks</label>
                                <select name="remarks" id="update_remarks"
                                    class="w-full rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] px-4 py-2.5 text-sm">
                                    <option value="New Loan">New Loan</option>
                                    <option value="Re-Loan">Re-Loan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button"
                                class="px-6 py-2.5 rounded-xl text-sm font-black text-[#638875] hover:bg-gray-200 dark:hover:bg-[#2a3a32] transition-all"
                                data-close-modal="updateLoanModal">
                                Cancel
                            </button>

                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl text-sm font-black bg-[#112119] dark:bg-white text-white dark:text-[#112119] hover:opacity-90 transition-all">
                                Update Loan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- ========================= Tailwind Modal: View Loan (Savings-style) ========================= --}}
    <div id="viewLoanModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm" data-close-modal="viewLoanModal"></div>

        <div class="relative min-h-screen w-full flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-[#112119] w-full max-w-2xl rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] flex flex-col max-h-[90vh] overflow-hidden">

                {{-- Header --}}
                <div class="px-8 py-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-start justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#111814] dark:text-white uppercase tracking-tight">
                            Loan Details
                        </h2>
                        <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                            View full record
                        </p>
                    </div>

                    <button type="button" class="text-[#638875] hover:text-red-500 transition-colors"
                        data-close-modal="viewLoanModal">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar space-y-4">

                    {{-- Summary (like savings record modal) --}}
                    <div
                        class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-[#f6f8f7] dark:bg-[#0d1a14]/50 p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p
                                    class="text-[10px] font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Employee
                                </p>
                                <p class="mt-1 text-lg font-black text-[#111814] dark:text-white truncate"
                                    id="view_employee_name">—</p>
                                <p class="text-sm font-bold text-[#638875] dark:text-[#a0b0a8]" id="view_office">—</p>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <span
                                        class="inline-flex items-center rounded-full bg-white/80 dark:bg-white/10 px-3 py-1 text-[11px] font-black text-[#112119] dark:text-white">
                                        Loan ID: <span class="ml-1" id="view_loan_id">—</span>
                                    </span>
                                    <span
                                        class="inline-flex items-center rounded-full bg-white/80 dark:bg-white/10 px-3 py-1 text-[11px] font-black text-[#112119] dark:text-white">
                                        Employee ID: <span class="ml-1" id="view_employee_id">—</span>
                                    </span>
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <p
                                    class="text-[10px] font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Loan Amount
                                </p>
                                <p class="mt-1 text-2xl font-black text-primary" id="view_loan_amount">—</p>

                                <p
                                    class="mt-3 text-[10px] font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Balance
                                </p>
                                <p class="mt-1 text-lg font-black text-[#111814] dark:text-white" id="view_balance">—
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Details (key/value rows like savings modal) --}}
                    <div class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] overflow-hidden">
                        <div class="bg-white dark:bg-[#112119] divide-y divide-[#dce5e0] dark:divide-[#2a3a32]">

                            @php
                                // helper row macro vibe - just markup repeated
                            @endphp

                            <div class="flex items-center justify-between gap-6 px-5 py-3">
                                <p
                                    class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Loan Type</p>
                                <p class="text-sm font-black text-[#111814] dark:text-white text-right"
                                    id="view_loan_type">—</p>
                            </div>

                            <div class="flex items-center justify-between gap-6 px-5 py-3">
                                <p
                                    class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Terms</p>
                                <p class="text-sm font-black text-[#111814] dark:text-white text-right" id="view_terms">
                                    —</p>
                            </div>

                            <div class="flex items-center justify-between gap-6 px-5 py-3">
                                <p
                                    class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Interest Rate</p>
                                <p class="text-sm font-black text-[#111814] dark:text-white text-right"
                                    id="view_interest_rate">—</p>
                            </div>

                            <div class="flex items-center justify-between gap-6 px-5 py-3">
                                <p
                                    class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Monthly Payment</p>
                                <p class="text-sm font-black text-[#111814] dark:text-white text-right"
                                    id="view_monthly_payment">—</p>
                            </div>

                            <div class="flex items-center justify-between gap-6 px-5 py-3">
                                <p
                                    class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Total Deduction</p>
                                <p class="text-sm font-black text-[#111814] dark:text-white text-right"
                                    id="view_total_deduction">—</p>
                            </div>

                            <div class="flex items-center justify-between gap-6 px-5 py-3">
                                <p
                                    class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Total Net</p>
                                <p class="text-sm font-black text-[#111814] dark:text-white text-right"
                                    id="view_total_net">—</p>
                            </div>

                            <div class="flex items-center justify-between gap-6 px-5 py-3">
                                <p
                                    class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Total Payments</p>
                                <p class="text-sm font-black text-[#111814] dark:text-white text-right"
                                    id="view_total_payments">—</p>
                            </div>

                            <div class="flex items-center justify-between gap-6 px-5 py-3">
                                <p
                                    class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Date Approved</p>
                                <p class="text-sm font-black text-[#111814] dark:text-white text-right"
                                    id="view_date_approved">—</p>
                            </div>

                            <div class="flex items-start justify-between gap-6 px-5 py-3">
                                <p
                                    class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    Remarks</p>
                                <p class="text-sm font-black text-[#111814] dark:text-white text-right"
                                    id="view_remarks">—</p>
                            </div>

                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex justify-end">
                        <button type="button"
                            class="px-6 py-2.5 rounded-xl text-sm font-black text-[#638875] hover:bg-gray-200 dark:hover:bg-[#2a3a32] transition-all"
                            data-close-modal="viewLoanModal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const URLS = {
                    userDetails: @json(url('/admin/get-user-details')), // + /{employeeId}
                    coMaker: @json(url('/admin/get-co-maker')),     // + /{name}
                    loanUpdateTemplate: @json(route('admin.loans.update', ['loanId' => '__LOANID__'])),
                    loanSearch: @json(url('/admin/loans/search')),     // + ?q=
                };

                // ===== Helpers (Tailwind modal) =====
                const openModal = (id) => {
                    const el = document.getElementById(id);
                    if (!el) return;
                    el.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                };

                const closeModal = (id) => {
                    const el = document.getElementById(id);
                    if (!el) return;
                    el.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                };

                document.addEventListener('click', (e) => {
                    const closeTarget = e.target.closest('[data-close-modal]');
                    if (!closeTarget) return;
                    closeModal(closeTarget.getAttribute('data-close-modal'));
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    ['updateLoanModal', 'viewLoanModal'].forEach(id => {
                        const m = document.getElementById(id);
                        if (m && !m.classList.contains('hidden')) closeModal(id);
                    });
                });

                const loanUpdateUrl = (loanId) => {
                    return URLS.loanUpdateTemplate.replace('__LOANID__', encodeURIComponent(loanId));
                };

                // ===== Auto-fill user by employee_id =====
                const employeeInput = document.getElementById("employee_id");
                if (employeeInput) {
                    employeeInput.addEventListener("keyup", function () {
                        const employeeId = this.value.trim();
                        if (employeeId.length <= 3) return;

                        fetch(`${URLS.userDetails}/${encodeURIComponent(employeeId)}`, { headers: { 'Accept': 'application/json' } })
                            .then(async res => {
                                if (!res.ok) {
                                    const text = await res.text();
                                    console.error("get-user-details non-ok:", res.status, text);
                                    return { success: false };
                                }
                                return res.json();
                            })
                            .then(data => {
                                if (data && data.success) {
                                    document.getElementById("employee_name").value = data.name || "";
                                    document.getElementById("office").value = data.office || "";
                                } else {
                                    document.getElementById("employee_name").value = "Not found";
                                    document.getElementById("office").value = "Not found";
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                document.getElementById("employee_name").value = "Not found";
                                document.getElementById("office").value = "Not found";
                            });
                    });
                }

                // ===== Co-maker search (Tailwind suggestions) =====
                function setupCoMakerSearch(inputId, positionId, suggestionBoxId, errorId) {
                    const inputField = document.getElementById(inputId);
                    const positionField = document.getElementById(positionId);
                    const suggestionBox = document.getElementById(suggestionBoxId);
                    const errorMessage = document.getElementById(errorId);

                    if (!inputField || !positionField || !suggestionBox || !errorMessage) return;

                    inputField.addEventListener("keyup", function () {
                        const name = this.value.trim();

                        if (name.length <= 2) {
                            suggestionBox.classList.add('hidden');
                            errorMessage.classList.add('hidden');
                            positionField.value = "";
                            return;
                        }

                        fetch(`${URLS.coMaker}/${encodeURIComponent(name)}`, { headers: { 'Accept': 'application/json' } })
                            .then(async res => {
                                if (!res.ok) {
                                    const txt = await res.text();
                                    console.error('get-co-maker non-ok:', res.status, txt);
                                    return null;
                                }
                                return res.json();
                            })
                            .then(data => {
                                suggestionBox.innerHTML = "";

                                if (data && data.success && Array.isArray(data.users) && data.users.length > 0) {
                                    suggestionBox.classList.remove('hidden');
                                    errorMessage.classList.add('hidden');

                                    data.users.forEach(user => {
                                        const item = document.createElement("button");
                                        item.type = "button";
                                        item.className = "w-full text-left px-4 py-2 text-sm hover:bg-[#f6f8f7] dark:hover:bg-[#0d1a14]/50 transition";
                                        item.textContent = user.name;

                                        item.addEventListener("click", function () {
                                            inputField.value = user.name;
                                            positionField.value = user.position || "";
                                            suggestionBox.classList.add('hidden');
                                        });

                                        suggestionBox.appendChild(item);
                                    });
                                } else {
                                    suggestionBox.classList.add('hidden');
                                    errorMessage.classList.remove('hidden');
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                suggestionBox.classList.add('hidden');
                                errorMessage.classList.remove('hidden');
                            });
                    });

                    document.addEventListener("click", function (e) {
                        if (!suggestionBox.contains(e.target) && e.target !== inputField) {
                            suggestionBox.classList.add('hidden');
                        }
                    });
                }

                setupCoMakerSearch("co_maker_name", "co_maker_position", "co_maker_suggestions", "co_maker_error");
                setupCoMakerSearch("co_maker2_name", "co_maker2_position", "co_maker2_suggestions", "co_maker2_error");

                // ===== Calculation =====
                function formatNumber(value) {
                    return Number(value || 0).toFixed(2);
                }

                function calculateTotalDeduction() {
                    const oldBalance = parseFloat((document.querySelector("#old_balance")?.value || "0").replace(/,/g, "")) || 0;
                    const lpp = parseFloat((document.querySelector("#lpp")?.value || "0").replace(/,/g, "")) || 0;
                    const interest = parseFloat((document.querySelector("#interest")?.value || "0").replace(/,/g, "")) || 0;
                    const pettyCashLoan = parseFloat((document.querySelector("#petty_cash_loan")?.value || "0").replace(/,/g, "")) || 0;
                    const handlingFee = parseFloat((document.querySelector("#handling_fee")?.value || "0").replace(/,/g, "")) || 0;
                    const loanAmount = parseFloat((document.querySelector("#loan_amount")?.value || "0").replace(/,/g, "")) || 0;
                    const terms = parseInt(document.querySelector("#terms")?.value) || 1;

                    const totalDeduction = oldBalance + lpp + interest + pettyCashLoan + handlingFee;
                    const totalNet = loanAmount - totalDeduction;
                    const monthlyPayment = loanAmount / terms;

                    const totalDeductionField = document.querySelector("#total_deduction");
                    const totalNetField = document.querySelector("#total_net");
                    const monthlyPaymentField = document.querySelector("#monthly_payment");

                    if (totalDeductionField) totalDeductionField.value = formatNumber(totalDeduction);
                    if (totalNetField) totalNetField.value = formatNumber(Math.max(totalNet, 0));
                    if (monthlyPaymentField) monthlyPaymentField.value = formatNumber(monthlyPayment);
                }

                ["old_balance", "lpp", "interest", "handling_fee", "petty_cash_loan", "loan_amount", "terms"].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.addEventListener("input", calculateTotalDeduction);
                });

                // ===== Update modal open (event delegation) =====
                document.addEventListener("click", function (e) {
                    const btn = e.target.closest(".update-loan-btn");
                    if (!btn) return;

                    const setValue = (id, value) => {
                        const el = document.getElementById(id);
                        if (el) el.value = value ?? "";
                    };

                    setValue("update_loan_id", btn.dataset.loanId);
                    setValue("update_employee_id", btn.dataset.employeeId);
                    setValue("update_employee_name", btn.dataset.employeeName);
                    setValue("update_loan_amount", btn.dataset.loanAmount || "0.00");
                    setValue("update_no_of_payments", btn.dataset.noOfPayments || 0);
                    setValue("update_total_payments", btn.dataset.totalPayments || "0.00");
                    setValue("update_latest_payment", btn.dataset.latestPayment || "");
                    setValue("update_monthly_payment", btn.dataset.monthlyPayment || "0.00");
                    setValue("update_remarks", btn.dataset.remarks || "");

                    const loanTypeDropdown = document.getElementById("update_loan_type");
                    if (loanTypeDropdown) loanTypeDropdown.value = btn.dataset.loanType || "Regular Loan";

                    openModal(btn.getAttribute("data-open-modal") || "updateLoanModal");
                });

                // ===== View modal open (event delegation) =====
                document.addEventListener("click", function (e) {
                    const btn = e.target.closest(".view-loan-btn");
                    if (!btn) return;

                    const setText = (id, value) => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = value ?? "—";
                    };

                    setText("view_loan_id", btn.dataset.loanId);
                    setText("view_employee_id", btn.dataset.employeeId);
                    setText("view_employee_name", btn.dataset.employeeName);
                    setText("view_office", btn.dataset.office);
                    setText("view_loan_type", btn.dataset.loanType);
                    setText("view_loan_amount", btn.dataset.loanAmount);
                    setText("view_terms", btn.dataset.terms);
                    setText("view_interest_rate", btn.dataset.interestRate);
                    setText("view_monthly_payment", btn.dataset.monthlyPayment);
                    setText("view_total_deduction", btn.dataset.totalDeduction);
                    setText("view_total_net", btn.dataset.totalNet);
                    setText("view_date_approved", btn.dataset.dateApproved);
                    setText("view_total_payments", btn.dataset.totalPayments);
                    setText("view_balance", btn.dataset.balance);

                    setText("view_remarks", btn.dataset.remarks);

                    openModal(btn.getAttribute("data-open-modal") || "viewLoanModal");
                });

                // ===== Submit update loan =====
                const updateLoanForm = document.getElementById("updateLoanForm");
                if (updateLoanForm) {
                    updateLoanForm.addEventListener("submit", function (event) {
                        event.preventDefault();

                        const loanId = document.getElementById("update_loan_id")?.value;
                        if (!loanId) return;

                        const formData = new FormData(this);
                        formData.append("_method", "PATCH"); // method spoof

                        fetch(loanUpdateUrl(loanId), {
                            method: "POST",
                            body: formData,
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content,
                                "Accept": "application/json"
                            }
                        })
                            .then(async res => {
                                const data = await res.json().catch(() => ({}));
                                if (!res.ok) throw new Error(data.message || "Update failed");
                                return data;
                            })
                            .then(data => {
                                if (!data.success) throw new Error(data.message || "Update failed");
                                alert("✅ Loan updated!");
                                closeModal("updateLoanModal");
                                location.reload();
                            })
                            .catch(err => {
                                console.error("🚨 Error updating loan:", err);
                                alert("⚠ Update failed: " + err.message);
                            });
                    });
                }

                // ===== Search (AJAX) =====
                const searchInput = document.getElementById("search_loans");
                if (searchInput) {
                    searchInput.addEventListener("keyup", function () {
                        const query = this.value.trim();

                        fetch(`${URLS.loanSearch}?q=${encodeURIComponent(query)}`, { headers: { "Accept": "application/json" } })
                            .then(async res => {
                                if (!res.ok) {
                                    const txt = await res.text();
                                    console.error("loans/search non-ok:", res.status, txt);
                                    return [];
                                }
                                return res.json();
                            })
                            .then(data => {
                                const tableBody = document.getElementById("loanTableBody");
                                if (!tableBody) return;

                                tableBody.innerHTML = "";

                                if (!data || data.length === 0) {
                                    tableBody.innerHTML = `<tr><td colspan="9" class="px-6 py-6 text-center text-sm font-black text-[#638875]">No results found</td></tr>`;
                                    return;
                                }

                                data.forEach((loan, index) => {
                                    const row = document.createElement("tr");
                                    row.className = "hover:bg-gray-50 dark:hover:bg-white/5 transition-colors";
                                    row.innerHTML = `
                                                                                                                                                                <td class="px-6 py-4">${index + 1}</td>
                                                                                                                                                                <td class="px-6 py-4 font-black text-primary">${loan.loan_id ?? ''}</td>
                                                                                                                                                                <td class="px-6 py-4">${loan.employee_ID ?? ''}</td>
                                                                                                                                                                <td class="px-6 py-4 font-black">${loan.employee_name ?? 'NA'}</td>
                                                                                                                                                                <td class="px-6 py-4">${loan.office ?? 'NA'}</td>
                                                                                                                                                                <td class="px-6 py-4">${loan.loan_type ?? ''}</td>
                                                                                                                                                                <td class="px-6 py-4 font-black">${loan.loan_amount ?? ''}</td>
                                                                                                                                                                <td class="px-6 py-4">${loan.date_approved ?? ''}</td>
                                                                                                                                                                <td class="px-6 py-4">
                                                                                                                                                                    <div class="flex flex-col gap-2 min-w-[160px]">
                                                                                                                                                                        <button type="button"
                                                                                                                                                                            class="view-loan-btn inline-flex items-center justify-center rounded-xl bg-[#112119] dark:bg-white text-white dark:text-[#112119] px-4 py-2 text-xs font-black hover:opacity-90 transition"
                                                                                                                                                                            data-open-modal="viewLoanModal"
                                                                                                                                                                            data-loan-id="${loan.loan_id ?? ''}"
                                                                                                                                                                            data-employee-id="${loan.employee_ID ?? ''}"
                                                                                                                                                                            data-employee-name="${loan.employee_name ?? 'NA'}"
                                                                                                                                                                            data-office="${loan.office ?? 'NA'}"
                                                                                                                                                                            data-loan-type="${loan.loan_type ?? ''}"
                                                                                                                                                                            data-loan-amount="${loan.loan_amount ?? ''}"
                                                                                                                                                                            data-terms="${loan.terms ?? ''}"
                                                                                                                                                                            data-interest-rate="${loan.interest_rate ?? ''}"
                                                                                                                                                                            data-monthly-payment="${loan.monthly_payment ?? ''}"
                                                                                                                                                                            data-total-deduction="${loan.total_deduction ?? ''}"
                                                                                                                                                                            data-total-net="${loan.total_net ?? ''}"
                                                                                                                                                                            data-date-approved="${loan.date_approved ?? ''}"
                                                                                                                                                                            data-remarks="${loan.remarks ?? ''}">
                                                                                                                                                                            View Details
                                                                                                                                                                        </button>

                                                                                                                                                                        <button type="button"
                                                                                                                                                                            class="update-loan-btn inline-flex items-center justify-center rounded-xl bg-primary text-[#112119] px-4 py-2 text-xs font-black hover:brightness-110 transition"
                                                                                                                                                                            data-open-modal="updateLoanModal"
                                                                                                                                                                            data-loan-id="${loan.loan_id ?? ''}"
                                                                                                                                                                            data-employee-id="${loan.employee_ID ?? ''}"
                                                                                                                                                                            data-employee-name="${loan.employee_name ?? 'NA'}"
                                                                                                                                                                            data-loan-type="${loan.loan_type ?? ''}"
                                                                                                                                                                            data-loan-amount="${loan.loan_amount ?? '0.00'}"
                                                                                                                                                                            data-no-of-payments="${loan.total_payments_count ?? 0}"
                                                                                                                                                                            data-total-payments="{{ number_format($loan->total_payments_sum ?? 0, 2) }}"
                                                                                                                                                                            data-monthly-payment="${loan.monthly_payment ?? '0.00'}"
                                                                                                                                                                            data-latest-payment="${loan.latest_remittance ?? ''}"
                                                                                                                                                                            data-remarks="${loan.remarks ?? ''}">
                                                                                                                                                                            Update
                                                                                                                                                                        </button>
                                                                                                                                                                    </div>
                                                                                                                                                                </td>
                                                                                                                                                            `;
                                    tableBody.appendChild(row);
                                });
                            })
                            .catch(err => console.error("Error fetching search results:", err));
                    });
                }
            });
        </script>
    @endpush

</x-admin-v2-layout>