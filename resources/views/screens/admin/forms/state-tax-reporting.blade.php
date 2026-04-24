@section('title', 'State Tax Reporting Wizard')
@extends('layouts.admin.master')
@php
    $reportingPeriods = ['First Quarter', 'Second Quarter', 'Third Quarter', 'Fourth Quarter'];
    $appName = config('app.name');
    $stateReportingConfig = $stateReportingConfig ?? [];
    $stateReportingEmployeeCounts = $stateReportingEmployeeCounts ?? [];
@endphp
@section('content')
<div class="container-fluid state-reporting-wizard">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10 col-xxl-8">
            <div class="card">
                <div class="card-header card-no-border d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h4 class="mb-0 f-w-600">State Tax Reporting Wizard</h4>
                    <a href="{{ route('admin.forms.index') }}" class="btn btn-sm button-light-primary">Back to Forms</a>
                </div>
                <div class="card-body">
                    <form id="state-tax-reporting-form" action="#" method="get" onsubmit="return false;">
                        {{-- Step 1: dropdowns --}}
                        <div id="wizard-step-1">
                            <div class="mb-4">
                                <div class="row g-2 align-items-start">
                                    <div class="col-12 col-md-4 col-lg-3 text-md-end">
                                        <label for="reporting_state" class="form-label fw-bold mb-0 pt-md-2">Reporting State:</label>
                                    </div>
                                    <div class="col-12 col-md-8 col-lg-9">
                                        <select id="reporting_state" name="reporting_state" class="form-select form-select-sm" style="max-width: 22rem;">
                                            @forelse ($states as $state)
                                                <option value="{{ $state->code }}" @selected(strtoupper($state->code) === 'CA')>{{ $state->name }}</option>
                                            @empty
                                                <option value="CA" selected>California</option>
                                            @endforelse
                                        </select>
                                        <p class="text-muted small mt-2 mb-0">
                                            (Select the state for which you are reporting Income Tax Withholding, Unemployment, or Disability Insurance)
                                            [If your state isn&apos;t listed in the &quot;Reporting State&quot; list above, then it&apos;s not currently supported by {{ $appName }}]
                                        </p>
                                        <div id="state-wizard-step1-error" class="alert alert-danger py-2 small mt-2 mb-0 d-none" role="alert"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="row g-2 align-items-start">
                                    <div class="col-12 col-md-4 col-lg-3 text-md-end">
                                        <label for="reporting_period" class="form-label fw-bold mb-0 pt-md-2">Reporting Period:</label>
                                    </div>
                                    <div class="col-12 col-md-8 col-lg-9">
                                        <select id="reporting_period" name="reporting_period" class="form-select form-select-sm" style="max-width: 22rem;">
                                            @foreach ($reportingPeriods as $idx => $label)
                                                <option value="{{ $idx + 1 }}" @selected($idx === 0)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="row g-2 align-items-start">
                                    <div class="col-12 col-md-4 col-lg-3 text-md-end">
                                        <label for="reported_tax" class="form-label fw-bold mb-0 pt-md-2">Reported Tax:</label>
                                    </div>
                                    <div class="col-12 col-md-8 col-lg-9">
                                        <select id="reported_tax" name="reported_tax" class="form-select form-select-sm" style="max-width: 36rem;"></select>
                                        <p class="text-muted small mt-2 mb-0">(Specify the type of tax you want to report)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Step 2: reporting method options (from state_reporting_* tables) --}}
                        <div id="wizard-step-2" class="d-none">
                            <div id="wizard-step-2-dynamic"></div>
                        </div>

                        {{-- Texas ICESA (and other flow_kind=icesa): transmitter + .ICE output path --}}
                        <div id="wizard-step-icesa" class="d-none">
                            <p id="icesa-intro" class="small text-muted mb-3"></p>
                            <div class="fw-semibold border-bottom pb-2 mb-3">Transmitter info</div>
                            <p class="small text-muted mb-2">Transmitter info (same as company info if transmitting for your own company).</p>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label small mb-0" for="icesa_transmitter_name">Transmitter name</label>
                                    <input type="text" class="form-control form-control-sm" id="icesa_transmitter_name" autocomplete="organization">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small mb-0" for="icesa_contact_name">Contact name</label>
                                    <input type="text" class="form-control form-control-sm" id="icesa_contact_name" autocomplete="name">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small mb-0" for="icesa_address">Address</label>
                                    <input type="text" class="form-control form-control-sm" id="icesa_address" autocomplete="street-address">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small mb-0" for="icesa_city">City</label>
                                    <input type="text" class="form-control form-control-sm" id="icesa_city" autocomplete="address-level2">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small mb-0" for="icesa_transmitter_state">State</label>
                                    <input type="text" class="form-control form-control-sm" id="icesa_transmitter_state" maxlength="2" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small mb-0" for="icesa_zip">ZIP</label>
                                    <input type="text" class="form-control form-control-sm" id="icesa_zip" autocomplete="postal-code">
                                </div>
                            </div>
                            <div class="fw-semibold border-bottom pb-2 mb-2 mt-3">Output file</div>
                            <label class="form-label small mb-0" for="icesa_output_path">Path for the magnetic/electronic file (.ICE)</label>
                            <input type="text" class="form-control form-control-sm font-monospace mb-0" id="icesa_output_path" placeholder="C:\Users\...\TWCWAGES.ICE" autocomplete="off">
                            <p class="text-muted small mt-2 mb-0">The file name must include <code>.ICE</code> to be valid for TWC electronic filing.</p>
                        </div>

                        {{-- Step 3: select employees for the report --}}
                        <div id="wizard-step-3" class="d-none">
                            <p class="small text-muted mb-3">
                                From the list below please select the employee(s) you would like to include in this report. You can edit the information being reported for a certain employee by first selecting that employee and then clicking the &quot;Edit Employee&quot; button.
                            </p>
                            <div class="row g-3 align-items-start">
                                <div class="col">
                                    <div class="table-responsive border rounded">
                                        <table class="table table-sm table-hover mb-0 align-middle" id="state-wizard-emp-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 2.5rem;" class="text-center"></th>
                                                    <th scope="col">Full Name</th>
                                                    <th scope="col">SSNumber</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($wizardTableEmployees as $emp)
                                                    <tr
                                                        class="state-wizard-emp-row"
                                                        data-employee-id="{{ (int) $emp['id'] }}"
                                                        data-emp="{{ e(json_encode($emp, JSON_UNESCAPED_UNICODE)) }}"
                                                    >
                                                        <td class="text-center">
                                                            <input type="checkbox" class="form-check-input state-emp-cb" name="state_report_emp[]" value="{{ (int) $emp['id'] }}" @checked($emp['include_in_state_reporting'] ?? true)>
                                                        </td>
                                                        <td class="state-emp-col-name">{{ $emp['full_name'] }}</td>
                                                        <td class="state-emp-col-ssn">{{ $emp['ssn'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="text-primary small mt-2 mb-0">
                                        Since the &apos;State Tax Reporting&apos; option is not enabled, only one employee is shown in this table. The rest of the names are omitted. Please make sure to order: &apos;{{ $appName }} Option #5 (State Tax Reporting)&apos;
                                    </p>
                                </div>
                                <div class="col-12 col-sm-auto d-flex flex-sm-column flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm btn-primary" id="state-wizard-btn-edit-employee">Edit Employee</button>
                                    <button type="button" class="btn btn-sm btn-primary" id="state-wizard-check-all">Check All</button>
                                    <button type="button" class="btn btn-sm btn-primary" id="state-wizard-uncheck-all">Uncheck All</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <div class="border border-primary text-primary small px-3 py-2 rounded" id="wizard-amounts-box">
                                    Amounts are for: <span id="wizard-amounts-period">1st Quarter {{ $amountsForYear }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Step 4: quarter summary (review) --}}
                        <div id="wizard-step-4" class="d-none">
                            <div class="row g-4 align-items-stretch">
                                <div class="col-md-6">
                                    <div class="fw-semibold border-bottom pb-2 mb-3">Summary</div>
                                    <div class="d-flex flex-column gap-3 state-wiz-summary-readonly">
                                        <div>
                                            <label for="sum_emp_count" class="form-label small text-muted mb-0">Number of employees reported in the quarter:</label>
                                            <input type="text" class="form-control form-control-sm bg-light" id="sum_emp_count" readonly value="0">
                                        </div>
                                        <div>
                                            <label for="sum_total_wages" class="form-label small text-muted mb-0">Total Wages:</label>
                                            <input type="text" class="form-control form-control-sm bg-light" id="sum_total_wages" readonly value="0.00">
                                        </div>
                                        <div>
                                            <label for="sum_taxable_wages" class="form-label small text-muted mb-0">Taxable Wages:</label>
                                            <input type="text" class="form-control form-control-sm bg-light" id="sum_taxable_wages" readonly value="0.00">
                                        </div>
                                        <div>
                                            <label for="sum_amount_due" class="form-label small text-muted mb-0">Amount Due:</label>
                                            <input type="text" class="form-control form-control-sm bg-light" id="sum_amount_due" readonly value="0.00">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="fw-semibold border-bottom pb-2 mb-3">Details</div>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-primary py-2" id="state-wizard-preview-form">Preview Form</button>
                                        <button type="button" class="btn btn-primary py-2" id="state-wizard-preview-details">Preview full details</button>
                                        <button type="button" class="btn btn-primary py-2" id="state-wizard-print-details">Print full details</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-end gap-2 pt-3 border-top" id="wizard-footer">
                            <a href="{{ route('admin.forms.index') }}" class="btn btn-secondary btn-sm" role="button">Cancel</a>
                            <button type="button" class="btn btn-secondary btn-sm" id="wizard-btn-back" disabled>&lt; Back</button>
                            <button type="button" class="btn btn-primary btn-sm" id="wizard-btn-next">Next &gt;</button>
                            <button type="button" class="btn btn-secondary btn-sm" id="wizard-btn-close" disabled>Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Employee (same step as employee list; matches Payroll Mate flow) --}}
<div class="modal fade" id="stateWizardEditEmployeeModal" tabindex="-1" aria-labelledby="stateWizardEditEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title" id="stateWizardEditEmployeeModalLabel">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 align-items-end mb-2">
                    <div class="col">
                        <label for="se_first_name" class="form-label small mb-0">First Name</label>
                        <input type="text" class="form-control form-control-sm" id="se_first_name" autocomplete="given-name">
                    </div>
                    <div class="col-auto" style="max-width: 3.5rem;">
                        <label for="se_middle_initial" class="form-label small mb-0">Middle Initial</label>
                        <input type="text" class="form-control form-control-sm text-center" id="se_middle_initial" maxlength="1" autocomplete="additional-name">
                    </div>
                    <div class="col">
                        <label for="se_last_name" class="form-label small mb-0">Last Name</label>
                        <input type="text" class="form-control form-control-sm" id="se_last_name" autocomplete="family-name">
                    </div>
                </div>
                <div class="mb-2">
                    <label for="se_ssn" class="form-label small mb-0">Social security number</label>
                    <input type="text" class="form-control form-control-sm" id="se_ssn" inputmode="numeric" autocomplete="off" maxlength="11">
                </div>
                <div class="mb-1">
                    <input type="text" class="form-control form-control-sm" id="se_extra_a" aria-label="Additional field 1" autocomplete="off">
                </div>
                <div class="mb-0">
                    <input type="text" class="form-control form-control-sm" id="se_extra_b" aria-label="Additional field 2" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer flex-wrap align-items-center justify-content-between gap-2">
                <div class="form-check mb-0 me-auto">
                    <input class="form-check-input" type="checkbox" id="se_include" checked>
                    <label class="form-check-label small" for="se_include">Include Employee in Current State Reporting</label>
                </div>
                <div class="d-flex gap-2 ms-auto">
                    <button type="button" class="btn btn-sm btn-primary" id="se_btn_ok">OK</button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- California DE 9 Options (opens from Preview Form on summary step) --}}
<div class="modal fade" id="californiaDe9OptionsModal" tabindex="-1" aria-labelledby="californiaDe9OptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title" id="californiaDe9OptionsModalLabel">California DE 9 Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-2">Check all that apply:</p>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="de9_no_wages" name="de9_no_wages" value="1">
                    <label class="form-check-label small" for="de9_no_wages">No Wages Paid This Quarter</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="de9_out_business" name="de9_out_business" value="1">
                    <label class="form-check-label small" for="de9_out_business">Out of Business</label>
                </div>
                <div class="mb-3">
                    <label for="de9_out_business_date" class="form-label small mb-0">Out of Business Date</label>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <input type="text" class="form-control form-control-sm" id="de9_out_business_date" placeholder="MMDDYYYY" inputmode="numeric" maxlength="8" autocomplete="off" disabled>
                        <span class="text-muted small">MMDDYYYY</span>
                    </div>
                </div>
                <div class="mb-2">
                    <label for="de9_ett_percent" class="form-label small mb-0">Employment Training Tax (ETT) %</label>
                    <input type="text" class="form-control form-control-sm" id="de9_ett_percent" value="0.00" inputmode="decimal" autocomplete="off">
                </div>
                <div class="mb-0">
                    <label for="de9_contributions" class="form-label small mb-0">Contributions and withholdings paid for the quarter</label>
                    <input type="text" class="form-control form-control-sm" id="de9_contributions" value="0.00" inputmode="decimal" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer py-2 justify-content-center gap-2">
                <button type="button" class="btn btn-sm btn-primary" id="de9_options_ok">OK</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" id="de9_options_cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const step1 = document.getElementById('wizard-step-1');
    const step2 = document.getElementById('wizard-step-2');
    const step3 = document.getElementById('wizard-step-3');
    const step4 = document.getElementById('wizard-step-4');
    const stepIcesa = document.getElementById('wizard-step-icesa');
    const reportedTax = document.getElementById('reported_tax');
    const reportingPeriod = document.getElementById('reporting_period');
    const btnBack = document.getElementById('wizard-btn-back');
    const btnNext = document.getElementById('wizard-btn-next');
    const btnClose = document.getElementById('wizard-btn-close');
    const amountsPeriod = document.getElementById('wizard-amounts-period');
    const amountsYear = {{ (int) $amountsForYear }};
    const sumEmpCount = document.getElementById('sum_emp_count');
    const sumTotalWages = document.getElementById('sum_total_wages');
    const sumTaxableWages = document.getElementById('sum_taxable_wages');
    const sumAmountDue = document.getElementById('sum_amount_due');
    const formsIndexUrl = @json(route('admin.forms.index'));
    const reportingState = document.getElementById('reporting_state');
    const step2Dynamic = document.getElementById('wizard-step-2-dynamic');
    const STATE_REPORTING_CONFIG = @json($stateReportingConfig);
    const STATE_REPORTING_EMP_COUNTS = @json($stateReportingEmployeeCounts ?? []);

    function escHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    const step1Error = document.getElementById('state-wizard-step1-error');

    function clearStep1Error() {
        if (step1Error) {
            step1Error.classList.add('d-none');
            step1Error.textContent = '';
        }
    }

    function showStep1Error(message) {
        if (step1Error) {
            step1Error.textContent = message;
            step1Error.classList.remove('d-none');
        } else {
            window.alert(message);
        }
    }

    function getEmployeeCountForSelectedReportingState() {
        if (! reportingState) {
            return 0;
        }
        const code = (reportingState.value || '').toString().toUpperCase();
        const c = STATE_REPORTING_EMP_COUNTS[code];
        return typeof c === 'number' ? c : 0;
    }

    function taxTypesForState(stateCode) {
        const code = (stateCode || '').toString().toUpperCase();
        return STATE_REPORTING_CONFIG.filter(function (t) {
            return (t.state_code || '').toUpperCase() === code;
        });
    }

    function fillReportedTaxSelect(stateCode) {
        if (!reportedTax) {
            return;
        }
        const types = taxTypesForState(stateCode);
        reportedTax.innerHTML = '';
        if (types.length === 0) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'No reporting types configured for this state';
            reportedTax.appendChild(opt);
            reportedTax.disabled = true;
            return;
        }
        reportedTax.disabled = false;
        types.forEach(function (t) {
            const opt = document.createElement('option');
            opt.value = String(t.id);
            opt.textContent = t.label;
            reportedTax.appendChild(opt);
        });
        reportedTax.selectedIndex = 0;
    }

    function renderStep2Methods() {
        if (!step2Dynamic || !reportedTax) {
            return;
        }
        const id = parseInt(reportedTax.value, 10);
        const tax = STATE_REPORTING_CONFIG.find(function (t) {
            return t.id === id;
        });
        if (!tax || !tax.methods || tax.methods.length === 0) {
            step2Dynamic.innerHTML = '<p class="text-muted small mb-0">No reporting method is configured for this selection.</p>';
            return;
        }
        let html = '';
        tax.methods.forEach(function (m, idx) {
            const radioId = 'sr_method_' + m.id;
            const checked = idx === 0 ? ' checked' : '';
            html += '<div class="state-wizard-method-block mb-4">';
            html += '<div class="form-check ps-0 mb-2">';
            const fk = (m.flow_kind || 'generic').toString().replace(/"/g, '&quot;');
            html += '<input class="form-check-input ms-0 me-2" type="radio" name="state_wizard_method" id="' + radioId + '" value="' + String(m.slug).replace(/"/g, '&quot;') + '" data-flow-kind="' + fk + '"' + checked + '>';
            html += '<label class="form-check-label fw-semibold" for="' + radioId + '">' + escHtml(m.label) + '</label></div>';
            if (m.link_text) {
                html += '<p class="text-primary small mb-2">' + escHtml(m.link_text) + '</p>';
            }
            if (m.description) {
                html += '<p class="text-muted small mb-0">' + escHtml(m.description) + '</p>';
            }
            html += '</div>';
        });
        step2Dynamic.innerHTML = html;
    }

    function showStep2Panel() {
        renderStep2Methods();
    }

    const wizardPanelIds = ['wizard-step-1', 'wizard-step-2', 'wizard-step-icesa', 'wizard-step-3', 'wizard-step-4'];
    let stepSequence = ['wizard-step-1', 'wizard-step-2', 'wizard-step-3', 'wizard-step-4'];
    let seqIndex = 0;

    function hideAllWizardPanels() {
        wizardPanelIds.forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('d-none');
            }
        });
    }

    function getSelectedMethodFlowKind() {
        if (!step2Dynamic) {
            return 'generic';
        }
        const r = step2Dynamic.querySelector('input[name="state_wizard_method"]:checked');
        return (r && r.getAttribute('data-flow-kind')) ? r.getAttribute('data-flow-kind') : 'generic';
    }

    function getSelectedMethodModel() {
        const tid = parseInt(reportedTax.value, 10);
        const tax = STATE_REPORTING_CONFIG.find(function (t) {
            return t.id === tid;
        });
        if (!tax || !tax.methods || !tax.methods.length) {
            return null;
        }
        const r = step2Dynamic ? step2Dynamic.querySelector('input[name="state_wizard_method"]:checked') : null;
        const slug = r ? r.value : tax.methods[0].slug;
        return tax.methods.find(function (m) {
            return m.slug === slug;
        }) || tax.methods[0];
    }

    function rebuildDefaultStepSequence() {
        stepSequence = ['wizard-step-1', 'wizard-step-2', 'wizard-step-3', 'wizard-step-4'];
    }

    function rebuildStepSequenceAfterMethods() {
        const useIcesa = getSelectedMethodFlowKind() === 'icesa' && stepIcesa;
        stepSequence = ['wizard-step-1', 'wizard-step-2'];
        if (useIcesa) {
            stepSequence.push('wizard-step-icesa');
        }
        stepSequence.push('wizard-step-3', 'wizard-step-4');
    }

    function applyIcesaIntroFromMeta() {
        const el = document.getElementById('icesa-intro');
        if (!el) {
            return;
        }
        const m = getSelectedMethodModel();
        const intro = (m && m.meta && m.meta.icesa_intro) ? m.meta.icesa_intro : 'Enter transmitter information and the path for the output magnetic/electronic file (.ICE).';
        el.textContent = intro;
        const outp = document.getElementById('icesa_output_path');
        if (outp && m && m.meta && m.meta.output_path_placeholder) {
            outp.placeholder = m.meta.output_path_placeholder;
        }
        const st = document.getElementById('icesa_transmitter_state');
        if (st && reportingState) {
            st.value = (reportingState.value || '').toString().toUpperCase();
        }
    }

    function showStepAtIndex(i) {
        hideAllWizardPanels();
        if (i < 0 || i >= stepSequence.length) {
            return;
        }
        const id = stepSequence[i];
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove('d-none');
        }
        seqIndex = i;
        if (id === 'wizard-step-3') {
            updateAmountsPeriodText();
        }
        if (id === 'wizard-step-4') {
            refreshStep4Summary();
        }
        if (id === 'wizard-step-icesa') {
            applyIcesaIntroFromMeta();
        }
        updateNavButtons();
    }

    function updateNavButtons() {
        if (btnBack) {
            btnBack.disabled = (seqIndex <= 0);
        }
        if (btnNext) {
            btnNext.disabled = (seqIndex >= stepSequence.length - 1);
        }
        if (btnClose) {
            btnClose.disabled = (seqIndex !== stepSequence.length - 1);
        }
    }

    function isOnMethodsStep() {
        return stepSequence[seqIndex] === 'wizard-step-2';
    }

    function updateAmountsPeriodText() {
        if (! amountsPeriod) return;
        const q = parseInt(reportingPeriod.value, 10) || 1;
        const ord = q === 1 ? '1st' : (q === 2 ? '2nd' : (q === 3 ? '3rd' : '4th'));
        amountsPeriod.textContent = ord + ' Quarter ' + amountsYear;
    }

    function refreshStep4Summary() {
        let n = 0;
        document.querySelectorAll('.state-wizard-emp-row').forEach(function (row) {
            const cb = row.querySelector('.state-emp-cb');
            if (cb && cb.checked) {
                n++;
            }
        });
        const wageEach = 700.0;
        const dueEach = 7.0;
        const totalW = n * wageEach;
        const taxable = totalW;
        const due = n * dueEach;
        if (sumEmpCount) {
            sumEmpCount.value = String(n);
        }
        if (sumTotalWages) {
            sumTotalWages.value = totalW.toFixed(2);
        }
        if (sumTaxableWages) {
            sumTaxableWages.value = taxable.toFixed(2);
        }
        if (sumAmountDue) {
            sumAmountDue.value = due.toFixed(2);
        }
    }

    btnNext.addEventListener('click', function () {
        if (seqIndex === 0) {
            clearStep1Error();
            if (getEmployeeCountForSelectedReportingState() < 1) {
                showStep1Error('No active employees are associated with the selected state (check address state or state withholding on employee records). You cannot continue until at least one employee is available for this state.');
                return;
            }
            showStep2Panel();
            showStepAtIndex(1);
            return;
        }
        if (seqIndex === 1) {
            rebuildStepSequenceAfterMethods();
            showStepAtIndex(2);
            return;
        }
        if (seqIndex < stepSequence.length - 1) {
            showStepAtIndex(seqIndex + 1);
        }
    });

    btnBack.addEventListener('click', function () {
        if (btnBack.disabled) {
            return;
        }
        if (seqIndex > 0) {
            showStepAtIndex(seqIndex - 1);
        }
    });

    if (btnClose) {
        btnClose.addEventListener('click', function () {
            if (btnClose.disabled) {
                return;
            }
            window.location.href = formsIndexUrl;
        });
    }

    const editModalEl = document.getElementById('stateWizardEditEmployeeModal');
    const seFirst = document.getElementById('se_first_name');
    const seMid = document.getElementById('se_middle_initial');
    const seLast = document.getElementById('se_last_name');
    const seSsn = document.getElementById('se_ssn');
    const seA = document.getElementById('se_extra_a');
    const seB = document.getElementById('se_extra_b');
    const seIncl = document.getElementById('se_include');
    const seOk = document.getElementById('se_btn_ok');
    let editModal = null;
    let editTargetRow = null;
    if (editModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        editModal = bootstrap.Modal.getOrCreateInstance(editModalEl);
    }

    function getEmpFromRow(row) {
        if (!row) {
            return null;
        }
        const raw = row.getAttribute('data-emp');
        if (!raw) {
            return null;
        }
        try {
            return JSON.parse(raw);
        } catch (e) {
            return null;
        }
    }

    function buildDisplayName(firstName, middle, lastName) {
        const f = (firstName || '').trim();
        const m0 = (middle || '').trim().charAt(0);
        const m = m0 ? m0.toUpperCase() : '';
        const l = (lastName || '').trim();
        const out = [f, m, l].filter(function (p) { return p !== ''; });
        return out.join(' ');
    }

    function setEmpOnRow(row, emp) {
        if (!row) {
            return;
        }
        row.setAttribute('data-emp', JSON.stringify(emp));
        const nameCell = row.querySelector('.state-emp-col-name');
        const ssnCell = row.querySelector('.state-emp-col-ssn');
        if (nameCell) {
            nameCell.textContent = emp.full_name || '';
        }
        if (ssnCell) {
            ssnCell.textContent = emp.ssn && emp.ssn !== '' ? emp.ssn : '—';
        }
        const cb = row.querySelector('.state-emp-cb');
        if (cb) {
            cb.checked = !!emp.include_in_state_reporting;
        }
    }

    function openEditEmployeeModal(row) {
        if (!row || !editModal || !seFirst) {
            return;
        }
        editTargetRow = row;
        const emp = getEmpFromRow(row) || {};
        seFirst.value = emp.first_name || '';
        seMid.value = (emp.middle_initial || '').toString().charAt(0) || '';
        seLast.value = emp.last_name || '';
        if (seSsn) {
            seSsn.value = (emp.ssn && emp.ssn !== '—') ? String(emp.ssn) : '';
        }
        if (seA) {
            seA.value = emp.phone != null ? String(emp.phone) : '';
        }
        if (seB) {
            seB.value = emp.email != null ? String(emp.email) : '';
        }
        if (seIncl) {
            seIncl.checked = emp.include_in_state_reporting !== false;
        }
        editModal.show();
    }

    const btnEdit = document.getElementById('state-wizard-btn-edit-employee');
    if (btnEdit) {
        btnEdit.addEventListener('click', function () {
            let target = null;
            document.querySelectorAll('.state-wizard-emp-row').forEach(function (row) {
                const cb = row.querySelector('.state-emp-cb');
                if (cb && cb.checked && !target) {
                    target = row;
                }
            });
            if (! target) {
                const first = document.querySelector('.state-wizard-emp-row');
                target = first;
            }
            if (target) {
                openEditEmployeeModal(target);
            }
        });
    }

    if (seOk) {
        seOk.addEventListener('click', function () {
            if (!editTargetRow || !seFirst || !seLast) {
                return;
            }
            const prev = getEmpFromRow(editTargetRow) || {};
            const firstName = (seFirst.value || '').trim();
            const mid = (seMid && seMid.value) ? seMid.value.trim() : '';
            const lastName = (seLast.value || '').trim();
            const ssn = seSsn ? (seSsn.value || '').trim() : '';
            const phone = seA ? (seA.value || '').trim() : '';
            const email = seB ? (seB.value || '').trim() : '';
            const include = seIncl ? seIncl.checked : true;
            const middleInitial = mid ? mid.charAt(0).toUpperCase() : '';
            const next = {
                id: prev.id,
                first_name: firstName,
                middle_initial: middleInitial,
                last_name: lastName,
                full_name: buildDisplayName(firstName, mid, lastName) || (prev.id ? 'Employee #'+prev.id : 'Employee'),
                ssn: ssn !== '' ? ssn : '—',
                phone: phone,
                email: email,
                include_in_state_reporting: include
            };
            setEmpOnRow(editTargetRow, next);
            if (editModal) {
                editModal.hide();
            }
            editTargetRow = null;
        });
    }

    const btnCheckAll = document.getElementById('state-wizard-check-all');
    if (btnCheckAll) {
        btnCheckAll.addEventListener('click', function () {
            document.querySelectorAll('.state-emp-cb').forEach(function (cb) {
                cb.checked = true;
            });
        });
    }
    const btnUncheckAll = document.getElementById('state-wizard-uncheck-all');
    if (btnUncheckAll) {
        btnUncheckAll.addEventListener('click', function () {
            document.querySelectorAll('.state-emp-cb').forEach(function (cb) {
                cb.checked = false;
            });
        });
    }

    function openStateReportingDetailsWindow(alsoPrint) {
        const w = window.open('', alsoPrint ? '_print_state_wiz' : '_preview_state_wiz', 'width=700,height=520,scrollbars=yes,menubar=no,toolbar=no');
        if (!w) {
            return;
        }
        let listHtml = '';
        document.querySelectorAll('.state-wizard-emp-row').forEach(function (row) {
            const cb = row.querySelector('.state-emp-cb');
            if (!cb || !cb.checked) {
                return;
            }
            const emp = getEmpFromRow(row);
            if (emp) {
                listHtml += '<li>' + escHtml(emp.full_name) + ' — SSN: ' + escHtml(emp.ssn) + '</li>';
            }
        });
        let icesaBlock = '';
        const icePath = document.getElementById('icesa_output_path');
        if (icePath && icePath.value) {
            icesaBlock += '<p><strong>ICE output path:</strong> ' + escHtml(icePath.value) + '</p>';
        }
        const tName = document.getElementById('icesa_transmitter_name');
        if (tName && tName.value) {
            icesaBlock += '<p><strong>Transmitter:</strong> ' + escHtml(tName.value) + '</p>';
        }
        w.document.open();
        w.document.write(
            '<!DOCTYPE html><html><head><meta charset="utf-8"><title>State Tax Reporting</title><style>body{font-family:system-ui,sans-serif;padding:1.25rem;line-height:1.4;} h1{font-size:1.15rem;} ul{padding-left:1.25rem;}</style></head><body>' +
            '<h1>State tax reporting — full details</h1>' +
            '<p><strong>Number of employees reported in the quarter:</strong> ' + escHtml(String(sumEmpCount && sumEmpCount.value ? sumEmpCount.value : '0')) + '</p>' +
            '<p><strong>Total Wages:</strong> ' + escHtml(String(sumTotalWages && sumTotalWages.value)) +
            ' &nbsp; <strong>Taxable Wages:</strong> ' + escHtml(String(sumTaxableWages && sumTaxableWages.value)) +
            ' &nbsp; <strong>Amount Due:</strong> ' + escHtml(String(sumAmountDue && sumAmountDue.value)) + '</p>' +
            icesaBlock +
            '<p><strong>Included employees</strong></p><ul>' + (listHtml || '<li>—</li>') + '</ul>' +
            '</body></html>'
        );
        w.document.close();
        w.focus();
        if (alsoPrint) {
            setTimeout(function () {
                w.print();
            }, 250);
        }
    }

    const btnPreviewDetails = document.getElementById('state-wizard-preview-details');
    if (btnPreviewDetails) {
        btnPreviewDetails.addEventListener('click', function () {
            openStateReportingDetailsWindow(false);
        });
    }
    const btnPrintDetails = document.getElementById('state-wizard-print-details');
    if (btnPrintDetails) {
        btnPrintDetails.addEventListener('click', function () {
            openStateReportingDetailsWindow(true);
        });
    }

    const de9OptionsModalEl = document.getElementById('californiaDe9OptionsModal');
    const btnPreviewForm = document.getElementById('state-wizard-preview-form');
    const de9OutBusiness = document.getElementById('de9_out_business');
    const de9OobDate = document.getElementById('de9_out_business_date');
    const de9NoWages = document.getElementById('de9_no_wages');
    const de9Ett = document.getElementById('de9_ett_percent');
    const de9Contrib = document.getElementById('de9_contributions');
    const de9Ok = document.getElementById('de9_options_ok');
    let de9OptionsModal = null;
    if (de9OptionsModalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        de9OptionsModal = bootstrap.Modal.getOrCreateInstance(de9OptionsModalEl);
    }

    function syncDe9OobDateField() {
        if (de9OobDate && de9OutBusiness) {
            de9OobDate.disabled = !de9OutBusiness.checked;
            if (! de9OutBusiness.checked) {
                de9OobDate.value = '';
            }
        }
    }
    if (de9OutBusiness) {
        de9OutBusiness.addEventListener('change', syncDe9OobDateField);
    }

    function resetDe9OptionsForm() {
        if (de9NoWages) {
            de9NoWages.checked = false;
        }
        if (de9OutBusiness) {
            de9OutBusiness.checked = false;
        }
        if (de9OobDate) {
            de9OobDate.value = '';
        }
        if (de9Ett) {
            de9Ett.value = '0.00';
        }
        if (de9Contrib) {
            de9Contrib.value = '0.00';
        }
        syncDe9OobDateField();
    }

    if (btnPreviewForm) {
        btnPreviewForm.addEventListener('click', function () {
            resetDe9OptionsForm();
            if (de9OptionsModal) {
                de9OptionsModal.show();
            }
        });
    }
    if (de9Ok) {
        de9Ok.addEventListener('click', function () {
            if (de9OptionsModal) {
                de9OptionsModal.hide();
            }
        });
    }

    if (reportingState) {
        reportingState.addEventListener('change', function () {
            clearStep1Error();
            fillReportedTaxSelect(reportingState.value);
            if (isOnMethodsStep()) {
                renderStep2Methods();
            }
        });
    }
    if (reportedTax) {
        reportedTax.addEventListener('change', function () {
            if (isOnMethodsStep()) {
                renderStep2Methods();
            }
        });
    }
    if (reportingState) {
        fillReportedTaxSelect(reportingState.value);
    } else {
        fillReportedTaxSelect('CA');
    }
    renderStep2Methods();
    rebuildDefaultStepSequence();
    showStepAtIndex(0);
})();
</script>
@endpush
