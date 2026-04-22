<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Schedule R (Form 941) — {{ (int) ($taxYear ?? date('Y')) }}</title>
    <style>
        @page { margin: 8mm 10mm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 6.5pt;
            color: #111;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        .hdr { font-weight: bold; font-size: 8.5pt; margin-bottom: 3pt; }
        .sub { font-size: 6.5pt; color: #333; margin-bottom: 6pt; }
        .box { border: 0.5pt solid #999; padding: 5pt; margin-bottom: 6pt; }
        .row2 { width: 100%; border-collapse: collapse; margin-bottom: 3pt; }
        .row2 td { padding: 2pt 3pt; vertical-align: top; }
        .k { font-weight: bold; width: 36%; font-size: 6.5pt; }
        .v { border-bottom: 0.35pt solid #999; font-size: 6.5pt; }
        .chk { display: inline-block; width: 8pt; height: 8pt; border: 0.5pt solid #222; text-align: center; line-height: 7pt; font-size: 6pt; font-weight: bold; margin-right: 2pt; }
        .instr { font-size: 6.5pt; margin: 4pt 0 6pt; text-align: justify; }
        table.t { width: 100%; border-collapse: collapse; margin-bottom: 6pt; table-layout: fixed; }
        table.t th, table.t td { border: 0.35pt solid #999; padding: 2pt; vertical-align: top; word-wrap: break-word; }
        table.t th { font-size: 5.5pt; font-weight: bold; }
        .num { width: 3.5%; text-align: center; font-weight: bold; }
        .cell { font-family: DejaVu Sans Mono, monospace; font-size: 6pt; text-align: right; }
        .lbl { font-size: 6pt; }
        .foot { font-size: 6pt; color: #444; margin-top: 6pt; }
    </style>
</head>
<body>
@php
    $ty = (int) ($taxYear ?? date('Y'));
    $cq = max(1, min(4, (int) ($currentQuarter ?? 1)));
    $emp = is_array($emp ?? null) ? $emp : [];
    $fields = is_array($fields ?? null) ? $fields : [];
    $checks = is_array($checks ?? null) ? $checks : [];
    $einRaw = preg_replace('/\D/', '', (string) ($emp['ein'] ?? ''));
    $einDisp = strlen($einRaw) === 9
        ? substr($einRaw, 0, 2).'-'.substr($einRaw, 2, 7)
        : (string) ($emp['ein'] ?? '');
    $field = static function (array $f, string $id): string {
        return (string) ($f[$id] ?? '');
    };
    $chk = static function (array $c, string $id): string {
        return ! empty($c[$id]) ? 'X' : '';
    };
    $colsAi = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i'];
    $colsJq = ['j', 'k', 'l', 'm', 'n', 'o', 'p', 'q'];
    $colsRy = ['r', 's', 't', 'u', 'v', 'w', 'x', 'y'];
    $b1RowLabels = [
        6 => '6 Subtotals for clients. Add lines 1 through 5',
        7 => '7 Enter the combined subtotal from line 9 of all Continuation Sheets for Schedule R',
        8 => '8 Enter Form 941 amounts for your employees',
        9 => '9 Totals. Add lines 6, 7, and 8.',
    ];
@endphp

<div class="hdr">Schedule R (Form 941): Allocation Schedule for Aggregate Form 941 Filers</div>
<div class="sub">(Rev. March {{ $ty }}) Department of the Treasury — Internal Revenue Service · OMB No. 1545-0029</div>

<div class="box">
    <table class="row2">
        <tr>
            <td class="k">Employer identification number (EIN) —</td>
            <td class="v">{{ $field($fields, 'f941r-ein') !== '' ? $field($fields, 'f941r-ein') : $einDisp }}</td>
        </tr>
        <tr>
            <td class="k">Name as shown on Form 941</td>
            <td class="v">{{ $field($fields, 'f941r-name') !== '' ? $field($fields, 'f941r-name') : ($emp['legal_name'] ?? '') }}</td>
        </tr>
    </table>
    <div style="font-weight:bold;margin:3pt 0 2pt;">Type of filer (check one):</div>
    <div><span class="chk">{{ $chk($checks, 'f941r-filer-3504') }}</span>Section 3504 Agent</div>
    <div><span class="chk">{{ $chk($checks, 'f941r-filer-cpeo') }}</span>CPEO</div>
    <div><span class="chk">{{ $chk($checks, 'f941r-filer-other') }}</span>Other Third Party</div>
    <table class="row2" style="margin-top:4pt;">
        <tr>
            <td class="k">Report for calendar year:</td>
            <td class="v">{{ $field($fields, 'f941r-cal-yr') !== '' ? $field($fields, 'f941r-cal-yr') : (string) $ty }}</td>
        </tr>
    </table>
    <div style="font-weight:bold;margin:4pt 0 2pt;">Check the quarter (same as Form 941):</div>
    <div><span class="chk">{{ $cq === 1 ? 'X' : '' }}</span>1: January, February, March</div>
    <div><span class="chk">{{ $cq === 2 ? 'X' : '' }}</span>2: April, May, June</div>
    <div><span class="chk">{{ $cq === 3 ? 'X' : '' }}</span>3: July, August, September</div>
    <div><span class="chk">{{ $cq === 4 ? 'X' : '' }}</span>4: October, November, December</div>
    <div style="font-weight:bold;margin:4pt 0 2pt;">This Schedule R is attached to:</div>
    <div><span class="chk">{{ $chk($checks, 'f941r-att-941') }}</span>Form 941 &nbsp; <span class="chk">{{ $chk($checks, 'f941r-att-941x') }}</span>Form 941-X</div>
</div>

<p class="instr">Read the instructions before you complete Schedule R. Type or print within the boxes. Complete a separate line for the amounts allocated to each of your clients. The term &quot;client&quot; as used on this form includes the term &quot;customer.&quot; See the instructions.</p>

<table class="t">
    <thead>
        <tr>
            <th class="num">#</th>
            <th>(a) Client&apos;s EIN</th>
            <th>(b) Type of wages (CPEO only)</th>
            <th>(c) Form 941, line 1</th>
            <th>(d) Form 941, line 2</th>
            <th>(e) Form 941, line 3</th>
            <th>(f) Form 941-X, lines 9 and 10, column 1, total</th>
            <th>(g) Form 941, lines 5a and 5b, column 2, total</th>
            <th>(h) Form 941, line 5c, column 2</th>
            <th>(i) Form 941, line 5e</th>
        </tr>
    </thead>
    <tbody>
        @foreach (range(1, 5) as $r)
            <tr>
                <td class="num">{{ $r }}</td>
                @foreach ($colsAi as $c)
                    <td class="cell">{{ $field($fields, 'f941r-b1-'.$r.'-'.$c) }}</td>
                @endforeach
            </tr>
        @endforeach
        @foreach (range(6, 9) as $r)
            <tr>
                <td class="lbl" colspan="10">{{ $b1RowLabels[$r] }}</td>
            </tr>
            <tr>
                <td class="num">{{ $r }}</td>
                @foreach ($colsAi as $c)
                    <td class="cell">{{ $field($fields, 'f941r-b1-'.$r.'-'.$c) }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<table class="t">
    <thead>
        <tr>
            <th class="num">#</th>
            <th>(j) Form 941, line 5f</th>
            <th>(k) Form 941, line 11</th>
            <th>(l) Form 941-X, lines 17 and 25, column 1, total</th>
            <th>(m) Reserved for future use</th>
            <th>(n) Form 941-X, lines 18b and 26b, column 1, total</th>
            <th>(o) Form 941-X, lines 18c and 26c, column 1, total</th>
            <th>(p) Form 941-X, line 18d, column 1</th>
            <th>(q) Form 941, line 12</th>
        </tr>
    </thead>
    <tbody>
        @foreach (range(1, 9) as $r)
            <tr>
                <td class="num">{{ $r }}</td>
                @foreach ($colsJq as $c)
                    <td class="cell">{{ $field($fields, 'f941r-b2-'.$r.'-'.$c) }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<table class="t">
    <thead>
        <tr>
            <th class="num">#</th>
            <th>(r) Form 941, line 13</th>
            <th>(s) Reserved for future use</th>
            <th>(t) Reserved for future use</th>
            <th>(u) Form 941-X, lines 28 and 29, column 1, total</th>
            <th>(v) Reserved for future use</th>
            <th>(w) Form 941-X, lines 35 and 37, column 1, total</th>
            <th>(x) Form 941-X, lines 36 and 39, column 1, total</th>
            <th>(y) Form 941-X, lines 38 and 40, column 1, total</th>
        </tr>
    </thead>
    <tbody>
        @foreach (range(1, 9) as $r)
            <tr>
                <td class="num">{{ $r }}</td>
                @foreach ($colsRy as $c)
                    <td class="cell">{{ $field($fields, 'f941r-b3-'.$r.'-'.$c) }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<p class="foot">For Paperwork Reduction Act Notice, see the separate instructions. www.irs.gov/Form941 Cat. No. 49301K Schedule R (Form 941) (Rev. 3-{{ $ty }})</p>
</body>
</html>
