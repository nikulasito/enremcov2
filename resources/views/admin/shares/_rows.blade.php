@php
    $shareTotals = $shareTotals ?? collect();
    $monthsContributedByUser = $monthsContributedByUser ?? collect();
    $firstRemittanceByUser = $firstRemittanceByUser ?? collect();
    $latestRemittanceByUser = $latestRemittanceByUser ?? collect();
    $latestUpdatedByUser = $latestUpdatedByUser ?? collect();
@endphp

@forelse($members as $member)
    @php
        $totalShares = (float) ($shareTotals[$member->id] ?? \App\Models\Share::where('employees_id', $member->id)->sum('amount'));
        $monthsContributed = (int) ($monthsContributedByUser[$member->id] ?? \App\Models\Share::where('employees_id', $member->id)->whereNotNull('covered_month')->count());
        $firstRemittance = $firstRemittanceByUser[$member->id] ?? \App\Models\Share::where('employees_id', $member->id)->orderBy('date_remittance', 'asc')->value('date_remittance');
        $latestRemittance = $latestRemittanceByUser[$member->id] ?? \App\Models\Share::where('employees_id', $member->id)->orderBy('date_remittance', 'desc')->value('date_remittance');
        $lastUpdated = $latestUpdatedByUser[$member->id] ?? \App\Models\Share::where('employees_id', $member->id)->max('date_updated');
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
@empty
    <tr>
        <td colspan="12" class="px-6 py-8 text-center text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
            No members found.
        </td>
    </tr>
@endforelse
