<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Form W-2 — {{ (int) ($taxYear ?? date('Y')) }}</title>
    <style>
        @page { margin: 10mm 12mm; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 7.5pt; color: #111; margin: 0; padding: 0; }
        .wm { position: fixed; left: 0; right: 0; top: 38%; text-align: center; font-size: 22pt; font-weight: bold; color: #ccc; opacity: 0.35; transform: rotate(-14deg); z-index: 0; pointer-events: none; }
        .doc { position: relative; z-index: 1; }
        .hdr { font-weight: bold; font-size: 11pt; margin-bottom: 6pt; border-bottom: 1pt solid #000; padding-bottom: 3pt; }
        .sub { font-size: 7pt; color: #333; margin-bottom: 8pt; }
        table.meta { width: 100%; border-collapse: collapse; margin-bottom: 8pt; }
        table.meta td { border: 0.35pt solid #666; padding: 4pt; vertical-align: top; font-size: 7pt; }
        table.meta .lbl { font-weight: bold; width: 22%; background: #f5f5f5; }
        table.boxes { width: 100%; border-collapse: collapse; margin-top: 4pt; }
        table.boxes th, table.boxes td { border: 0.35pt solid #333; padding: 3pt 4pt; font-size: 6.5pt; vertical-align: top; }
        table.boxes th { background: #eee; font-weight: bold; width: 18%; }
        .num { text-align: right; font-family: DejaVu Sans Mono, monospace; }
        .chk { font-weight: bold; }
    </style>
</head>
<body>
@php
    $ty = (int) ($taxYear ?? date('Y'));
    $boxes = is_array($boxes ?? null) ? $boxes : [];
    $emp = is_array($employee ?? null) ? $employee : [];
    $co = is_array($company ?? null) ? $company : [];
    $v = static function (array $b, string $k): string {
        return (string) ($b[$k] ?? '');
    };
    $yn = static function (array $b, string $k): string {
        return ! empty($b[$k]) ? 'Yes' : '';
    };
    $ename = trim(($emp['first_name'] ?? '').' '.($emp['last_name'] ?? ''));
@endphp
<div class="wm">Not Updated — Do NOT File</div>
<div class="doc">
    <div class="hdr">Form W-2 Wage and Tax Statement — {{ $ty }}</div>
    <div class="sub">Copy for records (informational PDF from payroll wizard)</div>

    <table class="meta">
        <tr>
            <td class="lbl">Employer&apos;s name, address, and ZIP</td>
            <td>{{ e($co['name'] ?? '') }}<br>{{ e($co['line1'] ?? '') }}<br>{{ e($co['cityStateZip'] ?? '') }}</td>
        </tr>
        <tr>
            <td class="lbl">Employer identification number (EIN)</td>
            <td>{{ e($co['ein'] ?? '') }}</td>
        </tr>
        <tr>
            <td class="lbl">Employee&apos;s name</td>
            <td>{{ e($ename) }}</td>
        </tr>
        <tr>
            <td class="lbl">Employee&apos;s social security number</td>
            <td>{{ e($emp['ssn'] ?? '') }}</td>
        </tr>
    </table>

    <table class="boxes">
        <tr><th>1 Wages, tips, other compensation</th><td class="num">{{ e($v($boxes, 'b1')) }}</td><th>2 Federal income tax withheld</th><td class="num">{{ e($v($boxes, 'b2')) }}</td></tr>
        <tr><th>3 Social security wages</th><td class="num">{{ e($v($boxes, 'b3')) }}</td><th>4 Social security tax withheld</th><td class="num">{{ e($v($boxes, 'b4')) }}</td></tr>
        <tr><th>5 Medicare wages and tips</th><td class="num">{{ e($v($boxes, 'b5')) }}</td><th>6 Medicare tax withheld</th><td class="num">{{ e($v($boxes, 'b6')) }}</td></tr>
        <tr><th>7 Social security tips</th><td class="num">{{ e($v($boxes, 'b7')) }}</td><th>8 Allocated tips</th><td class="num">{{ e($v($boxes, 'b8')) }}</td></tr>
        <tr><th>10 Dependent care benefits</th><td class="num">{{ e($v($boxes, 'b10')) }}</td><th>11 Nonqualified plans</th><td class="num">{{ e($v($boxes, 'b11')) }}</td></tr>
        <tr>
            <th colspan="2">12 See instructions for box 12</th>
            <td colspan="2">
                @for ($i = 0; $i < 4; $i++)
                    <div>{{ e($v($boxes, 'b12c'.$i)) }} &nbsp; {{ e($v($boxes, 'b12a'.$i)) }}</div>
                @endfor
            </td>
        </tr>
        <tr>
            <th colspan="2">13</th>
            <td colspan="2" class="chk">
                Statutory employee: {{ $yn($boxes, 'b13stat') }} &nbsp;
                Retirement plan: {{ $yn($boxes, 'b13ret') }} &nbsp;
                Third-party sick pay: {{ $yn($boxes, 'b13tp') }}
            </td>
        </tr>
        <tr>
            <th colspan="2">14 Other</th>
            <td colspan="2">
                @for ($i = 0; $i < 4; $i++)
                    <div>{{ e($v($boxes, 'b14t'.$i)) }} &nbsp; {{ e($v($boxes, 'b14n'.$i)) }}</div>
                @endfor
            </td>
        </tr>
        <tr><th>15 State / Employer&apos;s state ID no.</th><td>{{ e($v($boxes, 'b15s0')) }} / {{ e($v($boxes, 'b15e0')) }}</td><th>15 State wages, tips, etc. / State income tax</th><td class="num">{{ e($v($boxes, 'b15s1')) }} / {{ e($v($boxes, 'b15e1')) }}</td></tr>
        <tr><th>16 State wages, tips, etc.</th><td class="num">{{ e($v($boxes, 'b160')) }} / {{ e($v($boxes, 'b161')) }}</td><th>17 State income tax</th><td class="num">{{ e($v($boxes, 'b170')) }} / {{ e($v($boxes, 'b171')) }}</td></tr>
        <tr><th>18 Local wages, tips, etc.</th><td class="num">{{ e($v($boxes, 'b180')) }} / {{ e($v($boxes, 'b181')) }}</td><th>19 Local income tax</th><td class="num">{{ e($v($boxes, 'b190')) }} / {{ e($v($boxes, 'b191')) }}</td></tr>
        <tr><th>20 Locality name</th><td colspan="3">{{ e($v($boxes, 'b200')) }} / {{ e($v($boxes, 'b201')) }}</td></tr>
    </table>
</div>
</body>
</html>
