<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoanTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return []; // Empty row
    }

    public function headings(): array
    {
        return [
            'employee_ID',
            'loan_type',
            'loan_amount',
            'interest_rate',
            'interest',
            'terms',
            'monthly_payment',
            'date_applied',
            'date_approved',
            'total_net',
            'old_balance',
            'lpp',
            'handling_fee',
            'total_deduction',
            'petty_cash_loan',
            'co_maker_name',
            'co_maker_position',
            'co_maker2_name',
            'co_maker2_position',
            'remarks',
        ];
    }
}
