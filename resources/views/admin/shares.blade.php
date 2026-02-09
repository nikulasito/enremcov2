<x-admin-v2-layout title="ENREMCO - Shares Ledger" pageTitle="Shares Ledger Overview"
    pageSubtitle="Manage share capital deposits" :showSearch="false">
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

    {{-- Templates (Download/Upload) --}}
    <div class="mb-8 grid grid-cols-1 lg:grid-cols-2 gap-4">
        <a href="{{ url('/download/shares-template') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-black text-[#112119] hover:brightness-110">
            <span class="material-symbols-outlined">download</span>
            Download Shares Template
        </a>

        <form action="{{ route('shares.upload') }}" method="POST" enctype="multipart/form-data"
            class="rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] p-4">
            @csrf
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="file" name="file" required
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                <button type="submit"
                    class="shrink-0 rounded-lg bg-primary px-5 py-2.5 text-sm font-black text-[#112119] hover:brightness-110">
                    Upload Template
                </button>
            </div>
        </form>
    </div>

    {{-- Bulk Add Monthly Shares --}}
    <div class="mb-8 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] shadow-sm">
        <div class="p-6 border-b border-[#dce5e0] dark:border-[#2a3a32]">
            <h4 class="text-lg font-black">Add Monthly Shares (Bulk)</h4>
            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                Select members below, fill details here, then click “Add Monthly Shares”.
            </p>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Date of Remittance
                </label>
                <input type="date" id="latestRemittanceDate"
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
            </div>

            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Remittance/Receipt No.
                </label>
                <input type="text" id="remittanceNo" placeholder="Enter Remittance No."
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
            </div>

            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Covered Month
                </label>
                <select id="covered_month"
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                    <option value="">Month</option>
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
                <select id="covered_year"
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                    <option value="">Year</option>
                    @for ($y = date('Y'); $y >= date('Y') - 50; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Monthly Shares Amount
                </label>
                <div class="flex gap-2">
                    <input type="number" id="sharesAmount" placeholder="Enter Amount" step="any" min="0"
                        class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                    <button id="addSharesBtn"
                        class="shrink-0 rounded-lg bg-primary px-4 py-2.5 text-sm font-black text-[#112119] hover:brightness-110">
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ url()->current() }}" id="sharesFiltersForm" class="mb-6">
        <div class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] p-6 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label
                        class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                        Office
                    </label>
                    <select id="officeFilter" name="office"
                        class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                        <option value="">All Offices</option>
                        @foreach($offices as $office)
                            <option value="{{ $office }}" {{ request('office') === $office ? 'selected' : '' }}>
                                {{ $office }}
                            </option>
                        @endforeach
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
                        <input type="text" id="searchShares" name="search" value="{{ request('search') }}"
                            placeholder="Search by Employee Name, ID, or Office..."
                            class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 pl-10 pr-4 text-sm">
                    </div>
                </div>

                <div>
                    <label
                        class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                        Show
                    </label>
                    <select name="per_page" id="per_page"
                        class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                        @foreach ([10, 20, 50, 100] as $option)
                            <option value="{{ $option }}" {{ ($perPage ?? 10) == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <a href="{{ url()->current() }}?per_page={{ $perPage ?? 10 }}"
                    class="rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119]
                          px-4 py-2.5 text-sm font-black text-[#638875] dark:text-white/70 hover:bg-[#f6f8f7] dark:hover:bg-[#21352b]">
                    Clear
                </a>
            </div>
        </div>
    </form>

    {{-- Members Table --}}
    <div
        class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-b border-[#dce5e0] dark:border-[#2a3a32]">
                    <tr>
                        <th class="px-6 py-4">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            No.</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Member ID</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Name</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Office</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Monthly Share</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Initial</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Latest</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Total Shares</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Months</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Last Updated</th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32]">
                    @foreach($members as $member)
                        @if($member->is_admin != 1)
                            @php
                                $totalShares = \App\Models\Share::where('employees_id', $member->id)->sum('amount');
                                $monthsContributed = \App\Models\Share::where('employees_id', $member->id)->whereNotNull('covered_month')->count();
                                $firstRemittance = \App\Models\Share::where('employees_id', $member->id)->orderBy('date_remittance', 'asc')->value('date_remittance');
                                $latestRemittance = \App\Models\Share::where('employees_id', $member->id)->orderBy('date_remittance', 'desc')->value('date_remittance');
                                $lastUpdated = \App\Models\Share::where('employees_id', $member->id)->max('date_updated');
                            @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors memberRow"
                                data-name="{{ strtolower($member->name) }}" data-id="{{ strtolower($member->employee_ID) }}"
                                data-office="{{ strtolower($member->office) }}" data-shares="{{ $member->shares }}">
                                <td class="px-6 py-4">
                                    <input type="checkbox" class="memberCheckbox" value="{{ $member->id }}">
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    {{ ($members->currentPage() - 1) * $members->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 font-black text-primary">{{ $member->employee_ID }}</td>
                                <td class="px-6 py-4 font-black">{{ $member->name }}</td>
                                <td class="px-6 py-4">{{ $member->office }}</td>
                                <td class="px-6 py-4 current-shares">{{ $member->shares }}</td>
                                <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">{{ $firstRemittance ?? '—' }}</td>
                                <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">{{ $latestRemittance ?? '—' }}</td>
                                <td class="px-6 py-4 font-black">{{ number_format($totalShares, 2) }}</td>
                                <td class="px-6 py-4">{{ $monthsContributed }}</td>
                                <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">
                                    {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->format('Y-m-d') : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-2 min-w-0">
                                        <button type="button"
                                            class="w-full inline-flex items-center justify-center rounded-lg bg-primary px-3 py-2 text-xs font-black text-[#112119] hover:brightness-110 transition"
                                            data-open-modal="updateDetailsModal" data-id="{{ $member->id }}"
                                            data-employee_id="{{ $member->employee_ID }}" data-name="{{ $member->name }}"
                                            data-office="{{ $member->office }}" data-contribution="{{ $member->shares }}"
                                            data-first-remittance="{{ $firstRemittance ?? 'N/A' }}"
                                            data-latest-remittance="{{ $latestRemittance ?? 'N/A' }}"
                                            data-total-shares="{{ $totalShares }}"
                                            data-months-contributed="{{ $monthsContributed }}">
                                            Update
                                        </button>

                                        <button type="button"
                                            class="w-full inline-flex items-center justify-center rounded-lg bg-[#112119] px-3 py-2 text-xs font-black text-white hover:opacity-90 transition"
                                            data-open-modal="viewDetailsModal" data-id="{{ $member->id }}"
                                            data-employee_id="{{ $member->employee_ID }}" data-name="{{ $member->name }}"
                                            data-office="{{ $member->office }}" data-contribution="{{ $member->shares }}"
                                            data-first-remittance="{{ $firstRemittance ?? '—' }}"
                                            data-latest-remittance="{{ $latestRemittance ?? '—' }}"
                                            data-total-shares="{{ $totalShares }}"
                                            data-months-contributed="{{ $monthsContributed }}">
                                            View Contributions
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-[#dce5e0] dark:border-[#2a3a32]">
            {{ $members->appends(request()->except('page'))->links() }}
        </div>
    </div>

    {{-- ===== Tailwind Modals ===== --}}

    {{-- Update Contributions Modal (NEW DESIGN / Tailwind) --}}
    <div id="updateDetailsModal" class="fixed inset-0 z-50 hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm" data-close-modal="updateDetailsModal"></div>

        <div class="relative min-h-screen w-full flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-[#112119] w-full max-w-3xl rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] flex flex-col max-h-[90vh]">
                {{-- Header --}}
                <div
                    class="px-8 py-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#111814] dark:text-white uppercase tracking-tight">Update
                            Contributions</h2>
                        <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Member Financial Management Profile</p>
                    </div>

                    <button type="button" class="text-[#638875] hover:text-red-500 transition-colors"
                        data-close-modal="updateDetailsModal">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                {{-- Body (scrollable) --}}
                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    {{-- Member Summary Card --}}
                    <div
                        class="bg-[#f6f8f7] dark:bg-[#1a2e24] rounded-xl p-6 border border-[#dce5e0] dark:border-[#2a3a32] mb-8">
                        <div class="flex items-center gap-4 mb-6 border-b border-[#dce5e0] dark:border-[#2a3a32] pb-4">
                            <div
                                class="size-14 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-3xl">person</span>
                            </div>
                            <div>
                                <p id="upd_memberIdLine"
                                    class="text-[10px] font-bold text-primary uppercase tracking-widest">Member ID: —
                                </p>
                                <h3 id="upd_memberName" class="text-xl font-bold text-[#111814] dark:text-white">—</h3>
                                <p id="upd_memberOffice" class="text-sm text-[#638875] dark:text-[#a0b0a8]">—</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Monthly Share</p>
                                <p id="upd_monthlyShare" class="text-lg font-black text-[#111814] dark:text-white">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Initial Remittance</p>
                                <p id="upd_firstRemittance" class="text-lg font-black text-[#111814] dark:text-white">—
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Latest Remittance</p>
                                <p id="upd_latestRemittance" class="text-lg font-black text-primary">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Total
                                    Shares</p>
                                <p id="upd_totalShares" class="text-lg font-black text-[#111814] dark:text-white">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Months Contributed</p>
                                <p id="upd_monthsContributed" class="text-lg font-black text-[#111814] dark:text-white">
                                    —</p>
                            </div>
                        </div>
                    </div>

                    {{-- Search --}}
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-[#111814] dark:text-white uppercase tracking-wider">Search
                            Remittance Records</h4>

                        <div class="flex flex-col md:flex-row gap-3">
                            <div class="relative flex-1">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#638875]">calendar_month</span>
                                <input id="searchRemittanceModal"
                                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none"
                                    placeholder="Enter Remittance No. or Year (YYYY)" type="text" />
                            </div>

                            <button id="searchRemittanceBtn" type="button"
                                class="bg-primary hover:brightness-110 text-[#112119] font-bold px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-xl">search</span>
                                Search Remittance No.
                            </button>
                        </div>

                        <p class="text-[11px] text-[#638875] dark:text-[#a0b0a8]">Leave blank to view all historical
                            records for this member.</p>
                    </div>

                    {{-- Results --}}
                    <div id="remittanceResult" class="mt-6"></div>
                </div>

                {{-- Footer --}}
                <div
                    class="px-8 py-6 bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-t border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-end gap-3">
                    <button type="button"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold text-[#638875] hover:bg-gray-200 dark:hover:bg-[#2a3a32] transition-all"
                        data-close-modal="updateDetailsModal">
                        Cancel
                    </button>

                    {{-- This button will save whatever is currently rendered in the remittance table --}}
                    <button type="button" id="saveRemittanceChangesBtn"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold bg-[#112119] dark:bg-white text-white dark:text-[#112119] hover:opacity-90 transition-all hidden">
                        Update Ledger
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- View Contributions Modal (MATCH UPDATE MODAL DESIGN) --}}
    <div id="viewDetailsModal" class="fixed inset-0 z-50 hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm" data-close-modal="viewDetailsModal"></div>

        <div class="relative min-h-screen w-full flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-[#112119] w-full max-w-3xl rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] flex flex-col max-h-[90vh]">

                {{-- Header --}}
                <div
                    class="px-8 py-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#111814] dark:text-white uppercase tracking-tight">
                            View Contributions
                        </h2>
                        <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                            Member Financial Management Profile
                        </p>
                    </div>

                    <button type="button" class="text-[#638875] hover:text-red-500 transition-colors"
                        data-close-modal="viewDetailsModal">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                {{-- Body (scrollable) --}}
                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    {{-- Member Summary Card (same as update) --}}
                    <div
                        class="bg-[#f6f8f7] dark:bg-[#1a2e24] rounded-xl p-6 border border-[#dce5e0] dark:border-[#2a3a32] mb-8">
                        <div class="flex items-center gap-4 mb-6 border-b border-[#dce5e0] dark:border-[#2a3a32] pb-4">
                            <div
                                class="size-14 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-3xl">person</span>
                            </div>

                            <div>
                                <p class="text-[10px] font-bold text-primary uppercase tracking-widest">
                                    Member ID: <span id="modalEmployeeID">—</span>
                                </p>
                                <h3 id="modalName" class="text-xl font-bold text-[#111814] dark:text-white">—</h3>
                                <p id="modalOffice" class="text-sm text-[#638875] dark:text-[#a0b0a8]">—</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Monthly Share
                                </p>
                                <p id="modalContribution" class="text-lg font-black text-[#111814] dark:text-white">—
                                </p>
                            </div>

                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Initial Remittance
                                </p>
                                <p id="modalFirstRemittance" class="text-lg font-black text-[#111814] dark:text-white">—
                                </p>
                            </div>

                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Latest Remittance
                                </p>
                                <p id="modalLatestRemittance" class="text-lg font-black text-primary">—</p>
                            </div>

                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Total Shares
                                </p>
                                <p id="modalTotalShares" class="text-lg font-black text-[#111814] dark:text-white">—</p>
                            </div>

                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Months Contributed
                                </p>
                                <p id="modalMonthsContributed"
                                    class="text-lg font-black text-[#111814] dark:text-white">—</p>
                            </div>
                        </div>
                    </div>

                    {{-- Filter section (same “Search Remittance Records” layout style) --}}
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-[#111814] dark:text-white uppercase tracking-wider">
                            Filter Contribution Records
                        </h4>

                        <div class="flex flex-col md:flex-row gap-3">
                            <div class="relative flex-1">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#638875]">
                                    calendar_today
                                </span>
                                <input id="yearFilter" type="number" min="1900" max="2100"
                                    placeholder="Enter Year (YYYY)"
                                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" />
                            </div>

                            <button id="viewYearContributions" type="button"
                                class="bg-primary hover:brightness-110 text-[#112119] font-bold px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                                View Contributions
                            </button>
                        </div>

                        <p class="text-[11px] text-[#638875] dark:text-[#a0b0a8]">
                            Tip: Leave blank to view all historical records for this member.
                        </p>
                    </div>

                    {{-- Results (table gets injected here) --}}
                    <div id="contributionsResult" class="mt-6"></div>
                </div>

                {{-- Footer (match update) --}}
                <div
                    class="px-8 py-6 bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-t border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-end gap-3">
                    <button type="button"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold text-[#638875] hover:bg-gray-200 dark:hover:bg-[#2a3a32] transition-all"
                        data-close-modal="viewDetailsModal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>



    {{-- Select members error modal (renamed: your page had TWO #errorModal) --}}
    <div id="selectMembersErrorModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-close-modal="selectMembersErrorModal"></div>
        <div
            class="relative mx-auto mt-40 w-[92%] max-w-md overflow-hidden rounded-xl bg-white dark:bg-[#1a2e24] shadow-2xl">
            <div class="p-6">
                <h4 class="text-lg font-black mb-2">Error</h4>
                <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Please select at least one member before adding
                    shares.</p>
                <div class="mt-5 flex justify-end">
                    <button type="button"
                        class="rounded-lg bg-primary px-5 py-2.5 text-sm font-black text-[#112119] hover:brightness-110"
                        data-close-modal="selectMembersErrorModal">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // ===== Helpers =====
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

                // ===== Modal Elements =====
                const updateModal = document.getElementById('updateDetailsModal');
                const remittanceResult = document.getElementById('remittanceResult');
                const searchInput = document.getElementById('searchRemittanceModal');
                const searchBtn = document.getElementById('searchRemittanceBtn');
                const saveBtn = document.getElementById('saveRemittanceChangesBtn');

                const viewModal = document.getElementById('viewDetailsModal');
                const yearFilter = document.getElementById('yearFilter');
                const viewYearBtn = document.getElementById('viewYearContributions');
                const contributionsResult = document.getElementById('contributionsResult');

                let currentUpdateUserId = null;
                let currentViewUserId = null;

                // ===== Global Close (backdrop / close buttons) =====
                document.addEventListener('click', (e) => {
                    const closeTarget = e.target.closest('[data-close-modal]');
                    if (!closeTarget) return;
                    const id = closeTarget.getAttribute('data-close-modal');
                    closeModal(id);

                    // reset specific modal contents
                    if (id === 'updateDetailsModal') {
                        if (remittanceResult) remittanceResult.innerHTML = '';
                        if (searchInput) searchInput.value = '';
                        if (saveBtn) saveBtn.classList.add('hidden');
                        currentUpdateUserId = null;
                    }

                    if (id === 'viewDetailsModal') {
                        if (contributionsResult) contributionsResult.innerHTML = '';
                        if (yearFilter) yearFilter.value = '';
                        currentViewUserId = null;
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    if (updateModal && !updateModal.classList.contains('hidden')) closeModal('updateDetailsModal');
                    if (viewModal && !viewModal.classList.contains('hidden')) closeModal('viewDetailsModal');
                });

                // ===== Open + Populate Modals =====
                document.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-open-modal]');
                    if (!btn) return;

                    const modalId = btn.getAttribute('data-open-modal');

                    // --- UPDATE MODAL ---
                    if (modalId === 'updateDetailsModal') {
                        currentUpdateUserId = btn.dataset.id;
                        if (searchBtn) searchBtn.setAttribute('data-user-update-id', currentUpdateUserId);

                        // Fill header card
                        document.getElementById('upd_memberIdLine').textContent = `Member ID: ${btn.getAttribute('data-employee_id') || '—'}`;
                        document.getElementById('upd_memberName').textContent = btn.dataset.name || '—';
                        document.getElementById('upd_memberOffice').textContent = btn.dataset.office || '—';
                        document.getElementById('upd_monthlyShare').textContent = btn.dataset.contribution ? `₱ ${btn.dataset.contribution}` : '—';
                        document.getElementById('upd_firstRemittance').textContent = btn.dataset.firstRemittance || '—';
                        document.getElementById('upd_latestRemittance').textContent = btn.dataset.latestRemittance || '—';
                        document.getElementById('upd_totalShares').textContent = btn.dataset.totalShares ? `₱ ${btn.dataset.totalShares}` : '—';
                        document.getElementById('upd_monthsContributed').textContent = btn.dataset.monthsContributed ? `${btn.dataset.monthsContributed} Months` : '—';

                        if (remittanceResult) remittanceResult.innerHTML = '';
                        if (searchInput) searchInput.value = '';
                        if (saveBtn) saveBtn.classList.add('hidden');

                        openModal('updateDetailsModal');
                        return;
                    }

                    // --- VIEW MODAL ---
                    if (modalId === 'viewDetailsModal') {
                        currentViewUserId = btn.dataset.id;

                        document.getElementById('modalEmployeeID').textContent = btn.getAttribute('data-employee_id') || '—';
                        document.getElementById('modalName').textContent = btn.dataset.name || '—';
                        document.getElementById('modalOffice').textContent = btn.dataset.office || '—';
                        document.getElementById('modalContribution').textContent = btn.dataset.contribution ? `₱ ${btn.dataset.contribution}` : '—';
                        document.getElementById('modalFirstRemittance').textContent = btn.dataset.firstRemittance || '—';
                        document.getElementById('modalLatestRemittance').textContent = btn.dataset.latestRemittance || '—';
                        document.getElementById('modalTotalShares').textContent = btn.dataset.totalShares ? `₱ ${btn.dataset.totalShares}` : '—';
                        document.getElementById('modalMonthsContributed').textContent = btn.dataset.monthsContributed ? `${btn.dataset.monthsContributed} Months` : '—';

                        if (contributionsResult) contributionsResult.innerHTML = '';
                        if (yearFilter) yearFilter.value = '';

                        openModal('viewDetailsModal');
                        return;
                    }

                    // fallback (if you add more modals later)
                    openModal(modalId);
                });

                // ===== UPDATE MODAL: Search =====
                const runUpdateSearch = () => {
                    const userId = searchBtn?.getAttribute('data-user-update-id');
                    if (!userId) return alert('No member selected.');

                    const query = (searchInput?.value || '').trim();
                    const requestUrl = `/admin/get-contributions/${encodeURIComponent(userId)}/${encodeURIComponent(query || 'all')}`;

                    remittanceResult.innerHTML = `
                                                                        <div class="mt-4 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
                                                                            Loading records...
                                                                        </div>
                                                                    `;

                    fetch(requestUrl)
                        .then(r => r.json())
                        .then(data => {
                            if (!data.success || !Array.isArray(data.contributions) || data.contributions.length === 0) {
                                remittanceResult.innerHTML = `
                                                                                    <div class="mt-4 rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] p-5 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
                                                                                        ${data.message || 'No contributions found.'}
                                                                                    </div>
                                                                                `;
                                saveBtn.classList.add('hidden');
                                return;
                            }

                            let html = `
                                                                                <div class="mt-5 overflow-x-auto rounded-xl border border-[#dce5e0] dark:border-[#2a3a32]">
                                                                                    <table class="w-full text-left">
                                                                                        <thead>
                                                                                            <tr class="bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-b border-[#dce5e0] dark:border-[#2a3a32]">
                                                                                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Date</th>
                                                                                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Remittance No.</th>
                                                                                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Month</th>
                                                                                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Year</th>
                                                                                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Amount</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32] bg-white dark:bg-[#0d1a14]">
                                                                            `;

                            data.contributions.forEach((c, i) => {
                                html += `
                                                                                    <tr data-share-id="${c.shares_id}">
                                                                                        <td class="px-6 py-3">
                                                                                            <input type="date" name="date_remittance_${i}" value="${c.date_remittance || ''}"
                                                                                                class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] text-sm py-2 px-3 focus:ring-2 focus:ring-primary">
                                                                                        </td>
                                                                                        <td class="px-6 py-3">
                                                                                            <input type="text" name="remittance_no_${i}" value="${c.remittance_no || ''}"
                                                                                                class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] text-sm py-2 px-3 focus:ring-2 focus:ring-primary">
                                                                                        </td>
                                                                                        <td class="px-6 py-3">
                                                                                            <input type="text" name="month_name_${i}" value="${c.month_name || ''}"
                                                                                                class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] text-sm py-2 px-3 focus:ring-2 focus:ring-primary">
                                                                                        </td>
                                                                                        <td class="px-6 py-3">
                                                                                            <input type="text" name="covered_year_${i}" value="${c.covered_year || ''}"
                                                                                                class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] text-sm py-2 px-3 focus:ring-2 focus:ring-primary">
                                                                                        </td>
                                                                                        <td class="px-6 py-3">
                                                                                            <input type="number" step="any" min="0" name="amount_${i}" value="${c.amount || ''}"
                                                                                                class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] text-sm py-2 px-3 focus:ring-2 focus:ring-primary">
                                                                                        </td>
                                                                                    </tr>
                                                                                `;
                            });

                            html += `</tbody></table></div>`;
                            remittanceResult.innerHTML = html;
                            saveBtn.classList.remove('hidden');
                            remittanceResult.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        })
                        .catch(err => {
                            console.error(err);
                            remittanceResult.innerHTML = `
                                                                                <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-5 text-sm font-bold text-red-700">
                                                                                    Failed to load contributions.
                                                                                </div>
                                                                            `;
                            saveBtn.classList.add('hidden');
                        });
                };

                searchBtn?.addEventListener('click', runUpdateSearch);
                searchInput?.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') runUpdateSearch();
                });

                // ===== UPDATE MODAL: Save =====
                saveBtn?.addEventListener('click', () => {
                    const rows = remittanceResult.querySelectorAll('table tbody tr');
                    if (!rows.length) return alert('Nothing to save.');

                    const updates = [];
                    rows.forEach((row, index) => {
                        updates.push({
                            shares_id: row.getAttribute('data-share-id'),
                            date_remittance: row.querySelector(`input[name="date_remittance_${index}"]`)?.value || null,
                            remittance_no: row.querySelector(`input[name="remittance_no_${index}"]`)?.value || null,
                            month_name: row.querySelector(`input[name="month_name_${index}"]`)?.value || null,
                            covered_year: row.querySelector(`input[name="covered_year_${index}"]`)?.value || null,
                            amount: row.querySelector(`input[name="amount_${index}"]`)?.value || null,
                        });
                    });

                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!csrf) return alert("Missing CSRF token meta tag in your layout <head>.");

                    fetch("/admin/update-remittances", {
                        method: "POST",
                        headers: { "X-CSRF-TOKEN": csrf, "Content-Type": "application/json" },
                        body: JSON.stringify({ updates })
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                alert("✅ Remittances updated successfully!");
                                location.reload();
                                return;
                            }

                            if (data.duplicates?.length) {
                                let msg = "The following remittances already exist:\n";
                                data.duplicates.forEach(d => {
                                    msg += `Remittance No: ${d.remittance_no}, Covered Period: ${d.covered_month}/${d.covered_year}\n`;
                                });
                                alert(msg);
                                return;
                            }

                            alert("❌ Error: " + (data.error || "Unknown error"));
                        })
                        .catch(err => {
                            console.error(err);
                            alert("❌ An error occurred while saving.");
                        });
                });

                // ===== VIEW MODAL: View Contributions By Year (or All) =====
                const renderViewTable = (list) => {
                    if (!Array.isArray(list) || list.length === 0) {
                        contributionsResult.innerHTML = `
                                                                    <div class="mt-4 rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] p-5 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
                                                                        No contributions found.
                                                                    </div>
                                                                    `;
                        return;
                    }

                    let html = `
                                                                            <div class="mt-5 overflow-x-auto rounded-xl border border-[#dce5e0] dark:border-[#2a3a32]">
                                                                            <table class="w-full text-left">
                                                                                <thead>
                                                                                <tr class="bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-b border-[#dce5e0] dark:border-[#2a3a32]">
                                                                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Date</th>
                                                                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Remittance No.</th>
                                                                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Month</th>
                                                                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Year</th>
                                                                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Amount</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32] bg-white dark:bg-[#0d1a14]">
                                                                        `;

                    list.forEach((c) => {
                        html += `
                                                                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                                                                <td class="px-6 py-4 text-sm text-[#111814] dark:text-white">${c.date_remittance || '—'}</td>
                                                                                <td class="px-6 py-4 text-sm text-[#638875] dark:text-[#a0b0a8]">${c.remittance_no || '—'}</td>
                                                                                <td class="px-6 py-4 text-sm text-[#638875] dark:text-[#a0b0a8]">${c.month_name || '—'}</td>
                                                                                <td class="px-6 py-4 text-sm text-[#638875] dark:text-[#a0b0a8]">${c.covered_year || '—'}</td>
                                                                                <td class="px-6 py-4 text-sm font-black text-[#111814] dark:text-white">${c.amount ?? '—'}</td>
                                                                            </tr>
                                                                            `;
                    });

                    html += `</tbody></table></div>`;
                    contributionsResult.innerHTML = html;
                };


                const runViewYear = () => {
                    if (!currentViewUserId) return alert('No member selected.');

                    const year = (yearFilter?.value || '').trim();
                    const requestUrl = `/admin/get-contributions/${encodeURIComponent(currentViewUserId)}/${encodeURIComponent(year || 'all')}`;

                    contributionsResult.innerHTML = `
                                                                        <div class="p-5 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">Loading...</div>
                                                                    `;

                    fetch(requestUrl)
                        .then(r => r.json())
                        .then(data => {
                            if (!data.success) {
                                contributionsResult.innerHTML = `
                                                                                    <div class="p-5 text-sm font-bold text-red-700 bg-red-50 border border-red-200">
                                                                                        ${data.message || 'Failed to load.'}
                                                                                    </div>
                                                                                `;
                                return;
                            }
                            renderViewTable(data.contributions || []);
                        })
                        .catch(err => {
                            console.error(err);
                            contributionsResult.innerHTML = `
                                                                                <div class="p-5 text-sm font-bold text-red-700 bg-red-50 border border-red-200">
                                                                                    Failed to load contributions.
                                                                                </div>
                                                                            `;
                        });
                };

                viewYearBtn?.addEventListener('click', runViewYear);
                yearFilter?.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') runViewYear();
                });
            });
        </script>
    @endpush

</x-admin-v2-layout>