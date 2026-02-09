<x-admin-v2-layout title="ENREMCO - Savings Ledger" pageTitle="Savings Ledger Overview"
    pageSubtitle="Manage member savings deposits" :showSearch="false">
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
        <a href="{{ url('/download/savings-template') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-black text-[#112119] hover:brightness-110">
            <span class="material-symbols-outlined">download</span>
            Download Savings Template
        </a>

        <form action="{{ route('savings.upload') }}" method="POST" enctype="multipart/form-data"
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

    {{-- Bulk Add Monthly Savings --}}
    <div class="mb-8 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] shadow-sm">
        <div class="p-6 border-b border-[#dce5e0] dark:border-[#2a3a32]">
            <h4 class="text-lg font-black">Add Monthly Savings (Bulk)</h4>
            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                Select members below, fill details here, then click “Add Monthly Savings”.
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
                    Monthly Savings Amount
                </label>
                <div class="flex gap-2">
                    <input type="number" id="savingsAmount" placeholder="Enter Amount" step="any" min="0"
                        class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                    <button id="addSavingsBtn"
                        class="shrink-0 rounded-lg bg-primary px-4 py-2.5 text-sm font-black text-[#112119] hover:brightness-110">
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ url()->current() }}" id="savingsFiltersForm" class="mb-6">
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
                        <input type="text" id="searchSavings" name="search" value="{{ request('search') }}"
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
    <div class="rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-fixed text-left text-xs">
                <thead class="bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-b border-[#dce5e0] dark:border-[#2a3a32]">
                    <tr>
                        <th class="px-4 py-3"><input type="checkbox" id="selectAll"></th>

                        @foreach ([
                                'No.',
                                'Member ID',
                                'Name',
                                'Office',
                                'Monthly Savings',
                                'Initial',
                                'Latest',
                                'Total Savings',
                                'Withdrawn',
                                'Balance',
                                'Months',
                                'Last Updated',
                                'Action'
                            ] as $h)
                                <th class="px-4 py-3 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                    {{ $h }}
                                </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody id="savingsTableBody" class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32]">
                    @include('admin.savings._rows')
                </tbody>
            </table>
        </div>
        
        <div id="savingsPagination" class="p-6 border-t border-[#dce5e0] dark:border-[#2a3a32]">
            @include('admin.savings._pagination')
        </div>
</div>
    {{-- =======================
        TAILWIND MODALS (Savings)
        Matches Shares modal styling
    ======================== --}}

    {{-- Update Savings Modal --}}
    <div id="updateDetailsModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm" data-close-modal="updateDetailsModal"></div>


                           <div class="relative min-h-screen w-full flex items-center justify-center p-4">
            <div class="bg-white dark:bg-[#112119] w-full max-w-3xl rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] flex flex-col max-h-[90vh]">
                <div class="px-8 py-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#111814] dark:text-white uppercase tracking-tight">Update Savings</h2>
                        <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Member Financial Management Profile</p>
                    </div>
                    <button type="button" class="text-[#638875] hover:text-red-500 transition-colors" data-close-modal="updateDetailsModal">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                       
                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    <div class="bg-[#f6f8f7] dark:bg-[#1a2e24] rounded-xl p-6 border border-[#dce5e0] dark:border-[#2a3a32] mb-8">
                        <div class="flex items-center gap-4 mb-6 border-b border-[#dce5e0] dark:border-[#2a3a32] pb-4">
                            <div class="size-14 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-3xl">person</span>
                            </div>
                            <div>
                                <p id="upd_memberIdLine" class="text-[10px] font-bold text-primary uppercase tracking-widest">Member ID: —</p>
                                <h3 id="upd_memberName" class="text-xl font-bold text-[#111814] dark:text-white">—</h3>
                                <p id="upd_memberOffice" class="text-sm text-[#638875] dark:text-[#a0b0a8]">—</p>
                            </div>
                        </div>


                                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">

                                                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Monthly Savings</p>
                                <p id="upd_monthlySavings" class="text-lg font-black text-[#111814] dark:text-white
                                    ">—</p>
                            </div>

                                                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Initial Remittance</p>
                                <p id="upd_firstRemittance" class="text-lg font-black text-[#111814] dark:text-whit
                                    e">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Latest Remittance</p>
                                <p id="upd_latestRemittance" class="text-lg font-black text-primary">—</p>

                                                               </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Total Savings</p>
                                <p id="upd_totalSavings" class="text-lg font-black text-[#111814] dark:text-white">
                                    —</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Withdrawn</p>
                                <p id="upd_withdrawn" class="text-lg font-black text-[#111814] dark:text-white">—</
                                    p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Balance</p>
                                <p id="upd_balance" class="text-lg font-black text-[#111814] dark:text-white">—</p>
                            </div>
                        </div>
                    </div>

                           
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-[#111814] dark:text-white uppercase tracking-wider">Search Remittance Records</h4>


                                                           <div class="flex flex-col md:flex-row gap-3">
                            <div class="relative flex-1">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#638875]">calendar_month</span>
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

                        <p class="text-[11px] text-[#638875] dark:text-[#a0b0a8]">Leave blank to view all historical records for this member.</p>
                    </div>

                    <div id="remittanceResult" class="mt-6"></div>
                </div>

                <div class="px-8 py-6 bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-t border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-end gap-3">
                    <button type="button"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold text-[#638875] hover:bg-gray-200 dark:hover:bg-[#2a3a32] transition-all"
                        data-close-modal="updateDetailsModal">
                        Cancel
                    </button>

                    <button type="button" id="saveRemittanceChangesBtn"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold bg-[#112119] dark:bg-white text-white dark:text-[#112119] hover:opacity-90 transition-all hidden">
                        Update Ledger
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- View Savings Modal (same layout as update) --}}
    <div id="viewDetailsModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm" data-close-modal="viewDetailsModal"></div>

                   
        <div class="relative min-h-screen w-full flex items-center justify-center p-4">
            <div class="bg-white dark:bg-[#112119] w-full max-w-3xl rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] flex flex-col max-h-[90vh]">
                <div class="px-8 py-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#111814] dark:text-white uppercase tracking-tight">View Savings Records</h2>
                        <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Member Financial Management Profile</p>
                    </div>
                    <button type="button" class="text-[#638875] hover:text-red-500 transition-colors" data-close-modal="viewDetailsModal">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                       
                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    <div class="bg-[#f6f8f7] dark:bg-[#1a2e24] rounded-xl p-6 border border-[#dce5e0] dark:border-[#2a3a32] mb-8">
                        <div class="flex items-center gap-4 mb-6 border-b border-[#dce5e0] dark:border-[#2a3a32] pb-4">
                            <div class="size-14 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined text-3xl">person</span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-primary uppercase tracking-widest">
                                    Member ID: <span id="vw_memberId">—</span>
                                </p>
                                <h3 id="vw_memberName" class="text-xl font-bold text-[#111814] dark:text-white">—</h3>
                                <p id="vw_memberOffice" class="text-sm text-[#638875] dark:text-[#a0b0a8]">—</p>
                            </div>
                        </div>


                                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">

                                                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Monthly Savings</p>
                                <p id="vw_monthlySavings" class="text-lg font-black text-[#111814] dark:text-white"
                                    >—</p>
                            </div>

                                                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Initial Remittance</p>
                                <p id="vw_firstRemittance" class="text-lg font-black text-[#111814] dark:text-white
                                    ">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Latest Remittance</p>
                                <p id="vw_latestRemittance" class="text-lg font-black text-primary">—</p>

                                                               </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Total Savings</p>
                                <p id="vw_totalSavings" class="text-lg font-black text-[#111814] dark:text-white">—
                                    </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Balance</p>
                                <p id="vw_balance" class="text-lg font-black text-[#111814] dark:text-white">—</p>
                            </div>
                        </div>
                    </div>

                           
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-[#111814] dark:text-white uppercase tracking-wider">Filter Savings Records</h4>


                                                           <div class="flex flex-col md:flex-row gap-3">
                            <div class="relative flex-1">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#638875]">calendar_today</span>
                                <input id="yearFilter" type="number" min="1900" max="2100"
                                    placeholder="Enter Year (YYYY)"
                                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none" />
                            </div>

                            <button id="viewYearContributions" type="button"
                                class="bg-primary hover:brightness-110 text-[#112119] font-bold px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                                View Records
                            </button>

                                                   </div>

                        <p class="text-[11px] text-[#638875] dark:text-[#a0b0a8]">Leave blank to view all historical records for this member.</p>
                    </div>

                    
                   <div id="contributionsResult" class="mt-6"></div>
                </div>

                <div class="px-8 py-6 bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-t border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-end gap-3">
                    <button type="button"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold text-[#638875] hover:bg-gray-200 dark:hover:bg-[#2a3a32] transition-all"
                        data-close-modal="viewDetailsModal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
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

                const updateModal = document.getElementById('updateDetailsModal');
                const viewModal   = document.getElementById('viewDetailsModal');

                const remittanceResult = document.getElementById('remittanceResult');
                const searchInput = document.getElementById('searchRemittanceModal');
                const searchBtn = document.getElementById('searchRemittanceBtn');
                const saveBtn = document.getElementById('saveRemittanceChangesBtn');

                const yearFilter = document.getElementById('yearFilter');
                const viewYearBtn = document.getElementById('viewYearContributions');
                const contributionsResult = document.getElementById('contributionsResult');

                let currentUpdateUserId = null;
                let currentViewUserId = null;

                // Close (backdrop + buttons)
                document.addEventListener('click', (e) => {
                    const closeTarget = e.target.closest('[data-close-modal]');
                    if (!closeTarget) return;

                    const id = closeTarget.getAttribute('data-close-modal');
                    closeModal(id);

                    if (id === 'updateDetailsModal') {
                        remittanceResult.innerHTML = '';
                        searchInput.value = '';
                        saveBtn.classList.add('hidden');
                        currentUpdateUserId = null;
                    }

                    if (id === 'viewDetailsModal') {
                        contributionsResult.innerHTML = '';
                        yearFilter.value = '';
                        currentViewUserId = null;
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    if (updateModal && !updateModal.classList.contains('hidden')) closeModal('updateDetailsModal');
                    if (viewModal && !viewModal.classList.contains('hidden')) closeModal('viewDetailsModal');
                });

                // Open modals
                document.addEventListener('click', (e) => {
                    const btn = e.target.closest('[data-open-modal]');
                    if (!btn) return;

                    const modalId = btn.getAttribute('data-open-modal');

                    if (modalId === 'updateDetailsModal') {
                        currentUpdateUserId = btn.dataset.id;
                        searchBtn.setAttribute('data-user-update-id', currentUpdateUserId);

                        document.getElementById('upd_memberIdLine').textContent = `Member ID: ${btn.getAttribute('data-employee_id') || '—'}`;
                        document.getElementById('upd_memberName').textContent = btn.dataset.name || '—';
                        document.getElementById('upd_memberOffice').textContent = btn.dataset.office || '—';

                        document.getElementById('upd_monthlySavings').textContent = btn.dataset.contribution ? `₱ ${btn.dataset.contribution}` : '—';
                        document.getElementById('upd_firstRemittance').textContent = btn.dataset.firstRemittance || '—';
                        document.getElementById('upd_latestRemittance').textContent = btn.dataset.latestRemittance || '—';
                        document.getElementById('upd_totalSavings').textContent = btn.dataset.totalSavings ? `₱ ${btn.dataset.totalSavings}` : '—';
                        document.getElementById('upd_withdrawn').textContent = btn.dataset.withdrawn ? `₱ ${btn.dataset.withdrawn}` : '—';
                        document.getElementById('upd_balance').textContent = btn.dataset.balance ? `₱ ${btn.dataset.balance}` : '—';

                        remittanceResult.innerHTML = '';
                        searchInput.value = '';
                        saveBtn.classList.add('hidden');

                        openModal('updateDetailsModal');
                        return;
                    }

                    if (modalId === 'viewDetailsModal') {
                        currentViewUserId = btn.dataset.id;

                        document.getElementById('vw_memberId').textContent = btn.getAttribute('data-employee_id') || '—';
                        document.getElementById('vw_memberName').textContent = btn.dataset.name || '—';
                        document.getElementById('vw_memberOffice').textContent = btn.dataset.office || '—';

                        document.getElementById('vw_monthlySavings').textContent = btn.dataset.contribution ? `₱ ${btn.dataset.contribution}` : '—';
                        document.getElementById('vw_firstRemittance').textContent = btn.dataset.firstRemittance || '—';
                        document.getElementById('vw_latestRemittance').textContent = btn.dataset.latestRemittance || '—';
                        document.getElementById('vw_totalSavings').textContent = btn.dataset.totalSavings ? `₱ ${btn.dataset.totalSavings}` : '—';
                        document.getElementById('vw_balance').textContent = btn.dataset.balance ? `₱ ${btn.dataset.balance}` : '—';

                        contributionsResult.innerHTML = '';
                        yearFilter.value = '';

                        openModal('viewDetailsModal');
                        return;
                    }

                    openModal(modalId);
                });

                // UPDATE: Search (blank => all)
                const runUpdateSearch = () => {
                    const userId = searchBtn.getAttribute('data-user-update-id');
                    if (!userId) return alert('No member selected.');

                    const query = (searchInput.value || '').trim();
                    const requestUrl = `/admin/get-savings/${encodeURIComponent(userId)}/${encodeURIComponent(query || 'all')}`;

                    remittanceResult.innerHTML = `<div class="mt-4 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">Loading records...</div>`;

                    fetch(requestUrl)
                        .then(r => r.json())
                        .then(data => {
                            if (!data.success || !Array.isArray(data.contributions) || data.contributions.length === 0) {
                                remittanceResult.innerHTML = `
                                    <div class="mt-4 rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] p-5 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
                                        ${data.message || 'No savings records found.'}
                                    </div>`;
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
                                        <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32] bg-white dark:bg-[#0d1a14]">`;

                            data.contributions.forEach((c, i) => {
                                html += `
                                    <tr data-saving-id="${c.savings_id}">
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
                                    </tr>`;
                            });

                            html += `</tbody></table></div>`;
                            remittanceResult.innerHTML = html;
                            saveBtn.classList.remove('hidden');
                        })
                        .catch(() => {
                            remittanceResult.innerHTML = `<div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-5 text-sm font-bold text-red-700">Failed to load records.</div>`;
                            saveBtn.classList.add('hidden');
                        });
                };

                searchBtn.addEventListener('click', runUpdateSearch);
                searchInput.addEventListener('keydown', (e) => { if (e.key === 'Enter') runUpdateSearch(); });

                // UPDATE: Save
                saveBtn.addEventListener('click', () => {
                    const rows = remittanceResult.querySelectorAll('table tbody tr');
                    if (!rows.length) return alert('Nothing to save.');

                    const updates = [];
                    rows.forEach((row, index) => {
                        updates.push({
                            savings_id: row.getAttribute('data-saving-id'),
                            date_remittance: row.querySelector(`input[name="date_remittance_${index}"]`)?.value || null,
                            remittance_no: row.querySelector(`input[name="remittance_no_${index}"]`)?.value || null,
                            month_name: row.querySelector(`input[name="month_name_${index}"]`)?.value || null,
                            covered_year: row.querySelector(`input[name="covered_year_${index}"]`)?.value || null,
                            amount: row.querySelector(`input[name="amount_${index}"]`)?.value || null,
                        });
                    });

                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!csrf) return alert("Missing CSRF token meta tag in your layout <head>.");

                    fetch("/admin/update-saving-remittances", {
                        method: "POST",
                            headers: { "X-CSRF-TOKEN": csrf, "Content-Type": "application/json" },
                            body: JSON.stringify({ updates })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                alert("✅ Savings remittances updated successfully!");
                                location.reload();
                                return;
                            }
                        alert("❌ " + (data.message || "No changes made."));
                    })
                    .catch(() => alert("❌ Something went wrong while saving."));
                });

                // VIEW: filter by year (blank => all)
                const runViewYear = () => {
                    if (!currentViewUserId) return alert('No member selected.');

                    const year = (yearFilter.value || '').trim();
                    const requestUrl = `/admin/get-savings/${encodeURIComponent(currentViewUserId)}/${encodeURIComponent(year || 'all')}`;

                    contributionsResult.innerHTML = `<div class="p-5 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">Loading...</div>`;

                    fetch(requestUrl)
                        .then(r => r.json())
                        .then(data => {
                            if (!data.success || !Array.isArray(data.contributions) || data.contributions.length === 0) {
                                contributionsResult.innerHTML = `
                                    <div class="mt-4 rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#0d1a14] p-5 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
                                        ${data.message || 'No records found.'}
                                    </div>`;
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
                                        <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32] bg-white dark:bg-[#0d1a14]">`;

                            data.contributions.forEach((c) => {
                                html += `
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4">${c.date_remittance || '—'}</td>
                                        <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">${c.remittance_no || '—'}</td>
                                        <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">${c.month_name || '—'}</td>
                                        <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">${c.covered_year || '—'}</td>
                                        <td class="px-6 py-4 font-black">${c.amount ?? '—'}</td>
                                    </tr>`;
                            });

                            html += `</tbody></table></div>`;
                            contributionsResult.innerHTML = html;
                        })
                        .catch(() => {
                            contributionsResult.innerHTML = `<div class="p-5 text-sm font-bold text-red-700 bg-red-50 border border-red-200">Failed to load records.</div>`;
                        });
                };

                viewYearBtn.addEventListener('click', runViewYear);
                yearFilter.addEventListener('keydown', (e) => { if (e.key === 'Enter') runViewYear(); });

                // ===== LIVE SEARCH (Savings Ledger) =====
const filtersForm   = document.getElementById('savingsFiltersForm');
const ledgerSearch  = document.getElementById('searchSavings');
const ledgerOffice  = document.getElementById('officeFilter');
const ledgerPerPage = document.getElementById('per_page');

const tbody = document.getElementById('savingsTableBody');
const pager = document.getElementById('savingsPagination');

let t = null;
let abortCtrl = null;

function buildParams(page = 1) {
  return new URLSearchParams({
    search: ledgerSearch?.value || '',
    office: ledgerOffice?.value || '',
    per_page: ledgerPerPage?.value || 10,
    page: page
  });
}

function syncUrl(params) {
  const base = filtersForm?.action || window.location.pathname;
  history.replaceState({}, '', `${base}?${params.toString()}`);
}

function fetchLedger(page = 1) {
  const params = buildParams(page);
  syncUrl(params);

  if (abortCtrl) abortCtrl.abort();
  abortCtrl = new AbortController();

  fetch(`{{ route('admin.savings.partial') }}?${params.toString()}`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    signal: abortCtrl.signal
  })
  .then(r => r.json())
  .then(res => {
    tbody.innerHTML = res.tbody;
    pager.innerHTML = res.pagination;

    const selectAll = document.getElementById('selectAll');
    if (selectAll) selectAll.checked = false;
  })
  .catch(err => {
    if (err.name !== 'AbortError') console.error(err);
  });
}

filtersForm?.addEventListener('submit', (e) => e.preventDefault());

ledgerSearch?.addEventListener('input', () => {
  clearTimeout(t);
  t = setTimeout(() => fetchLedger(1), 300);
});

ledgerOffice?.addEventListener('change', () => fetchLedger(1));
ledgerPerPage?.addEventListener('change', () => fetchLedger(1));

document.addEventListener('click', (e) => {
  const link = e.target.closest('#savingsPagination a');
  if (!link) return;
  e.preventDefault();
  const url = new URL(link.href);
  fetchLedger(url.searchParams.get('page') || 1);
});

            });
        </script>
    @endpush
</x-admin-v2-layout>
