@section('title', 'Form 940')
@extends('layouts.admin.master')
@php
    $usStates = [
        'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
        'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
        'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY', 'DC',
    ];
    $emp = $employer940 ?? [];
    $sutaDefault = (! empty($emp['state_code']) && in_array($emp['state_code'], $usStates, true)) ? $emp['state_code'] : 'IL';
@endphp
@section('content')
<div class="container-fluid form-940-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border d-flex flex-wrap align-items-center justify-content-end gap-2">
                    <a href="{{ route('admin.forms.index') }}" class="btn btn-sm button-light-primary">Back to Forms</a>
                </div>
                <div class="card-body small">
                    <div class="d-flex flex-wrap gap-2 justify-content-end mb-2">
                        <button type="button" class="btn btn-primary btn-sm" id="f940BtnPreparer">Preparer / Designee</button>
                        <button type="button" class="btn btn-primary btn-sm" id="f940BtnPreview">Preview</button>
                        <button type="button" class="btn btn-primary btn-sm" id="f940BtnPrint">Print</button>
                        <button type="button" class="btn btn-primary btn-sm" id="f940BtnOverride">Override calculations</button>
                    </div>
                    <p class="text-primary fst-italic mb-3 ">If you click the Preview button and you still can&apos;t see the preview window, please look at the bottom of your screen, you should see a second icon for the form (next to the Payroll Mate icon). You can maximize it to show the form preview window.</p>

                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="fw-semibold mb-2">Type of Return (Check all that apply):</div>
                        <div class="form-check"><input class="form-check-input f940-cb" type="checkbox" id="f940-ret-a" disabled><label class="form-check-label" for="f940-ret-a">a. Amended</label></div>
                        <div class="form-check"><input class="form-check-input f940-cb" type="checkbox" id="f940-ret-b" disabled><label class="form-check-label" for="f940-ret-b">b. Successor employer</label></div>
                        <div class="form-check"><input class="form-check-input f940-cb" type="checkbox" id="f940-ret-c" disabled><label class="form-check-label" for="f940-ret-c">c. No payments to employees in {{ $taxYear }}</label></div>
                        <div class="form-check mb-0"><input class="form-check-input f940-cb" type="checkbox" id="f940-ret-d" disabled><label class="form-check-label" for="f940-ret-d">d. Final: Business closed or stopped paying wages</label></div>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-3">Part 1: Tell us about your return</div>
                    <div class="row mb-3 align-items-start">
                        <div class="col">
                            <label class="form-label mb-1"><span class="badge bg-secondary me-1">1a</span> If you had to pay state unemployment tax in one state only, enter the state abbreviation.</label>
                            <input type="text" id="f940-l1a" class="form-control form-control-sm f940-txt" maxlength="2" style="max-width: 5rem;" value="{{ $sutaDefault }}" readonly>
                        </div>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input f940-cb" type="checkbox" id="f940-l1b" disabled>
                        <label class="form-check-label" for="f940-l1b"><span class="badge bg-secondary me-1">1b</span> If you had to pay state unemployment tax in more than one state, you are a multi-state employer. Check here. Complete Schedule A (Form 940)</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input f940-cb" type="checkbox" id="f940-l2" disabled>
                        <label class="form-check-label" for="f940-l2"><span class="badge bg-secondary me-1">2</span> If you paid wages in a state that is subject to CREDIT REDUCTION. Check here. Complete Schedule A (Form 940)</label>
                    </div>
                    <div class="mb-4">
                        <label class="form-label mb-1" for="f940-stateSuta">State in which you were required to pay state unemployment tax this year</label>
                        <select id="f940-stateSuta" class="form-control form-control-sm" style="max-width: 12rem;" disabled>
                            @foreach ($usStates as $abbr)
                                <option value="{{ $abbr }}" @selected($abbr === $sutaDefault)>{{ $abbr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-3">Part 2: Determine your FUTA tax before adjustments</div>
                    @foreach ([
                        ['id' => 'f940-l3', 'line' => '3', 'text' => 'Total payments to all employees', 'val' => '700.00'],
                        ['id' => 'f940-l4', 'line' => '4', 'text' => 'Payments exempt from FUTA tax', 'val' => '0.00'],
                    ] as $row)
                        <div class="row mb-2 align-items-center g-2">
                            <div class="col">
                                <label class="form-label mb-0" for="{{ $row['id'] }}"><span class="badge bg-secondary me-1">{{ $row['line'] }}</span> {{ $row['text'] }}</label>
                            </div>
                            <div class="col-auto" style="min-width: 7rem;">
                                <input type="text" id="{{ $row['id'] }}" class="form-control form-control-sm text-end f940-num" maxlength="9" value="{{ $row['val'] }}" readonly>
                            </div>
                        </div>
                    @endforeach
                    <div class="ms-3 mb-3 border-start ps-3">
                        <div class="text-muted small mb-1">Check all that apply:</div>
                        @foreach ([['f940-l4a', '4a Fringe benefits'], ['f940-l4b', '4b Group term life insurance'], ['f940-l4c', '4c Retirement/Pension'], ['f940-l4d', '4d Dependent care'], ['f940-l4e', '4e Other']] as $cb)
                            <div class="form-check"><input class="form-check-input f940-cb" type="checkbox" id="{{ $cb[0] }}" disabled><label class="form-check-label" for="{{ $cb[0] }}">{{ $cb[1] }}</label></div>
                        @endforeach
                    </div>
                    @foreach ([
                        ['id' => 'f940-l5', 'line' => '5', 'text' => 'Total of payments made to each employee in excess of $7,000', 'val' => '0.00'],
                        ['id' => 'f940-l6', 'line' => '6', 'text' => 'Subtotal (line 4 + line 5 = line 6)', 'val' => '0.00'],
                        ['id' => 'f940-l7', 'line' => '7', 'text' => 'Total taxable FUTA wages (line 3 - line 6 = line 7)', 'val' => '700.00'],
                        ['id' => 'f940-l8', 'line' => '8', 'text' => 'FUTA tax before adjustments (line 7 x .006 = line 8)', 'val' => '4.20'],
                    ] as $row)
                        <div class="row mb-2 align-items-center g-2">
                            <div class="col">
                                <label class="form-label mb-0" for="{{ $row['id'] }}"><span class="badge bg-secondary me-1">{{ $row['line'] }}</span> {{ $row['text'] }}</label>
                            </div>
                            <div class="col-auto" style="min-width: 7rem;">
                                <input type="text" id="{{ $row['id'] }}" class="form-control form-control-sm text-end f940-num" maxlength="9" value="{{ $row['val'] }}" readonly>
                            </div>
                        </div>
                    @endforeach

                    <p class="text-primary fst-italic my-3">Do not contact our team with questions about Form 940; they will not be able to help you. If you want to compare the amounts on Form 940, you can generate the &quot;Form 940 Helper Sheet&quot; report and compare your numbers. You can also contact the IRS or a tax professional if you have any questions about tax forms. Do not compare reports printed before with data currently inside the software. You will need to compare the current reports with the current forms.</p>

                    <div class="fw-bold border-bottom pb-2 mb-2">Part 3: Determine your adjustments. If any line does NOT apply, leave it blank.</div>
                    <div class="row mb-2 align-items-start g-2">
                        <div class="col-auto pt-1"><input class="form-check-input f940-cb" type="checkbox" id="f940-l9cb" disabled></div>
                        <div class="col">
                            <label class="form-label mb-0" for="f940-l9"><span class="badge bg-secondary me-1">9</span> If ALL of the taxable FUTA wages you paid were excluded from state unemployment tax, multiply line 7 by .054 (line 7 X .054 = line 9). Then go to line 12</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;"><input type="text" id="f940-l9" class="form-control form-control-sm text-end f940-num" maxlength="9" value="0.00" readonly></div>
                    </div>
                    <div class="row mb-2 align-items-start g-2">
                        <div class="col-auto pt-1"><input class="form-check-input f940-cb" type="checkbox" id="f940-l10cb" disabled></div>
                        <div class="col">
                            <label class="form-label mb-0" for="f940-l10"><span class="badge bg-secondary me-1">10</span> If SOME of the taxable FUTA wages you paid were excluded from state unemployment tax, OR you paid ANY state unemployment tax late (after the due date for filing Form 940), complete the worksheet in the instructions. Enter the amount from line 7 of the worksheet.</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;"><input type="text" id="f940-l10" class="form-control form-control-sm text-end f940-num" maxlength="9" value="0.00" readonly></div>
                    </div>
                    <div class="row mb-4 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f940-l11"><span class="badge bg-secondary me-1">11</span> If credit reduction applies, enter the total from Schedule A (Form 940)</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;"><input type="text" id="f940-l11" class="form-control form-control-sm text-end f940-num" maxlength="9" value="0.00" readonly></div>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-2">Part 4: Determine your FUTA tax and balance due or overpayment. If any line does NOT apply, leave it blank.</div>
                    @foreach ([
                        ['id' => 'f940-l12', 'line' => '12', 'text' => 'Total FUTA tax after adjustments (lines 8 + 9 + 10 + 11 = line 12)', 'val' => '4.20'],
                        ['id' => 'f940-l13', 'line' => '13', 'text' => 'FUTA tax deposited for the year, including any payment applied from a prior year', 'val' => '0.00'],
                        ['id' => 'f940-l14', 'line' => '14', 'text' => 'Balance due (If line 12 is more than line 13, enter the difference on line 14.)', 'val' => '4.20'],
                        ['id' => 'f940-l15', 'line' => '15', 'text' => 'Overpayment (If line 13 is more than line 12, enter the difference on line 15 and check a box below.)', 'val' => ''],
                    ] as $row)
                        <div class="row mb-2 align-items-center g-2">
                            <div class="col">
                                <label class="form-label mb-0" for="{{ $row['id'] }}"><span class="badge bg-secondary me-1">{{ $row['line'] }}</span> {{ $row['text'] }}</label>
                            </div>
                            <div class="col-auto" style="min-width: 7rem;">
                                <input type="text" id="{{ $row['id'] }}" class="form-control form-control-sm text-end f940-num" maxlength="9" value="{{ $row['val'] }}" readonly>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-check form-check-inline ms-1 mb-4">
                        <input class="form-check-input f940-cb" type="radio" name="f940_l15opt" id="f940-l15a" value="next" disabled>
                        <label class="form-check-label" for="f940-l15a">Apply to next return</label>
                    </div>
                    <div class="form-check form-check-inline mb-4">
                        <input class="form-check-input f940-cb" type="radio" name="f940_l15opt" id="f940-l15b" value="refund" disabled>
                        <label class="form-check-label" for="f940-l15b">Send a refund</label>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-2">Part 5: Report your FUTA tax liability by quarter only if line 12 is more than $500. If not, go to Part 6.</div>
                    <p class="text-primary mb-2">This section gets populated ONLY when line 12 is more than $500. You can check the 940 instructions for details.</p>
                    <p class="small text-muted mb-3"><span class="badge bg-secondary me-1">16</span> Report the amount of your FUTA tax liability for each quarter; do NOT enter the amount you deposited. If you had no liability for a quarter, leave the line blank.</p>
                    @foreach ([['f940-l16a', '16a', '1st quarter (January 1 - March 31)'], ['f940-l16b', '16b', '2nd quarter (April 1 - June 30)'], ['f940-l16c', '16c', '3rd quarter (July 1 - September 30)'], ['f940-l16d', '16d', '4th quarter (October 1 - December 31)']] as $q)
                        <div class="row mb-2 align-items-center g-2">
                            <div class="col"><label class="form-label mb-0" for="{{ $q[0] }}"><span class="badge bg-secondary me-1">{{ $q[1] }}</span> {{ $q[2] }}</label></div>
                            <div class="col-auto" style="min-width: 7rem;"><input type="text" id="{{ $q[0] }}" class="form-control form-control-sm text-end f940-num" maxlength="9" value="" readonly></div>
                        </div>
                    @endforeach
                    <div class="row mb-4 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f940-l17"><span class="badge bg-secondary me-1">17</span> Total tax liability for the year (lines 16a + 16b + 16c + 16d = line 17) <span class="text-muted fw-normal">Total must equal line 12</span></label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;"><input type="text" id="f940-l17" class="form-control form-control-sm text-end f940-num" maxlength="9" value="" readonly></div>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-2">Part 6: Designee (optional) &amp; Part 7: Sign here</div>
                    <p class="text-muted small mb-0">Preparer / Designee and signature areas are completed on the official paper form or through your tax professional. Use Preview or Print to capture the numeric sections above.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modals.modal id="f940OverrideModal" title="Warning" size="modal-lg">
    <div class="d-flex gap-3">
        <div class="fs-2 text-warning flex-shrink-0"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="small lh-lg">
            <p class="mb-2">After clicking this button you will be able to override the values for the boxes shown on this screen.</p>
            <p class="mb-2">The form you generate from {{ config('app.name') }} will reflect your changes, but these changes will not be saved in the program.</p>
            <p class="mb-2">Please also note that these changes will not be reflected on any other tax forms such as W-2 or W-3.</p>
            <p class="mb-3">All numbers generated by this form are compiled from checks you have created during the year. Changes made to this form will cause discrepancies with other forms you may have generated or will generate using {{ config('app.name') }}.</p>
            <div class="form-check d-flex gap-2">
                <input class="form-check-input" type="checkbox" id="f940OverrideAck">
                <label class="form-check-label" for="f940OverrideAck">By checking this box you acknowledge that you are making changes at your own risk and understand that you might be required to submit corrected tax forms in the future.</label>
            </div>
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="f940OverrideOk" disabled>OK</button>
    </x-slot>
</x-modals.modal>

<x-modals.modal id="f940PreparerModal" title="Preparer / Designee" size="modal-lg">
    <p class="small mb-0">Third-party designee and paid preparer details are completed on the official Form 940 (Part 6 and Paid Preparer section on page 2 of the preview). Employer identification comes from your company profile. Enter Part 1–5 amounts on this screen, then use Preview or Print to open the four-page packet (Form 940 pages 1–2, Form 940-V, Schedule A).</p>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </x-slot>
</x-modals.modal>

<x-modals.modal id="f940PreviewModal" title="Preview — Form 940 (4 pages)" size="modal-fullscreen">
    <div id="f940PreviewMount" class="f940-preview-scroll bg-secondary bg-opacity-10 p-2 p-md-3" style="max-height: calc(100vh - 11rem); overflow-y: auto;"></div>
    <x-slot name="footer">
        <button type="button" class="btn btn-primary" id="f940PreviewPrint">Print</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </x-slot>
</x-modals.modal>
@endsection

@push('scripts')
<script>
window.__F940_TAX_YEAR = @json($taxYear);
window.__F940_STATES = @json($usStates);
window.__F940_EMPLOYER = @json($employer940 ?? []);
</script>
@include('screens.admin.forms.partials.form-940-script')
@endpush
