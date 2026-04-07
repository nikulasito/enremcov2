<x-admin-v2-layout title="ENREMCO Credit Officer Dashboard" pageTitle="Credit Officer Dashboard" pageSubtitle="Loan Review Queue" :showSearch="false">
    @php
        $peso = fn($v) => '₱' . number_format((float) $v, 2);
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="summary-card">
            <div class="flex items-center justify-between mb-5">
                <div class="size-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined text-[28px]">pending_actions</span>
                </div>
                <div class="text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                    Queue
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pending Reviews</p>
            <p class="text-3xl font-black text-slate-900 mt-1">{{ (int) ($pendingReviewsCount ?? 0) }}</p>
        </div>

        <div class="summary-card">
            <div class="flex items-center justify-between mb-5">
                <div class="size-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <span class="material-symbols-outlined text-[28px]">fact_check</span>
                </div>
                <div class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">
                    Reviewed
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Applications Reviewed</p>
            <p class="text-3xl font-black text-slate-900 mt-1">{{ (int) ($reviewedCount ?? 0) }}</p>
        </div>

        <div class="summary-card">
            <div class="flex items-center justify-between mb-5">
                <div class="size-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-600">
                    <span class="material-symbols-outlined text-[28px]">cancel</span>
                </div>
                <div class="text-[11px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">
                    Closed
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rejected Applications</p>
            <p class="text-3xl font-black text-slate-900 mt-1">{{ (int) ($rejectedCount ?? 0) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-md font-black text-slate-900">Recent Loan Applications</h3>
                <p class="text-[11px] text-slate-500 font-medium">Open the review queue to mark them as reviewed or rejected.</p>
            </div>
            <a href="{{ route('admin.loan-requests.index') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-xs font-black text-[#0d1a14] hover:brightness-105 transition-all">
                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                Open Review Queue
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Member</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Loan Type</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Amount</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse(($recentLoanEntries ?? collect()) as $loan)
                        @php
                            $statusRaw = strtolower((string) ($loan->status ?? 'pending'));
                            $statusLabel = match ($statusRaw) {
                                'reviewed' => 'Reviewed',
                                'for_processing' => 'For Processing',
                                'for_approval' => 'For Approval',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                default => 'Pending',
                            };
                            $statusClass = match ($statusRaw) {
                                'reviewed' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                'for_processing' => 'bg-purple-50 text-purple-600 border border-purple-100',
                                'for_approval' => 'bg-indigo-50 text-indigo-600 border border-indigo-100',
                                'approved' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                'rejected' => 'bg-red-50 text-red-600 border border-red-100',
                                default => 'bg-amber-50 text-amber-600 border border-amber-100',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-extrabold text-slate-900">{{ $loan->full_name ?? optional($loan->user)->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-600">{{ ucwords(str_replace('_', ' ', (string) ($loan->loan_type ?? '—'))) }}</td>
                            <td class="px-6 py-4 text-sm font-extrabold text-slate-900">{{ $peso($loan->loan_amount ?? 0) }}</td>
                            <td class="px-6 py-4">
                                <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-6 text-sm text-slate-500" colspan="4">No loan applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-v2-layout>
