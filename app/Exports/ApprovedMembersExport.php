<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ApprovedMembersExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $members;

    public function __construct(Collection $members)
    {
        $this->members = $members;
    }

    public function collection()
    {
        return $this->members;
    }

    public function headings(): array
    {
        return ['Employees ID', 'Name', 'Office', 'Status', 'Date Approved', 'Date Inactive', 'Date Reactive'];
    }

    public function map($member): array
    {
        return [
            $member->id,
            $member->name,
            $member->office,
            $member->status,
            $this->formatDate($member->date_approved),
            $this->formatDate($member->date_inactive),
            $this->formatDate($member->date_reactive),
        ];
    }

    private function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('F d, Y') : 'N/A';
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFB6D7A8'], // light green background
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Approved Members';
    }
}
