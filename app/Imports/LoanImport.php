<?php

namespace App\Imports;

use App\Models\LoanDetail;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use App\Models\LoanPayment;

class LoanImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            $rowNum = $i + 2; // header is row 1

            if (empty($row['employee_id'])) {
                throw new \Exception("❌ Missing employee_id in row {$rowNum}.");
            }

            $user = User::where('employee_ID', $row['employee_id'])->first();
            if (!$user) {
                throw new \Exception("❌ No user found with employee_ID '{$row['employee_id']}' (row {$rowNum}).");
            }

            $loanID = 'LN-' . now()->format('Ymd') . '-' . rand(1000, 9999);

            LoanDetail::create([
                'loan_id' => $loanID,
                'employee_ID' => $user->employee_ID,
                'loan_type' => $row['loan_type'],
                'loan_amount' => $row['loan_amount'],
                'interest_rate' => $row['interest_rate'],
                'interest' => $row['interest'],
                'terms' => $row['terms'],
                'monthly_payment' => $row['monthly_payment'],
                'date_applied' => $this->formatDate($row['date_applied']),
                'date_approved' => $this->formatDate($row['date_approved']),
                'total_net' => $row['total_net'],
                'old_balance' => $row['old_balance'],
                'lpp' => $row['lpp'],
                'handling_fee' => $row['handling_fee'],
                'total_deduction' => $row['total_deduction'],
                'petty_cash_loan' => $row['petty_cash_loan'],
                'co_maker_name' => $row['co_maker_name'],
                'co_maker_position' => $row['co_maker_position'],
                'co_maker2_name' => $row['co_maker2_name'],
                'co_maker2_position' => $row['co_maker2_position'],
                'remarks' => $row['remarks'],
            ]);

            LoanPayment::create([
                'loan_id' => $loanID,
                'total_payments_count' => 0,
                'total_payments' => 0,
                'outstanding_balance' => $row['loan_amount'],
                'latest_payment' => null,
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
