@foreach($members as $member)
    @if($member->is_admin != 1)
        @php
            $uid = $member->id;

            // Use precomputed totals from controller (FAST)
            $totalSavings = (float) ($savingTotals[$uid] ?? 0);
            $withdrawn = (float) ($withdrawTotals[$uid] ?? 0);
            $balance = $totalSavings - $withdrawn;

            $monthsContributed = (int) ($monthsContributedByUser[$uid] ?? 0);
            $firstRemittance = $firstRemittanceByUser[$uid] ?? null;
            $latestRemittance = $latestRemittanceByUser[$uid] ?? null;
            $lastUpdated = $latestUpdatedByUser[$uid] ?? null;

            // Values for datasets (no commas, safe for JS)
            $dsTotal = number_format($totalSavings, 2, '.', '');
            $dsWithdraw = number_format($withdrawn, 2, '.', '');
            $dsBalance = number_format($balance, 2, '.', '');

            // Display formatting
            $dispTotal = number_format($totalSavings, 2);
            $dispWithdraw = number_format($withdrawn, 2);
            $dispBalance = number_format($balance, 2);
        @endphp

        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
            <td class="px-6 py-4">
                <input type="checkbox" class="memberCheckbox" value="{{ $uid }}">
            </td>

            <td class="px-6 py-4 font-medium">
                {{ ($members->currentPage() - 1) * $members->perPage() + $loop->iteration }}
            </td>

            <td class="px-6 py-4 font-black text-primary">{{ $member->employee_ID }}</td>
            <td class="px-6 py-4 font-black">{{ $member->name }}</td>
            <td class="px-6 py-4">{{ $member->office }}</td>

            <td class="px-6 py-4">{{ $member->savings ?? '—' }}</td>

            <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">
                {{ $firstRemittance ? \Carbon\Carbon::parse($firstRemittance)->format('Y-m-d') : '—' }}
            </td>

            <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">
                {{ $latestRemittance ? \Carbon\Carbon::parse($latestRemittance)->format('Y-m-d') : '—' }}
            </td>

            <td class="px-6 py-4 font-black">{{ $dispTotal }}</td>
            <td class="px-6 py-4">{{ $dispWithdraw }}</td>
            <td class="px-6 py-4 font-black">{{ $dispBalance }}</td>

            <td class="px-6 py-4">{{ $monthsContributed }}</td>

            <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">
                {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->format('Y-m-d') : '—' }}
            </td>

            <td class="px-6 py-4">
                <div class="flex flex-col gap-2 min-w-0">
                    <button type="button"
                        class="w-40 inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-black text-[#112119] hover:brightness-110 transition"
                        data-open-modal="updateDetailsModal" data-id="{{ $uid }}" data-employee_id="{{ $member->employee_ID }}"
                        data-name="{{ $member->name }}" data-office="{{ $member->office }}"
                        data-contribution="{{ $member->savings ?? 0 }}"
                        data-first-remittance="{{ $firstRemittance ? \Carbon\Carbon::parse($firstRemittance)->format('Y-m-d') : '—' }}"
                        data-latest-remittance="{{ $latestRemittance ? \Carbon\Carbon::parse($latestRemittance)->format('Y-m-d') : '—' }}"
                        data-total-savings="{{ $dsTotal }}" data-withdrawn="{{ $dsWithdraw }}" data-balance="{{ $dsBalance }}"
                        data-months-contributed="{{ $monthsContributed }}">
                        Update
                    </button>

                    <button type="button"
                        class="w-40 inline-flex items-center justify-center rounded-lg bg-[#112119] px-4 py-2 text-sm font-black text-white hover:opacity-90 transition"
                        data-open-modal="viewDetailsModal" data-id="{{ $uid }}" data-employee_id="{{ $member->employee_ID }}"
                        data-name="{{ $member->name }}" data-office="{{ $member->office }}"
                        data-contribution="{{ $member->savings ?? 0 }}"
                        data-first-remittance="{{ $firstRemittance ? \Carbon\Carbon::parse($firstRemittance)->format('Y-m-d') : '—' }}"
                        data-latest-remittance="{{ $latestRemittance ? \Carbon\Carbon::parse($latestRemittance)->format('Y-m-d') : '—' }}"
                        data-total-savings="{{ $dsTotal }}" data-balance="{{ $dsBalance }}"
                        data-months-contributed="{{ $monthsContributed }}">
                        View Savings
                    </button>
                </div>
            </td>
        </tr>
    @endif
@endforeach