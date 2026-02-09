<?php

namespace App\Imports;

use App\Models\Share;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class SharesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Preload users for fast lookups
        $users = User::select('id', 'employee_ID', 'name', 'office')->get();

        // Maps for lookups
        $byEmpCode = $users->keyBy(function ($u) {
            return strtoupper(trim((string)$u->employee_ID));
        });

        $byNameOffice = $users->keyBy(function ($u) {
            return mb_strtolower(trim($u->name)).'|'.mb_strtolower(trim((string)$u->office));
        });

        $errors     = [];
        $duplicates = [];
        $toInsert   = [];

        // Build a set to detect in-file duplicates (same user + remit + month/year)
        $inFileKeys = [];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2; // account for heading row

            // Normalize incoming values
            $empCode        = strtoupper(trim((string)($row['employees_id'] ?? '')));
            $name           = trim((string)($row['name'] ?? ''));
            $office         = trim((string)($row['office'] ?? ''));
            $remittanceNo   = trim((string)($row['remittance_no'] ?? ''));
            $coveredMonth   = (string)($row['covered_month'] ?? '');
            $coveredYear    = (string)($row['covered_year'] ?? '');
            $amountRaw      = $row['amount'] ?? null;
            $dateRemitCell  = $row['date_remittance'] ?? null;

            // Required fields present?
            if ($empCode === '' && ($name === '' || $office === '')) {
                $errors[] = "Row {$rowNum}: Missing employee identifier. Provide employees_id or name+office.";
                continue;
            }
            if ($remittanceNo === '') {
                $errors[] = "Row {$rowNum}: Missing remittance_no.";
                continue;
            }
            if ($coveredMonth === '' || $coveredYear === '') {
                $errors[] = "Row {$rowNum}: Missing covered_month/covered_year.";
                continue;
            }

            // Normalize month to 1..12 (allow names like 'September')
            $monthNum = $this->normalizeMonth($coveredMonth);
            if ($monthNum === null) {
                $errors[] = "Row {$rowNum}: Invalid covered_month '{$coveredMonth}'.";
                continue;
            }

            // Normalize year
            $yearNum = (int)$coveredYear;
            if ($yearNum < 1900 || $yearNum > (int)date('Y') + 1) {
                $errors[] = "Row {$rowNum}: Invalid covered_year '{$coveredYear}'.";
                continue;
            }

            // Normalize amount
            if ($amountRaw === null || $amountRaw === '') {
                $errors[] = "Row {$rowNum}: Missing amount.";
                continue;
            }
            if (!is_numeric($amountRaw)) {
                $errors[] = "Row {$rowNum}: Amount is not numeric ('{$amountRaw}').";
                continue;
            }
            $amount = (float)$amountRaw;
            if ($amount <= 0) {
                $errors[] = "Row {$rowNum}: Amount must be > 0.";
                continue;
            }

            // Resolve date_remittance
            $dateRemittance = $this->formatDate($dateRemitCell);

            // Resolve the user: prefer employee_ID, else name+office
            $user = null;
            if ($empCode !== '') {
                $user = $byEmpCode->get($empCode);
            }
            if (!$user && $name !== '' && $office !== '') {
                $key  = mb_strtolower($name).'|'.mb_strtolower($office);
                $user = $byNameOffice->get($key);
            }

            if (!$user) {
                $errors[] = "Row {$rowNum}: Cannot find user (employees_id='{$empCode}', name='{$name}', office='{$office}').";
                continue;
            }

            // Detect in-file duplicates
            $inKey = $user->id.'|'.$remittanceNo.'|'.$monthNum.'|'.$yearNum;
            if (isset($inFileKeys[$inKey])) {
                $duplicates[] = "Row {$rowNum}: Duplicate in file for user id {$user->id}, remit {$remittanceNo}, {$monthNum}/{$yearNum}.";
                continue;
            }
            $inFileKeys[$inKey] = true;

            // Detect DB duplicates (same user + remit + month/year)
            $exists = Share::where('employees_id', $user->id)
                ->where('remittance_no', $remittanceNo)
                ->where('covered_month', $monthNum)
                ->where('covered_year', $yearNum)
                ->exists();

            if ($exists) {
                $duplicates[] = "Row {$rowNum}: Already exists in DB for user id {$user->id}, remit {$remittanceNo}, {$monthNum}/{$yearNum}.";
                continue;
            }

            $toInsert[] = [
                'employees_id'    => $user->id,          // ✅ numeric FK to users.id
                'name'            => $user->name,        // keep a snapshot if you want
                'date'            => now()->toDateString(),
                'date_remittance' => $dateRemittance,
                'remittance_no'   => $remittanceNo,
                'covered_month'   => $monthNum,
                'covered_year'    => $yearNum,
                'amount'          => $amount,
                'office'          => $user->office ?? $office,
                'date_created'    => now(),
            ];
        }

        // If any errors or duplicates, abort with a combined message
        if (!empty($errors) || !empty($duplicates)) {
            $msg = [];
            if ($errors) {
                $msg[] = "❌ Errors:";
                $msg[] = implode("\n", $errors);
            }
            if ($duplicates) {
                $msg[] = "⚠️ Duplicates:";
                $msg[] = implode("\n", $duplicates);
            }
            throw new \Exception(implode("\n\n", $msg));
        }

        if (!empty($toInsert)) {
            // Bulk insert for speed
            Share::insert($toInsert);
        }
    }

    private function formatDate($excelDate): string
    {
        if ($excelDate === null || $excelDate === '') {
            return now()->toDateString();
        }
        // Accept either Excel serial or a string date
        try {
            if (is_numeric($excelDate)) {
                return Carbon::instance(Date::excelToDateTimeObject($excelDate))->format('Y-m-d');
            }
            return Carbon::parse((string)$excelDate)->format('Y-m-d');
        } catch (\Throwable $e) {
            return now()->toDateString();
        }
    }

    private function normalizeMonth(string $val): ?int
    {
        $val = trim($val);
        if ($val === '') return null;

        // Numeric (1..12)
        if (ctype_digit($val)) {
            $n = (int)$val;
            return ($n >= 1 && $n <= 12) ? $n : null;
        }

        // Try month name
        $ts = strtotime("1 ".$val." 2000");
        if ($ts !== false) {
            $n = (int)date('n', $ts);
            return ($n >= 1 && $n <= 12) ? $n : null;
        }

        return null;
    }
}
