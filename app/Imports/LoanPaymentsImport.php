<?php

namespace App\Imports;

use App\Models\LoanPayment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LoanPaymentsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $duplicates = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            if (
                empty($row['remittance_no']) ||
                empty($row['date_of_remittance']) ||
                empty($row['date_covered_month']) ||
                empty($row['date_covered_year']) ||
                empty($row['total_payments'])
            ) {
                throw new \Exception("❌ Missing required field(s) in row {$rowNumber}.");
            }
        }

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;

            $loanId = !empty($row['loan_id'])
                ? trim((string)$row['loan_id'])
                : $this->generateLoanId();

            // duplicate check using your table structure
            $exists = LoanPayment::where('loan_id', $loanId)
                ->where('remittance_no', trim((string)$row['remittance_no']))
                ->where('date_covered_month', trim((string)$row['date_covered_month']))
                ->where('date_covered_year', trim((string)$row['date_covered_year']))
                ->exists();

            if ($exists) {
                $duplicates[] =
                    "Duplicate: Loan ID {$loanId}, Remittance No {$row['remittance_no']}, " .
                    "Covered {$row['date_covered_month']}/{$row['date_covered_year']} (Row {$rowNumber})";
                continue;
            }

            LoanPayment::create([
                'loan_id'              => $loanId,
                'remittance_no'        => trim((string)$row['remittance_no']),
                'date_of_remittance'   => $this->normalizeDate($row['date_of_remittance']),
                'date_covered_month'   => trim((string)$row['date_covered_month']),
                'date_covered_year'    => trim((string)$row['date_covered_year']),
                'total_payments'       => (float)$row['total_payments'],

                // REQUIRED/USEFUL defaults based on your table screenshot:
                'total_payments_count' => 1,
                'latest_remittance'    => $this->normalizeDate($row['date_of_remittance']),
                'outstanding_balance'  => 0,
            ]);
        }

        if (!empty($duplicates)) {
            throw new \Exception("⚠️ Upload finished BUT with duplicates skipped:\n" . implode("\n", $duplicates));
        }
    }

    private function generateLoanId(): string
    {
        $date = date('Ymd');

        do {
            $rand = mt_rand(1000, 9999);
            $loanId = "LN-{$date}-{$rand}";
        } while (LoanPayment::where('loan_id', $loanId)->exists());

        return $loanId;
    }

    private function normalizeDate($value): string
    {
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        if (is_numeric($value)) {
            $unix = ((int)$value - 25569) * 86400;
            return gmdate('Y-m-d', $unix);
        }

        return date('Y-m-d');
    }
}
