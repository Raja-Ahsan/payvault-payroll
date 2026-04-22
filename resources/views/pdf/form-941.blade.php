<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Form 941 — {{ $ty }}</title>
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
        .repeat-name-ein { margin-bottom: 8pt; padding-bottom: 6pt; border-bottom: 0.5pt solid #222; }
        .repeat-name-ein table { width: 100%; border-collapse: collapse; }
        .repeat-name-ein td { padding: 2pt 0; vertical-align: top; }
        .repeat-k { font-weight: bold; width: 42%; }
        .repeat-v { border-bottom: 0.35pt solid #999; }
        .part-title {
            font-weight: bold;
            font-size: 8.5pt;
            margin: 8pt 0 4pt;
            padding-bottom: 2pt;
            border-bottom: 0.75pt solid #222;
        }
        table.lines { width: 100%; border-collapse: collapse; margin-bottom: 3pt; }
        table.lines td { padding: 3pt 4pt; vertical-align: middle; border: 0.35pt solid #999; }
        table.lines .ln { width: 6%; text-align: center; font-weight: bold; background: #f3f3f3; }
        table.lines .desc { width: 64%; }
        table.lines .amt { width: 14%; text-align: right; font-family: DejaVu Sans Mono, monospace; }
        table.lines .rate { width: 16%; text-align: center; font-size: 6.5pt; color: #333; }
        .chk-box {
            display: inline-block;
            width: 10pt;
            height: 10pt;
            border: 0.5pt solid #222;
            text-align: center;
            line-height: 9pt;
            font-size: 7pt;
            font-weight: bold;
            margin-right: 4pt;
            vertical-align: middle;
        }
        .small-note { font-size: 7pt; color: #333; margin: 4pt 0 6pt; }
        .inline-opt { margin: 3pt 0; font-size: 7.5pt; }
    </style>
</head>
<body>
@php
    $ty = (int) ($ty ?? date('Y'));
    $m = $m ?? [];
    $emp = $emp ?? [];
    $months = $months ?? ['m1' => '', 'm2' => '', 'm3' => '', 'mtot' => ''];
    $checks = is_array($checks ?? null) ? $checks : [];
    $formFields = is_array($formFields ?? null) ? $formFields : [];
    $fmt = static function ($v): string {
        return number_format((float) $v, 2, '.', ',');
    };
    $einRaw = preg_replace('/\D/', '', (string) ($emp['ein'] ?? ''));
    $einDisp = strlen($einRaw) === 9
        ? substr($einRaw, 0, 2).'-'.substr($einRaw, 2, 7)
        : (string) ($emp['ein'] ?? '');
    $semi = ! empty($m['line16_semiweekly']);
    $under = ! empty($m['line12_under_2500']);
    $chk = static function (array $c, string $id): string {
        return ! empty($c[$id]) ? 'X' : "\u{00A0}";
    };
    $field = static function (array $f, string $id): string {
        return (string) ($f[$id] ?? '');
    };
@endphp

{{-- Match reference PDF from page 2 onward: name/EIN repeat, then Part 1 (continued) through Part 5. Labels match form-941.blade.php. --}}
<div class="repeat-name-ein">
    <table>
        <tr>
            <td class="repeat-k">Name (not your trade name)</td>
            <td class="repeat-v">{{ $emp['legal_name'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="repeat-k">Employer identification number (EIN)</td>
            <td class="repeat-v">{{ $einDisp }}</td>
        </tr>
    </table>
</div>

<div class="part-title">Part 1 (continued): Deposits, balance due, overpayment</div>

<table class="lines">
    <tr>
        <td class="ln">12</td>
        <td class="desc">Total taxes after adjustments and nonrefundable credits. Subtract line 11 from line 10</td>
        <td class="amt">{{ $fmt($m['line12'] ?? 0) }}</td>
    </tr>
    <tr>
        <td class="ln">13</td>
        <td class="desc">Total deposits for this quarter, including overpayment applied from a prior quarter and overpayments applied from Form 941-X, 941-X (PR), or 944-X filed in the current quarter</td>
        <td class="amt">{{ $fmt($m['line13'] ?? 0) }}</td>
    </tr>
    <tr>
        <td class="ln">14</td>
        <td class="desc">Balance due. If line 12 is more than line 13, enter the difference</td>
        <td class="amt">{{ $fmt($m['line14'] ?? 0) }}</td>
    </tr>
    <tr>
        <td class="ln">15a</td>
        <td class="desc">Overpayment. If line 13 is more than line 12, enter the difference</td>
        <td class="amt">{{ $fmt($m['line15a'] ?? 0) }}</td>
    </tr>
</table>

<div class="inline-opt">
    <span class="chk-box">{{ $chk($checks, 'f941-l15b-next') }}</span>
    <strong>15b</strong> Apply to next return
    &nbsp;&nbsp;&nbsp;
    <span class="chk-box">{{ $chk($checks, 'f941-l15b-refund') }}</span>
    Send a refund
</div>
<table class="lines" style="margin-top: 6pt;">
    <tr>
        <td class="ln">15c</td>
        <td class="desc">Routing number</td>
        <td class="amt" style="text-align:left;font-family:inherit;">{{ e($field($formFields, 'f941-l15c')) }}</td>
    </tr>
</table>
<div class="inline-opt" style="margin-top:4pt;">
    <strong>15d</strong> Account type
    <span class="chk-box">{{ $chk($checks, 'f941-l15d-chk') }}</span> Checking
    <span class="chk-box">{{ $chk($checks, 'f941-l15d-sav') }}</span> Savings
</div>
<table class="lines">
    <tr>
        <td class="ln">15e</td>
        <td class="desc">Account number</td>
        <td class="amt" style="text-align:left;font-family:inherit;">{{ e($field($formFields, 'f941-l15e')) }}</td>
    </tr>
</table>

<div class="part-title">Part 2: Tell us about your deposit schedule and tax liability for this quarter.</div>
<p class="small-note">If you&apos;re unsure about whether you&apos;re a monthly schedule depositor or a semiweekly schedule depositor, see section 11 of Pub. 15.</p>

<table class="lines">
    <tr>
        <td colspan="2">
            <div style="margin-bottom: 5pt;">
                <span class="chk-box">{{ $under && ! $semi ? 'X' : '' }}</span>
                <strong>16</strong> Line 12 on this return is less than $2,500 or line 12 on the return for the prior quarter was less than $2,500, and you didn&apos;t incur a $100,000 next-day deposit obligation during the current quarter. If line 12 for the prior quarter was less than $2,500 but line 12 on this return is $100,000 or more, you must provide a record of your federal tax liability. If you&apos;re a monthly schedule depositor, complete the deposit schedule below; if you&apos;re a semiweekly schedule depositor, attach Schedule B (Form 941). Go to Part 3.
            </div>
            <div style="margin-bottom: 5pt;">
                <span class="chk-box">{{ ! $under && ! $semi ? 'X' : '' }}</span>
                You were a monthly schedule depositor for the entire quarter. Enter your tax liability for each month and total liability for the quarter, then go to Part 3.
            </div>
        </td>
    </tr>
</table>

<p class="small-note" style="margin: 4pt 0 2pt;">Tax liability:</p>
<table class="lines" style="margin-top: 0;">
    <tr>
        <td class="desc">Month 1</td>
        <td class="amt">{{ ($months['m1'] ?? '') !== '' ? e($months['m1']) : '' }}</td>
    </tr>
    <tr>
        <td class="desc">Month 2</td>
        <td class="amt">{{ ($months['m2'] ?? '') !== '' ? e($months['m2']) : '' }}</td>
    </tr>
    <tr>
        <td class="desc">Month 3</td>
        <td class="amt">{{ ($months['m3'] ?? '') !== '' ? e($months['m3']) : '' }}</td>
    </tr>
    <tr>
        <td class="desc"><strong>Total liability for quarter</strong></td>
        <td class="amt">{{ ($months['mtot'] ?? '') !== '' ? e($months['mtot']) : '' }}</td>
    </tr>
</table>
<p class="small-note" style="margin-top: 2pt;">Total must equal line 12.</p>

<table class="lines" style="margin-top: 6pt;">
    <tr>
        <td colspan="2">
            <div>
                <span class="chk-box">{{ $semi ? 'X' : '' }}</span>
                You were a semiweekly schedule depositor for any part of this quarter. Complete Schedule B (Form 941), Report of Tax Liability for Semiweekly Schedule Depositors, and attach it to Form 941. Go to Part 3.
            </div>
        </td>
    </tr>
</table>

<div class="part-title">Part 3: Tell us about your business. If a question does NOT apply to your business, leave it blank.</div>
<div class="inline-opt">
    <span class="chk-box">{{ $chk($checks, 'f941-l17') }}</span>
    <strong>17</strong> If your business has closed or you stopped paying wages, check here and enter the final date you paid wages; also attach a statement to your return. See instructions.
</div>
<table class="lines">
    <tr>
        <td class="desc">Final date wages paid</td>
        <td class="amt" style="text-align:left;font-family:inherit;">{{ e($field($formFields, 'f941-l17d')) }}</td>
    </tr>
</table>
<div class="inline-opt" style="margin-top:4pt;">
    <span class="chk-box">{{ $chk($checks, 'f941-l18') }}</span>
    <strong>18</strong> If you&apos;re a seasonal employer and you don&apos;t have to file a return for every quarter of the year, check here.
</div>

<div class="part-title">Part 4: May we speak with your third-party designee?</div>
<p class="small-note">Do you want to allow an employee, a paid tax preparer, or another person to discuss this return with the IRS? See the instructions for details.</p>
<div class="inline-opt">
    <span class="chk-box">{{ $chk($checks, 'f941-l19y') }}</span>
    Yes. Designee&apos;s name and phone number<br><span class="muted" style="font-size: 7pt;">Select a 5-digit personal identification number (PIN) to use when talking to the IRS.</span>
</div>
<div class="inline-opt">
    <span class="chk-box">{{ $chk($checks, 'f941-l19n') }}</span>
    No.
</div>

<div class="part-title">Part 5: Sign here. You MUST complete both pages of Form 941 and SIGN it.</div>
<p class="small-note">Under penalties of perjury, I declare that I have examined this return, including accompanying schedules and statements, and to the best of my knowledge and belief, it is true, correct, and complete. Declaration of preparer (other than taxpayer) is based on all information of which preparer has any knowledge.</p>
<p class="small-note">Signature, date, title, paid preparer, and daytime phone are completed on the official paper form or through your tax professional.</p>
</body>
</html>
