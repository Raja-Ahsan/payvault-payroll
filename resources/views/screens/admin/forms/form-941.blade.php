@section('title', 'Form 941')
@extends('layouts.admin.master')
@php
    $emp = $employer941 ?? [];
    $ty = (int) ($taxYear ?? now()->format('Y'));
    $einStr = trim((string) ($emp['ein'] ?? ''));
    $einDigits = preg_replace('/\D/', '', $einStr);
    $einDisplay = strlen($einDigits) === 9
        ? substr($einDigits, 0, 2).'-'.substr($einDigits, 2, 7)
        : $einStr;
    $nameLegal = (string) ($emp['legal_name'] ?? '');
    $tradeName = (string) ($emp['trade_name'] ?? '');
    $addr1 = (string) ($emp['address_line1'] ?? '');
    $city = (string) ($emp['city'] ?? '');
    $st = (string) ($emp['state_code'] ?? '');
    $zip = (string) ($emp['zip_code'] ?? '');
    $m = $form941Metrics ?? [];
@endphp
@section('content')
<style>
.form-941-wrapper label,
.form-941-wrapper .form-label {
    margin-bottom: 10px !important;
}
@media print {
    .card-header .btn { display: none !important; }
    .f941-screen-toolbar { display: none !important; }
}
.f941-preview-scroll .f941-print-root {
    font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
}
</style>
<div class="container-fluid form-941-wrapper custom-form-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border d-flex flex-wrap align-items-center justify-content-end gap-2">
                    <a href="{{ route('admin.forms.index') }}" class="btn btn-sm button-light-primary">Back to Forms</a>
                </div>
                <div class="card-body small">
                    <div class="d-flex flex-wrap gap-2 justify-content-end mb-2 f941-screen-toolbar">
                        <a href="{{ route('admin.forms.form-941-x') }}" class="btn btn-primary btn-sm">941-X</a>
                        <a href="{{ route('admin.forms.form-941-schedule-b') }}" class="btn btn-primary btn-sm">Schedule B</a>
                        <a href="{{ route('admin.forms.form-941-schedule-r') }}" class="btn btn-primary btn-sm">Schedule R</a>
                        <button type="button" class="btn btn-primary btn-sm" id="f941BtnPreparer">Preparer / Designee</button>
                        <button type="button" class="btn btn-primary btn-sm" id="f941BtnPreview">Preview</button>
                        <button type="button" class="btn btn-primary btn-sm" id="f941BtnPrint">Download PDF</button>
                        <button type="button" class="btn btn-primary btn-sm" id="f941BtnOverride">Override calculations</button>
                    </div>
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="fw-semibold">Form 941 for {{ $ty }}: Employer&apos;s QUARTERLY Federal Tax Return</div>
                        <div class="text-muted">(Rev. March {{ $ty }}) Department of the Treasury &mdash; Internal Revenue Service &middot; OMB No. 1545-0029</div>
                    </div>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="fw-semibold mb-2">Employer information</div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941-ein">Employer identification number (EIN)</label>
                                <input type="text" id="f941-ein" class="form-control form-control-sm f941-txt f941-no-override" value="{{ $einDisplay }}" maxlength="20" readonly>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label mb-1" for="f941-name">Name (not your trade name)</label>
                                <input type="text" id="f941-name" class="form-control form-control-sm f941-txt f941-no-override" value="{{ $nameLegal }}" readonly>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-12">
                                <label class="form-label mb-1" for="f941-trade">Trade name (if any)</label>
                                <input type="text" id="f941-trade" class="form-control form-control-sm f941-txt f941-no-override" value="{{ $tradeName }}" readonly>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-12">
                                <label class="form-label mb-1" for="f941-addr">Address (number, street, suite)</label>
                                <input type="text" id="f941-addr" class="form-control form-control-sm f941-txt f941-no-override" value="{{ $addr1 }}" readonly>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5">
                                <label class="form-label mb-1" for="f941-city">City</label>
                                <input type="text" id="f941-city" class="form-control form-control-sm f941-txt f941-no-override" value="{{ $city }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-1" for="f941-state">State</label>
                                <input type="text" id="f941-state" class="form-control form-control-sm f941-txt f941-no-override" value="{{ $st }}" maxlength="2" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1" for="f941-zip">ZIP code</label>
                                <input type="text" id="f941-zip" class="form-control form-control-sm f941-txt f941-no-override" value="{{ $zip }}" readonly>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941-fc">Foreign country name</label>
                                <input type="text" id="f941-fc" class="form-control form-control-sm f941-txt f941-no-override" value="" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941-fp">Foreign province/county</label>
                                <input type="text" id="f941-fp" class="form-control form-control-sm f941-txt f941-no-override" value="" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941-fz">Foreign postal code</label>
                                <input type="text" id="f941-fz" class="form-control form-control-sm f941-txt f941-no-override" value="" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="fw-semibold mb-2">Report for this quarter of {{ $ty }} (check one)</div>
                        @php $cq = (int) ($m['current_quarter'] ?? 1); @endphp
                        <div class="form-check"><input class="form-check-input f941-cb" type="radio" name="f941_quarter" id="f941-q1" @checked($cq === 1) disabled><label class="form-check-label" for="f941-q1">January, February, March</label></div>
                        <div class="form-check"><input class="form-check-input f941-cb" type="radio" name="f941_quarter" id="f941-q2" @checked($cq === 2) disabled><label class="form-check-label" for="f941-q2">April, May, June</label></div>
                        <div class="form-check"><input class="form-check-input f941-cb" type="radio" name="f941_quarter" id="f941-q3" @checked($cq === 3) disabled><label class="form-check-label" for="f941-q3">July, August, September</label></div>
                        <div class="form-check mb-0"><input class="form-check-input f941-cb" type="radio" name="f941_quarter" id="f941-q4" @checked($cq === 4) disabled><label class="form-check-label" for="f941-q4">October, November, December</label></div>
                    </div>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="fw-semibold mb-2">Aggregate return filers only</div>
                        <div class="text-muted small mb-2">Type of filer (check one):</div>
                        <div class="form-check"><input class="form-check-input f941-cb" type="radio" name="f941_agg" id="f941-agg-3504" disabled><label class="form-check-label" for="f941-agg-3504">Section 3504 Agent</label></div>
                        <div class="form-check"><input class="form-check-input f941-cb" type="radio" name="f941_agg" id="f941-agg-cpeo" disabled><label class="form-check-label" for="f941-agg-cpeo">Certified Professional Employer Organization (CPEO)</label></div>
                        <div class="form-check mb-0"><input class="form-check-input f941-cb" type="radio" name="f941_agg" id="f941-agg-other" disabled><label class="form-check-label" for="f941-agg-other">Other Third Party</label></div>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-3">Part 1: Answer these questions for this quarter</div>
                    <p class="text-muted small mb-3">If any line does NOT apply, leave it blank. American Samoa, Guam, the Commonwealth of the Northern Mariana Islands, the U.S. Virgin Islands, and Puerto Rico have special rules. See Pub. 80 if you need information for those locations.</p>
                    <p class="text-muted small mb-3">Lines 1&ndash;2 and 5a&ndash;5c are built from active employees and income categories (excluding categories marked &quot;omit net pay&quot;). Tips on line 5b use categories marked as reported tips. Federal withholding (line 3) and deposits (line 13) require recorded payroll checks and are shown as 0 until that data exists.</p>

                    <div class="row mb-2 align-items-center g-2 rounded py-2 px-1 bg-primary bg-opacity-10">
                        <div class="col">
                            <label class="form-label mb-0" for="f941-l1"><span class="badge bg-secondary me-1">1</span> Number of employees who received wages, tips, or other compensation for the pay period including: Mar. 12 (Q1), June 12 (Q2), Sept. 12 (Q3), or Dec. 12 (Q4)</label>
                        </div>
                        <div class="col-auto" style="min-width: 5rem;">
                            <input type="text" id="f941-l1" class="form-control form-control-sm text-end f941-num" maxlength="9" value="{{ (int) ($m['line1'] ?? 0) }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f941-l2"><span class="badge bg-secondary me-1">2</span> Wages, tips, and other compensation</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f941-l2" class="form-control form-control-sm text-end f941-num" maxlength="14" value="{{ number_format((float) ($m['line2'] ?? 0), 2, '.', ',') }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f941-l3"><span class="badge bg-secondary me-1">3</span> Federal income tax withheld from wages, tips, and other compensation</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f941-l3" class="form-control form-control-sm text-end f941-num" maxlength="14" value="{{ number_format((float) ($m['line3'] ?? 0), 2, '.', ',') }}" readonly>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input f941-cb" type="checkbox" id="f941-l4" @checked(! empty($m['line4_no_ss_medicare'])) disabled>
                        <label class="form-check-label" for="f941-l4"><span class="badge bg-secondary me-1">4</span> If no wages, tips, and other compensation are subject to social security or Medicare tax, check here and go to line 6.</label>
                    </div>

                    <div class="fw-semibold mb-2">Lines 5a&ndash;5d (social security and Medicare)</div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f941-l5a1"><span class="badge bg-secondary me-1">5a</span> Taxable social security wages (Column 1)</label>
                        </div>
                        <div class="col-auto" style="min-width: 6rem;"><input type="text" id="f941-l5a1" class="form-control form-control-sm text-end f941-num" value="{{ number_format((float) ($m['line5a_c1'] ?? 0), 2, '.', ',') }}" readonly></div>
                        <div class="col-auto small text-muted">&times; 0.124</div>
                        <div class="col-auto" style="min-width: 6rem;"><input type="text" id="f941-l5a2" class="form-control form-control-sm text-end f941-num f941-derive" value="{{ number_format((float) ($m['line5a_c2'] ?? 0), 2, '.', ',') }}" readonly></div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f941-l5b1"><span class="badge bg-secondary me-1">5b</span> Taxable social security tips (Column 1)</label>
                        </div>
                        <div class="col-auto" style="min-width: 6rem;"><input type="text" id="f941-l5b1" class="form-control form-control-sm text-end f941-num" value="{{ number_format((float) ($m['line5b_c1'] ?? 0), 2, '.', ',') }}" readonly></div>
                        <div class="col-auto small text-muted">&times; 0.124</div>
                        <div class="col-auto" style="min-width: 6rem;"><input type="text" id="f941-l5b2" class="form-control form-control-sm text-end f941-num f941-derive" value="{{ number_format((float) ($m['line5b_c2'] ?? 0), 2, '.', ',') }}" readonly></div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f941-l5c1"><span class="badge bg-secondary me-1">5c</span> Taxable Medicare wages &amp; tips (Column 1)</label>
                        </div>
                        <div class="col-auto" style="min-width: 6rem;"><input type="text" id="f941-l5c1" class="form-control form-control-sm text-end f941-num" value="{{ number_format((float) ($m['line5c_c1'] ?? 0), 2, '.', ',') }}" readonly></div>
                        <div class="col-auto small text-muted">&times; 0.029</div>
                        <div class="col-auto" style="min-width: 6rem;"><input type="text" id="f941-l5c2" class="form-control form-control-sm text-end f941-num f941-derive" value="{{ number_format((float) ($m['line5c_c2'] ?? 0), 2, '.', ',') }}" readonly></div>
                    </div>
                    <div class="row mb-3 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f941-l5d1"><span class="badge bg-secondary me-1">5d</span> Taxable wages &amp; tips subject to Additional Medicare Tax withholding (Column 1)</label>
                        </div>
                        <div class="col-auto" style="min-width: 6rem;"><input type="text" id="f941-l5d1" class="form-control form-control-sm text-end f941-num" value="{{ number_format((float) ($m['line5d_c1'] ?? 0), 2, '.', ',') }}" readonly></div>
                        <div class="col-auto small text-muted">&times; 0.009</div>
                        <div class="col-auto" style="min-width: 6rem;"><input type="text" id="f941-l5d2" class="form-control form-control-sm text-end f941-num f941-derive" value="{{ number_format((float) ($m['line5d_c2'] ?? 0), 2, '.', ',') }}" readonly></div>
                    </div>

                    @foreach ([
                        ['id' => 'f941-l5e', 'line' => '5e', 'text' => 'Total social security and Medicare taxes. Add Column 2 from lines 5a, 5b, 5c, and 5d', 'key' => 'line5e'],
                        ['id' => 'f941-l5f', 'line' => '5f', 'text' => 'Section 3121(q) Notice and Demand—Tax due on unreported tips (see instructions)', 'key' => 'line5f'],
                        ['id' => 'f941-l6', 'line' => '6', 'text' => 'Total taxes before adjustments. Add lines 3, 5e, and 5f', 'key' => 'line6'],
                        ['id' => 'f941-l7', 'line' => '7', 'text' => 'Current quarter\'s adjustment for fractions of cents', 'key' => 'line7'],
                        ['id' => 'f941-l8', 'line' => '8', 'text' => 'Current quarter\'s adjustment for sick pay', 'key' => 'line8'],
                        ['id' => 'f941-l9', 'line' => '9', 'text' => 'Current quarter\'s adjustments for tips and group-term life insurance', 'key' => 'line9'],
                        ['id' => 'f941-l10', 'line' => '10', 'text' => 'Total taxes after adjustments. Combine lines 6 through 9', 'key' => 'line10'],
                        ['id' => 'f941-l11', 'line' => '11', 'text' => 'Qualified small business payroll tax credit for increasing research activities. Attach Form 8974', 'key' => 'line11'],
                    ] as $row)
                        <div class="row mb-2 align-items-center g-2">
                            <div class="col">
                                <label class="form-label mb-0" for="{{ $row['id'] }}"><span class="badge bg-secondary me-1">{{ $row['line'] }}</span> {{ $row['text'] }}</label>
                            </div>
                            <div class="col-auto" style="min-width: 7rem;">
                                <input type="text" id="{{ $row['id'] }}" class="form-control form-control-sm text-end f941-num @if(in_array($row['id'], ['f941-l5e','f941-l6','f941-l10'], true)) f941-derive @endif" maxlength="14" value="{{ number_format((float) ($m[$row['key']] ?? 0), 2, '.', ',') }}" readonly>
                            </div>
                        </div>
                    @endforeach

                    <div class="fw-bold border-bottom pb-2 mb-3 mt-4">Part 1 (continued): Deposits, balance due, overpayment</div>
                    @foreach ([
                        ['id' => 'f941-l12', 'line' => '12', 'text' => 'Total taxes after adjustments and nonrefundable credits. Subtract line 11 from line 10', 'key' => 'line12'],
                        ['id' => 'f941-l13', 'line' => '13', 'text' => 'Total deposits for this quarter, including overpayment applied from a prior quarter and overpayments applied from Form 941-X, 941-X (PR), or 944-X filed in the current quarter', 'key' => 'line13'],
                        ['id' => 'f941-l14', 'line' => '14', 'text' => 'Balance due. If line 12 is more than line 13, enter the difference', 'key' => 'line14'],
                        ['id' => 'f941-l15a', 'line' => '15a', 'text' => 'Overpayment. If line 13 is more than line 12, enter the difference', 'key' => 'line15a'],
                    ] as $row)
                        <div class="row mb-2 align-items-center g-2">
                            <div class="col">
                                <label class="form-label mb-0" for="{{ $row['id'] }}"><span class="badge bg-secondary me-1">{{ $row['line'] }}</span> {{ $row['text'] }}</label>
                            </div>
                            <div class="col-auto" style="min-width: 7rem;">
                                <input type="text" id="{{ $row['id'] }}" class="form-control form-control-sm text-end f941-num @if(in_array($row['id'], ['f941-l12','f941-l14','f941-l15a'], true)) f941-derive @endif" maxlength="14" value="{{ number_format((float) ($m[$row['key']] ?? 0), 2, '.', ',') }}" readonly>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-check form-check-inline ms-1 mb-2">
                        <input class="form-check-input f941-cb" type="radio" name="f941_l15b" id="f941-l15b-next" disabled>
                        <label class="form-check-label" for="f941-l15b-next"><span class="badge bg-secondary me-1">15b</span> Apply to next return</label>
                    </div>
                    <div class="form-check form-check-inline mb-3">
                        <input class="form-check-input f941-cb" type="radio" name="f941_l15b" id="f941-l15b-refund" disabled>
                        <label class="form-check-label" for="f941-l15b-refund">Send a refund</label>
                    </div>
                    <div class="row mb-2 align-items-end g-2">
                        <div class="col-md-6">
                            <label class="form-label mb-1" for="f941-l15c"><span class="badge bg-secondary me-1">15c</span> Routing number</label>
                            <input type="text" id="f941-l15c" class="form-control form-control-sm f941-txt" maxlength="9" value="" readonly>
                        </div>
                        <div class="col-md-6">
                            <span class="badge bg-secondary me-1">15d</span>
                            <span class="form-label d-inline">Account type</span>
                            <div class="mt-1">
                                <div class="form-check form-check-inline"><input class="form-check-input f941-cb" type="radio" name="f941_l15d" id="f941-l15d-chk" disabled><label class="form-check-label" for="f941-l15d-chk">Checking</label></div>
                                <div class="form-check form-check-inline"><input class="form-check-input f941-cb" type="radio" name="f941_l15d" id="f941-l15d-sav" disabled><label class="form-check-label" for="f941-l15d-sav">Savings</label></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 align-items-center g-2">
                        <div class="col-12">
                            <label class="form-label mb-1" for="f941-l15e"><span class="badge bg-secondary me-1">15e</span> Account number</label>
                            <input type="text" id="f941-l15e" class="form-control form-control-sm f941-txt" maxlength="17" value="" readonly>
                        </div>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-3">Part 2: Tell us about your deposit schedule and tax liability for this quarter.</div>
                    <p class="text-muted small mb-2">If you&apos;re unsure about whether you&apos;re a monthly schedule depositor or a semiweekly schedule depositor, see section 11 of Pub. 15.</p>
                    <div class="form-check mb-2">
                        <input class="form-check-input f941-cb" type="radio" name="f941_l16" id="f941-l16a" @checked(! empty($m['line12_under_2500'])) disabled>
                        <label class="form-check-label" for="f941-l16a"><span class="badge bg-secondary me-1">16</span> Line 12 on this return is less than $2,500 or line 12 on the return for the prior quarter was less than $2,500, and you didn&apos;t incur a $100,000 next-day deposit obligation during the current quarter. If line 12 for the prior quarter was less than $2,500 but line 12 on this return is $100,000 or more, you must provide a record of your federal tax liability. If you&apos;re a monthly schedule depositor, complete the deposit schedule below; if you&apos;re a semiweekly schedule depositor, attach Schedule B (Form 941). Go to Part 3.</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input f941-cb" type="radio" name="f941_l16" id="f941-l16b" @checked(empty($m['line12_under_2500'])) disabled>
                        <label class="form-check-label" for="f941-l16b">You were a monthly schedule depositor for the entire quarter. Enter your tax liability for each month and total liability for the quarter, then go to Part 3.</label>
                    </div>
                    <div class="ms-3 mb-2 border-start ps-3">
                        <div class="small text-muted mb-2">Tax liability:</div>
                        @foreach ([['f941-m1', 'Month 1', false], ['f941-m2', 'Month 2', false], ['f941-m3', 'Month 3', false], ['f941-mtot', 'Total liability for quarter', true]] as $mo)
                            <div class="row mb-2 align-items-center g-2">
                                <div class="col"><label class="form-label mb-0" for="{{ $mo[0] }}">{{ $mo[1] }}</label></div>
                                <div class="col-auto" style="min-width: 7rem;"><input type="text" id="{{ $mo[0] }}" class="form-control form-control-sm text-end f941-num @if(!empty($mo[2])) f941-derive @endif" value="" readonly></div>
                            </div>
                        @endforeach
                        <p class="text-muted small mb-0">Total must equal line 12.</p>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input f941-cb" type="radio" name="f941_l16" id="f941-l16c" disabled>
                        <label class="form-check-label" for="f941-l16c">You were a semiweekly schedule depositor for any part of this quarter. Complete Schedule B (Form 941), Report of Tax Liability for Semiweekly Schedule Depositors, and attach it to Form 941. Go to Part 3.</label>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-3">Part 3: Tell us about your business. If a question does NOT apply to your business, leave it blank.</div>
                    <div class="form-check mb-2">
                        <input class="form-check-input f941-cb" type="checkbox" id="f941-l17" disabled>
                        <label class="form-check-label" for="f941-l17"><span class="badge bg-secondary me-1">17</span> If your business has closed or you stopped paying wages, check here and enter the final date you paid wages; also attach a statement to your return. See instructions.</label>
                    </div>
                    <div class="row mb-3 align-items-center g-2">
                        <div class="col-auto">
                            <label class="form-label mb-0" for="f941-l17d">Final date wages paid</label>
                        </div>
                        <div class="col-auto" style="min-width: 10rem;">
                            <input type="text" id="f941-l17d" class="form-control form-control-sm f941-txt" placeholder="MM / DD / YYYY" value="" readonly>
                        </div>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input f941-cb" type="checkbox" id="f941-l18" disabled>
                        <label class="form-check-label" for="f941-l18"><span class="badge bg-secondary me-1">18</span> If you&apos;re a seasonal employer and you don&apos;t have to file a return for every quarter of the year, check here.</label>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-3">Part 4: May we speak with your third-party designee?</div>
                    <p class="text-muted small mb-2">Do you want to allow an employee, a paid tax preparer, or another person to discuss this return with the IRS? See the instructions for details.</p>
                    <div class="form-check mb-2">
                        <input class="form-check-input f941-cb" type="radio" name="f941_l19" id="f941-l19y" disabled>
                        <label class="form-check-label" for="f941-l19y">Yes. Designee&apos;s name and phone number<br><span class="small text-muted">Select a 5-digit personal identification number (PIN) to use when talking to the IRS.</span></label>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input f941-cb" type="radio" name="f941_l19" id="f941-l19n" disabled>
                        <label class="form-check-label" for="f941-l19n">No.</label>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-2">Part 5: Sign here. You MUST complete both pages of Form 941 and SIGN it.</div>
                    <p class="text-muted small mb-2">Under penalties of perjury, I declare that I have examined this return, including accompanying schedules and statements, and to the best of my knowledge and belief, it is true, correct, and complete. Declaration of preparer (other than taxpayer) is based on all information of which preparer has any knowledge.</p>
                    <p class="text-muted small mb-0">Signature, date, title, paid preparer, and daytime phone are completed on the official paper form or through your tax professional.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modals.modal id="f941OverrideModal" title="Warning" size="modal-lg">
    <div class="d-flex gap-3">
        <div class="fs-2 text-warning flex-shrink-0"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="small lh-lg">
            <p class="mb-2">After you continue, you can edit the Form 941 fields on this screen and amounts will recalculate (lines 5a&ndash;5d column 2, 5e, 6, 10, 12, 14, 15a, and monthly total) from your entries.</p>
            <p class="mb-2">Changes apply only to this screen and are not saved to the database.</p>
            <p class="mb-2">They may not match other tax forms until payroll check history is integrated.</p>
            <p class="mb-3">Use &quot;Enable calculations again&quot; to return to the read-only view built from your company data.</p>
            <div class="form-check d-flex gap-2">
                <input class="form-check-input" type="checkbox" id="f941OverrideAck">
                <label class="form-check-label" for="f941OverrideAck">By checking this box you acknowledge that you are making changes at your own risk and understand that you might be required to submit corrected tax forms in the future.</label>
            </div>
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="f941OverrideOk" disabled>OK</button>
    </x-slot>
</x-modals.modal>

<x-modals.modal id="f941PreparerModal" title="Preparer / Designee" size="modal-lg">
    <p class="small mb-0">Third-party designee and paid preparer details are completed on the official Form 941. Employer information comes from your company profile. Use Override calculations to adjust Part 1 numbers on this screen, then Download PDF if needed.</p>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </x-slot>
</x-modals.modal>

<x-modals.modal id="f941PreviewModal" title="Preview — Form 941" size="modal-fullscreen">
    <div id="f941PreviewMount" class="f941-preview-scroll bg-opacity-10 p-2 p-md-3" style="max-height: calc(100vh - 11rem); overflow-y: auto;"></div>
    <x-slot name="footer">
        <button type="button" class="btn btn-primary" id="f941PreviewPrint">Print</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </x-slot>
</x-modals.modal>
@endsection

@push('scripts')
<script>
window.__F941_TAX_YEAR = @json($ty);
window.__F941_EMPLOYER = @json($emp ?? []);
window.__F941_PDF_URL = @json(route('admin.forms.form-941.pdf'));
window.__F941_CQ = @json((int) ($m['current_quarter'] ?? 1));
</script>
@include('screens.admin.forms.partials.form-941-script')
<script>
(function () {
    var printBtn = document.getElementById('f941BtnPrint');
    if (!printBtn || !window.__F941_PDF_URL) {
        return;
    }
    printBtn.addEventListener('click', function () {
        var meta = document.querySelector('meta[name="csrf-token"]');
        if (!meta) {
            alert('Missing CSRF token. Refresh the page and try again.');
            return;
        }
        var fields = {};
        var checks = {};
        document.querySelectorAll('input[id^="f941-"], textarea[id^="f941-"]').forEach(function (el) {
            var id = el.id;
            if (!id) {
                return;
            }
            if (el.type === 'checkbox' || el.type === 'radio') {
                checks[id] = !!el.checked;
            } else {
                fields[id] = el.value == null ? '' : String(el.value);
            }
        });
        printBtn.disabled = true;
        fetch(window.__F941_PDF_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/pdf',
                'X-CSRF-TOKEN': meta.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ snapshot: { fields: fields, checks: checks } })
        }).then(function (res) {
            if (!res.ok) {
                throw new Error('bad status');
            }
            return res.blob();
        }).then(function (blob) {
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'Form-941-' + String(window.__F941_TAX_YEAR || '') + '-Q' + String(window.__F941_CQ || '1') + '.pdf';
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        }).catch(function () {
            alert('Could not generate the PDF. Please try again.');
        }).finally(function () {
            printBtn.disabled = false;
        });
    });
})();
</script>
@endpush
