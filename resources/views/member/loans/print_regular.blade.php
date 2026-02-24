<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
            margin: 18px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .muted {
            color: #444;
        }

        .small {
            font-size: 11px;
        }

        .xsmall {
            font-size: 10px;
        }

        .title {
            font-size: 16px;
            font-weight: 700;
            margin: 6px 0 12px;
        }

        .subtitle {
            font-size: 13px;
            font-weight: 700;
            margin: 4px 0 8px;
        }

        .box {
            border: 1px solid #222;
            padding: 10px;
        }

        .line {
            border-bottom: 1px solid #222;
            display: inline-block;
            min-width: 180px;
            line-height: 1.2;
        }

        .line-sm {
            border-bottom: 1px solid #222;
            display: inline-block;
            min-width: 110px;
            line-height: 1.2;
        }

        .line-lg {
            border-bottom: 1px solid #222;
            display: inline-block;
            min-width: 300px;
            line-height: 1.2;
        }

        .sigline {
            border-bottom: 1px solid #222;
            width: 260px;
            display: inline-block;
            height: 16px;
        }

        .hr {
            height: 1px;
            background: #222;
            margin: 10px 0;
        }

        .mt-4 {
            margin-top: 4px;
        }

        .mt-6 {
            margin-top: 6px;
        }

        .mt-8 {
            margin-top: 8px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mt-12 {
            margin-top: 12px;
        }

        .mt-16 {
            margin-top: 16px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mb-4 {
            margin-bottom: 4px;
        }

        .mb-8 {
            margin-bottom: 8px;
        }

        .indent {
            margin-left: 16px;
        }

        .indent-lg {
            margin-left: 28px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .plain td,
        .plain th {
            padding: 4px 6px;
            vertical-align: top;
        }

        .bordered {
            border: 1px solid #222;
        }

        .bordered td,
        .bordered th {
            border: 1px solid #222;
            padding: 6px;
            vertical-align: top;
        }

        .no-border td {
            border: none !important;
        }

        .nowrap {
            white-space: nowrap;
        }

        .justify {
            text-align: justify;
            line-height: 1.35;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    @php
        // Main amounts
        $loanAmount = (float) ($app->loan_amount ?? 0);
        $old = (float) ($app->old_balance ?? 0);
        $lpp = (float) ($app->lpp ?? 0);
        $int = (float) ($app->interest ?? 0);
        $fee = (float) ($app->handling_fee ?? 0);
        $pet = (float) ($app->petty_cash_loan ?? 0);
        $rebates = (float) ($app->rebates ?? 0);

        $ded = $old + $lpp + $int + $fee + $pet;
        $net = ($loanAmount - $ded) + $rebates;

        $typeSource = strtolower(trim((string) ($printLoanType ?? ($app->loan_type ?? 'regular'))));
        $typeSource = str_replace(['-', '_'], ' ', $typeSource);
        $typeKey = 'regular';
        if (str_contains($typeSource, 'education')) {
            $typeKey = 'educational';
        } elseif (str_contains($typeSource, 'appliance')) {
            $typeKey = 'appliance';
        } elseif (str_contains($typeSource, 'grocery')) {
            $typeKey = 'grocery';
        }

        $loanTypeConfig = [
            'regular' => [
                'title' => 'REGULAR LOAN',
                'label' => 'Regular Loan',
                'interest' => 'Interest (12%)',
            ],
            'educational' => [
                'title' => 'EDUCATIONAL LOAN',
                'label' => 'Educational Loan',
                'interest' => 'Interest (4.5%)',
            ],
            'appliance' => [
                'title' => 'APPLIANCE LOAN',
                'label' => 'Appliance Loan',
                'interest' => 'Interest (3%)',
            ],
            'grocery' => [
                'title' => 'GROCERY LOAN',
                'label' => 'Grocery Loan',
                'interest' => 'Interest (2.5%)',
            ],
        ];
        $selectedType = $loanTypeConfig[$typeKey] ?? $loanTypeConfig['regular'];

        $loanTitle = $selectedType['title'];
        $loanTypeLabel = $selectedType['label'];
        $interestRowLabel = $selectedType['interest'];
        $loanPurpose = $app->loan_purpose ?? $loanTypeLabel;

        // Optional display helpers (adjust field names to your DB if needed)
        $runTerm = $app->run_term ?? ($app->terms ?? '');
        $firstInstallmentDate = $app->first_installment_date ?? '';
        $installmentIncreasedTo = $app->installment_increased_to ?? '';
        $simpleAnnualRate = $app->simple_annual_rate ?? '';
        $resolutionNo = $app->resolution_no ?? '';
        $resolutionDate = $app->resolution_date ?? '';
        $receivedDate = $app->received_date ?? '';
        $receivedYear = $app->received_year ?? date('Y');
        $chequeNo = $app->cheque_no ?? '';
        $promissoryNoteNo = $app->promissory_note_no ?? '';
        $promissoryNoteDate = $app->promissory_note_date ?? '';
        $amountInWords = $app->amount_in_words ?? '';
        $monthlyLoanPayment = $app->monthly_loan_payment ?? '';
        $paymentEffectiveDate = $app->payment_effective_date ?? '';
        $position = $app->position ?? '';
        $natureOfAppointment = $app->nature_of_appointment ?? '';
        $coMaker1 = $app->co_maker_1 ?? '';
        $coMaker2 = $app->co_maker_2 ?? '';
        $appDate = $app->application_date ?? '';
        $loanVoucherNo = $app->application_no ?? '—';
    @endphp

    {{-- Header --}}
    <div class="center small">
        <div>REPUBLIC OF THE PHILIPPINES</div>
        <div>Department of Environment and Natural Resources</div>
        <div style="font-weight:700;">ENVIRONMENT AND NATURAL RESOURCES MULTI-PURPOSE</div>
        <div style="font-weight:700;">CREDIT COOPERATIVE</div>
        <div>Puntod, Cagayan de Oro City</div>
    </div>

    <div class="center title">{{ $loanTitle }}</div>

    {{-- Applicant and loan info --}}
    <table class="plain">
        <tr>
            <td style="width:60%;">
                <b>NAME:</b>
                <span class="line">{{ strtoupper($app->full_name ?? '') }}</span>
            </td>
            <td style="width:40%;">
                <b>L.V NO.:</b>
                <span class="line-sm">{{ $loanVoucherNo }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b>ADDRESS:</b>
                <span class="line">{{ $app->address ?? '—' }}</span>
            </td>
            <td>
                <b>AMOUNT OF LOAN:</b>
                <span class="line-sm">P{{ number_format($loanAmount, 2) }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>IN PAYMENT OF:</b>
                <span class="line-lg">{{ $loanPurpose }}</span>
            </td>
        </tr>
    </table>

    <div class="box mt-8">
        <table class="bordered">
            <tr>
                <th align="left">Kind of Loan</th>
                <th align="right" style="width:32%;">Amount</th>
            </tr>
            <tr>
                <td><b>Loan (Specify) {{ $loanTypeLabel }}</b></td>
                <td align="right"><b>P{{ number_format($loanAmount, 2) }}</b></td>
            </tr>

            <tr>
                <td colspan="2" class="small"><b>Less: Deductions</b></td>
            </tr>
            <tr>
                <td>Balance (Old Bal)</td>
                <td align="right">P{{ number_format($old, 2) }}</td>
            </tr>
            <tr>
                <td>LPP</td>
                <td align="right">P{{ number_format($lpp, 2) }}</td>
            </tr>
            <tr>
                <td>{{ $interestRowLabel }}</td>
                <td align="right">P{{ number_format($int, 2) }}</td>
            </tr>
            <tr>
                <td>Handling Fee</td>
                <td align="right">P{{ number_format($fee, 2) }}</td>
            </tr>
            <tr>
                <td>Petty Cash Loan</td>
                <td align="right">P{{ number_format($pet, 2) }}</td>
            </tr>
            <tr>
                <td><b>Total Deduction</b></td>
                <td align="right"><b>P{{ number_format($ded, 2) }}</b></td>
            </tr>
            <tr>
                <td>Total</td>
                <td align="right">P{{ number_format($loanAmount, 2) }}</td>
            </tr>
            <tr>
                <td>Add: Rebates (Unearned Int & Disc)</td>
                <td align="right">{{ $rebates ? 'P' . number_format($rebates, 2) : '______________' }}</td>
            </tr>
            <tr>
                <td><b>NET CASH RECEIVED</b></td>
                <td align="right"><b>P{{ number_format($net, 2) }}</b></td>
            </tr>
        </table>

        <div class="mt-10">
            <div>1. This is to run <span class="line-sm">{{ $runTerm }}</span> months/day</div>
            <div class="mt-4">
                2. The first installment increased will be on
                <span class="line-sm">{{ $firstInstallmentDate }}</span>
            </div>
            <div class="mt-4">
                3. Loan Installment increased to
                <span class="line-sm">{{ $installmentIncreasedTo }}</span>
            </div>
            <div class="mt-4">
                4. Simple annual rate required to
                <span class="line-sm">{{ $simpleAnnualRate }}</span>
            </div>
            <div class="mt-4">5. Disclosed Under R.A. 365</div>

            <div class="mt-16">
                <span class="sigline"></span><br>
                <span class="small muted">Signature Over Printed Name</span>
            </div>
        </div>
    </div>

    {{-- Approval / Paid --}}
    <div class="mt-12 small">
        This above transaction is approved by the Credit/Loan Committee or Board of Director under Resolution No.
        <span class="line-sm">{{ $resolutionNo }}</span>
        Dated <span class="line-sm">{{ $resolutionDate }}</span>
    </div>

    <table class="plain mt-8">
        <tr>
            <td style="width:50%;"></td>
            <td style="width:50%;" class="center">
                <b>APPROVED:</b>
                <div class="mt-16"><b>MARY GRACE O. ALEMANIO</b></div>
                <div>Chairperson</div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="center mt-12">
                <b>PAID:</b>
                <div class="mt-16"><b>PAMELA AMOR A. RAMOS</b></div>
                <div>Treasurer</div>
            </td>
        </tr>
    </table>

    {{-- Receipt text --}}
    <div class="mt-12 justify small">
        Received this day of <span class="line-sm">{{ $receivedDate }}</span> {{ $receivedYear }}
        Cheque No. <span class="line-sm">{{ $chequeNo }}</span>
        Representing proceed of my Consumption Loan with above-name association as evidence by Promissory Note No.
        <span class="line-sm">{{ $promissoryNoteNo }}</span>
        dated <span class="line-sm">{{ $promissoryNoteDate }}</span>.
        The Total amount P<span class="line">{{ $amountInWords }}</span>
    </div>

    <div class="center xsmall mt-4">(Amount of Loan in Words)</div>

    <div class="right mt-12">
        <span class="sigline"></span><br>
        <span class="small muted">Signature Over Printed Name</span>
    </div>

    {{-- Second header (ENREMCO) --}}
    <div class="center small mt-20">
        <div style="font-weight:700;">ENVIRONMENT AND NATURAL REOURCES</div>
        <div style="font-weight:700;">EMPLOYEES MULTI-PURPOSE COOPERATIVE</div>
        <div style="font-weight:700;">(ENREMCO)</div>
        <div>Puntod, Cagayan de Oro City</div>
    </div>

    {{-- Co-makers warranty --}}
    <div class="mt-16">
        <b>CO MAKERS WARRANTY:</b>
        <div class="small justify mt-6">
            I/WE HEREBY bind myself/ourselves for the payment of this loan if, at date of maturity, our PRINCIPAL,
            fails to make full settlement thereof, for whatever reason or reasons, and so hereby authorized the BOARD OF
            DIRECTORS to deduct from my/our salary the full payment of the unpaid balance including accrued interest, if
            any, with due notice to me/us by the Association.
        </div>

        <table class="plain mt-16">
            <tr>
                <td class="center" style="width:50%;">
                    <span class="sigline"></span><br>
                    <span class="small muted">{{ $coMaker1 ?: 'PRINTED NAME & SIG. CO MAKER (1)' }}</span>
                </td>
                <td class="center" style="width:50%;">
                    <span class="sigline"></span><br>
                    <span class="small muted">{{ $coMaker2 ?: 'PRINTED NAME & SIG. CO MAKER (2)' }}</span>
                </td>
            </tr>
        </table>

        <div class="mt-10">
            <b>Kind of Loan</b><br>
            <span class="small">
                ({{ $typeKey === 'regular' ? 'X' : ' ' }}) Salary Loan
                &nbsp;&nbsp;&nbsp;
                ({{ $typeKey === 'educational' ? 'X' : ' ' }}) Educational Loan
                &nbsp;&nbsp;&nbsp;
                ({{ $typeKey === 'appliance' ? 'X' : ' ' }}) Appliance Loan
                &nbsp;&nbsp;&nbsp;
                ({{ $typeKey === 'grocery' ? 'X' : ' ' }}) Grocery Loan
            </span>
        </div>

        <div class="small mt-10">
            After thorough study/evaluation of the herein application for
            <span class="line">{{ $loanPurpose }}</span>
        </div>
        <div class="small mt-4">
            The Committee hereby recommend the approval of the (P<span
                class="line-sm">{{ number_format($loanAmount, 2) }}</span>)
            in favor of the applicant
        </div>
    </div>

    {{-- Credit committee --}}
    <div class="mt-16">
        <b>CREDIT COMMITTEE:</b>

        <table class="plain mt-16">
            <tr>
                <td class="center" style="width:50%;">
                    <b>MARIA CATHERINE OPEÑA</b><br>
                    <span class="small">Member</span>
                </td>
                <td class="center" style="width:50%;">
                    <b>NECITA M. PAULMA</b><br>
                    <span class="small">Member</span>
                </td>
            </tr>
        </table>

        <div class="center mt-12">
            <b>ESPERANZA M. DOMINGO</b><br>
            <span class="small">Chairman</span>
        </div>
    </div>

    {{-- Application section --}}
    <div class="hr mt-16"></div>

    <table class="plain">
        <tr>
            <td style="width:60%;">
                <b>Application No.</b> <span class="line-sm">{{ $app->application_no ?? '' }}</span>
            </td>
            <td style="width:40%;">
                <b>Date</b> <span class="line-sm">{{ $appDate }}</span>
            </td>
        </tr>
    </table>

    <div class="mt-8"><b>TO:</b> The Board of Director</div>

    <div class="justify small mt-8">
        In accordance with pertinent provision of the Constitution and By Laws of the ENREMCO AS IMPLEMENTED BY THE
        RESOLUTION ADOPTED BY THE 1992 General Assembly, extended me a Loan of
        <span class="line-lg">{{ $amountInWords }}</span>
        (P <span class="line-sm">{{ number_format($loanAmount, 2) }}</span>)
        for the purpose of <span class="line">{{ $loanPurpose }}</span>
    </div>

    <div class="justify small mt-10">
        I HEREBY AUTHORIZED the CASHIER to deduct from my monthly salaries, the amount of
        (P<span class="line-sm">{{ $monthlyLoanPayment }}</span>) as my monthly loan payment effective
        <span class="line-sm">{{ $paymentEffectiveDate }}</span>
    </div>

    <div class="justify small mt-10">
        If this Loan is not fully paid at the date of maturity, the principal sum or the balance thereof shall become
        due, if any, Without need for demand.
    </div>

    <div class="right mt-16">Very truly yours,</div>

    <div class="right mt-16">
        <span class="sigline"></span><br>
        <span class="small muted">Printed Name & Signature</span>
    </div>

    <div class="right mt-12">
        <span class="sigline"></span><br>
        <span class="small muted">Position</span>
    </div>

    <div class="right mt-12">
        <span class="sigline"></span><br>
        <span class="small muted">Nature of Appointment</span>
    </div>

</body>

</html>
