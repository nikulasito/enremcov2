<x-admin-v2-layout title="ENREMCO - Loan Payments" pageTitle="Loan Payments"
    pageSubtitle="Manage remittances, monthly deductions, and outstanding balances" :showSearch="false">
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

    {{-- Filters --}}
    <div class="mb-8 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] shadow-sm">
        <div class="p-6 border-b border-[#dce5e0] dark:border-[#2a3a32]">
            <h4 class="text-lg font-black">Filters</h4>
            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                Filter the loan list by office, loan type, and search query.
            </p>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Filter by Office
                </label>
                <select id="filter_office"
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                    <option value="">All Offices</option>
                    @foreach ($offices as $office)
                        <option value="{{ $office }}">{{ $office }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Filter by Loan Type
                </label>
                <select id="filter_loan_type"
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                    <option value="">All Types</option>
                    <option value="Regular Loan">Regular Loan</option>
                    <option value="Educational Loan">Educational Loan</option>
                    <option value="Appliance Loan">Appliance Loan</option>
                    <option value="Grocery Loan">Grocery Loan</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Search
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#638875]">
                        search
                    </span>
                    <input type="text" id="searchLoanPayments"
                        class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 pl-10 pr-4 text-sm"
                        placeholder="Search by Employee Name, Employee ID, Loan ID, or Office...">
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Remittance Details + Add Payment --}}
    <div class="mb-8 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] shadow-sm">
        <div class="p-6 border-b border-[#dce5e0] dark:border-[#2a3a32]">
            <h4 class="text-lg font-black">Add Payments (Bulk)</h4>
            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                Fill the remittance details, select loans below, then click “Add Payment”.
            </p>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Remittance No.
                </label>
                <input type="text" id="remittance_no" placeholder="Enter Remittance No."
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
            </div>

            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Date of Remittance
                </label>
                <input type="date" id="date_of_remittance"
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
            </div>

            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Covered Month
                </label>
                <select id="date_covered_month"
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                    <option value="">Select Month</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Covered Year
                </label>
                <select id="date_covered_year"
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                    <option value="">Select Year</option>
                    @for ($y = date('Y'); $y >= date('Y') - 20; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    {{-- Loans Table --}}
    <div
        class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-b border-[#dce5e0] dark:border-[#2a3a32]">
                    <tr>
                        <th class="px-6 py-4"><input type="checkbox" id="selectAll"></th>
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
                        <!-- <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Office</th> -->
                        <!-- <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Loan Type</th> -->
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Loan Amount</th>
                        <!-- <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Monthly Payment</th> -->
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Total Payments</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Balance</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Remittance No.</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Date of Remittance</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Date Covered</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Payment</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Action</th>
                    </tr>
                </thead>

                <tbody id="loanPaymentTable" class="divide-y ...">
                    @foreach ($loans as $index => $loan)
                        @php
                            $latest = $loan->latestPayment;

                            $userOffice = strtolower(optional($loan->user)->office ?? '');
                            $loanTypeLower = strtolower($loan->loan_type ?? '');

                            $balance = $loan->current_balance ?? 0;
                          @endphp

                        @if($balance > 0)
                            <tr class="loanPaymentRow ..." data-office="{{ $userOffice }}" data-loan-type="{{ $loanTypeLower }}"
                                data-loan-id="{{ $loan->loan_id }}">

                                <td class="px-6 py-4">
                                    <input type="checkbox" class="rowCheckbox">
                                </td>

                                <td class="px-6 py-4">{{ $index + 1 }}</td>

                                <td class="px-6 py-4 font-black text-primary">{{ $loan->loan_id }}</td>

                                <td class="px-6 py-4">{{ $loan->employee_ID }}</td>

                                <td class="px-6 py-4">{{ optional($loan->user)->name ?? 'NA' }}</td>

                                <!-- <td class="px-6 py-4">{{ optional($loan->user)->office ?? 'NA' }}</td> -->

                                <!-- <td class="px-6 py-4">{{ $loan->loan_type }}</td> -->

                                <td class="px-6 py-4 font-black">{{ number_format($loan->loan_amount, 2) }}</td>

                                <!-- <td class="px-6 py-4 font-black">{{ number_format($loan->monthly_payment, 2) }}</td> -->

                                <td class="px-6 py-4 font-black total-payments">
                                    {{ number_format($loan->total_paid ?? 0, 2) }}
                                </td>

                                <td class="px-6 py-4 font-black outstanding-balance">
                                    {{ number_format($balance, 2) }}
                                </td>

                                <td class="px-6 py-4 remittance-no">
                                    {{ $latest->remittance_no ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 date-of-remittance">
                                    {{ $latest->date_of_remittance ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 date-covered">
                                    @if($latest && $latest->date_covered_month)
                                        {{ date('F', mktime(0, 0, 0, $latest->date_covered_month, 1)) }},
                                        {{ $latest->date_covered_year }}
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <input type="number" class="payment-amount w-28 rounded-lg border"
                                        value="{{ $loan->monthly_payment }}" placeholder="Enter Amount">
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-2 min-w-[140px]">
                                        <button type="button"
                                            class="update-payment-btn view-loan-btn inline-flex items-center justify-center rounded-xl bg-[#112119] dark:bg-white text-white dark:text-[#112119] px-4 py-2 text-xs font-black hover:opacity-90 transition "
                                            data-open-modal="updatePaymentModal" data-loan-id="{{ $loan->loan_id }}"
                                            data-employee-id="{{ $loan->employee_ID }}"
                                            data-employee-name="{{ optional($loan->user)->name ?? 'NA' }}"
                                            data-loan-type="{{ $loan->loan_type }}">
                                            Update
                                        </button>

                                        <!-- <button type="button" class="view-loan-btn ..." data-open-modal="viewLoanModal"
                                                                                                            data-loan-id="{{ $loan->loan_id }}">
                                                                                                            View
                                                                                                        </button> -->
                                    </div>
                                </td>

                            </tr>
                        @endif
                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- Footer actions --}}
        <div class="p-6 border-t border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-end gap-3">
            <button
                class="bg-primary bulkAddPayment rounded-lg bg-[#112119] dark:bg-white text-white dark:text-[#112119] px-6 py-2.5 text-sm font-black hover:opacity-90 transition"
                disabled>
                Add Payment
            </button>
        </div>
    </div>

    {{-- ===== Tailwind Modal: Update Payment ===== --}}
    <div id="updatePaymentModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm" data-close-modal="updatePaymentModal"></div>

        <div class="relative min-h-screen w-full flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-[#112119] w-full max-w-3xl rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] flex flex-col max-h-[90vh]">

                <div
                    class="px-8 py-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#111814] dark:text-white uppercase tracking-tight">Update
                            Loan Payment</h2>
                        <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Search remittance then edit details</p>
                    </div>
                    <button type="button" class="text-[#638875] hover:text-red-500 transition-colors"
                        data-close-modal="updatePaymentModal">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    <form id="updatePaymentForm">
                        @csrf
                        <input type="hidden" name="loan_id" id="modal_loan_id">

                        {{-- Search --}}
                        <div class="space-y-3 mb-6">
                            <h4 class="text-sm font-bold text-[#111814] dark:text-white uppercase tracking-wider">
                                Search Remittance Record
                            </h4>

                            <div class="flex flex-col md:flex-row gap-3">
                                <div class="relative flex-1">
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#638875]">
                                        search
                                    </span>
                                    <input type="text" id="search_remittance_no"
                                        class="w-full pl-12 pr-4 py-3 bg-white dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none"
                                        placeholder="Enter Remittance No.">
                                </div>

                                <button type="button" id="searchRemittanceBtn"
                                    class="bg-primary hover:brightness-110 text-[#112119] font-bold px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-xl">search</span>
                                    Search
                                </button>
                            </div>

                            <p class="text-[11px] text-[#638875] dark:text-[#a0b0a8]">
                                Tip: Enter the Remittance No. related to this loan.
                            </p>
                        </div>

                        {{-- Details --}}
                        <div id="paymentDetailsSection" class="hidden">
                            <div
                                class="bg-[#f6f8f7] dark:bg-[#1a2e24] rounded-xl p-6 border border-[#dce5e0] dark:border-[#2a3a32] mb-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <p
                                            class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                            Employee ID</p>
                                        <input type="text" id="modal_employee_id"
                                            class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm"
                                            readonly>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                            Employee Name</p>
                                        <input type="text" id="modal_employee_name"
                                            class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm"
                                            readonly>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                            Loan Type</p>
                                        <input type="text" id="modal_loan_type"
                                            class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label
                                        class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                        Remittance No.
                                    </label>
                                    <input type="text" id="modal_remittance_no" name="remittance_no"
                                        class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                                </div>

                                <div>
                                    <label
                                        class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                        Date of Remittance
                                    </label>
                                    <input type="date" id="modal_date_of_remittance" name="date_of_remittance"
                                        class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                                </div>

                                <div>
                                    <label
                                        class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                        Total Payment
                                    </label>
                                    <input type="text" id="modal_total_payment" name="total_payments"
                                        class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                        Covered Month
                                    </label>
                                    <select id="modal_date_covered_month" name="date_covered_month"
                                        class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label
                                        class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                        Covered Year
                                    </label>
                                    <select id="modal_date_covered_year" name="date_covered_year"
                                        class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                                        @for ($y = date('Y'); $y >= date('Y') - 20; $y--)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button"
                                class="px-6 py-2.5 rounded-lg text-sm font-bold text-[#638875] hover:bg-gray-200 dark:hover:bg-[#2a3a32] transition-all"
                                data-close-modal="updatePaymentModal">
                                Cancel
                            </button>

                            <button type="submit" id="savePaymentBtn"
                                class="px-6 py-2.5 rounded-lg text-sm font-bold bg-[#112119] dark:bg-white text-white dark:text-[#112119] hover:opacity-90 transition-all">
                                Save
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- ===== Tailwind Modal: View Loan Details ===== --}}
    <div id="viewLoanModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm" data-close-modal="viewLoanModal"></div>

        <div class="relative min-h-screen w-full flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-[#112119] w-full max-w-5xl rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] flex flex-col max-h-[90vh]">

                {{-- Header --}}
                <div
                    class="px-8 py-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#111814] dark:text-white uppercase tracking-tight">
                            Loan payment
                        </h2>
                        <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                            Includes payment history + other loans of this member
                        </p>
                    </div>

                    <button type="button" class="text-[#638875] hover:text-red-500 transition-colors"
                        data-close-modal="viewLoanModal">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    {{-- Top Summary --}}
                    <div
                        class="rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-[#f6f8f7] dark:bg-[#1a2e24] p-6 mb-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-primary uppercase tracking-widest">
                                    Loan ID: <span id="vLoanId">—</span>
                                </p>
                                <h3 class="text-xl font-black text-[#111814] dark:text-white" id="vEmployeeName">—</h3>
                                <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                                    <span id="vEmployeeId">—</span> • <span id="vOffice">—</span>
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <span id="vStatusBadge"
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-black border">
                                    —
                                </span>
                                <div class="text-right">
                                    <p
                                        class="text-[10px] font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                        Outstanding Balance
                                    </p>
                                    <p class="text-lg font-black text-[#111814] dark:text-white" id="vOutstandingTop">—
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Loan Type</p>
                                <p class="font-black" id="vLoanType">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Loan Amount</p>
                                <p class="font-black" id="vLoanAmount">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Terms</p>
                                <p class="font-black" id="vTerms">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Monthly Payment</p>
                                <p class="font-black" id="vMonthly">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Total Deduction</p>
                                <p class="font-black" id="vTotalDeduction">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Net Amount</p>
                                <p class="font-black" id="vNetAmount">—</p>
                            </div>

                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Total Paid</p>
                                <p class="font-black" id="vTotalPaid">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Outstanding</p>
                                <p class="font-black" id="vOutstanding">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Latest Remittance</p>
                                <p class="font-black" id="vLatestRemittance">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Remittance No.</p>
                                <p class="font-black" id="vRemittanceNo">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Date of Remittance</p>
                                <p class="font-black" id="vDateOfRemittance">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase text-[#638875]">Covered</p>
                                <p class="font-black"><span id="vCoveredMonth">—</span> <span id="vCoveredYear"></span>
                                </p>
                            </div>
                        </div>

                    </div>

                    {{-- Payment History --}}
                    <div class="mb-8">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-3">
                            <h4 class="text-sm font-black text-[#111814] dark:text-white uppercase tracking-wider">
                                Payment History
                            </h4>

                            <div class="relative w-full md:w-72">
                                <span
                                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#638875]">
                                    search
                                </span>
                                <input id="vPaymentSearch" type="text" placeholder="Filter remittance / year..."
                                    class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] py-2.5 pl-10 pr-3 text-sm">
                            </div>
                        </div>

                        <div id="vPaymentsWrap"></div>
                    </div>

                    {{-- Other Loans (Member History across types) --}}
                    <div>
                        <h4 class="text-sm font-black text-[#111814] dark:text-white uppercase tracking-wider mb-3">
                            Other Loans of this Member
                        </h4>
                        <div id="vOtherLoansWrap"></div>
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="px-8 py-6 bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-t border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-end">
                    <button type="button"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold text-[#638875] hover:bg-gray-200 dark:hover:bg-[#2a3a32] transition-all"
                        data-close-modal="viewLoanModal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            const STORE_BULK_URL = @json(route('admin.loan-payments.store-bulk'));
            document.addEventListener("DOMContentLoaded", function () {
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

                // close via backdrop / close buttons
                document.addEventListener('click', (e) => {
                    const closeTarget = e.target.closest('[data-close-modal]');
                    if (!closeTarget) return;
                    closeModal(closeTarget.getAttribute('data-close-modal'));
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    const modal = document.getElementById('updatePaymentModal');
                    if (modal && !modal.classList.contains('hidden')) closeModal('updatePaymentModal');
                });

                // ===== Client-side filtering (Office + Loan Type + Search) =====
                const filterOffice = document.getElementById("filter_office");
                const filterLoanType = document.getElementById("filter_loan_type");
                const searchInput = document.getElementById("searchLoanPayments");

                const applyFilters = () => {
                    const officeVal = (filterOffice?.value || '').toLowerCase().trim();
                    const typeVal = (filterLoanType?.value || '').toLowerCase().trim();
                    const q = (searchInput?.value || '').toLowerCase().trim();

                    document.querySelectorAll("#loanPaymentTable .loanPaymentRow").forEach(row => {
                        const name = row.getAttribute("data-name") || "";
                        const empId = row.getAttribute("data-id") || "";
                        const office = row.getAttribute("data-office") || "";
                        const loanId = (row.getAttribute("data-loan-id") || "").toLowerCase();
                        const loanType = row.getAttribute("data-loan-type") || "";

                        const matchOffice = !officeVal || office.includes(officeVal);
                        const matchType = !typeVal || loanType.includes(typeVal);
                        const matchSearch = !q || name.includes(q) || empId.includes(q) || office.includes(q) || loanId.includes(q);

                        row.style.display = (matchOffice && matchType && matchSearch) ? "" : "none";
                    });
                };

                searchInput?.addEventListener("input", applyFilters);
                filterOffice?.addEventListener("change", applyFilters);
                filterLoanType?.addEventListener("change", applyFilters);

                // ===== Bulk Add Payment Enable/Disable =====
                const selectAllCheckbox = document.getElementById("selectAll");
                const bulkAddPaymentBtn = document.querySelector(".bulkAddPayment");

                const getRowCheckboxes = () => Array.from(document.querySelectorAll("#loanPaymentTable input[type='checkbox']"));

                function updateButtonState() {
                    const checkboxes = getRowCheckboxes();
                    const anyChecked = checkboxes.some(cb => cb.checked);
                    if (bulkAddPaymentBtn) bulkAddPaymentBtn.disabled = !anyChecked;
                }

                selectAllCheckbox?.addEventListener("click", function () {
                    const checked = this.checked;
                    getRowCheckboxes().forEach(cb => cb.checked = checked);
                    updateButtonState();
                });

                document.addEventListener("change", function (e) {
                    if (e.target && e.target.matches("#loanPaymentTable input[type='checkbox']")) {
                        updateButtonState();
                    }
                });

                // ===== Bulk Add Payment Function (unchanged logic) =====
                bulkAddPaymentBtn?.addEventListener("click", function () {
                    const checkboxes = getRowCheckboxes();
                    let selectedLoans = [];

                    checkboxes.forEach(checkbox => {
                        if (!checkbox.checked) return;

                        let row = checkbox.closest("tr");
                        let loanId = row?.dataset?.loanId;
                        let amountInput = row?.querySelector(".payment-amount");
                        let balanceCell = row?.querySelector(".outstanding-balance");

                        let amount = parseFloat(amountInput?.value) || 0;
                        let outstandingBalance = parseFloat((balanceCell?.textContent || "0").replace(/,/g, "")) || 0;

                        if (amount <= 0) {
                            alert(`⚠ Invalid payment for Loan ID ${loanId}. Amount must be greater than 0.`);
                            return;
                        }

                        if (amount > outstandingBalance) {
                            alert(`⚠ Error: Payment for Loan ID ${loanId} cannot exceed the outstanding balance of ${outstandingBalance.toFixed(2)}.`);
                            return;
                        }

                        selectedLoans.push({
                            loan_id: loanId,
                            total_payments: amount
                        });
                    });

                    if (selectedLoans.length === 0) {
                        alert("⚠ Please select at least one loan with a valid amount.");
                        return;
                    }

                    let remittanceNo = document.getElementById("remittance_no")?.value;
                    let dateOfRemittance = document.getElementById("date_of_remittance")?.value;
                    let dateCoveredMonth = document.getElementById("date_covered_month")?.value;
                    let dateCoveredYear = document.getElementById("date_covered_year")?.value;

                    if (!remittanceNo || !dateOfRemittance || !dateCoveredMonth || !dateCoveredYear) {
                        alert("⚠ Please fill in all remittance and date details.");
                        return;
                    }

                    fetch(STORE_BULK_URL, {
                        method: "POST",
                        body: JSON.stringify({
                            loans: selectedLoans,
                            remittance_no: remittanceNo,
                            date_of_remittance: dateOfRemittance,
                            date_covered_month: parseInt(dateCoveredMonth),
                            date_covered_year: parseInt(dateCoveredYear)
                        }),
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                alert("⚠ Error: " + (data.message || "Failed"));
                                return;
                            }

                            alert("✅ Payments added successfully!");

                            selectedLoans.forEach(loan => {
                                let row = document.querySelector(`tr[data-loan-id="${loan.loan_id}"]`);
                                if (!row) return;

                                let balanceCell = row.querySelector(".outstanding-balance");
                                let totalPaymentsCell = row.querySelector(".total-payments");
                                let latestPaymentCell = row.querySelector(".latest-payment");
                                let remittanceNoCell = row.querySelector(".remittance-no");
                                let dateOfRemittanceCell = row.querySelector(".date-of-remittance");
                                let dateCoveredCell = row.querySelector(".date-covered");

                                const p = data.loan_payments?.[loan.loan_id];

                                if (p) {
                                    if (balanceCell) balanceCell.textContent = p.latest_outstanding_balance ?? balanceCell.textContent;
                                    if (totalPaymentsCell) totalPaymentsCell.textContent = p.total_payments ?? totalPaymentsCell.textContent;
                                    if (latestPaymentCell) latestPaymentCell.textContent = p.latest_remittance ?? 'N/A';
                                    if (remittanceNoCell) remittanceNoCell.textContent = p.remittance_no ?? 'N/A';
                                    if (dateOfRemittanceCell) dateOfRemittanceCell.textContent = p.date_of_remittance ?? 'N/A';
                                    if (dateCoveredCell) dateCoveredCell.textContent = `${p.date_covered_month ?? 'N/A'}, ${p.date_covered_year ?? 'N/A'}`;

                                    if (parseFloat(p.latest_outstanding_balance) === 0) row.remove();
                                }
                            });

                            if (selectAllCheckbox) selectAllCheckbox.checked = false;
                            updateButtonState();
                        })
                        .catch(error => {
                            console.error("❌ API Error: ", error);
                            alert(`❌ Error: ${error.message}`);
                        });
                });

                // ===== Open Update Modal =====
                document.addEventListener("click", function (e) {
                    const btn = e.target.closest(".update-payment-btn");
                    if (!btn) return;

                    // set loan context
                    document.getElementById("modal_loan_id").value = btn.dataset.loanId || '';
                    document.getElementById("modal_employee_id").value = btn.dataset.employeeId || '';
                    document.getElementById("modal_employee_name").value = btn.dataset.employeeName || '';
                    document.getElementById("modal_loan_type").value = btn.dataset.loanType || '';

                    // reset modal state
                    const details = document.getElementById("paymentDetailsSection");
                    if (details) details.classList.add("hidden");
                    const searchRem = document.getElementById("search_remittance_no");
                    if (searchRem) searchRem.value = '';

                    openModal("updatePaymentModal");
                });

                // ===== Search remittance inside modal =====
                document.getElementById("searchRemittanceBtn")?.addEventListener("click", function () {
                    const remittanceNo = document.getElementById("search_remittance_no")?.value?.trim();
                    const loanId = document.getElementById("modal_loan_id")?.value;

                    if (!remittanceNo) {
                        alert("Enter a Remittance No.");
                        return;
                    }

                    fetch(`/admin/loan-payments/remittance/${encodeURIComponent(remittanceNo)}/${encodeURIComponent(loanId)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                alert("No remittance record found.");
                                return;
                            }

                            const p = data.payment;

                            document.getElementById("modal_employee_id").value = data.employee_id || '';
                            document.getElementById("modal_employee_name").value = data.employee_name || '';
                            document.getElementById("modal_loan_type").value = data.loan_type || '';

                            document.getElementById("modal_remittance_no").value = p.remittance_no || '';
                            document.getElementById("modal_date_of_remittance").value = p.date_of_remittance || '';
                            document.getElementById("modal_total_payment").value = p.total_payments || '';
                            document.getElementById("modal_date_covered_month").value = p.date_covered_month || '';
                            document.getElementById("modal_date_covered_year").value = p.date_covered_year || '';

                            document.getElementById("paymentDetailsSection")?.classList.remove("hidden");
                        })
                        .catch(() => alert("Failed to fetch remittance data."));
                });

                // ===== Save update (POST to your named route) =====
                const updateUrl = @json(route('admin.loan-payments.update'));

                document.getElementById("updatePaymentForm")?.addEventListener("submit", function (e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    fetch(updateUrl, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
                            "Accept": "application/json"
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert("✅ Payment updated successfully!");
                                closeModal("updatePaymentModal");
                                location.reload();
                            } else {
                                alert("⚠ Update failed: " + (data.message || "Unknown error"));
                            }
                        })
                        .catch(() => alert("Something went wrong."));
                });

                // ===== View Loan Modal (AJAX) =====
                const viewLoanBaseUrl = @json(url('/admin/loan-payments/view'));

                const money = (v) => {
                    const n = parseFloat(v);
                    if (isNaN(n)) return '—';
                    return '₱ ' + n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                };

                const months = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

                const setBadge = (el, paid) => {
                    if (!el) return;

                    if (paid) {
                        el.textContent = "PAID";
                        el.className = "inline-flex items-center rounded-full px-3 py-1 text-xs font-black border border-emerald-200 bg-emerald-50 text-emerald-700";
                    } else {
                        el.textContent = "NOT YET PAID";
                        el.className = "inline-flex items-center rounded-full px-3 py-1 text-xs font-black border border-amber-200 bg-amber-50 text-amber-700";
                    }
                };

                const renderPayments = (wrapEl, payments, baseAmount) => {
                    if (!wrapEl) return;

                    if (!Array.isArray(payments) || payments.length === 0) {
                        wrapEl.innerHTML = `
                                                                                                                                  <div class="rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] p-5 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
                                                                                                                                    No payment history found.
                                                                                                                                  </div>
                                                                                                                                `;
                        return;
                    }

                    // We render descending; for running balance we compute from oldest -> newest, then display descending
                    const asc = [...payments].slice().reverse();
                    let paidSoFar = 0;

                    asc.forEach(p => {
                        paidSoFar += parseFloat(p.amount || 0) || 0;
                        p._cumulative = paidSoFar;
                        p._remaining = (typeof baseAmount === 'number') ? Math.max(baseAmount - paidSoFar, 0) : null;
                    });

                    const desc = asc.slice().reverse();

                    wrapEl.innerHTML = `
                                                                                                                                <div class="overflow-x-auto rounded-xl border border-[#dce5e0] dark:border-[#2a3a32]">
                                                                                                                                  <table class="w-full text-left text-sm">
                                                                                                                                    <thead>
                                                                                                                                      <tr class="bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-b border-[#dce5e0] dark:border-[#2a3a32]">
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Date</th>
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Remittance</th>
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Covered</th>
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Amount</th>
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Total Paid</th>
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Remaining</th>
                                                                                                                                      </tr>
                                                                                                                                    </thead>
                                                                                                                                    <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32] bg-white dark:bg-[#0d1a14]">
                                                                                                                                      ${desc.map(p => `
                                                                                                                                        <tr>
                                                                                                                                          <td class="px-6 py-4">${p.date_of_remittance || '—'}</td>
                                                                                                                                          <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">${p.remittance_no || '—'}</td>
                                                                                                                                          <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">${months[p.date_covered_month || 0] || '—'} ${p.date_covered_year || ''}</td>
                                                                                                                                          <td class="px-6 py-4 font-black">${money(p.amount)}</td>
                                                                                                                                          <td class="px-6 py-4 font-black">${money(p._cumulative)}</td>
                                                                                                                                          <td class="px-6 py-4 font-black">${p._remaining === null ? '—' : money(p._remaining)}</td>
                                                                                                                                        </tr>
                                                                                                                                      `).join('')}
                                                                                                                                    </tbody>
                                                                                                                                  </table>
                                                                                                                                </div>
                                                                                                                              `;
                };

                const renderOtherLoans = (wrapEl, list) => {
                    if (!wrapEl) return;

                    if (!Array.isArray(list) || list.length === 0) {
                        wrapEl.innerHTML = `
                                                                                                                                  <div class="rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] p-5 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
                                                                                                                                    No other loans found for this member.
                                                                                                                                  </div>
                                                                                                                                `;
                        return;
                    }

                    wrapEl.innerHTML = `
                                                                                                                                <div class="overflow-x-auto rounded-xl border border-[#dce5e0] dark:border-[#2a3a32]">
                                                                                                                                  <table class="w-full text-left text-sm">
                                                                                                                                    <thead>
                                                                                                                                      <tr class="bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-b border-[#dce5e0] dark:border-[#2a3a32]">
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Loan ID</th>
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Type</th>
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Amount</th>
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Outstanding</th>
                                                                                                                                        <th class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Status</th>
                                                                                                                                      </tr>
                                                                                                                                    </thead>
                                                                                                                                    <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32] bg-white dark:bg-[#0d1a14]">
                                                                                                                                      ${list.map(l => {
                        const out = parseFloat(l.latest_outstanding_balance || 0) || 0;
                        const paid = out <= 0;
                        return `
                                                                                                                                          <tr>
                                                                                                                                            <td class="px-6 py-4 font-black text-primary">${l.loan_id}</td>
                                                                                                                                            <td class="px-6 py-4">${l.loan_type || '—'}</td>
                                                                                                                                            <td class="px-6 py-4 font-black">${money(l.loan_amount)}</td>
                                                                                                                                            <td class="px-6 py-4 font-black">${money(out)}</td>
                                                                                                                                            <td class="px-6 py-4">
                                                                                                                                              <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-black border
                                                                                                                                                ${paid ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-amber-200 bg-amber-50 text-amber-700'}">
                                                                                                                                                ${paid ? 'PAID' : 'NOT YET PAID'}
                                                                                                                                              </span>
                                                                                                                                            </td>
                                                                                                                                          </tr>
                                                                                                                                        `;
                    }).join('')}
                                                                                                                                    </tbody>
                                                                                                                                  </table>
                                                                                                                                </div>
                                                                                                                              `;
                };

                document.addEventListener('click', async (e) => {
                    const btn = e.target.closest('.view-loan-btn');
                    if (!btn) return;

                    const loanId = btn.dataset.loanId;
                    if (!loanId) return;

                    // open modal (reuse your openModal helper if already defined)
                    openModal(btn.dataset.openModal || 'viewLoanModal');

                    // placeholders
                    const paymentsWrap = document.getElementById('vPaymentsWrap');
                    const otherWrap = document.getElementById('vOtherLoansWrap');

                    if (paymentsWrap) paymentsWrap.innerHTML = `<div class="p-5 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">Loading...</div>`;
                    if (otherWrap) otherWrap.innerHTML = '';

                    // reset search box
                    const paySearch = document.getElementById('vPaymentSearch');
                    if (paySearch) paySearch.value = '';

                    try {
                        const url = `${viewLoanBaseUrl}/${encodeURIComponent(loanId)}`;

                        const res = await fetch(url, {
                            headers: { 'Accept': 'application/json' }
                        });

                        if (!res.ok) {
                            const txt = await res.text();
                            console.error('VIEW LOAN HTTP ERROR:', res.status, txt);

                            if (paymentsWrap) {
                                paymentsWrap.innerHTML = `<div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-5 text-sm font-black text-red-700"> View failed (HTTP ${res.status}). Check console for details.</div>`;
                            }
                            return;
                        }

                        const data = await res.json();

                        if (!data.success) {
                            console.error('VIEW LOAN API ERROR:', data);
                            if (paymentsWrap) {
                                paymentsWrap.innerHTML = `<div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-5 text-sm font-black text-red-700">${data.message || 'Failed to load loan payment details.'}</div>`;
                            }
                            return;
                        }

                        // Fill summary fields
                        const loan = data.loan || {};
                        const payments = data.payments || [];
                        const otherLoans = data.other_loans || [];

                        // ✅ PAID must be based on OUTSTANDING BALANCE
                        const out = parseFloat(String(loan.latest_outstanding_balance ?? '0').replace(/,/g, '')) || 0;
                        const isPaid = out <= 0;
                        setBadge(document.getElementById('vStatusBadge'), isPaid);

                        // ===== Fill ALL details =====
                        document.getElementById('vLoanId').textContent = loan.loan_id ?? '—';
                        document.getElementById('vEmployeeId').textContent = loan.employee_ID ?? '—';
                        document.getElementById('vEmployeeName').textContent = loan.employee_name ?? '—';
                        document.getElementById('vOffice').textContent = loan.office ?? '—';

                        document.getElementById('vLoanType').textContent = loan.loan_type ?? '—';
                        document.getElementById('vLoanAmount').textContent = money(loan.loan_amount);
                        document.getElementById('vTerms').textContent = loan.terms ?? '—';
                        document.getElementById('vMonthly').textContent = money(loan.monthly_payment);

                        document.getElementById('vTotalDeduction').textContent = money(loan.total_deduction);
                        document.getElementById('vNetAmount').textContent = money(loan.total_net);

                        // Total payments = total paid so far
                        const totalPaid = payments.reduce((acc, p) => acc + (parseFloat(p.amount || 0) || 0), 0);
                        document.getElementById('vTotalPaid').textContent = money(totalPaid);

                        // Outstanding (update BOTH if you used vOutstandingTop)
                        const outTxt = money(loan.latest_outstanding_balance);
                        document.getElementById('vOutstanding').textContent = outTxt;
                        const outTop = document.getElementById('vOutstandingTop');
                        if (outTop) outTop.textContent = outTxt;

                        // Latest remittance fields
                        document.getElementById('vLatestRemittance').textContent = loan.latest_remittance ?? '—';
                        document.getElementById('vRemittanceNo').textContent = loan.remittance_no ?? '—';
                        document.getElementById('vDateOfRemittance').textContent = loan.date_of_remittance ?? '—';

                        // Covered month/year
                        const m = parseInt(loan.date_covered_month || 0, 10);
                        document.getElementById('vCoveredMonth').textContent = months[m] || '—';
                        document.getElementById('vCoveredYear').textContent = loan.date_covered_year ?? '';

                        // Render lists
                        // base amount for remaining computation: prefer total_net, else loan_amount
                        const baseAmount = (() => {
                            const net = parseFloat(loan.total_net);
                            if (!isNaN(net) && net > 0) return net;
                            const amt = parseFloat(loan.loan_amount);
                            if (!isNaN(amt) && amt > 0) return amt;
                            return null;
                        })();

                        renderPayments(paymentsWrap, payments, baseAmount);
                        renderOtherLoans(otherWrap, otherLoans);


                        // filter inside modal (client-side)
                        if (paySearch) {
                            paySearch.oninput = () => {
                                const q = paySearch.value.toLowerCase().trim();
                                if (!q) {
                                    renderPayments(paymentsWrap, payments, baseAmount);
                                    return;
                                }
                                const filtered = payments.filter(p => {
                                    const a = (p.remittance_no || '').toLowerCase();
                                    const b = (p.date_of_remittance || '').toLowerCase();
                                    const c = String(p.date_covered_year || '').toLowerCase();
                                    return a.includes(q) || b.includes(q) || c.includes(q);
                                });
                                renderPayments(paymentsWrap, filtered, baseAmount);
                            };
                        }

                    } catch (err) {
                        console.error(err);
                        if (paymentsWrap) paymentsWrap.innerHTML = `<div class="p-5 text-sm font-bold text-red-700 bg-red-50 border border-red-200">Failed to load.</div>`;
                    }
                });


            });
        </script>
    @endpush
</x-admin-v2-layout>