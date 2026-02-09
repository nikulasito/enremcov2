<?php

namespace App\Imports;

use App\Models\Withdraw;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class WithdrawImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // REQUIRED columns coming from the sheet
        // employees_id here is the *employee number* in your template
        if (empty($row['employees_id']) || empty($row['name'])
            || empty($row['date_of_withdrawal']) || empty($row['amount_withdrawn'])) {
            throw new \Exception("❌ Missing required fields on row with employees_id: ".($row['employees_id'] ?? 'N/A'));
        }

        // 1) Find the user by their *employee number* (employee_ID in users table)
        $user = User::where('employee_ID', $row['employees_id'])->first();

        if (!$user) {
            // If no match, fail fast so you can fix the sheet value
            throw new \Exception("❌ No user found with employee_ID: {$row['employees_id']}");
        }

        // 2) Store the *numeric* users.id in withdrawals.employees_id
        return new Withdraw([
            'employees_id'       => $user->id,  // IMPORTANT: use numeric users.id
            'name'               => $row['name'],
            'office'             => $row['office'] ?? null,
            'date_of_withdrawal' => $this->formatDate($row['date_of_withdrawal']),
            'amount_withdrawn'   => (float) $row['amount_withdrawn'],
            'reference_no'       => $row['reference_no'] ?? null,
            'covered_month'      => isset($row['covered_month']) ? (int)$row['covered_month'] : null,
            'covered_year'       => isset($row['covered_year']) ? (int)$row['covered_year'] : null,
            'remarks'            => $row['remarks'] ?? null,
            'date_created'       => now()->format('Y-m-d'),
        ]);
    }

    private function formatDate($excelOrIsoDate)
    {
        try {
            return is_numeric($excelOrIsoDate)
                ? Carbon::instance(Date::excelToDateTimeObject($excelOrIsoDate))->format('Y-m-d')
                : Carbon::parse($excelOrIsoDate)->format('Y-m-d');
        } catch (\Exception $e) {
            return now()->toDateString();
        }
    }
}
