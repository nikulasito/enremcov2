<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoanPaymentsTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'loan_id',
            'employee_ID',
            'name',
            'office',
            'loan_type',
            'remittance_no',
            'date_of_remittance',
            'date_covered_month',
            'date_covered_year',
            'total_payments',
        ];
    }

    public function array(): array
    {
        // empty template rows
        return [];
    }
}
