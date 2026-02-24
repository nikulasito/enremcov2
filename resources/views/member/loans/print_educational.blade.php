<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; margin: 18px; }
        .center { text-align: center; }
        .right { text-align: right; }
        .small { font-size: 11px; }
        .xsmall { font-size: 10px; }
        .title { font-size: 16px; font-weight: 700; margin: 6px 0 12px; }
        .box { border: 1px solid #222; padding: 10px; }
        .line { border-bottom: 1px solid #222; display: inline-block; min-width: 190px; line-height: 1.2; }
        .line-sm { border-bottom: 1px solid #222; display: inline-block; min-width: 120px; line-height: 1.2; }
        .line-lg { border-bottom: 1px solid #222; display: inline-block; min-width: 300px; line-height: 1.2; }
        .sigline { border-bottom: 1px solid #222; width: 260px; display: inline-block; height: 16px; }
        .mt-4 { margin-top: 4px; } .mt-8 { margin-top: 8px; } .mt-10 { margin-top: 10px; }
        .mt-12 { margin-top: 12px; } .mt-16 { margin-top: 16px; } .mt-20 { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        .plain td, .plain th { padding: 4px 6px; vertical-align: top; }
        .bordered { border: 1px solid #222; }
        .bordered td, .bordered th { border: 1px solid #222; padding: 6px; vertical-align: top; }
        .justify { text-align: justify; line-height: 1.35; }
    </style>
</head>

<body>
    @php
        $loanAmount = (float) ($app->loan_amount ?? 0);
        $old = (float) ($app->old_balance ?? 0);
        $lpp = (float) ($app->lpp ?? 0);
        $int = (float) ($app->interest ?? 0);
        $fee = (float) ($app->handling_fee ?? 0);
        $pet = (float) ($app->petty_cash_loan ?? 0);
        $ded = $old + $lpp + $int + $fee + $pet;
        $net = $loanAmount - $ded;

        $loanVoucherNo = $app->application_no ?? '-';
        $beneficiary = $app->beneficiary_name ?? $app->full_name ?? '';
        $schoolName = $app->school_name ?? '';
        $program = $app->school_program ?? '';
        $schoolYear = $app->school_year ?? '';
        $semester = $app->semester ?? '';
    @endphp

    <div class="center small">
        <div>REPUBLIC OF THE PHILIPPINES</div>
        <div>Department of Environment and Natural Resources</div>
        <div style="font-weight:700;">ENVIRONMENT AND NATURAL RESOURCES MULTI-PURPOSE CREDIT COOPERATIVE</div>
        <div>Puntod, Cagayan de Oro City</div>
    </div>

    <div class="center title">EDUCATIONAL LOAN</div>

    <table class="plain">
        <tr>
            <td style="width:60%;"><b>NAME:</b> <span class="line">{{ strtoupper($app->full_name ?? '') }}</span></td>
            <td style="width:40%;"><b>L.V NO.:</b> <span class="line-sm">{{ $loanVoucherNo }}</span></td>
        </tr>
        <tr>
            <td><b>ADDRESS:</b> <span class="line">{{ $app->address ?? '-' }}</span></td>
            <td><b>AMOUNT OF LOAN:</b> <span class="line-sm">P{{ number_format($loanAmount, 2) }}</span></td>
        </tr>
    </table>

    <div class="box mt-8">
        <div class="small" style="font-weight:700;">EDUCATIONAL DETAILS</div>
        <table class="plain mt-4">
            <tr>
                <td style="width:50%;"><b>Beneficiary:</b> <span class="line">{{ $beneficiary }}</span></td>
                <td style="width:50%;"><b>School:</b> <span class="line">{{ $schoolName }}</span></td>
            </tr>
            <tr>
                <td><b>Program/Course:</b> <span class="line">{{ $program }}</span></td>
                <td><b>School Year/Sem:</b> <span class="line">{{ trim($schoolYear . ' ' . $semester) }}</span></td>
            </tr>
        </table>
    </div>

    <div class="box mt-8">
        <table class="bordered">
            <tr>
                <th align="left">Kind of Loan</th>
                <th align="right" style="width:32%;">Amount</th>
            </tr>
            <tr>
                <td><b>Loan (Specify) Educational Loan</b></td>
                <td align="right"><b>P{{ number_format($loanAmount, 2) }}</b></td>
            </tr>
            <tr><td colspan="2" class="small"><b>Less: Deductions</b></td></tr>
            <tr><td>Balance (Old Bal)</td><td align="right">P{{ number_format($old, 2) }}</td></tr>
            <tr><td>LPP</td><td align="right">P{{ number_format($lpp, 2) }}</td></tr>
            <tr><td>Interest (4.5%)</td><td align="right">P{{ number_format($int, 2) }}</td></tr>
            <tr><td>Handling Fee</td><td align="right">P{{ number_format($fee, 2) }}</td></tr>
            <tr><td>Petty Cash Loan</td><td align="right">P{{ number_format($pet, 2) }}</td></tr>
            <tr><td><b>Total Deduction</b></td><td align="right"><b>P{{ number_format($ded, 2) }}</b></td></tr>
            <tr><td><b>NET CASH RECEIVED</b></td><td align="right"><b>P{{ number_format($net, 2) }}</b></td></tr>
        </table>
    </div>

    <div class="justify small mt-12">
        I certify that this educational loan shall be used for tuition, school fees, books, and related educational
        expenses only. I agree to submit supporting school documents when required by ENREMCO.
    </div>

    <div class="mt-16">
        <table class="plain">
            <tr>
                <td class="center" style="width:50%;">
                    <span class="sigline"></span><br>
                    <span class="small">Applicant Signature over Printed Name</span>
                </td>
                <td class="center" style="width:50%;">
                    <span class="sigline"></span><br>
                    <span class="small">Credit Committee / Approver</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-20 xsmall center">Educational Loan Printable Form</div>
</body>

</html>
