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

        .box {
            border: 1px solid #222;
            padding: 10px;
        }

        .line {
            border-bottom: 1px solid #222;
            display: inline-block;
            min-width: 190px;
            line-height: 1.2;
        }

        .line-sm {
            border-bottom: 1px solid #222;
            display: inline-block;
            min-width: 120px;
            line-height: 1.2;
        }

        .sigline {
            border-bottom: 1px solid #222;
            width: 260px;
            display: inline-block;
            height: 16px;
        }

        .mt-4 {
            margin-top: 4px;
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

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #222;
            padding: 5px 6px;
            font-size: 11px;
        }

        .items-table th {
            background: #f5f5f5;
        }

        .justify {
            text-align: justify;
            line-height: 1.35;
        }
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
        $item = $app->appliance_item ?? '';
        $brandModel = $app->appliance_brand_model ?? '';
        $supplier = $app->appliance_store ?? '';
        $downpayment = (float) ($app->appliance_downpayment ?? 0);
        $warrantyMonths = $app->appliance_warranty_months ?? '';
        $coMaker1 = $app->comaker1_name ?? $app->co_maker_1 ?? '';
        $coMaker2 = $app->comaker2_name ?? $app->co_maker_2 ?? '';

        $applianceItemsRaw = $app->appliance_items ?? null;
        if (is_string($applianceItemsRaw) && trim($applianceItemsRaw) !== '') {
            $decodedItems = json_decode($applianceItemsRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedItems)) {
                $applianceItemsRaw = $decodedItems;
            }
        }

        $applianceItems = collect(is_array($applianceItemsRaw) ? $applianceItemsRaw : [])
            ->map(function ($row) {
                $itemName = trim((string) ($row['item_name'] ?? ''));
                $quantity = max((int) ($row['quantity'] ?? 0), 0);
                $unitPrice = round((float) ($row['unit_price'] ?? 0), 2);
                $amount = round((float) ($row['amount'] ?? ($quantity * $unitPrice)), 2);

                return [
                    'item_name' => $itemName,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                ];
            })
            ->filter(fn($row) => $row['item_name'] !== '' && $row['quantity'] > 0)
            ->values();

        if ($applianceItems->isEmpty() && trim((string) $item) !== '') {
            $fallbackNames = preg_split('/\s*,\s*/', (string) $item, -1, PREG_SPLIT_NO_EMPTY);
            if (!is_array($fallbackNames) || empty($fallbackNames)) {
                $fallbackNames = [$item];
            }

            $fallbackPrice = (float) ($app->appliance_total_amount ?? $app->appliance_cash_price ?? $loanAmount);
            foreach ($fallbackNames as $index => $fallbackName) {
                $applianceItems->push([
                    'item_name' => trim((string) $fallbackName),
                    'quantity' => 1,
                    'unit_price' => count($fallbackNames) === 1 ? round($fallbackPrice, 2) : 0.0,
                    'amount' => count($fallbackNames) === 1 ? round($fallbackPrice, 2) : 0.0,
                ]);
            }
        }

        $itemsTotal = (float) $applianceItems->sum('amount');
        $cashPrice = (float) ($app->appliance_total_amount ?? $app->appliance_cash_price ?? ($itemsTotal > 0 ? $itemsTotal : 0));
    @endphp

    <div class="center small">
        <div>REPUBLIC OF THE PHILIPPINES</div>
        <div>Department of Environment and Natural Resources</div>
        <div style="font-weight:700;">ENVIRONMENT AND NATURAL RESOURCES EMPLOYEES MULTI-PURPOSE COOPERATIVE</div>
        <div>Puntod, Cagayan de Oro City</div>
    </div>

    <div class="center title">APPLIANCE LOAN</div>

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
        <div class="small" style="font-weight:700;">APPLIANCE DETAILS</div>
        @if($applianceItems->isNotEmpty())
            <table class="items-table">
                <thead>
                    <tr>
                        <th align="left">Item</th>
                        <th align="right" style="width:14%;">Qty</th>
                        <th align="right" style="width:22%;">Unit Price</th>
                        <th align="right" style="width:22%;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applianceItems as $row)
                        <tr>
                            <td>{{ $row['item_name'] }}</td>
                            <td align="right">{{ number_format((float) $row['quantity'], 0) }}</td>
                            <td align="right">P{{ number_format((float) $row['unit_price'], 2) }}</td>
                            <td align="right">P{{ number_format((float) $row['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="small mt-4"><b>Item:</b> {{ $item !== '' ? $item : '-' }}</div>
        @endif
        <table class="plain mt-4">
            <tr>
                <td style="width:50%;"><b>Brand/Model:</b> <span class="line">{{ $brandModel }}</span></td>
                <td style="width:50%;"><b>Supplier/Store:</b> <span class="line">{{ $supplier }}</span></td>
            </tr>
            <tr>
                <td><b>Total Amount:</b> <span class="line-sm">P{{ number_format($cashPrice, 2) }}</span></td>
                <td><b>Warranty (months):</b> <span class="line-sm">{{ $warrantyMonths }}</span></td>
            </tr>
            <tr>
                <td><b>Downpayment:</b> <span class="line-sm">P{{ number_format($downpayment, 2) }}</span></td>
                <td></td>
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
                <td><b>Loan (Specify) Appliance Loan</b></td>
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
                <td>Interest (3%)</td>
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
                <td><b>NET CASH RECEIVED</b></td>
                <td align="right"><b>P{{ number_format($net, 2) }}</b></td>
            </tr>
        </table>
    </div>

    <div class="justify small mt-12">
        I certify that this appliance loan shall be used solely for the declared appliance purchase. I agree to provide
        sales invoice and related proof of purchase when required by ENREMCO.
    </div>

    <div class="mt-16">
        <table class="plain">
            <tr>
                <td class="center" style="width:50%;">
                    <span class="sigline">{{ strtoupper($app->full_name ?? '') }}</span><br>
                    <span class="small">Applicant Signature over Printed Name</span>
                </td>
                <td class="center" style="width:50%;">
                    <span class="sigline"></span><br>
                    <span class="small">Credit Committee / Approver</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="mt-16">
        <b>CO MAKERS WARRANTY:</b>
        <div class="small justify mt-4">
            I/WE HEREBY bind myself/ourselves for the payment of this loan if, at date of maturity, our PRINCIPAL,
            fails to make full settlement thereof, for whatever reason or reasons, and so hereby authorized the BOARD OF
            DIRECTORS to deduct from my/our salary the full payment of the unpaid balance including accrued interest, if
            any, with due notice to me/us by the Association.
        </div>

        <table class="plain mt-16">
            <tr>
                <td class="center" style="width:50%;">
                    <span class="sigline"></span><br>
                    <span class="small">{{ $coMaker1 ?: 'PRINTED NAME & SIG. CO MAKER (1)' }}</span>
                </td>
                <td class="center" style="width:50%;">
                    <span class="sigline"></span><br>
                    <span class="small">{{ $coMaker2 ?: 'PRINTED NAME & SIG. CO MAKER (2)' }}</span>
                </td>
            </tr>
        </table>

        <div class="mt-10">
            <b>Kind of Loan</b><br>
            <span class="small">
                ( ) Salary Loan
                &nbsp;&nbsp;&nbsp;
                ( ) Educational Loan
                &nbsp;&nbsp;&nbsp;
                (X) Appliance Loan
                &nbsp;&nbsp;&nbsp;
                ( ) Grocery Loan
            </span>
        </div>
    </div>
</body>

</html>