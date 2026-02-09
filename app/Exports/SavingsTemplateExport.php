<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SavingsTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return []; // Empty row
    }

    public function headings(): array
    {
        return [
            'employees_id', 'name', 'date', 'date_remittance', 'remittance_no',
            'covered_month', 'covered_year', 'amount', 'office', 'date_created', 'date_updated'
        ];
    }
}
