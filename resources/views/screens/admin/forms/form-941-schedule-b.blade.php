@section('title', 'Schedule B (Form 941)')
@extends('layouts.admin.master')
@php
    $emp = $employer941 ?? [];
    $ty = (int) ($taxYear ?? now()->format('Y'));
    $cq = (int) ($currentQuarter ?? 1);
    $einStr = trim((string) ($emp['ein'] ?? ''));
    $einDigits = preg_replace('/\D/', '', $einStr);
    $einDisplay = strlen($einDigits) === 9
        ? substr($einDigits, 0, 2).'-'.substr($einDigits, 2, 7)
        : $einStr;
    $nameLegal = (string) ($emp['legal_name'] ?? '');
    $monthsOrdered = ($scheduleBDaysGrouped ?? collect())->sortKeys();
@endphp
@section('content')
<style>
.form-941b-wrapper label,
.form-941b-wrapper .form-label {
    margin-bottom: 10px !important;
}
@media print {
    .card-header .btn { display: none !important; }
    .f941b-screen-toolbar { display: none !important; }
}
</style>
<div class="container-fluid form-941b-wrapper custom-form-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.forms.index') }}" class="btn btn-sm button-light-primary">Back to Forms</a>
                        <a href="{{ route('admin.forms.form-941') }}" class="btn btn-sm button-light-primary">Form 941</a>
                        <a href="{{ route('admin.forms.form-941-schedule-r') }}" class="btn btn-sm button-light-primary">Schedule R</a>
                    </div>
                </div>
                <div class="card-body small">
                    <div class="d-flex flex-wrap gap-2 justify-content-end mb-2 f941b-screen-toolbar">
                        <button type="button" class="btn btn-primary btn-sm" id="f941bBtnPrint">Download PDF</button>
                    </div>
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="fw-semibold">Schedule B (Form 941): Report of Tax Liability for Semiweekly Schedule Depositors</div>
                        <div class="text-muted">(Rev. March {{ $ty }}) Department of the Treasury &mdash; Internal Revenue Service &middot; OMB No. 1545-0029</div>
                    </div>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941b-ein">Employer identification number (EIN) &mdash;</label>
                                <input type="text" id="f941b-ein" class="form-control form-control-sm" value="{{ $einDisplay }}" maxlength="20" readonly>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label mb-1" for="f941b-name">Name (not your trade name)</label>
                                <input type="text" id="f941b-name" class="form-control form-control-sm" value="{{ $nameLegal }}" readonly>
                            </div>
                        </div>
                        <div class="row g-2 align-items-end mb-2">
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941b-cal-yr">Calendar year (Also check quarter)</label>
                                <input type="text" id="f941b-cal-yr" class="form-control form-control-sm" value="{{ $ty }}" maxlength="4" readonly>
                            </div>
                        </div>
                        <div class="fw-semibold mb-2">Report for this Quarter... (Check one.)</div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941b_quarter" id="f941b-q1" @checked($cq === 1) disabled><label class="form-check-label" for="f941b-q1">1: January, February, March</label></div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941b_quarter" id="f941b-q2" @checked($cq === 2) disabled><label class="form-check-label" for="f941b-q2">2: April, May, June</label></div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941b_quarter" id="f941b-q3" @checked($cq === 3) disabled><label class="form-check-label" for="f941b-q3">3: July, August, September</label></div>
                        <div class="form-check mb-0"><input class="form-check-input" type="radio" name="f941b_quarter" id="f941b-q4" @checked($cq === 4) disabled><label class="form-check-label" for="f941b-q4">4: October, November, December</label></div>
                    </div>

                    <p class="small mb-3">Use this schedule to show your TAX LIABILITY for the quarter; don&apos;t use it to show your deposits. When you file this schedule with Form 941, don&apos;t change your tax liability by adjustments reported on any Forms 941-X or 944-X. You must fill out this schedule and attach it to Form 941 if you&apos;re a semiweekly schedule depositor or became one because your accumulated tax liability on any day was $100,000 or more. Write your daily tax liability on the numbered space that corresponds to the date wages were paid. See Section 11 in Pub. 15 for details.</p>

                    @php $mi = 0; @endphp
                    @foreach ($monthsOrdered as $monthNum => $dayList)
                        @php $mi++; @endphp
                        <div class="border rounded p-3 mb-3 bg-white">
                            <div class="fw-semibold mb-2">Month {{ $mi }}</div>
                            <div class="row g-2">
                                @foreach ($dayList as $day)
                                    <div class="col-md-4 col-lg-3">
                                        <label class="form-label mb-0 small" for="f941b-{{ $day['id'] }}">{{ $day['d'] }} .</label>
                                        <input type="text" id="f941b-{{ $day['id'] }}" class="form-control form-control-sm" value="" inputmode="decimal" autocomplete="off">
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-2 pt-2 border-top">
                                <label class="form-label mb-1 small" for="f941b-m{{ $mi }}-sub">Tax liability for Month {{ $mi }}</label>
                                <input type="text" id="f941b-m{{ $mi }}-sub" class="form-control form-control-sm" value="" inputmode="decimal" autocomplete="off">
                            </div>
                        </div>
                    @endforeach

                    <div class="border rounded p-3 mb-3 bg-light">
                        <p class="small mb-2">Fill in your total liability for the quarter (Month 1 + Month 2 + Month 3). Total must equal line 12 on Form 941.</p>
                        <label class="form-label mb-1" for="f941b-total">Total liability for the quarter</label>
                        <input type="text" id="f941b-total" class="form-control form-control-sm" value="" inputmode="decimal" autocomplete="off">
                        <p class="text-muted small mt-2 mb-0">For Paperwork Reduction Act Notice, see separate instructions. <span class="text-break">www.irs.gov/Form941</span> Cat. No. 11967Q Schedule B (Form 941) (Rev. 3-{{ $ty }})</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.__F941B_TAX_YEAR = @json($ty);
window.__F941B_CQ = @json($cq);
window.__F941B_PDF_URL = @json(route('admin.forms.form-941-schedule-b.pdf'));
</script>
<script>
(function () {
    var printBtn = document.getElementById('f941bBtnPrint');
    if (!printBtn || !window.__F941B_PDF_URL) {
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
        document.querySelectorAll('input[id^="f941b-"], textarea[id^="f941b-"]').forEach(function (el) {
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
        fetch(window.__F941B_PDF_URL, {
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
            a.download = 'Schedule-B-Form-941-' + String(window.__F941B_TAX_YEAR || '') + '-Q' + String(window.__F941B_CQ || '1') + '.pdf';
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
