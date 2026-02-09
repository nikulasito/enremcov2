<x-admin-v2-layout title="ENREMCO - Member Directory" pageTitle="Members" pageSubtitle="Manage membership"
    :showSearch="true">
    <div class="bg-white">


        {{-- Success --}}
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-black text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filters row --}}
        <form method="GET" action="{{ route('admin.members') }}" id="filtersForm"
            class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex flex-1 flex-wrap items-center gap-4">
                {{-- Search --}}
                <div class="relative w-full max-w-sm">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input id="searchInput" name="search" value="{{ request('search') }}"
                        class="w-full rounded-lg border-[#dce5e0] bg-white py-2.5 pl-10 pr-4 text-sm focus:border-primary focus:ring-primary dark:border-[#2a3a32] dark:bg-[#1a2e24] dark:text-white"
                        placeholder="Search by name or ID..." type="text" />
                </div>

                {{-- Status --}}
                <select id="statusFilter" name="status"
                    class="rounded-lg border-[#dce5e0] bg-white py-2.5 text-sm focus:border-primary focus:ring-primary dark:border-[#2a3a32] dark:bg-[#1a2e24] dark:text-white">
                    <option value="">Filter by Status</option>
                    <option value="Active" {{ request('status') === 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ request('status') === 'Inactive' ? 'selected' : '' }}>Inactive
                    </option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending
                    </option>
                </select>

                {{-- Office --}}
                <select id="officeFilter" name="office"
                    class="rounded-lg border-[#dce5e0] bg-white py-2.5 text-sm focus:border-primary focus:ring-primary dark:border-[#2a3a32] dark:bg-[#1a2e24] dark:text-white">
                    @include('admin.partials.office_options', ['offices' => $offices])
                </select>

                {{-- Per page --}}
                <select id="per_page" name="per_page"
                    class="rounded-lg border-[#dce5e0] bg-white py-2.5 text-sm focus:border-primary focus:ring-primary dark:border-[#2a3a32] dark:bg-[#1a2e24] dark:text-white">
                    @foreach ([10, 20, 50, 100] as $option)
                        <option value="{{ $option }}" {{ ($perPage ?? 10) == $option ? 'selected' : '' }}>
                            Show {{ $option }}
                        </option>
                    @endforeach
                </select>

                <button type="button" id="clearFilters"
                    class="rounded-lg border border-[#dce5e0] bg-white px-4 py-2.5 text-sm font-black text-[#638875] hover:bg-background-light dark:border-[#2a3a32] dark:bg-[#1a2e24] dark:text-white/70 dark:hover:bg-[#21352b]">
                    Clear
                </button>
            </div>

            {{-- Download --}}
            <a href="{{ route('admin.download-members') }}"
                class="flex items-center justify-center gap-2 rounded-lg bg-primary px-5 py-2.5 text-sm font-black text-[#112119] transition-all hover:brightness-110">
                <span class="material-symbols-outlined text-xl">download</span>
                Download Members List
            </a>
        </form>

        {{-- Table + pagination wrapper --}}
        <div id="membersTable"
            class="overflow-hidden rounded-xl border border-[#dce5e0] bg-white dark:border-[#2a3a32] dark:bg-[#1a2e24]">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-[#f6f8f7] dark:bg-[#0a1410]/50 border-b border-[#dce5e0] dark:border-[#2a3a32]">
                        <tr>
                            <th
                                class="px-6 py-4 font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                No.</th>
                            <th
                                class="px-6 py-4 font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Member ID</th>
                            <th
                                class="px-6 py-4 font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Name</th>
                            <th
                                class="px-6 py-4 font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Office</th>
                            <th
                                class="px-6 py-4 font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Status</th>
                            <th
                                class="px-6 py-4 font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Date Approved</th>
                            <th
                                class="px-6 py-4 font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Date Inactive</th>
                            <th
                                class="px-6 py-4 font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Date Reactive</th>
                            <th
                                class="px-6 py-4 font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                                Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#dce5e0] dark:divide-[#2a3a32]">
                        @forelse($members as $index => $member)
                            @php
                                $rowNo = ($members->currentPage() - 1) * $members->perPage() + $index + 1;
                                $status = $member->status ?? '—';
                                $s = strtolower($status);

                                $pill = 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-white/60';
                                if ($s === 'active')
                                    $pill = 'bg-primary/20 text-primary';
                                if ($s === 'pending' || $s === 'awaiting approval')
                                    $pill = 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400';
                                if ($s === 'inactive')
                                    $pill = 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-white/60';
                            @endphp

                            <tr class="hover:bg-[#f6f8f7] dark:hover:bg-[#21352b] transition-colors">
                                <td class="px-6 py-4 font-medium">{{ str_pad($rowNo, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-6 py-4 text-primary font-black">{{ $member->employee_ID }}</td>
                                <td class="px-6 py-4 font-black">{{ $member->name }}</td>
                                <td class="px-6 py-4">{{ $member->office }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-black {{ $pill }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $member->date_approved ? \Carbon\Carbon::parse($member->date_approved)->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">
                                    {{ $member->date_inactive ? \Carbon\Carbon::parse($member->date_inactive)->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">
                                    {{ $member->date_reactive ? \Carbon\Carbon::parse($member->date_reactive)->format('M d, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="text-[#638875] hover:text-blue-500 transition-colors"
                                            data-update-url="{{ route('admin.edit-member', $member->id) }}" title="Update">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9"
                                    class="px-6 py-10 text-center text-sm font-black text-[#638875] dark:text-[#a0b0a8]">
                                    No members found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination footer (custom, Tailwind) --}}
        <div id="membersPagination"
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-t border-[#dce5e0] dark:border-[#2a3a32] bg-[#f6f8f7] dark:bg-[#0a1410]/30 px-6 py-4 rounded-b-xl">
            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                @if($members->total() > 0)
                    Showing <span class="font-black">{{ $members->firstItem() }}</span>
                    to <span class="font-black">{{ $members->lastItem() }}</span>
                    of <span class="font-black">{{ $members->total() }}</span> members
                @else
                    Showing 0 members
                @endif
            </p>

            @php
                $p = $members->appends(request()->except('page'));
                $current = $p->currentPage();
                $last = $p->lastPage();

                $start = max(1, $current - 1);
                $end = min($last, $current + 1);
            @endphp

            <div class="flex gap-2">
                {{-- Previous --}}
                @if($current > 1)
                    <a href="{{ $p->url($current - 1) }}"
                        class="rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] px-3 py-1.5 text-xs font-black hover:bg-background-light dark:hover:bg-[#21352b]">
                        Previous
                    </a>
                @else
                    <span
                        class="rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white/60 dark:bg-[#1a2e24]/50 px-3 py-1.5 text-xs font-black opacity-60">
                        Previous
                    </span>
                @endif

                {{-- First + Ellipsis --}}
                @if($start > 1)
                    <a href="{{ $p->url(1) }}"
                        class="rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] px-3 py-1.5 text-xs font-black hover:bg-background-light dark:hover:bg-[#21352b]">
                        1
                    </a>
                    @if($start > 2)
                        <span class="px-2 py-1.5 text-xs font-black text-[#638875] dark:text-[#a0b0a8]">…</span>
                    @endif
                @endif

                {{-- Window --}}
                @for($page = $start; $page <= $end; $page++)
                    @if($page == $current)
                        <span class="rounded-lg bg-primary px-3 py-1.5 text-xs font-black text-[#112119]">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $p->url($page) }}"
                            class="rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] px-3 py-1.5 text-xs font-black hover:bg-background-light dark:hover:bg-[#21352b]">
                            {{ $page }}
                        </a>
                    @endif
                @endfor

                {{-- Ellipsis + Last --}}
                @if($end < $last)
                    @if($end < $last - 1)
                        <span class="px-2 py-1.5 text-xs font-black text-[#638875] dark:text-[#a0b0a8]">…</span>
                    @endif
                    <a href="{{ $p->url($last) }}"
                        class="rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] px-3 py-1.5 text-xs font-black hover:bg-background-light dark:hover:bg-[#21352b]">
                        {{ $last }}
                    </a>
                @endif

                {{-- Next --}}
                @if($current < $last)
                    <a href="{{ $p->url($current + 1) }}"
                        class="rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-[#1a2e24] px-3 py-1.5 text-xs font-black hover:bg-background-light dark:hover:bg-[#21352b]">
                        Next
                    </a>
                @else
                    <span
                        class="rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-white/60 dark:bg-[#1a2e24]/50 px-3 py-1.5 text-xs font-black opacity-60">
                        Next
                    </span>
                @endif
            </div>
        </div>


        {{-- Update Member Modal (Scrollable) --}}
        <div id="updateMemberModal" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-modal-close="updateMemberModal"></div>

            <div class="relative mx-auto mt-10 w-[92%] max-w-3xl overflow-hidden rounded-xl bg-white shadow-2xl dark:bg-[#1a2e24]
                max-h-[85vh] flex flex-col">

                {{-- Header --}}
                <div
                    class="flex items-center justify-between border-b border-[#dce5e0] px-8 py-5 dark:border-[#2a3a32]">
                    <h3 class="text-xl font-black">Update Member Information</h3>
                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-white"
                        data-modal-close="updateMemberModal">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                {{-- Scrollable body --}}
                <div id="updateMemberModalBody" class="flex-1 overflow-y-auto">
                    <div class="p-8">
                        <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Loading...</p>
                    </div>
                </div>
            </div>
        </div>



    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const filtersForm = document.getElementById('filtersForm');
                const searchInput = document.getElementById('searchInput');
                const statusFilter = document.getElementById('statusFilter');
                const officeFilter = document.getElementById('officeFilter');
                const perPage = document.getElementById('per_page');
                const clearBtn = document.getElementById('clearFilters');

                const membersTableEl = document.getElementById('membersTable');
                const paginationEl = document.getElementById('membersPagination');

                let debounceTimer = null;
                let abortController = null;

                function buildUrl() {
                    const params = new URLSearchParams(new FormData(filtersForm));
                    return `${filtersForm.action}?${params.toString()}`;
                }

                async function loadAndReplace(url) {
                    if (abortController) abortController.abort();
                    abortController = new AbortController();

                    membersTableEl.style.opacity = '0.6';
                    paginationEl.style.opacity = '0.6';

                    const resp = await fetch(url, { signal: abortController.signal });
                    if (!resp.ok) throw new Error('Failed to load');

                    const html = await resp.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');

                    const newMembersTable = doc.getElementById('membersTable');
                    const newPagination = doc.getElementById('membersPagination');

                    if (newMembersTable) membersTableEl.innerHTML = newMembersTable.innerHTML;
                    if (newPagination) paginationEl.innerHTML = newPagination.innerHTML;

                    // sync office options from fetched HTML
                    const fetchedOffice = doc.querySelector('#officeFilter');
                    if (fetchedOffice) {
                        const selectedOffice = officeFilter.value;
                        officeFilter.innerHTML = fetchedOffice.innerHTML;
                        if ([...officeFilter.options].some(o => o.value === selectedOffice)) {
                            officeFilter.value = selectedOffice;
                        }
                    }


                    membersTableEl.style.opacity = '1';
                    paginationEl.style.opacity = '1';

                    window.history.pushState({}, '', url);
                }

                function requestUpdate(urlOverride = null) {
                    const url = urlOverride || buildUrl();
                    loadAndReplace(url).catch(err => {
                        if (err.name === 'AbortError') return;
                        console.error(err);
                        membersTableEl.style.opacity = '1';
                        paginationEl.style.opacity = '1';
                    });
                }

                // Prevent normal submit
                filtersForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    requestUpdate();
                });

                // Debounced search
                searchInput.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => requestUpdate(), 300);
                });

                statusFilter.addEventListener('change', () => requestUpdate());
                officeFilter.addEventListener('change', () => requestUpdate());
                perPage.addEventListener('change', () => requestUpdate());

                clearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    statusFilter.value = '';
                    officeFilter.value = '';
                    perPage.value = '10'; // or keep current if you prefer
                    requestUpdate();
                });

                // AJAX pagination (links inside footer)
                paginationEl.addEventListener('click', (e) => {
                    const link = e.target.closest('a');
                    if (!link) return;
                    e.preventDefault();
                    requestUpdate(link.href);
                });

                // Back/forward support
                window.addEventListener('popstate', () => {
                    requestUpdate(window.location.href);
                });

                // ===== Update Modal (Tailwind + fetch) =====
                const modal = document.getElementById('updateMemberModal');
                const modalBodyEl = document.getElementById('updateMemberModalBody');

                function openModal() {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }

                function closeModal() {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }

                function bindUpdateMemberForm(containerEl) {
                    const form = containerEl.querySelector('#updateMemberForm');
                    if (!form) return;

                    form.addEventListener('submit', async (e) => {
                        e.preventDefault();

                        const formData = new FormData(form);

                        try {
                            const resp = await fetch(form.action, {
                                method: 'POST', // will respect _method=PATCH from form
                                headers: {
                                    "Accept": "application/json",
                                    "X-Requested-With": "XMLHttpRequest",
                                },
                                body: formData
                            });

                            if (resp.status === 422) {
                                const err = await resp.json();
                                alert(Object.values(err.errors).flat().join("\n"));
                                return;
                            }

                            const data = await resp.json();

                            if (data.success) {
                                closeModal();

                                // refresh your members table (use your existing function)
                                requestUpdate();
                            } else {
                                alert("❌ Error updating member.");
                            }
                        } catch (error) {
                            console.error(error);
                            alert("❌ An error occurred while updating the member.");
                        }
                    });
                }

                document.addEventListener('click', (e) => {
                    const openBtn = e.target.closest('[data-update-url]');
                    if (openBtn) {
                        const url = openBtn.getAttribute('data-update-url');

                        modalBodyEl.innerHTML = `<p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Loading...</p>`;
                        openModal();

                        fetch(url)
                            .then(r => {
                                if (!r.ok) throw new Error('Network response was not ok');
                                return r.text();
                            })
                            .then(html => {
                                modalBodyEl.innerHTML = html;
                                bindUpdateMemberForm(modalBodyEl);
                            })
                            .catch(err => {
                                modalBodyEl.innerHTML =
                                    `<p class="text-sm font-black text-red-600">Error loading content: ${err.message}</p>`;
                            });

                        return;
                    }

                    const closeBtn = e.target.closest('[data-modal-close]');
                    if (closeBtn && closeBtn.getAttribute('data-modal-close') === 'updateMemberModal') {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') closeModal();
                });
            });

            function bindUpdateMemberForm() {
                const form = modalBody.querySelector('#updateMemberForm');
                if (!form) return;

                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const formData = new FormData(form);

                    try {
                        const resp = await fetch(form.action, {
                            method: 'POST', // _method=PATCH is inside the formData
                            headers: {
                                "Accept": "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                            },
                            body: formData
                        });

                        // validation errors
                        if (resp.status === 422) {
                            const err = await resp.json();
                            alert(Object.values(err.errors).flat().join("\n"));
                            return;
                        }

                        const data = await resp.json();

                        if (data.success) {
                            // close Tailwind modal
                            closeModal();

                            // refresh table using your existing ajax reload
                            requestUpdate();
                        } else {
                            alert("❌ Error updating member.");
                        }
                    } catch (error) {
                        console.error(error);
                        alert("❌ An error occurred while updating the member.");
                    }
                });
            }

        </script>
    @endpush
</x-admin-v2-layout>