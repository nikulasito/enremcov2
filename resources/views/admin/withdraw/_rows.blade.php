@php $i = $members->firstItem() ?? 1; @endphp

@forelse($members as $m)
    @php
        $uid = $m->id;
        $totalWithdraw = (float) ($withdrawTotals[$uid] ?? 0);
        $latestWithdraw = $latestWithdrawByUser[$uid] ?? null;
    @endphp

    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors memberRow" data-name="{{ strtolower($m->name) }}"
        data-id="{{ strtolower($m->employee_ID) }}" data-office="{{ strtolower($m->office) }}">

        <td class="px-6 py-4">
            <input type="checkbox" class="memberCheckbox" value="{{ $m->id }}">
        </td>

        <td class="px-6 py-4 font-medium">{{ $i++ }}</td>

        <td class="px-6 py-4 font-black text-primary">{{ $m->employee_ID }}</td>
        <td class="px-6 py-4 font-black">{{ $m->name }}</td>
        <td class="px-6 py-4">{{ $m->office }}</td>

        <td class="px-6 py-4 text-[#638875] dark:text-[#a0b0a8]">
            {{ $latestWithdraw ?? '—' }}
        </td>

        <td class="px-6 py-4 font-black">
            {{ number_format($totalWithdraw, 2) }}
        </td>

        <td class="px-6 py-4">
            <button type="button"
                class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-xs font-black text-[#112119] hover:brightness-110 transition view-withdrawals-btn"
                data-open-modal="viewWithdrawalsModal" data-id="{{ $m->id }}" data-employee_id="{{ $m->employee_ID }}"
                data-name="{{ $m->name }}" data-office="{{ $m->office }}">
                View / Edit
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-6 py-10 text-center text-sm font-bold text-[#638875] dark:text-[#a0b0a8]">
            No records found
        </td>
    </tr>
@endforelse