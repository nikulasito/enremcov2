<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WithdrawTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        // Same columns as your Withdraw page
        return [
            'employees_id',        // Member ID
            'name',                // Name
            'office',              // Office
            'date_of_withdrawal',  // Date of Withdrawal
            'amount_withdrawn',    // Amount Withdrawn
            'latest_withdraw',     // Computed, view-only
            'total_withdraw',      // Computed, view-only
        ];
    }

    public function array(): array
    {
        // Return an empty array → no sample data, just headers
        return [];
    }
}
