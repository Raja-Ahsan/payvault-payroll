<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Schedule B (Form 941) — {{ (int) ($taxYear ?? date('Y')) }}</title>
    <style>
        @page { margin: 10mm 12mm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7.5pt;
            color: #111;
            line-height: 1.25;
            margin: 0;
            padding: 0;
        }
        .hdr { font-weight: bold; font-size: 9pt; margin-bottom: 4pt; }
        .sub { font-size: 7pt; color: #333; margin-bottom: 8pt; }
        .box { border: 0.5pt solid #999; padding: 6pt; margin-bottom: 8pt; }
        .row2 { width: 100%; border-collapse: collapse; margin-bottom: 4pt; }
        .row2 td { padding: 2pt 4pt; vertical-align: top; }
        .k { font-weight: bold; width: 38%; }
        .v { border-bottom: 0.35pt solid #999; }
        .instr { font-size: 7pt; margin: 6pt 0 8pt; text-align: justify; }
        .mhdr { font-weight: bold; margin: 6pt 0 4pt; border-bottom: 0.5pt solid #222; padding-bottom: 2pt; }
        table.grid { width: 100%; border-collapse: collapse; margin-bottom: 6pt; }
        table.grid td { border: 0.35pt solid #999; padding: 2pt 3pt; font-size: 7pt; vertical-align: middle; }
        .dayl { width: 12%; font-weight: bold; }
        .dayv { font-family: DejaVu Sans Mono, monospace; text-align: right; }
        .chk { display: inline-block; width: 9pt; height: 9pt; border: 0.5pt solid #222; text-align: center; line-height: 8pt; font-size: 6.5pt; font-weight: bold; margin-right: 3pt; }
        .foot { font-size: 6.5pt; color: #444; margin-top: 8pt; }
    </style>
</head>
<body>
@php
    $ty = (int) ($taxYear ?? date('Y'));
    $cq = max(1, min(4, (int) ($currentQuarter ?? 1)));
    $emp = is_array($emp ?? null) ? $emp : [];
    $fields = is_array($fields ?? null) ? $fields : [];
    $checks = is_array($checks ?? null) ? $checks : [];
    $grp = isset($scheduleBDaysGrouped) && $scheduleBDaysGrouped instanceof \Illuminate\Support\Collection
        ? $scheduleBDaysGrouped->sortKeys()
        : collect();
    $einRaw = preg_replace('/\D/', '', (string) ($emp['ein'] ?? ''));
    $einDisp = strlen($einRaw) === 9
        ? substr($einRaw, 0, 2).'-'.substr($einRaw, 2, 7)
        : (string) ($emp['ein'] ?? '');
    $field = static function (array $f, string $id): string {
        return (string) ($f[$id] ?? '');
    };
    $chk = static function (array $c, string $id): string {
        return ! empty($c[$id]) ? 'X' : "\u{00A0}";
    };
@endphp

<div class="hdr">Schedule B (Form 941): Report of Tax Liability for Semiweekly Schedule Depositors</div>
<div class="sub">(Rev. March {{ $ty }}) Department of the Treasury — Internal Revenue Service · OMB No. 1545-0029</div>

<div class="box">
    <table class="row2">
        <tr>
            <td class="k">Employer identification number (EIN) —</td>
            <td class="v">{{ $field($fields, 'f941b-ein') !== '' ? $field($fields, 'f941b-ein') : $einDisp }}</td>
        </tr>
        <tr>
            <td class="k">Name (not your trade name)</td>
            <td class="v">{{ $field($fields, 'f941b-name') !== '' ? $field($fields, 'f941b-name') : ($emp['legal_name'] ?? '') }}</td>
        </tr>
    </table>
    <table class="row2">
        <tr>
            <td class="k">Calendar year (Also check quarter)</td>
            <td class="v">{{ $field($fields, 'f941b-cal-yr') !== '' ? $field($fields, 'f941b-cal-yr') : (string) $ty }}</td>
        </tr>
    </table>
    <div style="font-weight:bold;margin:4pt 0 2pt;">Report for this Quarter... (Check one.)</div>
    <div><span class="chk">{{ $cq === 1 ? 'X' : '' }}</span>1: January, February, March</div>
    <div><span class="chk">{{ $cq === 2 ? 'X' : '' }}</span>2: April, May, June</div>
    <div><span class="chk">{{ $cq === 3 ? 'X' : '' }}</span>3: July, August, September</div>
    <div><span class="chk">{{ $cq === 4 ? 'X' : '' }}</span>4: October, November, December</div>
</div>

<p class="instr">Use this schedule to show your TAX LIABILITY for the quarter; don&apos;t use it to show your deposits. When you file this schedule with Form 941, don&apos;t change your tax liability by adjustments reported on any Forms 941-X or 944-X. You must fill out this schedule and attach it to Form 941 if you&apos;re a semiweekly schedule depositor or became one because your accumulated tax liability on any day was $100,000 or more. Write your daily tax liability on the numbered space that corresponds to the date wages were paid. See Section 11 in Pub. 15 for details.</p>

@php $mi = 0; @endphp
@foreach ($grp as $monthNum => $dayList)
    @php $mi++; @endphp
    <div class="mhdr">Month {{ $mi }}</div>
    <table class="grid">
        @foreach ($dayList as $day)
            <tr>
                <td class="dayl">{{ $day['d'] }} .</td>
                <td class="dayv">{{ $field($fields, 'f941b-'.$day['id']) }}</td>
            </tr>
        @endforeach
    </table>
    <table class="row2">
        <tr>
            <td class="k">Tax liability for Month {{ $mi }}</td>
            <td class="v" style="text-align:right;font-family:DejaVu Sans Mono,monospace;">{{ $field($fields, 'f941b-m'.$mi.'-sub') }}</td>
        </tr>
    </table>
@endforeach

<div class="box">
    <p style="margin:0 0 4pt;font-size:7pt;">Fill in your total liability for the quarter (Month 1 + Month 2 + Month 3). Total must equal line 12 on Form 941.</p>
    <table class="row2">
        <tr>
            <td class="k">Total liability for the quarter</td>
            <td class="v" style="text-align:right;font-family:DejaVu Sans Mono,monospace;">{{ $field($fields, 'f941b-total') }}</td>
        </tr>
    </table>
</div>

<p class="foot">For Paperwork Reduction Act Notice, see separate instructions. www.irs.gov/Form941 Cat. No. 11967Q Schedule B (Form 941) (Rev. 3-{{ $ty }})</p>
</body>
</html>
