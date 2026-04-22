<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Form 941-X — {{ (int) ($correctingYear ?? $taxYear ?? date('Y')) }}</title>
    <style>
        @page { margin: 8mm 10mm; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 7pt;
            color: #111;
            line-height: 1.25;
            margin: 0;
            padding: 0;
        }
        .hdr { font-weight: bold; font-size: 9pt; margin-bottom: 4pt; }
        .sub { font-size: 6.5pt; color: #333; margin-bottom: 6pt; }
        .box { border: 0.5pt solid #999; padding: 5pt; margin-bottom: 6pt; }
        .row2 { width: 100%; border-collapse: collapse; margin-bottom: 3pt; }
        .row2 td { padding: 2pt 3pt; vertical-align: top; font-size: 6.5pt; }
        .k { font-weight: bold; width: 38%; }
        .v { border-bottom: 0.35pt solid #999; }
        .chk { display: inline-block; width: 9pt; height: 9pt; border: 0.5pt solid #222; text-align: center; line-height: 8pt; font-size: 6.5pt; font-weight: bold; margin-right: 3pt; vertical-align: middle; }
        .part { font-weight: bold; margin: 5pt 0 3pt; font-size: 7.5pt; border-bottom: 0.5pt solid #222; padding-bottom: 2pt; }
        .small { font-size: 6.5pt; color: #333; margin: 2pt 0; }
        .f941x-pdf-val { display: block; border: 0.35pt solid #bbb; min-height: 11pt; padding: 2pt 3pt; font-family: DejaVu Sans Mono, monospace; font-size: 6.5pt; text-align: right; }
        table.tmini { width: 100%; border-collapse: collapse; margin-bottom: 4pt; }
        table.tmini td { padding: 2pt 0; font-size: 6.5pt; vertical-align: top; }
        .foot { font-size: 6pt; color: #444; margin-top: 6pt; }
        .pb { page-break-before: always; }
        table.table { width: 100%; border-collapse: collapse; }
        table.table th, table.table td { border: 0.35pt solid #999; padding: 2pt; vertical-align: top; }
        thead.table-light th { background: #f0f0f0; font-weight: bold; }
    </style>
</head>
<body>
@php
    $ty = (int) ($taxYear ?? date('Y'));
    $cy = (int) ($correctingYear ?? $ty);
    $cq = max(1, min(4, (int) ($currentQuarter ?? 1)));
    $emp = is_array($emp ?? null) ? $emp : [];
    $fields = is_array($fields ?? null) ? $fields : [];
    $checks = is_array($checks ?? null) ? $checks : [];
    $chk = static function (array $c, string $id): string {
        return ! empty($c[$id]) ? 'X' : '';
    };
    $einRaw = preg_replace('/\D/', '', (string) ($emp['ein'] ?? ''));
    $einDisp = strlen($einRaw) === 9
        ? substr($einRaw, 0, 2).'-'.substr($einRaw, 2, 7)
        : (string) ($emp['ein'] ?? '');
    $einOut = trim((string) ($fields['f941x-ein'] ?? '')) !== '' ? (string) $fields['f941x-ein'] : $einDisp;
    $nameOut = trim((string) ($fields['f941x-name'] ?? '')) !== '' ? (string) $fields['f941x-name'] : (string) ($emp['legal_name'] ?? '');
@endphp

<div class="hdr">Form 941-X: Adjusted Employer&apos;s QUARTERLY Federal Tax Return or Claim for Refund</div>
<div class="sub">(Rev. April 2025) Department of the Treasury — Internal Revenue Service · OMB No. 1545-0029</div>

<p class="small" style="margin-bottom:6pt;">Read the separate instructions before completing this form. Use this form to correct errors you made on Form 941 or 941-SS. Use a separate Form 941-X for each quarter that needs correction. Type or print within the boxes. You MUST complete all five pages. Don&apos;t attach this form to Form 941 unless you&apos;re reclassifying workers; see the instructions for line 42.</p>

<div class="box">
    <table class="row2">
        <tr><td class="k">Employer identification number (EIN) —</td><td class="v">{{ e($einOut) }}</td></tr>
        <tr><td class="k">Name (not your trade name)</td><td class="v">{{ e($nameOut) }}</td></tr>
        <tr><td class="k">Trade name (if any)</td><td class="v">{{ e((string) ($fields['f941x-trade'] ?? $emp['trade_name'] ?? '')) }}</td></tr>
        <tr><td class="k">Address (number, street, suite)</td><td class="v">{{ e((string) ($fields['f941x-addr'] ?? $emp['address_line1'] ?? '')) }}</td></tr>
        <tr><td class="k">City / State / ZIP code</td><td class="v">{{ e(trim(($fields['f941x-city'] ?? $emp['city'] ?? '').' '.($fields['f941x-state'] ?? $emp['state_code'] ?? '').' '.($fields['f941x-zip'] ?? $emp['zip_code'] ?? ''))) }}</td></tr>
    </table>
    <div style="font-weight:bold;margin:3pt 0 2pt;">Return You&apos;re Correcting...</div>
    <div class="small">Check the type of return you&apos;re correcting.</div>
    <div><span class="chk">{{ $chk($checks, 'f941x-ret-941') }}</span>941 &nbsp; <span class="chk">{{ $chk($checks, 'f941x-ret-941ss') }}</span>941-SS</div>
    <div style="font-weight:bold;margin:4pt 0 2pt;">Check the ONE quarter you&apos;re correcting.</div>
    <div><span class="chk">{{ $cq === 1 ? 'X' : '' }}</span>1: January, February, March</div>
    <div><span class="chk">{{ $cq === 2 ? 'X' : '' }}</span>2: April, May, June</div>
    <div><span class="chk">{{ $cq === 3 ? 'X' : '' }}</span>3: July, August, September</div>
    <div><span class="chk">{{ $cq === 4 ? 'X' : '' }}</span>4: October, November, December</div>
    <table class="row2" style="margin-top:4pt;">
        <tr><td class="k">Enter the calendar year of the quarter you&apos;re correcting. (YYYY)</td><td class="v">{{ e((string) ($fields['f941x-year-correct'] ?? (string) $cy)) }}</td></tr>
        <tr><td class="k">Enter the date you discovered errors. (MM / DD / YYYY)</td><td class="v">{{ e(trim(($fields['f941x-disc-mm'] ?? '').'/'.($fields['f941x-disc-dd'] ?? '').'/'.($fields['f941x-disc-yyyy'] ?? ''))) }}</td></tr>
    </table>
</div>

<div class="box">
    <div class="part">Part 1: Select ONLY one process. See page 6 for additional guidance, including information on how to treat employment tax credits.</div>
    <div class="small" style="margin-bottom:3pt;"><span class="chk">{{ $chk($checks, 'f941x-p1-1') }}</span><strong>1.</strong> Adjusted employment tax return. Check this box if you underreported tax amounts. Also check this box if you overreported tax amounts and you would like to use the adjustment process to correct the errors. You must check this box if you&apos;re correcting both underreported and overreported tax amounts on this form. The amount shown on line 27, if less than zero, may only be applied as a credit to your Form 941 or Form 944 for the tax period in which you&apos;re filing this form.</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-p1-2') }}</span><strong>2.</strong> Claim. Check this box if you overreported tax amounts only and you would like to use the claim process to ask for a refund or abatement of the amount shown on line 27. Don&apos;t check this box if you&apos;re correcting ANY underreported tax amounts on this form.</div>
</div>

<div class="box">
    <div class="part">Part 2: Complete the certifications.</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-p2-3') }}</span><strong>3.</strong> I certify that I&apos;ve filed or will file Forms W-2, Wage and Tax Statement, or Forms W-2c, Corrected Wage and Tax Statement, as required.</div>
    <p class="small">Note: If you&apos;re correcting underreported tax amounts only, go to Part 3 on page 2 and skip lines 4 and 5. If you&apos;re correcting overreported tax amounts, for purposes of the certifications on lines 4 and 5, Medicare tax doesn&apos;t include Additional Medicare Tax. Form 941-X can&apos;t be used to correct overreported amounts of Additional Medicare Tax unless the amounts weren&apos;t withheld from employee wages or an adjustment is being made for the current year.</p>
    <div class="small" style="font-weight:bold;">4. If you checked line 1 because you&apos;re adjusting overreported federal income tax, social security tax, Medicare tax, or Additional Medicare Tax, check all that apply. You must check at least one box.</div>
    <div class="small">I certify that:</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-p2-4a') }}</span><strong>a.</strong> I repaid or reimbursed each affected employee for the overcollected federal income tax or Additional Medicare Tax for the current year and the overcollected social security tax and Medicare tax for current and prior years. For adjustments of employee social security tax and Medicare tax overcollected in prior years, I have a written statement from each affected employee stating that they haven&apos;t claimed (or the claim was rejected) and won&apos;t claim a refund or credit for the overcollection.</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-p2-4b') }}</span><strong>b.</strong> The adjustments of social security tax and Medicare tax are for the employer&apos;s share only. I couldn&apos;t find the affected employees or each affected employee didn&apos;t give me a written statement that they haven&apos;t claimed (or the claim was rejected) and won&apos;t claim a refund or credit for the overcollection.</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-p2-4c') }}</span><strong>c.</strong> The adjustment is for federal income tax, social security tax, Medicare tax, or Additional Medicare Tax that I didn&apos;t withhold from employee wages.</div>
    <div class="small" style="font-weight:bold;margin-top:3pt;">5. If you checked line 2 because you&apos;re claiming a refund or abatement of overreported federal income tax, social security tax, Medicare tax, or Additional Medicare Tax, check all that apply. You must check at least one box.</div>
    <div class="small">I certify that:</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-p2-5a') }}</span><strong>a.</strong> I repaid or reimbursed each affected employee for the overcollected social security tax and Medicare tax. For claims of employee social security tax and Medicare tax overcollected in prior years, I have a written statement from each affected employee stating that they haven&apos;t claimed (or the claim was rejected) and won&apos;t claim a refund or credit for the overcollection.</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-p2-5b') }}</span><strong>b.</strong> I have a written consent from each affected employee stating that I may file this claim for the employee&apos;s share of social security tax and Medicare tax. For refunds of employee social security tax and Medicare tax overcollected in prior years, I also have a written statement from each affected employee stating that they haven&apos;t claimed (or the claim was rejected) and won&apos;t claim a refund or credit for the overcollection.</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-p2-5c') }}</span><strong>c.</strong> The claim for social security tax and Medicare tax is for the employer&apos;s share only. I couldn&apos;t find the affected employees, or each affected employee didn&apos;t give me a written consent to file a claim for the employee&apos;s share of social security tax and Medicare tax, or each affected employee didn&apos;t give me a written statement that they haven&apos;t claimed (or the claim was rejected) and won&apos;t claim a refund or credit for the overcollection.</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-p2-5d') }}</span><strong>d.</strong> The claim is for federal income tax, social security tax, Medicare tax, or Additional Medicare Tax that I didn&apos;t withhold from employee wages.</div>
</div>

<table class="tmini pb">
    <tr>
        <td class="k" style="width:50%;">Name (not your trade name)</td>
        <td>{{ e($nameOut) }}</td>
    </tr>
    <tr>
        <td class="k">Employer identification number (EIN)</td>
        <td>{{ e($einOut) }}</td>
    </tr>
    <tr>
        <td class="k">Correcting quarter (1, 2, 3, 4)</td>
        <td>{{ $cq }}</td>
    </tr>
    <tr>
        <td class="k">Correcting calendar year (YYYY)</td>
        <td>{{ $cy }}</td>
    </tr>
</table>

@include('screens.admin.forms.partials.form-941-x-part3', ['pdfMode' => true, 'fields' => $fields])

<div class="box pb">
    <div class="part">Part 4: Explain your corrections for this quarter.</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-l41') }}</span><strong>41.</strong> Check here if any corrections you entered on a line include both underreported and overreported amounts. Explain both your underreported and overreported amounts on line 43.</div>
    <div class="small"><span class="chk">{{ $chk($checks, 'f941x-l42') }}</span><strong>42.</strong> Check here if any corrections involve reclassified workers. Explain on line 43.</div>
    <div class="small" style="font-weight:bold;margin-top:3pt;"><strong>43.</strong> You must give us a detailed explanation of how you determined your corrections. See the instructions.</div>
    <div style="border:0.35pt solid #999;padding:4pt;min-height:36pt;font-size:6.5pt;white-space:pre-wrap;">{{ e((string) ($fields['f941x-l43'] ?? '')) }}</div>
</div>

<div class="box">
    <div class="part">Part 5: Sign here. You must complete all five pages of this form and sign it.</div>
    <p class="small">Under penalties of perjury, I declare that I have filed an original Form 941 or Form 941-SS and that I have examined this adjusted return or claim, including accompanying schedules and statements, and to the best of my knowledge and belief, it is true, correct, and complete. Declaration of preparer (other than taxpayer) is based on all information of which preparer has any knowledge.</p>
    <p class="small">Signature, date, title, paid preparer, and daytime phone are completed on the official paper form or through your tax professional.</p>
</div>

<p class="foot">For Paperwork Reduction Act Notice, see separate instructions. www.irs.gov/Form941X Cat. No. 17025J Form 941-X (Rev. 4-2025)</p>
</body>
</html>
