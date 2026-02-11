<x-member-layout title="ENREMCO - Loan Application">
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900">Loan Application Form</h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-400 uppercase">Application ID</p>
                    <p class="text-sm font-bold text-slate-700">LA-{{ now()->format('YmdHis') }}</p>
                </div>
                <button
                    class="flex size-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined">help_outline</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="w-full">
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">description</span>
                Application Details
            </h3>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">

            <form class="p-8 space-y-10" method="POST" action="{{ route('member.loans.store') }}">
                @csrf

                {{-- Personal Information --}}
                <section class="space-y-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        Personal Information
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700" for="full-name">Full Name</label>
                            <input
                                class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-900 px-4 py-3 font-medium focus:bg-white"
                                id="full-name" name="full_name" type="text"
                                value="{{ old('full_name', auth()->user()->name) }}" />
                            @error('full_name') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700" for="member-id">Member ID</label>
                            <input
                                class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-400 px-4 py-3 font-medium cursor-not-allowed"
                                disabled id="member-id" type="text"
                                value="{{ auth()->user()->employee_ID ?? auth()->user()->employees_id ?? auth()->user()->employee_id ?? 'N/A' }}" />
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-bold text-slate-700" for="address">Residential Address</label>
                            <input
                                class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-900 px-4 py-3 font-medium focus:bg-white"
                                id="address" name="address" type="text"
                                value="{{ old('address', auth()->user()->address ?? '') }}" />
                            @error('address') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                {{-- Loan Specifications --}}
                <section class="space-y-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        Loan Specifications
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700" for="loan-type">Loan Type</label>
                            <select
                                class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                id="loan-type" name="loan_type">
                                <option value="regular" @selected(old('loan_type') === 'regular')>Regular Loan</option>
                                <option value="educational" @selected(old('loan_type') === 'educational')>Educational Loan
                                </option>
                                <option value="appliance" @selected(old('loan_type') === 'appliance')>Appliance Loan
                                </option>
                                <option value="grocery" @selected(old('loan_type') === 'grocery')>Grocery Loan</option>
                            </select>
                            @error('loan_type') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700" for="loan-amount">Desired Loan Amount
                                (₱)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">₱</span>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 pl-8 pr-4 py-3 font-black placeholder:text-slate-300"
                                    id="loan-amount" name="loan_amount" placeholder="0.00" type="number" step="0.01"
                                    value="{{ old('loan_amount') }}" />
                            </div>
                            @error('loan_amount') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                {{-- Co-makers --}}
                <section class="space-y-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        Co-maker Requirements
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        {{-- Co-maker 1 --}}
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div
                                    class="size-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-xs">
                                    01</div>
                                <h4 class="font-bold text-slate-800">Co-maker 1</h4>
                            </div>

                            <div class="space-y-2 relative">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                    for="cm1-name">Full Name</label>

                                <input
                                    class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                    id="cm1-name" name="comaker1_name" type="text" autocomplete="off"
                                    value="{{ old('comaker1_name') }}" />

                                {{-- hidden selected co-maker user id --}}
                                <input type="hidden" id="cm1-user-id" name="comaker1_user_id"
                                    value="{{ old('comaker1_user_id') }}">

                                {{-- suggestion dropdown --}}
                                <div id="cm1-suggestions"
                                    class="hidden absolute z-30 mt-1 w-full rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                    for="cm1-position">Position</label>
                                <input
                                    class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                    id="cm1-position" name="comaker1_position" type="text"
                                    value="{{ old('comaker1_position') }}" readonly />
                            </div>
                        </div>

                        {{-- Co-maker 2 --}}
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div
                                    class="size-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-xs">
                                    02</div>
                                <h4 class="font-bold text-slate-800">Co-maker 2</h4>
                            </div>

                            <div class="space-y-2 relative">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                    for="cm2-name">Full Name</label>

                                <input
                                    class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                    id="cm2-name" name="comaker2_name" type="text" autocomplete="off"
                                    value="{{ old('comaker2_name') }}" />

                                <input type="hidden" id="cm2-user-id" name="comaker2_user_id"
                                    value="{{ old('comaker2_user_id') }}">

                                <div id="cm2-suggestions"
                                    class="hidden absolute z-30 mt-1 w-full rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                    for="cm2-position">Position</label>
                                <input
                                    class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                    id="cm2-position" name="comaker2_position" type="text"
                                    value="{{ old('comaker2_position') }}" readonly />
                            </div>
                        </div>

                    </div>

                </section>

                <div class="pt-8 flex flex-col items-center gap-6 border-t border-slate-100">
                    <button
                        class="w-full max-w-md py-4 rounded-xl bg-primary text-background-dark font-black text-base transition-all hover:brightness-105 active:scale-[0.98] shadow-lg shadow-primary/20 flex items-center justify-center gap-2"
                        type="submit">
                        <span class="material-symbols-outlined">send</span>
                        Submit Loan Application
                    </button>

                    <a class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors underline underline-offset-4 decoration-2"
                        href="{{ route('dashboard') }}">
                        Cancel and return to dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(session('loan_submitted'))
    @php($m = session('loan_submitted'))
    <div id="successModal" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 px-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Application Submitted!</h3>
                        <p class="text-sm text-slate-500">Your loan application has been sent to Admin for review.</p>
                    </div>
                </div>
                <button type="button" id="closeSuccessModal" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Application Reference</p>
                    <p class="text-lg font-black text-slate-900">{{ $m['application_no'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Loan Type</p>
                        <p class="text-sm font-bold text-slate-900">{{ ucwords($m['loan_type']) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Amount</p>
                        <p class="text-sm font-black text-slate-900">₱{{ number_format($m['loan_amount'], 2) }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('dashboard') }}"
                        class="px-5 py-3 rounded-xl bg-primary text-background-dark font-black text-sm hover:brightness-105 transition-all">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
        <script>
            (function () {
                const endpoint = "{{ route('member.loans.comakers.search') }}";

                function debounce(fn, delay = 250) {
                    let t;
                    return (...args) => {
                        clearTimeout(t);
                        t = setTimeout(() => fn(...args), delay);
                    };
                }

                function renderSuggestions(box, items, onPick) {
                    if (!items.length) {
                        box.innerHTML = `<div class="px-3 py-2 text-xs text-slate-500">No matches found</div>`;
                        box.classList.remove('hidden');
                        return;
                    }

                    box.innerHTML = items.map((item) => `
                    <button type="button"
                        class="w-full text-left px-3 py-2 hover:bg-slate-50 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-slate-800 truncate">${escapeHtml(item.name)}</div>
                            <div class="text-xs text-slate-500 truncate">${escapeHtml(item.position || '—')}</div>
                        </div>
                        <span class="text-xs font-bold text-slate-400">Select</span>
                    </button>
                `).join('');

                    [...box.querySelectorAll('button')].forEach((btn, idx) => {
                        btn.addEventListener('click', () => onPick(items[idx]));
                    });

                    box.classList.remove('hidden');
                }

                function hideSuggestions(box) {
                    box.classList.add('hidden');
                    box.innerHTML = '';
                }

                function escapeHtml(str) {
                    return String(str ?? '')
                        .replaceAll('&', '&amp;')
                        .replaceAll('<', '&lt;')
                        .replaceAll('>', '&gt;')
                        .replaceAll('"', '&quot;')
                        .replaceAll("'", '&#039;');
                }

                function wireCoMaker({ nameInputId, posInputId, userIdInputId, boxId }) {
                    const nameEl = document.getElementById(nameInputId);
                    const posEl = document.getElementById(posInputId);
                    const userIdEl = document.getElementById(userIdInputId);
                    const boxEl = document.getElementById(boxId);

                    if (!nameEl || !posEl || !userIdEl || !boxEl) return;

                    let abortCtrl = null;

                    const doSearch = debounce(async () => {
                        const q = nameEl.value.trim();

                        userIdEl.value = '';
                        posEl.value = '';

                        if (q.length < 2) {
                            hideSuggestions(boxEl);
                            return;
                        }

                        if (abortCtrl) abortCtrl.abort();
                        abortCtrl = new AbortController();

                        try {
                            const res = await fetch(`${endpoint}?q=${encodeURIComponent(q)}`, {
                                headers: { 'Accept': 'application/json' },
                                signal: abortCtrl.signal
                            });

                            if (!res.ok) throw new Error('Search failed');

                            const items = await res.json();

                            renderSuggestions(boxEl, items, (picked) => {
                                nameEl.value = picked.name;
                                posEl.value = picked.position || '';
                                userIdEl.value = picked.id;
                                hideSuggestions(boxEl);
                            });
                        } catch (e) {
                            if (e.name !== 'AbortError') hideSuggestions(boxEl);
                        }
                    }, 250);

                    nameEl.addEventListener('input', doSearch);

                    document.addEventListener('click', (e) => {
                        if (!boxEl.contains(e.target) && e.target !== nameEl) hideSuggestions(boxEl);
                    });

                    nameEl.addEventListener('focus', () => {
                        if (nameEl.value.trim().length >= 2) doSearch();
                    });
                }

                wireCoMaker({ nameInputId: 'cm1-name', posInputId: 'cm1-position', userIdInputId: 'cm1-user-id', boxId: 'cm1-suggestions' });
                wireCoMaker({ nameInputId: 'cm2-name', posInputId: 'cm2-position', userIdInputId: 'cm2-user-id', boxId: 'cm2-suggestions' });
            })();
        </script>

        <script>
            // ✅ Modal close script (safe even if modal doesn't exist)
            (function () {
                const modal = document.getElementById('successModal');
                const btn = document.getElementById('closeSuccessModal');
                if (!modal) return;

                function close() { modal.remove(); }

                btn?.addEventListener('click', close);

                modal.addEventListener('click', (e) => {
                    if (e.target === modal) close();
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') close();
                });
            })();
        </script>
    @endpush

</x-member-layout>