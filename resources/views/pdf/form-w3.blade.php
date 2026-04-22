<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Form W-3 — {{ (int) ($taxYear ?? date('Y')) }}</title>
    <style>
        @page { margin: 10mm 12mm; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 7.5pt; color: #111; margin: 0; padding: 0; }
        .wm { position: fixed; left: 0; right: 0; top: 38%; text-align: center; font-size: 22pt; font-weight: bold; color: #ccc; opacity: 0.35; transform: rotate(-14deg); z-index: 0; pointer-events: none; }
        .doc { position: relative; z-index: 1; }
        .hdr { font-weight: bold; font-size: 11pt; margin-bottom: 6pt; border-bottom: 1pt solid #000; padding-bottom: 3pt; }
        .sub { font-size: 7pt; color: #333; margin-bottom: 8pt; }
        table.t { width: 100%; border-collapse: collapse; margin-bottom: 6pt; }
        table.t td { border: 0.35pt solid #666; padding: 4pt; vertical-align: top; }
        table.t .lbl { font-weight: bold; width: 42%; background: #f5f5f5; font-size: 7pt; }
        .num { text-align: right; font-family: DejaVu Sans Mono, monospace; }
    </style>
</head>
<body>
@php
    $ty = (int) ($taxYear ?? date('Y'));
    $t = is_array($totals ?? null) ? $totals : [];
    $co = is_array($company ?? null) ? $company : [];
    $n = max(0, (int) ($employeeCount ?? 0));
    $v = static function (array $b, string $k): string {
        return (string) ($b[$k] ?? '');
    };
@endphp
<div class="wm">Not Updated — Do NOT File</div>
<div class="doc">
    <div class="hdr">Form W-3 Transmittal of Wage and Tax Statements — {{ $ty }}</div>
    <div class="sub">Totals from payroll wizard (informational PDF)</div>

    <table class="t">
        <tr><td class="lbl">c Total number of Forms W-2</td><td class="num">{{ $n }}</td></tr>
        <tr><td class="lbl">e Employer identification number (EIN)</td><td>{{ e($co['ein'] ?? '') }}</td></tr>
        <tr><td class="lbl">f Employer name</td><td>{{ e($co['name'] ?? '') }}</td></tr>
        <tr><td class="lbl">f Employer address</td><td>{{ e(trim(($co['line1'] ?? '').' '.($co['cityStateZip'] ?? ''))) }}</td></tr>
        <tr><td class="lbl">1 Wages, tips, other compensation</td><td class="num">{{ e($v($t, 'b1')) }}</td></tr>
        <tr><td class="lbl">2 Federal income tax withheld</td><td class="num">{{ e($v($t, 'b2')) }}</td></tr>
        <tr><td class="lbl">3 Social security wages</td><td class="num">{{ e($v($t, 'b3')) }}</td></tr>
        <tr><td class="lbl">4 Social security tax withheld</td><td class="num">{{ e($v($t, 'b4')) }}</td></tr>
        <tr><td class="lbl">5 Medicare wages and tips</td><td class="num">{{ e($v($t, 'b5')) }}</td></tr>
        <tr><td class="lbl">6 Medicare tax withheld</td><td class="num">{{ e($v($t, 'b6')) }}</td></tr>
        <tr><td class="lbl">7 Social security tips</td><td class="num">{{ e($v($t, 'b7')) }}</td></tr>
        <tr><td class="lbl">8 Allocated tips</td><td class="num">{{ e($v($t, 'b8')) }}</td></tr>
        <tr><td class="lbl">10 Dependent care benefits</td><td class="num">{{ e($v($t, 'b10')) }}</td></tr>
        <tr><td class="lbl">11 Nonqualified plans</td><td class="num">{{ e($v($t, 'b11')) }}</td></tr>
        <tr><td class="lbl">12a Amount (combined)</td><td class="num">{{ e($v($t, 'b12a0')) }}</td></tr>
        <tr><td class="lbl">15 State / Employer&apos;s state ID no.</td><td>{{ e($v($t, 'b15s0')) }} / {{ e($v($t, 'b15e0')) }}</td></tr>
        <tr><td class="lbl">16 State wages, tips, etc.</td><td class="num">{{ e($v($t, 'b160')) }} / {{ e($v($t, 'b161')) }}</td></tr>
        <tr><td class="lbl">17 State income tax</td><td class="num">{{ e($v($t, 'b170')) }} / {{ e($v($t, 'b171')) }}</td></tr>
        <tr><td class="lbl">18 Local wages, tips, etc.</td><td class="num">{{ e($v($t, 'b180')) }} / {{ e($v($t, 'b181')) }}</td></tr>
        <tr><td class="lbl">19 Local income tax</td><td class="num">{{ e($v($t, 'b190')) }} / {{ e($v($t, 'b191')) }}</td></tr>
        <tr><td class="lbl">20 Locality name</td><td>{{ e($v($t, 'b200')) }} / {{ e($v($t, 'b201')) }}</td></tr>
        <tr><td class="lbl">Contact</td><td>{{ e($co['contact'] ?? '') }}</td></tr>
        <tr><td class="lbl">Phone</td><td>{{ e($co['phone'] ?? '') }}</td></tr>
        <tr><td class="lbl">Email</td><td>{{ e($co['email'] ?? '') }}</td></tr>
    </table>
</div>
</body>
</html>
