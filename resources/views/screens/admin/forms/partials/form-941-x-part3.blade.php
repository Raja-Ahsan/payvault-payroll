@php
    $pdfMode = $pdfMode ?? false;
    $fields = is_array($fields ?? null) ? $fields : [];
    $p3Four = [
        ['line' => '6', 'html' => 'Wages, tips, and other compensation (Form 941, line 2)<br><span class="text-muted small">Use the amount in Column 1 when you prepare your Forms W-2 or Forms W-2c.</span>'],
        ['line' => '7', 'html' => 'Federal income tax withheld from wages, tips, and other compensation (Form 941, line 3)<br><span class="text-muted small">Copy Column 3 here . . .</span>'],
        ['line' => '8', 'html' => 'Taxable social security wages (Form 941 or 941-SS, line 5a, Column 1)<br><span class="text-muted small">&times; 0.124* =<br>* If you&apos;re correcting your employer share only, use 0.062. See instructions.</span>'],
        ['line' => '9', 'html' => 'Qualified sick leave wages* (Form 941 or 941-SS, line 5a(i), Column 1)<br><span class="text-muted small">&times; 0.062 =<br>* Use line 9 only for qualified sick leave wages paid after March 31, 2020, for leave taken before April 1, 2021.</span>'],
        ['line' => '10', 'html' => 'Qualified family leave wages* (Form 941 or 941-SS, line 5a(ii), Column 1)<br><span class="text-muted small">&times; 0.062 =<br>* Use line 10 only for qualified family leave wages paid after March 31, 2020, for leave taken before April 1, 2021.</span>'],
        ['line' => '11', 'html' => 'Taxable social security tips (Form 941 or 941-SS, line 5b, Column 1)<br><span class="text-muted small">&times; 0.124* =<br>* If you&apos;re correcting your employer share only, use 0.062. See instructions.</span>'],
        ['line' => '12', 'html' => 'Taxable Medicare wages &amp; tips (Form 941 or 941-SS, line 5c, Column 1)<br><span class="text-muted small">&times; 0.029* =<br>* If you&apos;re correcting your employer share only, use 0.0145. See instructions.</span>'],
        ['line' => '13', 'html' => 'Taxable wages &amp; tips subject to Additional Medicare Tax withholding (Form 941 or 941-SS, line 5d)<br><span class="text-muted small">&times; 0.009* =<br>* Certain wages and tips reported in Column 3 shouldn&apos;t be multiplied by 0.009. See instructions.</span>'],
        ['line' => '14', 'html' => 'Section 3121(q) Notice and Demand—Tax due on unreported tips (Form 941 or 941-SS, line 5f)<br><span class="text-muted small">Copy Column 3 here . . .</span>'],
        ['line' => '15', 'html' => 'Tax adjustments (Form 941 or 941-SS, lines 7 through 9)<br><span class="text-muted small">Copy Column 3 here . . .</span>'],
        ['line' => '16', 'html' => 'Qualified small business payroll tax credit for increasing research activities (See instructions; you must attach Form 8974.)<br><span class="text-muted small">See instructions .</span>'],
        ['line' => '17', 'html' => 'Nonrefundable portion of credit for qualified sick and family leave wages for leave taken before April 1, 2021 (Form 941 or 941-SS, line 11b)<br><span class="text-muted small">See instructions .</span>'],
        ['line' => '18a', 'html' => 'Reserved for future use'],
        ['line' => '18b', 'html' => 'Nonrefundable portion of credit for qualified sick and family leave wages for leave taken after March 31, 2021, and before October 1, 2021 (Form 941 or 941-SS, line 11d)<br><span class="text-muted small">See instructions .</span>'],
        ['line' => '18c', 'html' => 'Nonrefundable portion of COBRA premium assistance credit (Form 941 or 941-SS, line 11e)<br><span class="text-muted small">See instructions .</span>'],
        ['line' => '18d', 'html' => 'Number of individuals provided COBRA premium assistance (Form 941 or 941-SS, line 11f)'],
        ['line' => '19', 'html' => 'Special addition to wages for federal income tax<br><span class="text-muted small">See instructions .</span>'],
        ['line' => '20', 'html' => 'Special addition to wages for social security taxes<br><span class="text-muted small">See instructions .</span>'],
        ['line' => '21', 'html' => 'Special addition to wages for Medicare taxes<br><span class="text-muted small">See instructions .</span>'],
        ['line' => '22', 'html' => 'Special addition to wages for Additional Medicare Tax<br><span class="text-muted small">See instructions .</span>'],
    ];
    $p3ThreeA = [
        ['line' => '28', 'html' => 'Qualified health plan expenses allocable to qualified sick leave wages for leave taken before April 1, 2021 (Form 941 or 941-SS, line 19)'],
        ['line' => '29', 'html' => 'Qualified health plan expenses allocable to qualified family leave wages for leave taken before April 1, 2021 (Form 941 or 941-SS, line 20)'],
        ['line' => '30', 'html' => 'Reserved for future use'],
        ['line' => '31a', 'html' => 'Reserved for future use'],
        ['line' => '31b', 'html' => 'Reserved for future use'],
        ['line' => '32', 'html' => 'Reserved for future use'],
        ['line' => '33a', 'html' => 'Reserved for future use'],
        ['line' => '33b', 'html' => 'Reserved for future use'],
        ['line' => '34', 'html' => 'Reserved for future use'],
    ];
    $p3ThreeB = [
        ['line' => '35', 'html' => 'Qualified sick leave wages for leave taken after March 31, 2021, and before October 1, 2021 (Form 941 or 941-SS, line 23)'],
        ['line' => '36', 'html' => 'Qualified health plan expenses allocable to qualified sick leave wages for leave taken after March 31, 2021, and before October 1, 2021 (Form 941 or 941-SS, line 24)'],
        ['line' => '37', 'html' => 'Amounts under certain collectively bargained agreements allocable to qualified sick leave wages for leave taken after March 31, 2021, and before October 1, 2021 (Form 941 or 941-SS, line 25)'],
        ['line' => '38', 'html' => 'Qualified family leave wages for leave taken after March 31, 2021, and before October 1, 2021 (Form 941 or 941-SS, line 26)'],
        ['line' => '39', 'html' => 'Qualified health plan expenses allocable to qualified family leave wages for leave taken after March 31, 2021, and before October 1, 2021 (Form 941 or 941-SS, line 27)'],
        ['line' => '40', 'html' => 'Amounts under certain collectively bargained agreements allocable to qualified family leave wages for leave taken after March 31, 2021, and before October 1, 2021 (Form 941 or 941-SS, line 28)'],
    ];
@endphp

<div class="border rounded p-3 mb-3 bg-light">
    <div class="fw-semibold mb-2">Part 3: Enter the corrections for this quarter. If any line doesn&apos;t apply, leave it blank.</div>
    <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle mb-0 text-break">
            <thead class="table-light">
                <tr>
                    <th style="width:3rem;">#</th>
                    <th style="min-width:14rem;"></th>
                    <th class="small" style="min-width:6.5rem;">Column 1<br>Total corrected amount (for ALL employees)</th>
                    <th class="small" style="min-width:6.5rem;">Column 2<br>Amount originally reported or as previously corrected (for ALL employees)</th>
                    <th class="small" style="min-width:6.5rem;">Column 3<br>Difference (If this amount is a negative number, use a minus sign.)</th>
                    <th class="small" style="min-width:6.5rem;">Column 4<br>Tax correction</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($p3Four as $row)
                    @php $lid = 'f941x-l'.$row['line']; @endphp
                    <tr>
                        <td class="fw-semibold text-center">{{ $row['line'] }}</td>
                        <td class="small">{!! $row['html'] !!}</td>
                        <td class="p-1"><x-f941x-amt :id="$lid.'-c1'" :pdf="$pdfMode" :fields="$fields" /></td>
                        <td class="p-1"><x-f941x-amt :id="$lid.'-c2'" :pdf="$pdfMode" :fields="$fields" /></td>
                        <td class="p-1"><x-f941x-amt :id="$lid.'-c3'" :pdf="$pdfMode" :fields="$fields" /></td>
                        <td class="p-1"><x-f941x-amt :id="$lid.'-c4'" :pdf="$pdfMode" :fields="$fields" /></td>
                    </tr>
                @endforeach
                <tr>
                    <td class="fw-semibold text-center">23</td>
                    <td class="small">Combine the amounts on lines 7 through 22 of Column 4</td>
                    <td class="p-1 bg-light border-0" colspan="3"></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l23-c4" :pdf="$pdfMode" :fields="$fields" /></td>
                </tr>
                <tr>
                    <td class="fw-semibold text-center">24</td>
                    <td class="small">Reserved for future use</td>
                    <td class="p-1"><x-f941x-amt id="f941x-l24-c1" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l24-c2" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l24-c3" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l24-c4" :pdf="$pdfMode" :fields="$fields" /></td>
                </tr>
                <tr>
                    <td class="fw-semibold text-center">25</td>
                    <td class="small">Refundable portion of credit for qualified sick and family leave wages for leave taken before April 1, 2021 (Form 941 or 941-SS, line 13c)<br><span class="text-muted small">See instructions .</span></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l25-c1" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l25-c2" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l25-c3" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l25-c4" :pdf="$pdfMode" :fields="$fields" /></td>
                </tr>
                <tr>
                    <td class="fw-semibold text-center">26a</td>
                    <td class="small">Reserved for future use</td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26a-c1" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26a-c2" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26a-c3" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26a-c4" :pdf="$pdfMode" :fields="$fields" /></td>
                </tr>
                <tr>
                    <td class="fw-semibold text-center">26b</td>
                    <td class="small">Refundable portion of credit for qualified sick and family leave wages for leave taken after March 31, 2021, and before October 1, 2021 (Form 941 or 941-SS, line 13e)<br><span class="text-muted small">See instructions .</span></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26b-c1" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26b-c2" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26b-c3" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26b-c4" :pdf="$pdfMode" :fields="$fields" /></td>
                </tr>
                <tr>
                    <td class="fw-semibold text-center">26c</td>
                    <td class="small">Refundable portion of COBRA premium assistance credit (Form 941 or 941-SS, line 13f)<br><span class="text-muted small">See instructions .</span></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26c-c1" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26c-c2" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26c-c3" :pdf="$pdfMode" :fields="$fields" /></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l26c-c4" :pdf="$pdfMode" :fields="$fields" /></td>
                </tr>
                <tr>
                    <td class="fw-semibold text-center">27</td>
                    <td class="small">Total. Combine the amounts on lines 23 through 26c of Column 4</td>
                    <td class="p-1 bg-light border-0" colspan="3"></td>
                    <td class="p-1"><x-f941x-amt id="f941x-l27-c4" :pdf="$pdfMode" :fields="$fields" /></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="small text-muted mt-2 mb-3">
        <p class="mb-1">If line 27 is less than zero:</p>
        <ul class="mb-2 ps-3">
            <li>If you checked line 1, this is the amount you want applied as a credit to your Form 941 for the tax period in which you&apos;re filing this form. (If you&apos;re currently filing a Form 944, Employer&apos;s ANNUAL Federal Tax Return, see the instructions.)</li>
            <li>If you checked line 2, this is the amount you want refunded or abated.</li>
        </ul>
        <p class="mb-0">If line 27 is more than zero, this is the amount you owe. Pay this amount by the time you file this return. For information on how to pay, see Amount you owe in the instructions.</p>
    </div>

    <div class="fw-semibold mb-2">Part 3: Enter the corrections for this quarter. If any line doesn&apos;t apply, leave it blank. (continued)</div>
    <div class="table-responsive mb-3">
        <table class="table table-bordered table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:3rem;">#</th>
                    <th style="min-width:14rem;"></th>
                    <th class="small" style="min-width:7rem;">Column 1<br>Total corrected amount (for ALL employees)</th>
                    <th class="small" style="min-width:7rem;">Column 2<br>Amount originally reported or as previously corrected (for ALL employees)</th>
                    <th class="small" style="min-width:7rem;">Column 3<br>Difference (If this amount is a negative number, use a minus sign.)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($p3ThreeA as $row)
                    @php $lid = 'f941x-l'.$row['line']; @endphp
                    <tr>
                        <td class="fw-semibold text-center">{{ $row['line'] }}</td>
                        <td class="small">{{ $row['html'] }}</td>
                        <td class="p-1"><x-f941x-amt :id="$lid.'-c1'" :pdf="$pdfMode" :fields="$fields" /></td>
                        <td class="p-1"><x-f941x-amt :id="$lid.'-c2'" :pdf="$pdfMode" :fields="$fields" /></td>
                        <td class="p-1"><x-f941x-amt :id="$lid.'-c3'" :pdf="$pdfMode" :fields="$fields" /></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="small fw-semibold">Caution: Lines 35–40 apply only to quarters beginning after March 31, 2021.</td>
                </tr>
                @foreach ($p3ThreeB as $row)
                    @php $lid = 'f941x-l'.$row['line']; @endphp
                    <tr>
                        <td class="fw-semibold text-center">{{ $row['line'] }}</td>
                        <td class="small">{{ $row['html'] }}</td>
                        <td class="p-1"><x-f941x-amt :id="$lid.'-c1'" :pdf="$pdfMode" :fields="$fields" /></td>
                        <td class="p-1"><x-f941x-amt :id="$lid.'-c2'" :pdf="$pdfMode" :fields="$fields" /></td>
                        <td class="p-1"><x-f941x-amt :id="$lid.'-c3'" :pdf="$pdfMode" :fields="$fields" /></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
