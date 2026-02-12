<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .center {
            text-align: center;
        }

        .muted {
            color: #444;
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 6px;
            vertical-align: top;
        }

        .line {
            border-bottom: 1px solid #222;
            display: inline-block;
            min-width: 220px;
        }

        .small {
            font-size: 11px;
        }

        .hr {
            height: 1px;
            background: #222;
            margin: 10px 0;
        }

        .sigline {
            border-bottom: 1px solid #222;
            width: 260px;
            display: inline-block;
        }
    </style>
</head>

<body>

    <div class="center small">
        <div>REPUBLIC OF THE PHILIPPINES</div>
        <div>Department of Environment and Natural Resources</div>
        <div style="font-weight:700;">ENVIRONMENT AND NATURAL RESOURCES MULTI-PURPOSE</div>
        <div style="font-weight:700;">CREDIT COOPERATIVE</div>
        <div>Puntod, Cagayan de Oro City</div>
    </div>

    <div class="center title">REGULAR LOAN</div>

    <table>
        <tr>
            <td>
                <b>NAME:</b> <span class="line">{{ strtoupper($app->full_name ?? '') }}</span>
            </td>
            <td>
                <b>L.V NO.:</b> <span class="line">{{ $app->application_no ?? '—' }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <b>ADDRESS:</b> <span class="line">{{ $app->address ?? '—' }}</span>
            </td>
            <td>
                <b>AMOUNT OF LOAN:</b> <span
                    class="line">₱{{ number_format((float) ($app->loan_amount ?? 0), 2) }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <b>IN PAYMENT OF:</b> <span
                    class="line">{{ ucwords(str_replace('_', ' ', $app->loan_type ?? '')) }}</span>
            </td>
        </tr>
    </table>

    <div class="hr"></div>

    <div class="box">
        <table border="1">
            <tr>
                <th align="left">Kind of Loan</th>
                <th align="right">Amount</th>
            </tr>
            <tr>
                <td><b>Loan (Specify) Consumption</b></td>
                <td align="right"><b>₱{{ number_format((float) ($app->loan_amount ?? 0), 2) }}</b></td>
            </tr>

            <tr>
                <td colspan="2" class="small muted"><b>Less: Deductions</b></td>
            </tr>

            @php
                // If you add these fields later, it will auto-fill
                $old = (float) ($app->old_balance ?? 0);
                $lpp = (float) ($app->lpp ?? 0);
                $int = (float) ($app->interest ?? 0);
                $fee = (float) ($app->handling_fee ?? 0);
                $pet = (float) ($app->petty_cash_loan ?? 0);
                $ded = $old + $lpp + $int + $fee + $pet;
                $net = ((float) ($app->loan_amount ?? 0)) - $ded;
            @endphp

            <tr>
                <td>Balance (Old Bal)</td>
                <td align="right">₱{{ number_format($old, 2) }}</td>
            </tr>
            <tr>
                <td>LPP</td>
                <td align="right">₱{{ number_format($lpp, 2) }}</td>
            </tr>
            <tr>
                <td>Interest (12%)</td>
                <td align="right">₱{{ number_format($int, 2) }}</td>
            </tr>
            <tr>
                <td>Handling Fee</td>
                <td align="right">₱{{ number_format($fee, 2) }}</td>
            </tr>
            <tr>
                <td>Petty Cash Loan</td>
                <td align="right">₱{{ number_format($pet, 2) }}</td>
            </tr>

            <tr>
                <td><b>Total Deduction</b></td>
                <td align="right"><b>₱{{ number_format($ded, 2) }}</b></td>
            </tr>
            <tr>
                <td><b>NET CASH RECEIVED</b></td>
                <td align="right"><b>₱{{ number_format($net, 2) }}</b></td>
            </tr>
        </table>

        <div style="margin-top:12px;">
            <div>Disclosed Under R.A. 365</div>
            <div style="margin-top:18px;">
                <span class="sigline"></span><br>
                <span class="small muted">Signature Over Printed Name</span>
            </div>
        </div>
    </div>

    <div style="margin-top:16px;">
        <b>CO MAKERS WARRANTY:</b>
        <div class="small" style="margin-top:6px; line-height: 1.4;">
            I/WE HEREBY bind myself/ourselves for the payment of this loan if, at date of maturity,
            our PRINCIPAL fails to make full settlement thereof, for whatever reason or reasons, and so hereby
            authorized
            the BOARD OF DIRECTORS to deduct from my/our salary the full payment of the unpaid balance including accrued
            interest, if any, with due notice to me/us by the Association.
        </div>

        <table style="margin-top:18px;">
            <tr>
                <td class="center">
                    <span class="sigline"></span><br>
                    <span class="small muted">PRINTED NAME & SIG. CO MAKER (1)</span>
                </td>
                <td class="center">
                    <span class="sigline"></span><br>
                    <span class="small muted">PRINTED NAME & SIG. CO MAKER (2)</span>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>