<x-admin-v2-layout title="ENREMCO - New Members Approval" pageTitle="New Members Approval"
    pageSubtitle="Manage and review new membership applications" :showSearch="true"
    :searchAction="route('admin.new-members')" searchPlaceholder="Search applicants...">
    {{-- Success message --}}
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-[#dce5e0] overflow-hidden shadow-sm">
        {{-- Card header --}}
        <div
            class="p-6 border-b border-[#dce5e0] flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white">
            <div>
                <h3 class="font-black text-[#111814]">Pending Applications</h3>
                <p class="text-sm text-[#638875]">Applicants awaiting approval</p>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                {{-- Per page --}}
                <form method="GET" action="{{ route('admin.new-members') }}" class="flex items-center gap-2">
                    {{-- keep search when changing per_page --}}
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <span class="text-sm font-bold text-[#638875]">Show</span>
                    <select name="per_page" onchange="this.form.submit()"
                        class="h-10 rounded-lg border border-[#dce5e0] bg-white text-sm font-bold text-[#111814] focus:border-primary focus:ring-primary">
                        @foreach ([10, 20, 50, 100] as $option)
                            <option value="{{ $option }}" {{ ($perPage ?? 10) == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-sm text-[#638875]">per page</span>
                </form>

                <div class="flex gap-2">
                    <button type="button"
                        class="px-4 py-2 border border-[#dce5e0] rounded-lg text-sm font-black text-[#638875] hover:bg-gray-50 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">filter_list</span>
                        Filter
                    </button>

                    <button type="button"
                        class="px-4 py-2 border border-[#dce5e0] rounded-lg text-sm font-black text-[#638875] hover:bg-gray-50 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">download</span>
                        Export
                    </button>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[#f6f8f7] text-[#638875] text-xs font-black uppercase tracking-wider">
                        <th class="px-6 py-4">No.</th>
                        <th class="px-6 py-4">Member ID</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Office</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#dce5e0]">
                    @forelse ($newMembers as $index => $member)
                        @php
                            $rowNo = ($newMembers->currentPage() - 1) * $newMembers->perPage() + $index + 1;

                            $statusText = $member->status ?? 'Pending';
                            $s = strtolower($statusText);

                            // Default to pending styling
                            $pillClass = 'bg-amber-100 text-amber-700';
                            $dotClass = 'bg-amber-500';

                            if (in_array($s, ['active', 'approved'])) {
                                $pillClass = 'bg-emerald-100 text-emerald-700';
                                $dotClass = 'bg-emerald-500';
                            } elseif (in_array($s, ['disapproved', 'rejected', 'declined'])) {
                                $pillClass = 'bg-red-100 text-red-700';
                                $dotClass = 'bg-red-500';
                            }
                          @endphp

                        <tr class="hover:bg-[#f6f8f7]/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium">
                                {{ str_pad($rowNo, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-primary">
                                {{ $member->employee_ID }}
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-[#111814]">
                                {{ $member->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-[#638875]">
                                {{ $member->office }}
                            </td>
                            <td class="px-6 py-4 text-sm text-[#638875]">
                                {{ $member->email }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-black {{ $pillClass }}">
                                    <span class="size-1.5 rounded-full {{ $dotClass }}"></span>
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Approve --}}
                                    <button type="button" data-modal-open="approveModal-{{ $member->id }}"
                                        class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                        title="Approve">
                                        <span class="material-symbols-outlined">check_circle</span>
                                    </button>

                                    {{-- Disapprove --}}
                                    <button type="button" data-modal-open="disapproveModal-{{ $member->id }}"
                                        class="p-2 text-red-500 hover:bg-red-50/50 rounded-lg transition-colors"
                                        title="Reject">
                                        <span class="material-symbols-outlined">cancel</span>
                                    </button>

                                    {{-- View --}}
                                    <button type="button" data-modal-open="viewModal-{{ $member->id }}"
                                        class="p-2 text-[#638875] hover:bg-gray-100 rounded-lg transition-colors"
                                        title="View">
                                        <span class="material-symbols-outlined">visibility</span>
                                    </button>
                                </div>

                                {{-- =========================
                                DISAPPROVE MODAL
                                ========================= --}}
                                <div id="disapproveModal-{{ $member->id }}" class="fixed inset-0 z-50 hidden">
                                    <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm"
                                        data-modal-close="disapproveModal-{{ $member->id }}"></div>

                                    <div
                                        class="relative mx-auto mt-24 w-[92%] max-w-lg rounded-2xl bg-white border border-[#dce5e0] shadow-xl overflow-hidden">
                                        <div class="px-6 py-4 border-b border-[#dce5e0] flex items-center justify-between">
                                            <h4 class="text-lg font-black text-[#111814]">Disapprove Member</h4>
                                            <button type="button" class="text-[#638875] hover:text-[#111814]"
                                                data-modal-close="disapproveModal-{{ $member->id }}">
                                                <span class="material-symbols-outlined">close</span>
                                            </button>
                                        </div>

                                        <form method="POST" action="{{ route('admin.disapprove-member', $member->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <div class="p-6 space-y-4">
                                                <p class="text-sm text-[#638875]">
                                                    Are you sure you want to disapprove <span
                                                        class="font-black text-[#111814]">{{ $member->name }}</span>?
                                                </p>

                                                <div class="space-y-2">
                                                    <label class="text-sm font-black text-[#111814]">Reason for
                                                        disapproval</label>
                                                    <textarea name="reason" rows="4" required
                                                        class="w-full rounded-xl border border-[#dce5e0] bg-[#f6f8f7] p-3 text-sm focus:border-primary focus:ring-primary"></textarea>
                                                </div>
                                            </div>

                                            <div
                                                class="px-6 py-4 border-t border-[#dce5e0] flex items-center justify-end gap-2 bg-white">
                                                <button type="button" data-modal-close="disapproveModal-{{ $member->id }}"
                                                    class="px-4 py-2 rounded-lg border border-[#dce5e0] text-sm font-black text-[#638875] hover:bg-gray-50">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-black hover:brightness-105">
                                                    Disapprove
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- =========================
                                APPROVE MODAL
                                ========================= --}}
                                <div id="approveModal-{{ $member->id }}" class="fixed inset-0 z-50 hidden">
                                    <div class="absolute inset-0 bg-black/40"
                                        data-modal-close="approveModal-{{ $member->id }}"></div>

                                    <div
                                        class="relative mx-auto mt-24 w-[92%] max-w-lg rounded-2xl bg-white border border-[#dce5e0] shadow-xl overflow-hidden">
                                        <div class="px-6 py-4 border-b border-[#dce5e0] flex items-center justify-between">
                                            <h4 class="text-lg font-black text-[#111814]">Approve Member</h4>
                                            <button type="button" class="text-[#638875] hover:text-[#111814]"
                                                data-modal-close="approveModal-{{ $member->id }}">
                                                <span class="material-symbols-outlined">close</span>
                                            </button>
                                        </div>

                                        <form method="POST" action="{{ route('admin.approve-member', $member->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <div class="p-6 space-y-4">
                                                <p class="text-sm text-[#638875]">
                                                    Approve <span
                                                        class="font-black text-[#111814]">{{ $member->name }}</span>?
                                                </p>

                                                <div class="space-y-2">
                                                    <label class="text-sm font-black text-[#111814]">Approval date</label>
                                                    <input type="date" name="approved_at"
                                                        value="{{ old('approved_at') ?? now()->format('Y-m-d') }}"
                                                        class="h-11 w-full rounded-xl border border-[#dce5e0] bg-[#f6f8f7] px-4 text-sm focus:border-primary focus:ring-primary">
                                                    <p class="text-xs text-[#638875]">Change this if approving a different
                                                        date.</p>
                                                </div>

                                                <div class="space-y-2">
                                                    <label class="text-sm font-black text-[#111814]">Notes
                                                        (optional)</label>
                                                    <textarea name="approve_notes" rows="3"
                                                        class="w-full rounded-xl border border-[#dce5e0] bg-[#f6f8f7] p-3 text-sm focus:border-primary focus:ring-primary"></textarea>
                                                </div>
                                            </div>

                                            <div
                                                class="px-6 py-4 border-t border-[#dce5e0] flex items-center justify-end gap-2 bg-white">
                                                <button type="button" data-modal-close="approveModal-{{ $member->id }}"
                                                    class="px-4 py-2 rounded-lg border border-[#dce5e0] text-sm font-black text-[#638875] hover:bg-gray-50">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="px-4 py-2 rounded-lg bg-primary text-[#112119] text-sm font-black hover:brightness-105">
                                                    Confirm Approve
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- =========================
                                VIEW MODAL (PRINTABLE)
                                ========================= --}}
                                <div id="viewModal-{{ $member->id }}" class="fixed inset-0 z-50 hidden">
                                    <div class="absolute inset-0 bg-black/40"
                                        data-modal-close="viewModal-{{ $member->id }}"></div>

                                    <div
                                        class="relative mx-auto mt-10 w-[94%] max-w-6xl rounded-2xl bg-white border border-[#dce5e0] shadow-xl overflow-hidden">
                                        <div class="px-6 py-4 border-b border-[#dce5e0] flex items-center justify-between">
                                            <h4 class="text-lg font-black text-[#111814]">
                                                Registration Details — {{ $member->name }}
                                            </h4>
                                            <div class="flex items-center gap-2">
                                                <button type="button"
                                                    class="px-4 py-2 rounded-lg border border-[#dce5e0] text-sm font-black text-[#638875] hover:bg-gray-50"
                                                    onclick="printMember({{ $member->id }})">
                                                    Print
                                                </button>
                                                <button type="button" class="text-[#638875] hover:text-[#111814]"
                                                    data-modal-close="viewModal-{{ $member->id }}">
                                                    <span class="material-symbols-outlined">close</span>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="p-6 max-h-[70vh] overflow-y-auto">
                                            <div id="printable-member-{{ $member->id }}" class="p-4">
                                                <div class="text-center">
                                                    <img class="mx-auto mb-2 h-16"
                                                        src="https://enremco.com/assets/images/enremco_logo.png"
                                                        alt="ENREMCO Logo">
                                                    <h1 class="text-lg font-black uppercase leading-tight">
                                                        ENVIRONMENT AND NATURAL RESOURCES<br>
                                                        EMPLOYEES MULTI-PURPOSE COOPERATIVE<br>
                                                        (ENREMCO)
                                                    </h1>
                                                    <div class="text-sm text-[#638875]">DENR-10, Compound Cagayan de Oro
                                                        City</div>
                                                </div>

                                                <div class="mt-6 space-y-3 text-sm">
                                                    <p><span class="font-black">The Chairperson</span><br>ENREMCO</p>
                                                    <p>Madam/Sir:</p>
                                                    <p>
                                                        I have the honor to apply as member of ENREMCO Region 10. If
                                                        accepted, I shall pledge my loyalty to the Cooperative.
                                                    </p>
                                                </div>

                                                <div class="mt-6 border border-[#dce5e0] rounded-xl overflow-hidden">
                                                    <table class="w-full text-sm">
                                                        <tbody class="divide-y divide-[#dce5e0]">
                                                            <tr class="divide-x divide-[#dce5e0]">
                                                                <td class="p-3"><span class="font-black">Name:</span>
                                                                    {{ $member->name }}</td>
                                                                <td class="p-3"><span class="font-black">Email:</span>
                                                                    {{ $member->email }}</td>
                                                            </tr>
                                                            <tr class="divide-x divide-[#dce5e0]">
                                                                <td class="p-3"><span class="font-black">Office:</span>
                                                                    {{ $member->office }}</td>
                                                                <td class="p-3"><span class="font-black">Home
                                                                        Address:</span> {{ $member->address }}</td>
                                                            </tr>
                                                            <tr class="divide-x divide-[#dce5e0]">
                                                                <td class="p-3"><span class="font-black">Religion:</span>
                                                                    {{ $member->religion }}</td>
                                                                <td class="p-3"><span class="font-black">Sex:</span>
                                                                    {{ $member->sex }}</td>
                                                            </tr>
                                                            <tr class="divide-x divide-[#dce5e0]">
                                                                <td class="p-3"><span class="font-black">Marital
                                                                        Status:</span> {{ $member->marital_status }}</td>
                                                                <td class="p-3"><span class="font-black">Annual
                                                                        Income:</span> {{ $member->annual_income }}</td>
                                                            </tr>
                                                            <tr class="divide-x divide-[#dce5e0]">
                                                                <td class="p-3"><span
                                                                        class="font-black">Beneficiaries:</span>
                                                                    {{ $member->beneficiaries }}</td>
                                                                <td class="p-3"><span class="font-black">Birthdate:</span>
                                                                    {{ $member->birthdate }}</td>
                                                            </tr>
                                                            <tr class="divide-x divide-[#dce5e0]">
                                                                <td class="p-3"><span class="font-black">Education:</span>
                                                                    {{ $member->education }}</td>
                                                                <td class="p-3"><span class="font-black">Member ID:</span>
                                                                    {{ $member->employee_ID }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                @php
                                                    $dateToShow = $member->approved_at ?? $member->created_at ?? now();
                                                    $dateToShow = \Carbon\Carbon::parse($dateToShow);
                                                  @endphp

                                                <div class="mt-6 space-y-3 text-sm">
                                                    <p>
                                                        I hereby authorize the Disbursing Office/Cashier to deduct from my
                                                        salary the amount of Fifty Pesos (50.00 PESOS) as membership fee.
                                                    </p>
                                                    <p>
                                                        Further authorize to deduct the amount of ₱ {{ $member->shares }}
                                                        per month which is equivalent to 2% of my salary...
                                                    </p>
                                                    <p>This application, where my signature is affixed, is my voluntary act
                                                        and deed.</p>
                                                    <p>
                                                        This {{ $dateToShow->format('jS') }} day of
                                                        {{ $dateToShow->format('F') }}, {{ $dateToShow->format('Y') }}.
                                                    </p>
                                                </div>

                                                <div class="mt-8 text-center text-sm">
                                                    <p class="font-black uppercase">{{ $member->name }}</p>
                                                    <p class="text-[#638875]">Applicant</p>
                                                </div>

                                                <div class="mt-10 grid grid-cols-2 gap-8 text-center text-sm">
                                                    <div>
                                                        <p class="text-[#638875]">Attested by:</p>
                                                        <div class="mt-10 border-t border-black w-3/4 mx-auto"></div>
                                                        <p class="mt-2">Member</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-[#638875]">Approved:</p>
                                                        <div class="mt-10 border-t border-black w-3/4 mx-auto"></div>
                                                        <p class="mt-2 font-black uppercase">MARY GRACE O. ALEMANIO</p>
                                                        <p class="text-[#638875]">Chairperson</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="px-6 py-4 border-t border-[#dce5e0] flex items-center justify-end gap-2 bg-white">
                                            <button type="button" data-modal-close="viewModal-{{ $member->id }}"
                                                class="px-4 py-2 rounded-lg border border-[#dce5e0] text-sm font-black text-[#638875] hover:bg-gray-50">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-sm font-bold text-[#638875]">
                                No new members awaiting approval.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination footer --}}
        <div
            class="p-6 bg-white border-t border-[#dce5e0] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <p class="text-sm text-[#638875]">
                @if($newMembers->total() > 0)
                    Showing {{ $newMembers->firstItem() }} to {{ $newMembers->lastItem() }} of {{ $newMembers->total() }}
                    applicants
                @else
                    Showing 0 applicants
                @endif
            </p>

            <div class="text-sm">
                {{ $newMembers->withQueryString()->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const openBtns = document.querySelectorAll('[data-modal-open]');
                const closeBtns = document.querySelectorAll('[data-modal-close]');
                const modals = new Set();

                function openModal(id) {
                    const m = document.getElementById(id);
                    if (!m) return;
                    m.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    modals.add(id);
                }

                function closeModal(id) {
                    const m = document.getElementById(id);
                    if (!m) return;
                    m.classList.add('hidden');
                    modals.delete(id);
                    if (modals.size === 0) document.body.style.overflow = '';
                }

                openBtns.forEach(btn => {
                    btn.addEventListener('click', () => openModal(btn.getAttribute('data-modal-open')));
                });

                closeBtns.forEach(btn => {
                    btn.addEventListener('click', () => closeModal(btn.getAttribute('data-modal-close')));
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        [...modals].forEach(id => closeModal(id));
                    }
                });

                window.printMember = function (memberId) {
                    const element = document.getElementById('printable-member-' + memberId);
                    if (!element) {
                        alert('Printable content not found.');
                        return;
                    }

                    const content = element.innerHTML;
                    const win = window.open('', '_blank', 'toolbar=0,location=0,menubar=0,width=900,height=800');

                    if (!win) {
                        alert('Popup blocked. Allow popups for this site to print.');
                        return;
                    }

                    win.document.write(`
                              <html>
                              <head>
                                  <title>Registration Details</title>
                                  <meta name="viewport" content="width=device-width, initial-scale=1">
                                  <style>
                                      body { font-family: Arial, sans-serif; line-height: 1.5; margin: 10px; }
                                      table { width: 100%; border-collapse: collapse; }
                                      td { vertical-align: top; }
                                  </style>
                              </head>
                              <body>${content}</body>
                              </html>
                          `);

                    win.document.close();
                    win.focus();

                    setTimeout(() => {
                        win.print();
                    }, 300);
                };
            });
        </script>
    @endpush
</x-admin-v2-layout>