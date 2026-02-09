{{-- resources/views/admin/savings/_modals_tailwind.blade.php --}}

{{-- Update Savings Modal (Tailwind) --}}
<div id="updateDetailsModal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-[#0d1a14]/80 backdrop-blur-sm" data-close-modal="updateDetailsModal"></div>

    <div class="relative min-h-screen w-full flex items-center justify-center p-4">
        <div
            class="bg-white dark:bg-[#112119] w-full max-w-3xl rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] flex flex-col max-h-[90vh]">
            {{-- Header --}}
            <div class="px-8 py-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-black text-[#111814] dark:text-white uppercase tracking-tight">
                        Update Savings
                    </h2>
                    <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Member Financial Management Profile</p>
                </div>

                <button type="button" class="text-[#638875] hover:text-red-500 transition-colors"
                    data-close-modal="updateDetailsModal">
                    <span class="material-symbols-outlined text-2xl">close</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                {{-- Member Summary Card --}}
                <div
                    class="bg-[#f6f8f7] dark:bg-[#1a2e24] rounded-xl p-6 border border-[#dce5e0] dark:border-[#2a3a32] mb-8">
                    <div class="flex items-center gap-4 mb-6 border-b border-[#dce5e0] dark:border-[#2a3a32] pb-4">
                        <div class="size-14 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-3xl">person</span>
                        </div>
                        <div>
                            <p id="upd_memberIdLine"
                                class="text-[10px] font-bold text-primary uppercase tracking-widest">
                                Member ID: —
                            </p>
                            <h3 id="upd_memberName" class="text-xl font-bold text-[#111814] dark:text-white">—</h3>
                            <p id="upd_memberOffice" class="text-sm text-[#638875] dark:text-[#a0b0a8]">—</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Monthly
                                Savings</p>
                            <p id="upd_monthlyShare" class="text-lg font-black text-[#111814] dark:text-white">—</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Initial
                                Remittance</p>
                            <p id="upd_firstRemittance" class="text-lg font-black text-[#111814] dark:text-white">—</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Latest
                                Remittance</p>
                            <p id="upd_latestRemittance" class="text-lg font-black text-primary">—</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Total
                                Savings</p>
                            <p id="upd_totalShares" class="text-lg font-black text-[#111814] dark:text-white">—</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase mb-1">Months
                                Contributed</p>
                            <p id="upd_monthsContributed" class="text-lg font-black text-[#111814] dark:text-white">—
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Search --}}
                <div class="space-y-4">
                    <h4 class="text-sm font-bold text-[#111814] dark:text-white uppercase tracking-wider">
                        Search Remittance Records
                    </h4>

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

                    <p class="text-[11px] text-[#638875] dark:text-[#a0b0a8]">
                        Leave blank to view all historical records for this member.
                    </p>
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

                <button type="button" id="saveRemittanceChangesBtn"
                    class="px-6 py-2.5 rounded-lg text-sm font-bold bg-[#112119] dark:bg-white text-white dark:text-[#112119] hover:opacity-90 transition-all hidden">
                    Update Ledger
                </button>
            </div>
        </div>
    </div>
</div>

{{-- View Savings Modal (Tailwind) --}}
<div id="viewDetailsModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" data-close-modal="viewDetailsModal"></div>

    <div class="relative min-h-screen w-full flex items-center justify-center p-4">
        <div
            class="w-full max-w-2xl bg-white dark:bg-[#1a2e24] rounded-2xl shadow-2xl border border-[#dce5e0] dark:border-[#2a3a32] overflow-hidden">
            <div class="p-6 border-b border-[#dce5e0] dark:border-[#2a3a32] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">receipt_long</span>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#111814] dark:text-white">View Savings</h3>
                        <p class="text-xs font-medium text-[#638875] dark:text-[#a0b0a8]">Member Financial Management
                        </p>
                    </div>
                </div>

                <button type="button"
                    class="size-10 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-white/10 text-[#638875] dark:text-[#a0b0a8] transition-colors"
                    data-close-modal="viewDetailsModal">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-2 gap-x-8 gap-y-6 mb-8">
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider mb-1.5">Member
                            ID</label>
                        <div id="modalEmployeeID"
                            class="px-4 py-2.5 bg-[#f6f8f7] dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-lg text-sm font-bold text-[#111814] dark:text-white">
                            —</div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider mb-1.5">Member
                            Name</label>
                        <div id="modalName"
                            class="px-4 py-2.5 bg-[#f6f8f7] dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-lg text-sm font-bold text-[#111814] dark:text-white">
                            —</div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider mb-1.5">Office</label>
                        <div id="modalOffice"
                            class="px-4 py-2.5 bg-[#f6f8f7] dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-lg text-sm font-bold text-[#111814] dark:text-white">
                            —</div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider mb-1.5">Monthly
                            Savings</label>
                        <div id="modalContribution"
                            class="px-4 py-2.5 bg-[#f6f8f7] dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-lg text-sm font-bold text-[#111814] dark:text-white">
                            —</div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider mb-1.5">Initial
                            Remittance</label>
                        <div id="modalFirstRemittance"
                            class="px-4 py-2.5 bg-[#f6f8f7] dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-lg text-sm font-bold text-[#111814] dark:text-white">
                            —</div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider mb-1.5">Latest
                            Remittance</label>
                        <div id="modalLatestRemittance"
                            class="px-4 py-2.5 bg-[#f6f8f7] dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-lg text-sm font-bold text-[#111814] dark:text-white">
                            —</div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider mb-1.5">Total
                            Savings</label>
                        <div id="modalTotalShares"
                            class="px-4 py-2.5 bg-primary/10 border border-primary/20 rounded-lg text-sm font-black text-primary">
                            —</div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider mb-1.5">Months
                            Contributed</label>
                        <div id="modalMonthsContributed"
                            class="px-4 py-2.5 bg-[#f6f8f7] dark:bg-[#0d1a14] border border-[#dce5e0] dark:border-[#2a3a32] rounded-lg text-sm font-bold text-[#111814] dark:text-white">
                            —</div>
                    </div>
                </div>

                <div
                    class="bg-[#f6f8f7] dark:bg-[#0d1a14] p-6 rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] flex items-end gap-4">
                    <div class="flex-1">
                        <label class="block text-[10px] font-black text-primary uppercase tracking-[0.2em] mb-2">Filter
                            Contribution Year</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#638875] dark:text-[#a0b0a8] text-xl">calendar_today</span>
                            <input id="yearFilter" type="number" min="1900" max="2100"
                                placeholder="Enter Year (e.g. 2023)"
                                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-[#1a2e24] border border-[#dce5e0] dark:border-[#2a3a32] rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary placeholder:text-gray-400">
                        </div>
                    </div>

                    <button id="viewYearContributions" type="button"
                        class="px-8 py-3 bg-primary text-[#112119] font-black rounded-lg shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined">visibility</span>
                        View Savings
                    </button>
                </div>

                <div id="contributionsResult"
                    class="mt-6 rounded-xl border border-[#dce5e0] dark:border-[#2a3a32] overflow-hidden"></div>
            </div>

            <div
                class="px-8 py-4 bg-[#f6f8f7] dark:bg-[#0d1a14]/50 border-t border-[#dce5e0] dark:border-[#2a3a32] flex justify-end">
                <button type="button"
                    class="px-6 py-2 text-sm font-bold text-[#638875] dark:text-[#a0b0a8] hover:text-[#111814] dark:hover:text-white transition-colors"
                    data-close-modal="viewDetailsModal">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>