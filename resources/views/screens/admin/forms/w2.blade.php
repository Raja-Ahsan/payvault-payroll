@section('title', 'W-2 / W-3 Wizard')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid w2-forms">
    <div class="row">
        <div class="col-12">
            <div class="card height-equal">
                <div class="card-body basic-wizard">
                    <div id="wizard-step-0" class="wizard-panel">
                        <div class="text-primary small lh-lg mb-0 p-3 rounded">
                            <p class="mb-2">This wizard allows you to print W-2 / W-3 forms.</p>
                            <p class="mb-2">The wizard contains one page for the W-2 information, and another page for the W-3 information.</p>
                            <p class="mb-2">You can use the Employee drop down list in the W-2 page to move between the different employees.</p>
                            <p class="mb-2">Please note that any changes you apply to the W-2 boxes will be lost once you exit this wizard except for the values in the Red boxes.</p>
                            <p class="mb-2">If you make any changes to the W-2 boxes that you want to get reflected on the printed W-2/W3 forms, please make sure to print the forms (W-2/W-3) before you exit this wizard.</p>
                            <p class="mb-0">Please also note that any changes you apply to the W-2 boxes will NOT affect other payroll forms such as form 941 or other payroll reports.</p>
                        </div>
                    </div>

                    <div id="wizard-step-1" class="wizard-panel d-none">
                        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-3">
                            <div class="flex-grow-1" style="min-width: 220px;">
                                <label for="w2EmployeeSelect" class="form-label small mb-1">Employee <span class="text-primary">[Use this drop-down list to move between different employees]</span></label>
                                <select id="w2EmployeeSelect" class="form-control"></select>
                            </div>
                            <button type="button" class="btn btn-primary" id="btnJumpW3">Click Here to Continue to the W-3 form</button>
                        </div>
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            <button type="button" class="btn btn-primary px-4" id="btnW2Preview">Preview</button>
                            <button type="button" class="btn btn-primary px-4" id="btnW2Print">Print</button>
                            <button type="button" class="btn btn-primary px-4" id="btnOverrideToggle">Override calculations</button>
                        </div>

                        <div class="row g-3 small">
                            <div class="col-lg-4">
                                @foreach ([
                                    ['b1', '1', 'Wages, tips, other compensation', 'money'],
                                    ['b3', '3', 'Social security wages', 'money'],
                                    ['b5', '5', 'Medicare wages and tips', 'money'],
                                    ['b7', '7', 'Social security tips', 'money'],
                                ] as $f)
                                    <div class="mb-2">
                                        <label class="form-label mb-0 fw-semibold">{{ $f[1] }} {{ $f[2] }}</label>
                                        <input type="text" class="form-control w2-field" data-w2-field="{{ $f[0] }}" data-field-type="{{ $f[3] }}" maxlength="{{ $f[3] === 'money' ? '9' : '' }}" autocomplete="off" readonly>
                                    </div>
                                @endforeach
                                <p class="text-muted mb-2 border rounded px-2 py-1 bg-light">Box 9 is no longer in use</p>
                                <div class="mb-2">
                                    <label class="form-label mb-0 fw-semibold">11 Nonqualified plans</label>
                                    <input type="text" class="form-control w2-field" data-w2-field="b11" data-field-type="money" maxlength="9" autocomplete="off" readonly>
                                </div>
                                <div class="border rounded p-2">
                                    <div class="fw-semibold mb-2">13</div>
                                    <div class="form-check"><input class="form-check-input w2-field" type="checkbox" data-w2-field="b13stat" id="b13stat" disabled><label class="form-check-label" for="b13stat">Statutory employee</label></div>
                                    <div class="form-check"><input class="form-check-input w2-field" type="checkbox" data-w2-field="b13ret" id="b13ret" disabled><label class="form-check-label" for="b13ret">Retirement plan</label></div>
                                    <div class="form-check mb-0"><input class="form-check-input w2-field" type="checkbox" data-w2-field="b13tp" id="b13tp" disabled><label class="form-check-label" for="b13tp">Third-party sick pay</label></div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                @foreach ([
                                    ['b2', '2', 'Federal income tax withheld', 'money'],
                                    ['b4', '4', 'Social security tax withheld', 'money'],
                                    ['b6', '6', 'Medicare tax withheld', 'money'],
                                    ['b8', '8', 'Allocated tips', 'money'],
                                    ['b10', '10', 'Dependent care benefits', 'money'],
                                ] as $f)
                                    <div class="mb-2">
                                        <label class="form-label mb-0 fw-semibold">{{ $f[1] }} {{ $f[2] }}</label>
                                        <input type="text" class="form-control w2-field" data-w2-field="{{ $f[0] }}" data-field-type="{{ $f[3] }}" maxlength="{{ $f[3] === 'money' ? '9' : '' }}" autocomplete="off" readonly>
                                    </div>
                                @endforeach
                                <div class="border rounded p-2">
                                    <div class="fw-semibold mb-1">12 <span class="fw-normal text-muted">See instructions for box 12</span></div>
                                    <div class="row g-1 fw-semibold text-muted mb-1"><div class="col-5">Code</div><div class="col-7">Amount</div></div>
                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="row g-1 mb-1">
                                            <div class="col-5"><input type="text" class="form-control form-control-sm w2-field" data-w2-field="b12c{{ $i }}" data-field-type="code" data-b12-row="{{ $i }}" autocomplete="off" readonly></div>
                                            <div class="col-7"><input type="text" class="form-control form-control-sm w2-field" data-w2-field="b12a{{ $i }}" data-field-type="money" data-b12-row="{{ $i }}" maxlength="9" autocomplete="off" readonly></div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="fw-semibold mb-1">14 Other</div>
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="row g-1 mb-1">
                                        <div class="col-6"><input type="text" class="form-control form-control-sm w2-field" data-w2-field="b14t{{ $i }}" data-field-type="alpha" autocomplete="off" readonly></div>
                                        <div class="col-6"><input type="text" class="form-control form-control-sm w2-field" data-w2-field="b14n{{ $i }}" data-field-type="money" maxlength="9" autocomplete="off" readonly></div>
                                    </div>
                                @endfor
                                <div class="mb-2 mt-2">
                                    <label class="form-label mb-0 fw-semibold">15 State</label>
                                    <div class="row g-1">
                                        <div class="col-6"><input type="text" class="form-control w2-field" data-w2-field="b15s0" data-field-type="state" maxlength="2" autocomplete="off" readonly></div>
                                        <div class="col-6"><input type="text" class="form-control w2-field" data-w2-field="b15s1" data-field-type="money" maxlength="9" autocomplete="off" readonly></div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mb-0 fw-semibold">Employer’s state ID number</label>
                                    <div class="row g-1">
                                        <div class="col-6"><input type="text" class="form-control w2-field" data-w2-field="b15e0" data-field-type="alpha" autocomplete="off" readonly></div>
                                        <div class="col-6"><input type="text" class="form-control w2-field" data-w2-field="b15e1" data-field-type="money" maxlength="9" autocomplete="off" readonly></div>
                                    </div>
                                </div>
                                @foreach ([
                                    ['b16', '16', 'State wages, tips, etc.'],
                                    ['b17', '17', 'State income tax'],
                                    ['b18', '18', 'Local wages, tips, etc.'],
                                    ['b19', '19', 'Local income tax'],
                                ] as $bx)
                                    <div class="mb-2">
                                        <label class="form-label mb-0 fw-semibold">{{ $bx[1] }} {{ $bx[2] }}</label>
                                        <div class="row g-1">
                                            <div class="col-6"><input type="text" class="form-control w2-field" data-w2-field="{{ $bx[0] }}0" data-field-type="money" maxlength="9" autocomplete="off" readonly></div>
                                            <div class="col-6"><input type="text" class="form-control w2-field" data-w2-field="{{ $bx[0] }}1" data-field-type="money" maxlength="9" autocomplete="off" readonly></div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="mb-0">
                                    <label class="form-label mb-0 fw-semibold">20 Locality name</label>
                                    <div class="row g-1">
                                        <div class="col-6"><input type="text" class="form-control w2-field" data-w2-field="b200" data-field-type="alpha" autocomplete="off" readonly></div>
                                        <div class="col-6"><input type="text" class="form-control w2-field" data-w2-field="b201" data-field-type="alpha" autocomplete="off" readonly></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="wizard-step-2" class="wizard-panel d-none">
                        <h5 class="mb-3">W-3 Page</h5>
                        <p class="text-muted small mb-3">After you acknowledge the reminder below, you can preview or print Form W-3 totals (summed from the W-2 entries in this wizard).</p>
                        <div class="d-flex flex-wrap gap-2 mb-3 opacity-50" id="w3Actions" style="pointer-events: none;">
                            <button type="button" class="btn btn-primary btn-sm" id="btnW3Preview">Preview Form W-3</button>
                            <button type="button" class="btn btn-primary btn-sm" id="btnW3Print">Print Form W-3</button>
                        </div>
                    </div>
                </div>
                <div class="card-footer card-no-border bg-transparent">
                    <div class="d-flex flex-wrap justify-content-end gap-2">
                        <a href="{{ route('admin.forms.index') }}" class="btn button-light-primary">Cancel</a>
                        <button type="button" class="btn button-light-primary" id="wizBack" disabled>&lt; Back</button>
                        <button type="button" class="btn btn-primary" id="wizNext">Next &gt;</button>
                        <button type="button" class="btn btn-secondary" id="wizClose" disabled>Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modals.modal id="w2OverrideModal" title="Warning" size="modal-lg">
    <div class="d-flex gap-3">
        <div class="fs-2 text-warning flex-shrink-0"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="small lh-lg">
            <p class="mb-2">After clicking <strong>OK</strong> you will be able to override the values for the boxes shown on this screen.</p>
            <p class="mb-2">The form you generate from this screen will reflect your changes, but these changes will <strong>not</strong> be saved in the program.</p>
            <p class="mb-2">These changes will not be reflected on other tax forms such as Form 941.</p>
            <p class="mb-3">All numbers shown here are compiled from payroll checks you have created during the year. Manual changes can cause discrepancies with other forms you may have generated.</p>
            <div class="form-check d-flex gap-2">
                <input class="form-check-input" type="checkbox" id="w2OverrideAck">
                <label class="form-check-label" for="w2OverrideAck">By checking this box you acknowledge that you are making changes at your own risk and understand that you might be required to submit corrected tax forms in the future.</label>
            </div>
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="w2OverrideOk" disabled>OK</button>
    </x-slot>
</x-modals.modal>

<x-modals.modal id="paperFilingModal" title="Attention" size="modal-lg">
    <div class="text-center mb-3">
        <h5 class="text-danger fw-bold mb-0">Friendly Reminder About Paper Filing</h5>
    </div>
    <div class="small lh-lg">
        <p>The SSA / IRS periodically update rules that can require more employers to file W-2 forms electronically (e-File).</p>
        <p>It is your responsibility to review the latest SSA / IRS requirements and decide whether you must file W-2 forms electronically or may file on paper.</p>
        <p class="mb-2">You can contact the SSA to understand W-2 electronic filing requirements:</p>
        <ul class="mb-3">
            <li>Phone: <a class="text-white" href="tel:18007726270">1-800-772-6270</a></li>
            <li>Email: <a class="text-white" href="mailto:employerinfo@ssa.gov">employerinfo@ssa.gov</a></li>
            <li>URL: <a class="text-white" href="https://www.ssa.gov/employer/empcontacts.htm" target="_blank" rel="noopener">https://www.ssa.gov/employer/empcontacts.htm</a></li>
        </ul>
        <p class="text-white mb-0">Do not contact DIY Payroll support about SSA / IRS filing obligations; support cannot interpret tax law for you.</p>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-primary" id="paperFilingUnderstand">I understand</button>
    </x-slot>
</x-modals.modal>

<x-modals.modal id="w3PreviewModal" title="Preview — Form W-3" size="modal-fullscreen">
    <div id="w3PreviewMount" class="small font-monospace overflow-auto" style="max-height: 75vh;"></div>
    <x-slot name="footer">
        <button type="button" class="btn btn-primary" onclick="window.printW3FromPreview && window.printW3FromPreview()">Print</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </x-slot>
</x-modals.modal>
@endsection

@push('scripts')
<script>
window.__W2_WIZARD_EMPLOYEES = @json($wizardEmployees);
</script>
@include('screens.admin.forms.partials.w2-wizard-script')
@endpush
