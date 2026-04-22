@section('title', 'Schedule R (Form 941)')
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
    $colsAi = [
        'a' => '(a) Client&apos;s EIN',
        'b' => '(b) Type of wages (CPEO only)',
        'c' => '(c) Form 941, line 1',
        'd' => '(d) Form 941, line 2',
        'e' => '(e) Form 941, line 3',
        'f' => '(f) Form 941-X, lines 9 and 10, column 1, total',
        'g' => '(g) Form 941, lines 5a and 5b, column 2, total',
        'h' => '(h) Form 941, line 5c, column 2',
        'i' => '(i) Form 941, line 5e',
    ];
    $colsJq = [
        'j' => '(j) Form 941, line 5f',
        'k' => '(k) Form 941, line 11',
        'l' => '(l) Form 941-X, lines 17 and 25, column 1, total',
        'm' => '(m) Reserved for future use',
        'n' => '(n) Form 941-X, lines 18b and 26b, column 1, total',
        'o' => '(o) Form 941-X, lines 18c and 26c, column 1, total',
        'p' => '(p) Form 941-X, line 18d, column 1',
        'q' => '(q) Form 941, line 12',
    ];
    $colsRy = [
        'r' => '(r) Form 941, line 13',
        's' => '(s) Reserved for future use',
        't' => '(t) Reserved for future use',
        'u' => '(u) Form 941-X, lines 28 and 29, column 1, total',
        'v' => '(v) Reserved for future use',
        'w' => '(w) Form 941-X, lines 35 and 37, column 1, total',
        'x' => '(x) Form 941-X, lines 36 and 39, column 1, total',
        'y' => '(y) Form 941-X, lines 38 and 40, column 1, total',
    ];
    $b1RowLabels = [
        6 => '6 Subtotals for clients. Add lines 1 through 5',
        7 => '7 Enter the combined subtotal from line 9 of all Continuation Sheets for Schedule R',
        8 => '8 Enter Form 941 amounts for your employees',
        9 => '9 Totals. Add lines 6, 7, and 8.',
    ];
@endphp
@section('content')
<style>
.form-941r-wrapper label,
.form-941r-wrapper .form-label {
    margin-bottom: 10px !important;
}
.form-941r-wrapper .table-sched-r th {
    font-size: 0.7rem;
    font-weight: 600;
    vertical-align: bottom;
    min-width: 4.5rem;
}
@media print {
    .card-header .btn { display: none !important; }
    .f941r-screen-toolbar { display: none !important; }
}
</style>
<div class="container-fluid form-941r-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.forms.index') }}" class="btn btn-sm button-light-primary">Back to Forms</a>
                        <a href="{{ route('admin.forms.form-941') }}" class="btn btn-sm button-light-primary">Form 941</a>
                        <a href="{{ route('admin.forms.form-941-schedule-b') }}" class="btn btn-sm button-light-primary">Schedule B</a>
                    </div>
                </div>
                <div class="card-body small">
                    <div class="d-flex flex-wrap gap-2 justify-content-end mb-2 f941r-screen-toolbar">
                        <button type="button" class="btn btn-primary btn-sm" id="f941rBtnPrint">Download PDF</button>
                    </div>
                    <div class="mb-3 pb-2 border-bottom">
                        <div class="fw-semibold">Schedule R (Form 941): Allocation Schedule for Aggregate Form 941 Filers</div>
                        <div class="text-muted">(Rev. March {{ $ty }}) Department of the Treasury &mdash; Internal Revenue Service &middot; OMB No. 1545-0029</div>
                    </div>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941r-ein">Employer identification number (EIN) &mdash;</label>
                                <input type="text" id="f941r-ein" class="form-control form-control-sm" value="{{ $einDisplay }}" maxlength="20" readonly>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label mb-1" for="f941r-name">Name as shown on Form 941</label>
                                <input type="text" id="f941r-name" class="form-control form-control-sm" value="{{ $nameLegal }}" readonly>
                            </div>
                        </div>
                        <div class="fw-semibold mb-2">Type of filer (check one):</div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941r_filer" id="f941r-filer-3504"><label class="form-check-label" for="f941r-filer-3504">Section 3504 Agent</label></div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941r_filer" id="f941r-filer-cpeo"><label class="form-check-label" for="f941r-filer-cpeo">CPEO</label></div>
                        <div class="form-check mb-3"><input class="form-check-input" type="radio" name="f941r_filer" id="f941r-filer-other"><label class="form-check-label" for="f941r-filer-other">Other Third Party</label></div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-4">
                                <label class="form-label mb-1" for="f941r-cal-yr">Report for calendar year:</label>
                                <input type="text" id="f941r-cal-yr" class="form-control form-control-sm" value="{{ $ty }}" maxlength="4" readonly>
                            </div>
                        </div>
                        <div class="fw-semibold mb-2">Check the quarter (same as Form 941):</div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941r_quarter" id="f941r-q1" @checked($cq === 1) disabled><label class="form-check-label" for="f941r-q1">1: January, February, March</label></div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941r_quarter" id="f941r-q2" @checked($cq === 2) disabled><label class="form-check-label" for="f941r-q2">2: April, May, June</label></div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="f941r_quarter" id="f941r-q3" @checked($cq === 3) disabled><label class="form-check-label" for="f941r-q3">3: July, August, September</label></div>
                        <div class="form-check mb-2"><input class="form-check-input" type="radio" name="f941r_quarter" id="f941r-q4" @checked($cq === 4) disabled><label class="form-check-label" for="f941r-q4">4: October, November, December</label></div>
                        <div class="fw-semibold mb-1">This Schedule R is attached to:</div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="f941r-att-941"><label class="form-check-label" for="f941r-att-941">Form 941</label></div>
                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="f941r-att-941x"><label class="form-check-label" for="f941r-att-941x">Form 941-X</label></div>
                    </div>

                    <p class="small mb-3">Read the instructions before you complete Schedule R. Type or print within the boxes. Complete a separate line for the amounts allocated to each of your clients. The term &quot;client&quot; as used on this form includes the term &quot;customer.&quot; See the instructions.</p>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm table-sched-r align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:2.5rem;">#</th>
                                    @foreach ($colsAi as $h)
                                        <th>{!! $h !!}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (range(1, 5) as $r)
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $r }}</td>
                                        @foreach (array_keys($colsAi) as $c)
                                            <td class="p-1"><input type="text" id="f941r-b1-{{ $r }}-{{ $c }}" class="form-control form-control-sm" value="" autocomplete="off"></td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                @foreach (range(6, 9) as $r)
                                    <tr>
                                        <td class="text-start small" colspan="10">{{ $b1RowLabels[$r] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $r }}</td>
                                        @foreach (array_keys($colsAi) as $c)
                                            <td class="p-1"><input type="text" id="f941r-b1-{{ $r }}-{{ $c }}" class="form-control form-control-sm" value="" autocomplete="off"></td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-sm table-sched-r align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:2.5rem;">#</th>
                                    @foreach ($colsJq as $h)
                                        <th>{!! $h !!}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (range(1, 9) as $r)
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $r }}</td>
                                        @foreach (array_keys($colsJq) as $c)
                                            <td class="p-1"><input type="text" id="f941r-b2-{{ $r }}-{{ $c }}" class="form-control form-control-sm" value="" autocomplete="off"></td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-sm table-sched-r align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width:2.5rem;">#</th>
                                    @foreach ($colsRy as $h)
                                        <th>{!! $h !!}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (range(1, 9) as $r)
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $r }}</td>
                                        @foreach (array_keys($colsRy) as $c)
                                            <td class="p-1"><input type="text" id="f941r-b3-{{ $r }}-{{ $c }}" class="form-control form-control-sm" value="" autocomplete="off"></td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p class="text-muted small mb-0">For Paperwork Reduction Act Notice, see the separate instructions. <span class="text-break">www.irs.gov/Form941</span> Cat. No. 49301K Schedule R (Form 941) (Rev. 3-{{ $ty }})</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.__F941R_TAX_YEAR = @json($ty);
window.__F941R_CQ = @json($cq);
window.__F941R_PDF_URL = @json(route('admin.forms.form-941-schedule-r.pdf'));
</script>
<script>
(function () {
    var printBtn = document.getElementById('f941rBtnPrint');
    if (!printBtn || !window.__F941R_PDF_URL) {
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
        document.querySelectorAll('input[id^="f941r-"], textarea[id^="f941r-"]').forEach(function (el) {
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
        fetch(window.__F941R_PDF_URL, {
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
            a.download = 'Schedule-R-Form-941-' + String(window.__F941R_TAX_YEAR || '') + '-Q' + String(window.__F941R_CQ || '1') + '.pdf';
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
