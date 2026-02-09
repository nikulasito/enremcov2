<x-admin-v2-layout title="ENREMCO - Withdrawals" pageTitle="Withdrawals Management"
    pageSubtitle="Manage member withdrawals and withdrawal ledger" :showSearch="false">

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
        <a href="{{ url('/download/withdraw-template') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-black text-[#112119] hover:brightness-110">
            <span class="material-symbols-outlined">download</span>
            Download Withdrawals Template
        </a>

        <form action="{{ route('withdraw.upload') }}" method="POST" enctype="multipart/form-data"
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

    {{-- Bulk Add Withdrawals --}}
    <div class="mb-8 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] shadow-sm">
        <div class="p-6 border-b border-[#dce5e0] dark:border-[#2a3a32]">
            <h4 class="text-lg font-black">Add Withdrawal (Bulk)</h4>
            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                Select members below, fill details here, then click “Add Withdrawal”.
            </p>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Date of Withdrawal
                </label>
                <input type="date" id="dateWithdrawal"
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
            </div>

            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Reference / Receipt No.
                </label>
                <input type="text" id="referenceNo" placeholder="Optional"
                    class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
            </div>

            <div>
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Covered Period (Optional)
                </label>
                <div class="flex gap-2">
                    <select id="covered_month"
                        class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                        <option value="">Month</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>

                    <select id="covered_year"
                        class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                        <option value="">Year</option>
                        @for ($y = date('Y'); $y >= date('Y') - 50; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="lg:col-span-2">
                <label
                    class="mb-1.5 block text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                    Amount Withdrawn
                </label>
                <div class="flex gap-2">
                    <input type="number" step="any" min="0" id="withdrawAmount" placeholder="Enter amount"
                        class="w-full rounded-lg border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] py-2.5 px-4 text-sm">
                    <button id="addWithdrawBtn"
                        class="shrink-0 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-black text-white hover:brightness-110">
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ url()->current() }}" id="withdrawFiltersForm" class="mb-6">
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
                        <input type="text" id="searchWithdraw" name="search" value="{{ request('search') }}"
                            placeholder="Search by Name, ID, or Office..."
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
                            <option value="{{ $option }}" {{ (request('per_page', $perPage ?? 10) == $option) ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="button" id="clearWithdrawFilters"
                    class="rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119]
                           px-4 py-2.5 text-sm font-black text-[#638875] dark:text-white/70 hover:bg-[#f6f8f7] dark:hover:bg-[#21352b]">
                    Clear
                </button>
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
                            No.
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Member ID
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Name
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Office
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Latest Withdraw
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Total Withdraw
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody id="membersTableBody" class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32]">
    @include('admin.withdraw._rows', [
        'members' => $members,
        'withdrawTotals' => $withdrawTotals,
        'latestWithdrawByUser' => $latestWithdrawByUser,
    ])
</tbody>
            </table>
        </div>

        <div id="withdrawPagination" class="p-6 border-t border-[#dce5e0] dark:border-[#2a3a32]">
            @include('admin.withdraw._pagination', ['members' => $members])
        </div>
    </div>

    {{-- ===== View/Edit Withdrawals Modal (Tailwind) ===== --}}
    <div id="viewWithdrawalsModal" class="fixed inset-0 z-50 hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm" data-close-modal="viewWithdrawalsModal"></div>

        <div class="relative min-h-screen w-full flex items-center justify-center p-4">
            <div
                class="bg-white dark:bg-[#112119] w-full max-w-4xl rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] flex flex-col max-h-[90vh]">

                {{-- Header --}}
                <div
                    class="px-8 py-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[#111814] dark:text-white uppercase tracking-tight">
                            Member Withdrawals
                        </h2>
                        <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">View and edit withdrawal records</p>
                    </div>

                    <button type="button" class="text-[#638875] hover:text-red-500 transition-colors"
                        data-close-modal="viewWithdrawalsModal">
                        <span class="material-symbols-outlined text-2xl">close</span>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                    {{-- Member Summary --}}
                    <div
                        class="bg-[#f6f8f7] dark:bg-[#1a2e24] rounded-xl p-6 border border-[#dce5e0] dark:border-[#2a3a32] mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Member ID</p>
                                <p id="wModalEmployeeID" class="text-lg font-black text-primary">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Name
                                </p>
                                <p id="wModalName" class="text-lg font-black text-[#111814] dark:text-white">—</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">
                                    Office</p>
                                <p id="wModalOffice" class="text-lg font-black text-[#111814] dark:text-white">—</p>
                            </div>
                        </div>
                    </div>

                    {{-- Search --}}
                    <div class="space-y-3">
                        <h4 class="text-sm font-bold text-[#111814] dark:text-white uppercase tracking-wider">
                            Search Withdrawal Records
                        </h4>

                        <div class="flex flex-col md:flex-row gap-3">
                            <div class="relative flex-1">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#638875]">
                                    search
                                </span>
                                <input type="text" id="wSearch"
                                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-xl text-sm focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none"
                                    placeholder="Enter YYYY or Reference No." />
                            </div>

                            <button type="button" id="wSearchBtn"
                                class="bg-primary hover:brightness-110 text-[#112119] font-bold px-6 py-3 rounded-xl transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-xl">search</span>
                                Search
                            </button>
                        </div>

                        <p class="text-[11px] text-[#638875] dark:text-[#a0b0a8]">
                            Tip: You can search by Year (YYYY) or Reference No.
                        </p>
                    </div>

                    {{-- Results --}}
                    <div id="withdrawalsResult" class="mt-6"></div>
                </div>

                {{-- Footer --}}
                <div
                    class="px-8 py-6 bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-t border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-end gap-3">
                    <button type="button"
                        class="px-6 py-2.5 rounded-lg text-sm font-bold text-[#638875] hover:bg-gray-200 dark:hover:bg-[#2a3a32] transition-all"
                        data-close-modal="viewWithdrawalsModal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // ===== Filters behavior (keep your existing logic, same IDs) =====
                const filtersForm = document.getElementById('withdrawFiltersForm');
                const searchInput = document.getElementById('searchWithdraw');
                const officeSelect = document.getElementById('officeFilter');
                const perPageSelect = document.getElementById('per_page');
                const clearBtn = document.getElementById('clearWithdrawFilters');

                let t = null;

                searchInput?.addEventListener('input', () => {
                    clearTimeout(t);
                });

                // ===== AJAX partial/live search (same as Savings/Shares) =====
const partialUrl = @json(route('admin.withdraw.partial'));

const tbody = document.getElementById('membersTableBody');
const paginationWrap = document.getElementById('withdrawPagination');

if (!filtersForm || !searchInput || !officeSelect || !perPageSelect || !tbody || !paginationWrap) return;

let debounceTimer = null;

const buildParams = (page = 1) => {
    const params = new URLSearchParams();
    params.set('page', page);
    params.set('search', (searchInput.value || '').trim());
    params.set('office', (officeSelect.value || '').trim());
    params.set('per_page', perPageSelect.value || 10);
    return params;
};

const syncUrl = (params) => {
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.pushState({}, '', newUrl);
};

const fetchPartial = async (page = 1, pushUrl = true) => {
    const params = buildParams(page);

    // loading state
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="px-6 py-10 text-center text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
                Loading...
            </td>
        </tr>
    `;

    try {
        const res = await fetch(`${partialUrl}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();

        tbody.innerHTML = data.tbody ?? '';
        paginationWrap.innerHTML = data.pagination ?? '';

        // reset select all after refresh
        const selectAllBox = document.getElementById('selectAll');
        if (selectAllBox) selectAllBox.checked = false;

        if (pushUrl) syncUrl(params);
    } catch (e) {
        console.error(e);
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-10 text-center text-sm font-bold text-red-700">
                    Failed to load records.
                </td>
            </tr>
        `;
    }
};

// prevent normal submit (Enter key)
filtersForm.addEventListener('submit', (e) => {
    e.preventDefault();
    fetchPartial(1);
});

// debounced typing
searchInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchPartial(1), 300);
});

// filter changes
officeSelect.addEventListener('change', () => fetchPartial(1));
perPageSelect.addEventListener('change', () => fetchPartial(1));

// clear (keep per_page)
clearBtn?.addEventListener('click', () => {
    searchInput.value = '';
    officeSelect.value = '';
    fetchPartial(1);
});

// pagination click (AJAX)
paginationWrap.addEventListener('click', (e) => {
    const link = e.target.closest('a');
    if (!link) return;
    e.preventDefault();

    const url = new URL(link.href);
    const page = url.searchParams.get('page') || 1;
    fetchPartial(page);
});

// back/forward support
window.addEventListener('popstate', () => {
    const params = new URLSearchParams(window.location.search);

    searchInput.value = params.get('search') || '';
    officeSelect.value = params.get('office') || '';
    perPageSelect.value = params.get('per_page') || (perPageSelect.value || 10);

    fetchPartial(params.get('page') || 1, false);
});



                clearBtn?.addEventListener('click', () => {
                    const per = perPageSelect?.value || 10;
                    window.location.href = `${filtersForm.action}?per_page=${encodeURIComponent(per)}`;
                });

                // ===== Select all + bulk add withdrawals (unchanged) =====
                const selectAll = document.getElementById('selectAll');
                const addBtn = document.getElementById('addWithdrawBtn');

                function selectedIds() {
                    return Array.from(document.querySelectorAll('.memberCheckbox:checked')).map(cb => cb.value);
                }

                selectAll?.addEventListener('change', () => {
                    document.querySelectorAll('.memberCheckbox').forEach(cb => cb.checked = selectAll.checked);
                });

                addBtn?.addEventListener('click', function (e) {
                    e.preventDefault();

                    const members = selectedIds();
                    const amount = document.getElementById('withdrawAmount')?.value;
                    const date = document.getElementById('dateWithdrawal')?.value;
                    const ref = document.getElementById('referenceNo')?.value;
                    const month = document.getElementById('covered_month')?.value;
                    const year = document.getElementById('covered_year')?.value;

                    if (members.length === 0) { alert('Select at least one member.'); return; }
                    if (!amount || isNaN(amount) || amount <= 0) { alert('Enter a valid amount.'); return; }
                    if (!date) { alert('Select a date of withdrawal.'); return; }

                    fetch("{{ route('admin.bulk-add-withdraw') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            member_ids: members,
                            amount_withdrawn: amount,
                            date_of_withdrawal: date,
                            reference_no: ref,
                            covered_month: month || null,
                            covered_year: year || null,
                        })
                    })
                        .then(r => r.json())
                        .then(d => {
                            if (d.success) { alert('Withdrawals saved.'); location.reload(); }
                            else { alert('Error: ' + (d.error || 'Unknown error')); }
                        })
                        .catch(err => alert('Error: ' + err.message));
                });

                // ===== Tailwind modal open/close =====
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
                    if (closeTarget) {
                        closeModal(closeTarget.getAttribute('data-close-modal'));
                        return;
                    }

                    const openTarget = e.target.closest('[data-open-modal]');
                    if (openTarget) {
                        const id = openTarget.getAttribute('data-open-modal');
                        openModal(id);

                        // set modal fields
                        document.getElementById('wModalEmployeeID').textContent = openTarget.dataset.employee_id || '—';
                        document.getElementById('wModalName').textContent = openTarget.dataset.name || '—';
                        document.getElementById('wModalOffice').textContent = openTarget.dataset.office || '—';
                        document.getElementById('wSearch').value = '';
                        document.getElementById('withdrawalsResult').innerHTML = '';
                        document.getElementById('wSearchBtn').setAttribute('data-user-id', openTarget.dataset.id);
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    const modal = document.getElementById('viewWithdrawalsModal');
                    if (modal && !modal.classList.contains('hidden')) closeModal('viewWithdrawalsModal');
                });

                // ===== Search withdrawals (same backend endpoints, improved UI) =====
                document.getElementById('wSearchBtn')?.addEventListener('click', function () {
                    const userId = this.getAttribute('data-user-id');
                    const q = document.getElementById('wSearch').value.trim();
                    if (!q) { alert('Enter a Year or a Reference No.'); return; }

                    const result = document.getElementById('withdrawalsResult');
                    result.innerHTML = `<div class="p-5 text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">Loading...</div>`;

                    fetch(`/admin/get-withdrawals/${encodeURIComponent(userId)}/${encodeURIComponent(q)}`)
                        .then(r => r.json())
                        .then(data => {
                            let html = `
                                                    <div class="overflow-x-auto rounded-xl border border-[#dce5e0] dark:border-[#2a3a32]">
                                                        <table class="w-full text-left">
                                                            <thead>
                                                                <tr class="bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-b border-[#dce5e0] dark:border-[#2a3a32]">
                                                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Date</th>
                                                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Reference No.</th>
                                                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Month</th>
                                                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Year</th>
                                                                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32] bg-white dark:bg-[#0d1a14]">
                                                `;

                            if (data.success && data.withdrawals.length > 0) {
                                data.withdrawals.forEach((w, i) => {
                                    html += `
                                                            <tr data-withdrawals-id="${w.withdrawals_id}">
                                                                <td class="px-6 py-3">
                                                                    <input type="date" class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] text-sm py-2 px-3"
                                                                        name="date_of_withdrawal_${i}" value="${w.date_of_withdrawal || ''}">
                                                                </td>
                                                                <td class="px-6 py-3">
                                                                    <input type="text" class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] text-sm py-2 px-3"
                                                                        name="reference_no_${i}" value="${w.reference_no || ''}">
                                                                </td>
                                                                <td class="px-6 py-3">
                                                                    <input type="text" class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] text-sm py-2 px-3"
                                                                        name="month_name_${i}" value="${w.month_name || ''}">
                                                                </td>
                                                                <td class="px-6 py-3">
                                                                    <input type="text" class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] text-sm py-2 px-3"
                                                                        name="covered_year_${i}" value="${w.covered_year || ''}">
                                                                </td>
                                                                <td class="px-6 py-3">
                                                                    <input type="number" step="any" min="0" class="w-full rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#112119] text-sm py-2 px-3"
                                                                        name="amount_withdrawn_${i}" value="${w.amount_withdrawn || ''}">
                                                                </td>
                                                            </tr>
                                                        `;
                                });
                            } else {
                                html += `
                                                        <tr>
                                                            <td colspan="5" class="px-6 py-10 text-center text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
                                                                No withdrawals found
                                                            </td>
                                                        </tr>
                                                    `;
                            }

                            html += `
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <div class="mt-4 flex justify-end">
                                                        <button class="rounded-lg bg-[#112119] dark:bg-white text-white dark:text-[#112119] px-5 py-2.5 text-sm font-black hover:opacity-90"
                                                            id="saveWithdrawChangesBtn">
                                                            Save Changes
                                                        </button>
                                                    </div>
                                                `;

                            result.innerHTML = html;
                        })
                        .catch(() => {
                            result.innerHTML = `<div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-5 text-sm font-bold text-red-700">Failed to load.</div>`;
                        });
                });

                // ===== Save changes (same endpoint) =====
                document.addEventListener('click', function (e) {
                    if (e.target && e.target.id === 'saveWithdrawChangesBtn') {
                        const rows = document.querySelectorAll('#withdrawalsResult table tbody tr');
                        const updates = [];

                        rows.forEach((row, i) => {
                            const id = row.getAttribute('data-withdrawals-id');
                            const date = row.querySelector(`[name="date_of_withdrawal_${i}"]`)?.value;
                            const ref = row.querySelector(`[name="reference_no_${i}"]`)?.value;
                            const mon = row.querySelector(`[name="month_name_${i}"]`)?.value;
                            const yr = row.querySelector(`[name="covered_year_${i}"]`)?.value;
                            const amt = row.querySelector(`[name="amount_withdrawn_${i}"]`)?.value;

                            if (!id || !date || isNaN(amt)) return;

                            updates.push({
                                withdrawals_id: id,
                                date_of_withdrawal: date,
                                reference_no: ref,
                                month_name: mon,
                                covered_year: yr,
                                amount_withdrawn: amt
                            });
                        });

                        fetch("{{ url('/admin/update-withdrawals') }}", {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                            body: JSON.stringify({ updates })
                        })
                            .then(r => r.json())
                            .then(d => {
                                alert(d.message || (d.success ? 'Saved' : 'No changes'));
                                if (d.success) location.reload();
                            })
                            .catch(() => alert('Error saving changes'));
                    }
                });
            });
        </script>
    @endpush

</x-admin-v2-layout>