@section('title', 'Edit Employee')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills mb-3 custom-tab-wrapper" id="employee-edit-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="employee-edit-general-tab" data-bs-toggle="pill" data-bs-target="#employee-edit-general" type="button" role="tab" aria-controls="employee-edit-general" aria-selected="true">General Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-edit-tax-setup-tab" data-bs-toggle="pill" data-bs-target="#employee-edit-tax-setup" type="button" role="tab" aria-controls="employee-edit-tax-setup" aria-selected="false">Tax Setup</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-edit-income-tab" data-bs-toggle="pill" data-bs-target="#employee-edit-income" type="button" role="tab" aria-controls="employee-edit-income" aria-selected="false">Incomes</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-edit-taxes-tab" data-bs-toggle="pill" data-bs-target="#employee-edit-taxes" type="button" role="tab" aria-controls="employee-edit-taxes" aria-selected="false">Taxes</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-edit-deductions-tab" data-bs-toggle="pill" data-bs-target="#employee-edit-deductions" type="button" role="tab" aria-controls="employee-edit-deductions" aria-selected="false">Deductions</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-edit-direct-deposit-tab" data-bs-toggle="pill" data-bs-target="#employee-edit-direct-deposit" type="button" role="tab" aria-controls="employee-edit-direct-deposit" aria-selected="false">Direct Deposit</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-edit-vacation-tab" data-bs-toggle="pill" data-bs-target="#employee-edit-vacation" type="button" role="tab" aria-controls="employee-edit-vacation" aria-selected="false">Vacation / Sick</button>
                        </li>
                    </ul>

                    <form class="ajax-form" action="{{ route('employees.update', $employee) }}" id="submit-form" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="tab-content" id="employee-edit-tabContent">
                            <div class="tab-pane fade show active" id="employee-edit-general" role="tabpanel" aria-labelledby="employee-edit-general-tab" tabindex="0">
                                <div class="row g-3 needs-validation">
                                    @include('screens.admin.employees.partials.edit-tab-general')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="employee-edit-tax-setup" role="tabpanel" aria-labelledby="employee-edit-tax-setup-tab" tabindex="0">
                                <div class="row g-3 needs-validation">
                                    @include('screens.admin.employees.partials.edit-tab-tax-setup')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="employee-edit-income" role="tabpanel" aria-labelledby="employee-edit-income-tab" tabindex="0">
                                <div class="row g-3 needs-validation">
                                    @include('screens.admin.employees.partials.edit-tab-income')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="employee-edit-taxes" role="tabpanel" aria-labelledby="employee-edit-taxes-tab" tabindex="0">
                                <div class="row g-3 needs-validation">
                                    @include('screens.admin.employees.partials.edit-tab-taxes')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="employee-edit-deductions" role="tabpanel" aria-labelledby="employee-edit-deductions-tab" tabindex="0">
                                <div class="row g-3 needs-validation">
                                    @include('screens.admin.employees.partials.edit-tab-deductions')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="employee-edit-direct-deposit" role="tabpanel" aria-labelledby="employee-edit-direct-deposit-tab" tabindex="0">
                                <div class="row g-3 needs-validation">
                                    @include('screens.admin.employees.partials.edit-tab-direct-deposit')
                                </div>
                            </div>
                            <div class="tab-pane fade" id="employee-edit-vacation" role="tabpanel" aria-labelledby="employee-edit-vacation-tab" tabindex="0">
                                <div class="row g-3 needs-validation">
                                    @include('screens.admin.employees.partials.edit-tab-vacation')
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    ajaxCreate("{{ route('employees.index') }}");

    (function() {
        function syncEmployeeTaxDeductionByIncome() {
            const incomePanel = document.getElementById('employee-wizard-income-step');
            const taxList = document.getElementById('employee-wizard-tax-list');
            const taxEmpty = document.getElementById('employee-wizard-tax-empty');
            const dedList = document.getElementById('employee-wizard-deduction-list');
            const dedEmpty = document.getElementById('employee-wizard-deduction-empty');
            if (!incomePanel || !taxList || !taxEmpty || !dedList || !dedEmpty) {
                return;
            }
            let anyIncome = false;
            incomePanel.querySelectorAll('input[type="checkbox"]').forEach(function(cb) {
                if (cb.name === 'income_category_id[]' && cb.checked) {
                    anyIncome = true;
                }
            });
            if (!anyIncome) {
                taxList.classList.add('d-none');
                taxEmpty.classList.remove('d-none');
                dedList.classList.add('d-none');
                dedEmpty.classList.remove('d-none');
            } else {
                taxList.classList.remove('d-none');
                taxEmpty.classList.add('d-none');
                dedList.classList.remove('d-none');
                dedEmpty.classList.add('d-none');
            }
        }

        const incomeStepEl = document.getElementById('employee-wizard-income-step');
        if (incomeStepEl) {
            incomeStepEl.addEventListener('change', function(e) {
                const t = e.target;
                if (t && t.type === 'checkbox' && t.name === 'income_category_id[]') {
                    syncEmployeeTaxDeductionByIncome();
                }
            });
        }
        syncEmployeeTaxDeductionByIncome();

        document.querySelectorAll('.toggle-input-wrapper').forEach(function(wrapper) {
            const checkbox = wrapper.querySelector('.toggle-checkbox');
            const target = wrapper.querySelector('.toggle-input');
            if (!checkbox || !target) {
                return;
            }

            function syncToggleAmountRequired() {
                const on = checkbox.checked;
                const isFormControl = target.matches('input, select, textarea');
                if (isFormControl) {
                    target.disabled = !on;
                    if (on) {
                        target.setAttribute('required', 'required');
                    } else {
                        target.removeAttribute('required');
                        target.classList.remove('is-invalid');
                        const n = target.nextElementSibling;
                        if (n && n.classList && n.classList.contains('field-error-msg')) {
                            n.remove();
                        }
                    }
                } else {
                    target.disabled = !on;
                }
            }

            checkbox.addEventListener('change', syncToggleAmountRequired);
            syncToggleAmountRequired();
        });

        function syncDirectDepositBanking() {
            const cb = document.getElementById('include_in_direct_deposit');
            if (!cb) {
                return;
            }
            const wrapper = cb.closest('.toggle-input-wrapper');
            const fieldset = wrapper && wrapper.querySelector('fieldset.toggle-input');
            const routing = document.getElementById('bank_routing_number');
            const account = document.getElementById('account_number');
            if (!fieldset || !routing || !account) {
                return;
            }
            const on = cb.checked;
            fieldset.disabled = !on;
            if (on) {
                routing.setAttribute('required', 'required');
                account.setAttribute('required', 'required');
            } else {
                routing.removeAttribute('required');
                account.removeAttribute('required');
                [routing, account].forEach(function(f) {
                    f.classList.remove('is-invalid');
                    var n = f.nextElementSibling;
                    if (n && n.classList && n.classList.contains('field-error-msg')) {
                        n.remove();
                    }
                });
            }
        }

        const includeDirectDeposit = document.getElementById('include_in_direct_deposit');
        if (includeDirectDeposit) {
            includeDirectDeposit.addEventListener('change', syncDirectDepositBanking);
            syncDirectDepositBanking();
        }

        (function() {
            const labels = {
                per_check: 'Per Check',
                per_total_hours: 'Per Total Hours on Check',
            };
            const vacationEl = document.getElementById('js-vacation-earned-method-label');
            const sickEl = document.getElementById('js-sick-earned-method-label');
            const radios = document.querySelectorAll('input[name="vacation_sick_calculation_method"]');

            function syncVacationSickMethodLabels() {
                const selected = document.querySelector('input[name="vacation_sick_calculation_method"]:checked');
                const key = selected ? selected.value : 'per_check';
                const text = labels[key] || labels.per_check;
                if (vacationEl) {
                    vacationEl.textContent = text;
                }
                if (sickEl) {
                    sickEl.textContent = text;
                }
            }

            radios.forEach((radio) => {
                radio.addEventListener('change', syncVacationSickMethodLabels);
            });
            syncVacationSickMethodLabels();
        })();

        (function() {
            const useNewW4 = document.getElementById('use_new_w4_2020');
            const fedAllowances = document.getElementById('fed_allowances');
            const step2 = document.getElementById('w4_step2_two_jobs');
            const step3 = document.getElementById('w4_step3_dependents');
            const step4a = document.getElementById('w4_step4a_other_income');
            const step4b = document.getElementById('w4_step4b_deductions');
            const step2Label = document.getElementById('w4_step2_two_jobs_label');

            function syncNewW4Mode() {
                if (!useNewW4) {
                    return;
                }
                const on = useNewW4.checked;
                if (fedAllowances) {
                    fedAllowances.disabled = on;
                }
                [step2, step3, step4a, step4b].forEach(function(el) {
                    if (el) {
                        el.disabled = !on;
                    }
                });
                if (step2Label) {
                    step2Label.classList.toggle('text-muted', !on);
                }
            }

            if (useNewW4) {
                useNewW4.addEventListener('change', syncNewW4Mode);
                syncNewW4Mode();
            }
        })();
    })();
</script>
@endpush
@endsection
