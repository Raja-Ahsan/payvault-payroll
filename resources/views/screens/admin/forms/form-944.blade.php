@section('title', 'Form 944')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid form-944-wrapper custom-form-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border d-flex flex-wrap justify-content-end gap-2">
                    <a href="{{ route('admin.forms.index') }}" class="btn btn-sm button-light-primary">Back to Forms</a>
                </div>
                <div class="card-body small">
                    <div class="d-flex flex-wrap align-items-center justify-content-end gap-2 mb-2">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-primary btn-sm" id="f944BtnPreparer">Preparer / Designee</button>
                            <button type="button" class="btn btn-primary btn-sm" id="f944BtnPreview">Preview</button>
                            <button type="button" class="btn btn-primary btn-sm" id="f944BtnPrint">Print</button>
                            <button type="button" class="btn btn-primary btn-sm" id="f944BtnOverride">Override calculations</button>
                        </div>
                    </div>
                    <p class="text-primary fst-italic mb-3">If you click the Preview button and you still can&apos;t see the preview window, please look at the bottom of your screen, you should see a second icon for the form (next to the Payroll Mate icon). You can maximize it to show the form preview window.</p>
                    <p class="text-muted small mb-3">Employer name, EIN, trade name, and address on printed forms come from your saved company record.</p>

                    <div class="fw-bold border-bottom pb-2 mb-3">Part 1: Answer these questions for {{ $taxYear }}</div>

                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l1"><span class="badge bg-secondary me-1">1</span> Wages, tips, and other compensation</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l1" class="form-control form-control-sm text-end f944-num" maxlength="9" value="700.00" readonly>
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l2"><span class="badge bg-secondary me-1">2</span> Federal income tax withheld from wages, tips, and other compensation</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l2" class="form-control form-control-sm text-end f944-num" maxlength="9" value="64.64" readonly>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input f944-cb" type="checkbox" id="f944-l3" disabled>
                        <label class="form-check-label" for="f944-l3"><span class="badge bg-secondary me-1">3</span> If no wages, tips, and other compensation are subject to social security or Medicare tax, check here and go to line 5.</label>
                    </div>

                    <div class="fw-semibold mb-2">Line 4: Taxable social security and Medicare wages and tips</div>
                    @foreach ([
                        ['a', '4a', 'Taxable social security wages', '0.124'],
                        ['b', '4b', 'Taxable social security tips', '0.124'],
                        ['c', '4c', 'Taxable Medicare wages &amp; tips', '0.029'],
                        ['d', '4d', 'Taxable wages &amp; tips subject to Additional Medicare Tax withholding', '0.009'],
                    ] as $row)
                        <div class="row mb-2 align-items-center g-2 small">
                            <div class="col-md-5">
                                <span class="badge bg-secondary me-1">{{ $row[1] }}</span> {!! $row[2] !!}
                            </div>
                            <div class="col-md-7">
                                <div class="d-flex flex-wrap align-items-center justify-content-end gap-1">
                                    <input type="text" id="f944-l4{{ $row[0] }}1" class="form-control form-control-sm text-end f944-num" style="max-width: 6rem;" maxlength="9" value="{{ $row[0] === 'a' || $row[0] === 'c' ? '700.00' : '0.00' }}" readonly>
                                    <span class="text-muted">× {{ $row[3] }} =</span>
                                    <input type="text" id="f944-l4{{ $row[0] }}2" class="form-control form-control-sm text-end bg-light" style="max-width: 6rem;" maxlength="9" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="row mb-3 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l4e"><span class="badge bg-secondary me-1">4e</span> Total social security and Medicare taxes (add column 2 from lines 4a, 4b, 4c, and 4d)</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l4e" class="form-control form-control-sm text-end bg-light" maxlength="9" readonly tabindex="-1">
                        </div>
                    </div>

                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l5"><span class="badge bg-secondary me-1">5</span> Total taxes before adjustments (add lines 2 and 4e)</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l5" class="form-control form-control-sm text-end bg-light" maxlength="9" readonly tabindex="-1">
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l6"><span class="badge bg-secondary me-1">6</span> Current year&apos;s adjustments</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l6" class="form-control form-control-sm text-end f944-num" maxlength="9" value="0.00" readonly>
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l7"><span class="badge bg-secondary me-1">7</span> Total taxes after adjustments (combine lines 5 and 6)</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l7" class="form-control form-control-sm text-end bg-light" maxlength="9" readonly tabindex="-1">
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l8"><span class="badge bg-secondary me-1">8</span> Qualified small business payroll tax credit for increasing research activities</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l8" class="form-control form-control-sm text-end f944-num" maxlength="9" value="0.00" readonly>
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l9"><span class="badge bg-secondary me-1">9</span> Total taxes after adjustments and nonrefundable credits (subtract line 8 from line 7)</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l9" class="form-control form-control-sm text-end bg-light" maxlength="9" readonly tabindex="-1">
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l10"><span class="badge bg-secondary me-1">10</span> Total deposits for this year, including overpayment applied from a prior year</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l10" class="form-control form-control-sm text-end f944-num" maxlength="9" value="0.00" readonly>
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l11"><span class="badge bg-secondary me-1">11</span> Balance due (if line 9 is more than line 10, enter the difference)</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l11" class="form-control form-control-sm text-end bg-light" maxlength="9" readonly tabindex="-1">
                        </div>
                    </div>
                    <div class="row mb-2 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l12"><span class="badge bg-secondary me-1">12</span> Overpayment (if line 10 is more than line 9, enter the difference)</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l12" class="form-control form-control-sm text-end bg-light" maxlength="9" readonly tabindex="-1">
                        </div>
                    </div>
                    <div class="form-check form-check-inline ms-1 mb-4">
                        <input class="form-check-input f944-cb" type="radio" name="f944_l12opt" id="f944-l12a" value="next" disabled>
                        <label class="form-check-label" for="f944-l12a">Apply to next return</label>
                    </div>
                    <div class="form-check form-check-inline mb-4">
                        <input class="form-check-input f944-cb" type="radio" name="f944_l12opt" id="f944-l12b" value="refund" disabled>
                        <label class="form-check-label" for="f944-l12b">Send a refund</label>
                    </div>

                    <div class="fw-bold border-bottom pb-2 mb-2">Part 2: Tell us about your deposit schedule and tax liability for {{ $taxYear }}</div>
                    <div class="mb-2 small"><span class="badge bg-secondary me-1">13</span> Check one:</div>
                    <div class="form-check mb-2">
                        <input class="form-check-input f944-cb" type="radio" name="f944_l13opt" id="f944-l13-under" value="under" checked disabled>
                        <label class="form-check-label" for="f944-l13-under">Line 9 is less than $2,500. Go to Part 3.</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input f944-cb" type="radio" name="f944_l13opt" id="f944-l13-over" value="over" disabled>
                        <label class="form-check-label" for="f944-l13-over">Line 9 is $2,500 or more. (Complete the monthly schedule below.)</label>
                    </div>

                    <p class="text-muted small mb-2">Monthly tax liability (lines 13a–13l). Total on line 13m must equal line 9.</p>
                    <div class="row g-2 mb-2">
                        @foreach ([['f944-l13a', '13a', 'Jan'], ['f944-l13d', '13d', 'Apr'], ['f944-l13g', '13g', 'Jul'], ['f944-l13j', '13j', 'Oct']] as $m)
                            <div class="col-6 col-md-3">
                                <label class="form-label mb-0 small" for="{{ $m[0] }}">{{ $m[1] }} {{ $m[2] }}</label>
                                <input type="text" id="{{ $m[0] }}" class="form-control form-control-sm text-end f944-num" maxlength="9" value="{{ $m[0] === 'f944-l13a' ? '171.74' : '0.00' }}" readonly>
                            </div>
                        @endforeach
                    </div>
                    <div class="row g-2 mb-2">
                        @foreach ([['f944-l13b', '13b', 'Feb'], ['f944-l13e', '13e', 'May'], ['f944-l13h', '13h', 'Aug'], ['f944-l13k', '13k', 'Nov']] as $m)
                            <div class="col-6 col-md-3">
                                <label class="form-label mb-0 small" for="{{ $m[0] }}">{{ $m[1] }} {{ $m[2] }}</label>
                                <input type="text" id="{{ $m[0] }}" class="form-control form-control-sm text-end f944-num" maxlength="9" value="0.00" readonly>
                            </div>
                        @endforeach
                    </div>
                    <div class="row g-2 mb-3">
                        @foreach ([['f944-l13c', '13c', 'Mar'], ['f944-l13f', '13f', 'Jun'], ['f944-l13i', '13i', 'Sep'], ['f944-l13l', '13l', 'Dec']] as $m)
                            <div class="col-6 col-md-3">
                                <label class="form-label mb-0 small" for="{{ $m[0] }}">{{ $m[1] }} {{ $m[2] }}</label>
                                <input type="text" id="{{ $m[0] }}" class="form-control form-control-sm text-end f944-num" maxlength="9" value="0.00" readonly>
                            </div>
                        @endforeach
                    </div>
                    <div class="row mb-4 align-items-center g-2">
                        <div class="col">
                            <label class="form-label mb-0" for="f944-l13m"><span class="badge bg-secondary me-1">13m</span> Total liability for year (add lines 13a through 13l). Total must equal line 9.</label>
                        </div>
                        <div class="col-auto" style="min-width: 7rem;">
                            <input type="text" id="f944-l13m" class="form-control form-control-sm text-end bg-light" maxlength="9" readonly tabindex="-1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modals.modal id="f944OverrideModal" title="Warning" size="modal-lg">
    <div class="d-flex gap-3">
        <div class="fs-2 text-warning flex-shrink-0"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="small lh-lg">
            <p class="mb-2">After clicking <strong>OK</strong> you will be able to override the values for the boxes shown on this screen.</p>
            <p class="mb-2">The form you generate from {{ config('app.name') }} will reflect your changes, but these changes will not be saved in the program.</p>
            <p class="mb-2">Please also note that these changes will not be reflected on other tax forms such as Form 941 or W-2.</p>
            <p class="mb-3">All numbers generated by this form are compiled from payroll you have recorded during the year. Manual changes can cause discrepancies with other forms.</p>
            <div class="form-check d-flex gap-2">
                <input class="form-check-input" type="checkbox" id="f944OverrideAck">
                <label class="form-check-label" for="f944OverrideAck">By checking this box you acknowledge that you are making changes at your own risk and understand that you might be required to submit corrected tax forms in the future.</label>
            </div>
        </div>
    </div>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="f944OverrideOk" disabled>OK</button>
    </x-slot>
</x-modals.modal>

<x-modals.modal id="f944PreparerModal" title="Preparer / Designee" size="modal-lg">
    <p class="small mb-0">Designee, signature, and paid preparer sections appear on page 2 of the preview. Employer information is taken from your company profile. Use Preview or Print for the three-page packet (Form 944 pages 1–2 and Form 944-V).</p>
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </x-slot>
</x-modals.modal>

<x-modals.modal id="f944PreviewModal" title="Preview — Form 944 (3 pages)" size="modal-fullscreen">
    <div id="f944PreviewMount" class="f944-preview-scroll bg-secondary bg-opacity-10 p-2 p-md-3" style="max-height: calc(100vh - 11rem); overflow-y: auto;"></div>
    <x-slot name="footer">
        <button type="button" class="btn btn-primary" id="f944PreviewPrint">Print</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    </x-slot>
</x-modals.modal>
@endsection

@push('scripts')
<script>
window.__F944_TAX_YEAR = @json($taxYear);
window.__F944_EMPLOYER = @json($employer944 ?? []);
</script>
@include('screens.admin.forms.partials.form-944-script')
@endpush
