@section('title', 'Form 941-X')
@extends('layouts.admin.master')
@php
    $emp = $employer941x ?? [];
    $ty = (int) ($taxYear ?? now()->format('Y'));
    $cq = (int) ($currentQuarter ?? 1);
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
@endphp
@section('content')
<style>
.form-941x-wrapper label,
.form-941x-wrapper .form-label {
    margin-bottom: 10px !important;
}
@media print {
    .card-header .btn { display: none !important; }
    .f941x-screen-toolbar { display: none !important; }
}
</style>
<div class="container-fluid form-941x-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.forms.index') }}" class="btn btn-sm button-light-primary">Back to Forms</a>
                        <a href="{{ route('admin.forms.form-941') }}" class="btn btn-sm button-light-primary">Form 941</a>
                        <a href="{{ route('admin.forms.form-941-schedule-b') }}" class="btn btn-sm button-light-primary">Schedule B</a>
                        <a href="{{ route('admin.forms.form-941-schedule-r') }}" class="btn btn-sm button-light-primary">Schedule R</a>
                    </div>
                </div>
                <div class="card-body small">
                    <div class="d-flex flex-wrap gap-2 justify-content-end mb-2 f941x-screen-toolbar">
                        <button type="button" class="btn btn-primary btn-sm" id="f941xBtnPrint">Download PDF</button>
                    </div>
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="fw-semibold">Form 941-X: Adjusted Employer&apos;s QUARTERLY Federal Tax Return or Claim for Refund</div>
                        <div class="text-muted">(Rev. April 2025) Department of the Treasury &mdash; Internal Revenue Service &middot; OMB No. 1545-0029</div>
                    </div>

                    <p class="small mb-3">Read the separate instructions before completing this form. Use this form to correct errors you made on Form 941 or 941-SS. Use a separate Form 941-X for each quarter that needs correction. Type or print within the boxes. You MUST complete all five pages. Don&apos;t attach this form to Form 941 unless you&apos;re reclassifying workers; see the instructions for line 42.</p>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="fw-semibold mb-2">Employer information</div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941x-ein">Employer identification number (EIN) &mdash;</label>
                                <input type="text" id="f941x-ein" class="form-control form-control-sm" value="{{ $einDisplay }}" maxlength="20" readonly>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label mb-1" for="f941x-name">Name (not your trade name)</label>
                                <input type="text" id="f941x-name" class="form-control form-control-sm" value="{{ $nameLegal }}" readonly>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-12">
                                <label class="form-label mb-1" for="f941x-trade">Trade name (if any)</label>
                                <input type="text" id="f941x-trade" class="form-control form-control-sm" value="{{ $tradeName }}" readonly>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-12">
                                <label class="form-label mb-1" for="f941x-addr">Address (number, street, suite)</label>
                                <input type="text" id="f941x-addr" class="form-control form-control-sm" value="{{ $addr1 }}" readonly>
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-5">
                                <label class="form-label mb-1" for="f941x-city">City</label>
                                <input type="text" id="f941x-city" class="form-control form-control-sm" value="{{ $city }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-1" for="f941x-state">State</label>
                                <input type="text" id="f941x-state" class="form-control form-control-sm" value="{{ $st }}" maxlength="2" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1" for="f941x-zip">ZIP code</label>
                                <input type="text" id="f941x-zip" class="form-control form-control-sm" value="{{ $zip }}" readonly>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941x-fc">Foreign country name</label>
                                <input type="text" id="f941x-fc" class="form-control form-control-sm" value="" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941x-fp">Foreign province/county</label>
                                <input type="text" id="f941x-fp" class="form-control form-control-sm" value="" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941x-fz">Foreign postal code</label>
                                <input type="text" id="f941x-fz" class="form-control form-control-sm" value="" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="fw-semibold mb-2">Return You&apos;re Correcting...</div>
                        <div class="text-muted small mb-2">Check the type of return you&apos;re correcting.</div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941x_ret_type" id="f941x-ret-941" value="941" checked><label class="form-check-label" for="f941x-ret-941">941</label></div>
                        <div class="form-check mb-3"><input class="form-check-input" type="radio" name="f941x_ret_type" id="f941x-ret-941ss" value="941ss"><label class="form-check-label" for="f941x-ret-941ss">941-SS</label></div>
                        <div class="fw-semibold mb-2">Check the ONE quarter you&apos;re correcting.</div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941x_cq" id="f941x-cq-1" @checked($cq === 1)><label class="form-check-label" for="f941x-cq-1">1: January, February, March</label></div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941x_cq" id="f941x-cq-2" @checked($cq === 2)><label class="form-check-label" for="f941x-cq-2">2: April, May, June</label></div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941x_cq" id="f941x-cq-3" @checked($cq === 3)><label class="form-check-label" for="f941x-cq-3">3: July, August, September</label></div>
                        <div class="form-check mb-3"><input class="form-check-input" type="radio" name="f941x_cq" id="f941x-cq-4" @checked($cq === 4)><label class="form-check-label" for="f941x-cq-4">4: October, November, December</label></div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941x-year-correct">Enter the calendar year of the quarter you&apos;re correcting. (YYYY)</label>
                                <input type="text" id="f941x-year-correct" class="form-control form-control-sm" value="{{ $ty }}" maxlength="4" autocomplete="off">
                            </div>
                        </div>
                        <div class="fw-semibold mb-1">Enter the date you discovered errors. (MM / DD / YYYY)</div>
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label class="form-label mb-1 small" for="f941x-disc-mm">MM</label>
                                <input type="text" id="f941x-disc-mm" class="form-control form-control-sm" value="" maxlength="2" autocomplete="off">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-1 small" for="f941x-disc-dd">DD</label>
                                <input type="text" id="f941x-disc-dd" class="form-control form-control-sm" value="" maxlength="2" autocomplete="off">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-1 small" for="f941x-disc-yyyy">YYYY</label>
                                <input type="text" id="f941x-disc-yyyy" class="form-control form-control-sm" value="" maxlength="4" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="fw-semibold mb-2">Part 1: Select ONLY one process. See page 6 for additional guidance, including information on how to treat employment tax credits.</div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="f941x_p1" id="f941x-p1-1" value="1">
                            <label class="form-check-label" for="f941x-p1-1"><span class="fw-semibold">1.</span> Adjusted employment tax return. Check this box if you underreported tax amounts. Also check this box if you overreported tax amounts and you would like to use the adjustment process to correct the errors. You must check this box if you&apos;re correcting both underreported and overreported tax amounts on this form. The amount shown on line 27, if less than zero, may only be applied as a credit to your Form 941 or Form 944 for the tax period in which you&apos;re filing this form.</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="f941x_p1" id="f941x-p1-2" value="2">
                            <label class="form-check-label" for="f941x-p1-2"><span class="fw-semibold">2.</span> Claim. Check this box if you overreported tax amounts only and you would like to use the claim process to ask for a refund or abatement of the amount shown on line 27. Don&apos;t check this box if you&apos;re correcting ANY underreported tax amounts on this form.</label>
                        </div>
                    </div>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="fw-semibold mb-2">Part 2: Complete the certifications.</div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="f941x-p2-3">
                            <label class="form-check-label" for="f941x-p2-3"><span class="fw-semibold">3.</span> I certify that I&apos;ve filed or will file Forms W-2, Wage and Tax Statement, or Forms W-2c, Corrected Wage and Tax Statement, as required.</label>
                        </div>
                        <p class="text-muted small mb-2">Note: If you&apos;re correcting underreported tax amounts only, go to Part 3 on page 2 and skip lines 4 and 5. If you&apos;re correcting overreported tax amounts, for purposes of the certifications on lines 4 and 5, Medicare tax doesn&apos;t include Additional Medicare Tax. Form 941-X can&apos;t be used to correct overreported amounts of Additional Medicare Tax unless the amounts weren&apos;t withheld from employee wages or an adjustment is being made for the current year.</p>
                        <div class="fw-semibold small mb-1">4. If you checked line 1 because you&apos;re adjusting overreported federal income tax, social security tax, Medicare tax, or Additional Medicare Tax, check all that apply. You must check at least one box.</div>
                        <div class="text-muted small mb-1">I certify that:</div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="f941x-p2-4a">
                            <label class="form-check-label" for="f941x-p2-4a"><span class="fw-semibold">a.</span> I repaid or reimbursed each affected employee for the overcollected federal income tax or Additional Medicare Tax for the current year and the overcollected social security tax and Medicare tax for current and prior years. For adjustments of employee social security tax and Medicare tax overcollected in prior years, I have a written statement from each affected employee stating that they haven&apos;t claimed (or the claim was rejected) and won&apos;t claim a refund or credit for the overcollection.</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="f941x-p2-4b">
                            <label class="form-check-label" for="f941x-p2-4b"><span class="fw-semibold">b.</span> The adjustments of social security tax and Medicare tax are for the employer&apos;s share only. I couldn&apos;t find the affected employees or each affected employee didn&apos;t give me a written statement that they haven&apos;t claimed (or the claim was rejected) and won&apos;t claim a refund or credit for the overcollection.</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="f941x-p2-4c">
                            <label class="form-check-label" for="f941x-p2-4c"><span class="fw-semibold">c.</span> The adjustment is for federal income tax, social security tax, Medicare tax, or Additional Medicare Tax that I didn&apos;t withhold from employee wages.</label>
                        </div>
                        <div class="fw-semibold small mb-1">5. If you checked line 2 because you&apos;re claiming a refund or abatement of overreported federal income tax, social security tax, Medicare tax, or Additional Medicare Tax, check all that apply. You must check at least one box.</div>
                        <div class="text-muted small mb-1">I certify that:</div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="f941x-p2-5a">
                            <label class="form-check-label" for="f941x-p2-5a"><span class="fw-semibold">a.</span> I repaid or reimbursed each affected employee for the overcollected social security tax and Medicare tax. For claims of employee social security tax and Medicare tax overcollected in prior years, I have a written statement from each affected employee stating that they haven&apos;t claimed (or the claim was rejected) and won&apos;t claim a refund or credit for the overcollection.</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="f941x-p2-5b">
                            <label class="form-check-label" for="f941x-p2-5b"><span class="fw-semibold">b.</span> I have a written consent from each affected employee stating that I may file this claim for the employee&apos;s share of social security tax and Medicare tax. For refunds of employee social security tax and Medicare tax overcollected in prior years, I also have a written statement from each affected employee stating that they haven&apos;t claimed (or the claim was rejected) and won&apos;t claim a refund or credit for the overcollection.</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" id="f941x-p2-5c">
                            <label class="form-check-label" for="f941x-p2-5c"><span class="fw-semibold">c.</span> The claim for social security tax and Medicare tax is for the employer&apos;s share only. I couldn&apos;t find the affected employees, or each affected employee didn&apos;t give me a written consent to file a claim for the employee&apos;s share of social security tax and Medicare tax, or each affected employee didn&apos;t give me a written statement that they haven&apos;t claimed (or the claim was rejected) and won&apos;t claim a refund or credit for the overcollection.</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="f941x-p2-5d">
                            <label class="form-check-label" for="f941x-p2-5d"><span class="fw-semibold">d.</span> The claim is for federal income tax, social security tax, Medicare tax, or Additional Medicare Tax that I didn&apos;t withhold from employee wages.</label>
                        </div>
                    </div>

                    @include('screens.admin.forms.partials.form-941-x-part3', ['pdfMode' => false, 'fields' => []])

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="fw-semibold mb-2">Part 4: Explain your corrections for this quarter.</div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="f941x-l41">
                            <label class="form-check-label" for="f941x-l41"><span class="fw-semibold">41.</span> Check here if any corrections you entered on a line include both underreported and overreported amounts. Explain both your underreported and overreported amounts on line 43.</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="f941x-l42">
                            <label class="form-check-label" for="f941x-l42"><span class="fw-semibold">42.</span> Check here if any corrections involve reclassified workers. Explain on line 43.</label>
                        </div>
                        <label class="form-label mb-1" for="f941x-l43"><span class="fw-semibold">43.</span> You must give us a detailed explanation of how you determined your corrections. See the instructions.</label>
                        <textarea id="f941x-l43" class="form-control form-control-sm" rows="5" autocomplete="off"></textarea>
                    </div>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="fw-semibold mb-2">Part 5: Sign here. You must complete all five pages of this form and sign it.</div>
                        <p class="text-muted small mb-2">Under penalties of perjury, I declare that I have filed an original Form 941 or Form 941-SS and that I have examined this adjusted return or claim, including accompanying schedules and statements, and to the best of my knowledge and belief, it is true, correct, and complete. Declaration of preparer (other than taxpayer) is based on all information of which preparer has any knowledge.</p>
                        <p class="text-muted small mb-0">Signature, date, title, paid preparer, and daytime phone are completed on the official paper form or through your tax professional.</p>
                    </div>

                    <p class="text-muted small mb-0">For Paperwork Reduction Act Notice, see separate instructions. <span class="text-break">www.irs.gov/Form941X</span> Cat. No. 17025J Form 941-X (Rev. 4-2025)</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.__F941X_TAX_YEAR = @json($ty);
window.__F941X_PDF_URL = @json(route('admin.forms.form-941-x.pdf'));
</script>
<script>
(function () {
    var printBtn = document.getElementById('f941xBtnPrint');
    if (!printBtn || !window.__F941X_PDF_URL) {
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
        document.querySelectorAll('input[id^="f941x-"], textarea[id^="f941x-"]').forEach(function (el) {
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
        fetch(window.__F941X_PDF_URL, {
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
            a.download = 'Form-941-X-' + String(window.__F941X_TAX_YEAR || '') + '.pdf';
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
