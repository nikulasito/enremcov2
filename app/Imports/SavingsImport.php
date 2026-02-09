<?php

namespace App\Imports;

use App\Models\Saving;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SavingsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $duplicates = [];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2; // header is row 1

            // Basic required fields from your template
            if (
                empty($row['employees_id']) || empty($row['name']) ||
                empty($row['remittance_no']) || empty($row['covered_month']) || empty($row['covered_year'])
            ) {
                throw new \Exception("❌ Missing required field(s) in row {$rowNum}.");
            }

            // Map employee number (string) -> users.id (int)
            $employeeNumber = trim((string)$row['employees_id']);
            $user = User::where('employee_ID', $employeeNumber)->first();
            if (!$user) {
                throw new \Exception("❌ No user found with employee_ID '{$employeeNumber}' (row {$rowNum}).");
            }

            // Normalize values
            $coveredMonth = (int)$row['covered_month'];
            $coveredYear  = (int)$row['covered_year'];
            $amount       = (float)($row['amount'] ?? 0);

            // Duplicate check (per your rules)
            $exists = Saving::where('employees_id', $user->id)
                ->where('name', $row['name'])
                ->where('remittance_no', $row['remittance_no'])
                ->where('covered_month', $coveredMonth)
                ->where('covered_year', $coveredYear)
                ->exists();

            if ($exists) {
                $duplicates[] = "Duplicate: EmpNo {$employeeNumber}, Name: {$row['name']}, Remittance No: {$row['remittance_no']}, Month: {$coveredMonth}, Year: {$coveredYear} (Row {$rowNum})";
            }
        }

        if (!empty($duplicates)) {
            throw new \Exception("⚠️ Upload aborted due to duplicates:\n" . implode("\n", $duplicates));
        }

        // If no duplicates, insert
        foreach ($rows as $i => $row) {
            $employeeNumber = trim((string)$row['employees_id']);
            $user = User::where('employee_ID', $employeeNumber)->first(); // safe due to earlier check

            Saving::create([
                'employees_id'    => $user->id, // <-- store users.id (INT)
                'name'            => $row['name'],
                'date'            => now()->toDateString(),
                'date_remittance' => $this->formatDate($row['date_remittance'] ?? null),
                'remittance_no'   => $row['remittance_no'],
                'covered_month'   => (int)$row['covered_month'],
                'covered_year'    => (int)$row['covered_year'],
                'amount'          => (float)($row['amount'] ?? 0),
                'office'          => $row['office'] ?? null,
                'date_created'    => now(),
            ]);
        }
    }

    private function formatDate($val)
    {
        if ($val === null || $val === '') {
            return now()->toDateString();
        }
        try {
            // excel serial?
            if (is_numeric($val)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($val))->format('Y-m-d');
            }
            // string date
            return Carbon::parse($val)->format('Y-m-d');
        } catch (\Throwable $e) {
            return now()->toDateString();
        }
    }
}
