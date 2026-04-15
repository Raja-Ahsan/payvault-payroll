@section('title', 'Employee: ' . $employee->first_name . ' ' . $employee->last_name)
@extends('layouts.admin.master')
@section('content')
@php
    $d = $employee->detail;
    $yn = fn ($v) => $v ? 'Yes' : 'No';
@endphp
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h4 class="mb-0">Employee details</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('employees.index') }}" class="btn btn-light">Back to list</a>
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-primary"><i class="fa-solid fa-pen pe-1"></i>Edit</a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3 custom-tab-wrapper flex-wrap" id="employee-show-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="employee-show-general-tab" data-bs-toggle="pill" data-bs-target="#employee-show-general" type="button" role="tab" aria-controls="employee-show-general" aria-selected="true">General Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-show-tax-setup-tab" data-bs-toggle="pill" data-bs-target="#employee-show-tax-setup" type="button" role="tab" aria-controls="employee-show-tax-setup" aria-selected="false">Tax Setup</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-show-income-tab" data-bs-toggle="pill" data-bs-target="#employee-show-income" type="button" role="tab" aria-controls="employee-show-income" aria-selected="false">Income</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-show-taxes-tab" data-bs-toggle="pill" data-bs-target="#employee-show-taxes" type="button" role="tab" aria-controls="employee-show-taxes" aria-selected="false">Taxes</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-show-deductions-tab" data-bs-toggle="pill" data-bs-target="#employee-show-deductions" type="button" role="tab" aria-controls="employee-show-deductions" aria-selected="false">Deductions</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-show-direct-deposit-tab" data-bs-toggle="pill" data-bs-target="#employee-show-direct-deposit" type="button" role="tab" aria-controls="employee-show-direct-deposit" aria-selected="false">Direct Deposit</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employee-show-vacation-tab" data-bs-toggle="pill" data-bs-target="#employee-show-vacation" type="button" role="tab" aria-controls="employee-show-vacation" aria-selected="false">Vacation / Sick</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="employee-show-tabContent">
                        <div class="tab-pane fade show active" id="employee-show-general" role="tabpanel" aria-labelledby="employee-show-general-tab" tabindex="0">
                            <h5 class="border-bottom pb-2 mb-3">General</h5>
                            <div class="row mb-4">
                                <div class="col-md-4 mb-2"><span class="text-muted">Company</span><br><strong>{{ $employee->company?->company_name ?? '—' }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">First name</span><br><strong>{{ $employee->first_name }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">Middle name</span><br><strong>{{ $employee->middle_name ?: '—' }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">Last name</span><br><strong>{{ $employee->last_name }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">SSN</span><br><strong>{{ $employee->ssn }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">Date of birth</span><br><strong>{{ $employee->dob?->format('Y-m-d') ?? '—' }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">Phone</span><br><strong>{{ $employee->phone ?: '—' }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">Fax</span><br><strong>{{ $employee->fax ?: '—' }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">Email</span><br><strong>{{ $employee->email ?: '—' }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">Employee ID</span><br><strong>{{ $employee->employee_id ?: '—' }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">Inactive</span><br><strong>{{ $yn($employee->inactive) }}</strong></div>
                                <div class="col-12 mb-2"><span class="text-muted">Address 1</span><br><strong>{{ $employee->address_1 }}</strong></div>
                                <div class="col-12 mb-2"><span class="text-muted">Address 2</span><br><strong>{{ $employee->address_2 ?: '—' }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">City</span><br><strong>{{ $employee->city }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">State</span><br><strong>{{ $employee->state?->name ?? '—' }}</strong></div>
                                <div class="col-md-4 mb-2"><span class="text-muted">ZIP</span><br><strong>{{ $employee->zip_code }}</strong></div>
                                <div class="col-12 mb-2"><span class="text-muted">Memo</span><br><strong>{!! $employee->message ? nl2br(e($employee->message)) : '—' !!}</strong></div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="employee-show-tax-setup" role="tabpanel" aria-labelledby="employee-show-tax-setup-tab" tabindex="0">
                            @if ($d)
                                <h5 class="border-bottom pb-2 mb-3">Federal &amp; W-2</h5>
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-2"><span class="text-muted">Filing status</span><br><strong>{{ str_replace('_', ' ', $d->fed_filing_status ?? '—') }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted"># of allowances</span><br><strong>{{ $d->fed_allowances ?? '—' }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Pay frequency</span><br><strong>{{ $d->pay_frequency ?? '—' }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Additional fed. withholding</span><br><strong>{{ $d->additional_fed_withholding }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Use new W-4 (2020+)</span><br><strong>{{ $yn($d->use_new_w4_2020) }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">W-4 Step 2 (two jobs)</span><br><strong>{{ $yn($d->w4_step2_two_jobs) }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">W-4 Step 3 dependents $</span><br><strong>{{ $d->w4_step3_dependents }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">W-4 Step 4a other income $</span><br><strong>{{ $d->w4_step4a_other_income }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">W-4 Step 4b deductions $</span><br><strong>{{ $d->w4_step4b_deductions }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Statutory employee</span><br><strong>{{ $yn($d->w2_statutory_employee) }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Retirement plan</span><br><strong>{{ $yn($d->w2_retirement_plan) }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Advance EIC</span><br><strong>{{ $yn($d->w2_advance_eic) }}</strong></div>
                                </div>

                                <h5 class="border-bottom pb-2 mb-3">Taxes set to zero on check</h5>
                                <div class="row mb-4">
                                    <div class="col-md-3 mb-2"><span class="text-muted">Federal income</span><br><strong>{{ $yn($d->tax_zero_federal_income) }}</strong></div>
                                    <div class="col-md-3 mb-2"><span class="text-muted">State income</span><br><strong>{{ $yn($d->tax_zero_state_income) }}</strong></div>
                                    <div class="col-md-3 mb-2"><span class="text-muted">SS &amp; Med. (employee)</span><br><strong>{{ $yn($d->tax_zero_ss_med_employee) }}</strong></div>
                                    <div class="col-md-3 mb-2"><span class="text-muted">SS &amp; Med. (employer)</span><br><strong>{{ $yn($d->tax_zero_ss_med_employer) }}</strong></div>
                                </div>

                                <h5 class="border-bottom pb-2 mb-3">State withholding</h5>
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-2"><span class="text-muted">Withholding state</span><br><strong>{{ $d->withholdingState?->name ?? '—' }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Additional state withholding</span><br><strong>{{ $d->additional_state_withholding }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">State filing status</span><br><strong>{{ str_replace('_', ' ', $d->state_filing_status ?? '—') }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Personal allowances</span><br><strong>{{ $d->state_personal_allowances }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Dependent allowances</span><br><strong>{{ $d->state_dependent_allowances }}</strong></div>
                                </div>
                            @else
                                <p class="text-muted mb-0">No payroll detail record for this employee.</p>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="employee-show-income" role="tabpanel" aria-labelledby="employee-show-income-tab" tabindex="0">
                            <h5 class="border-bottom pb-2 mb-3">Income categories</h5>
                            @if ($employee->incomeCategories->isEmpty())
                                <p class="text-muted">None selected.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Income type</th>
                                                <th>Category</th>
                                                <th class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($employee->incomeCategories as $row)
                                                <tr>
                                                    <td>{{ $row->incomeCategory?->incomeType?->title ?? '—' }}</td>
                                                    <td>{{ $row->incomeCategory?->title ?? '—' }}</td>
                                                    <td class="text-end">{{ number_format((float) $row->amount, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="employee-show-taxes" role="tabpanel" aria-labelledby="employee-show-taxes-tab" tabindex="0">
                            <!-- show the tax setup if found -->
                            @if ($d)
                                <h5 class="border-bottom pb-2 mb-3">Tax setup</h5>
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-2"><span class="text-muted">Federal income</span><br><strong>{{ $yn($d->tax_zero_federal_income) }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">State income</span><br><strong>{{ $yn($d->tax_zero_state_income) }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">SS &amp; Med. (employee)</span><br><strong>{{ $yn($d->tax_zero_ss_med_employee) }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">SS &amp; Med. (employer)</span><br><strong>{{ $yn($d->tax_zero_ss_med_employer) }}</strong></div>
                                </div>
                            @endif
                            <!-- <p class="text-muted mb-0">Tax category checkbox selections from the employee form are not stored in the database yet. Federal and state withholding shown under Tax Setup reflects saved detail.</p> -->
                        </div>

                        <div class="tab-pane fade" id="employee-show-deductions" role="tabpanel" aria-labelledby="employee-show-deductions-tab" tabindex="0">
                            
                            <!-- <p class="text-muted mb-0">Deduction category selections from the employee form are not stored in the database yet.</p> -->
                        </div>

                        <div class="tab-pane fade" id="employee-show-direct-deposit" role="tabpanel" aria-labelledby="employee-show-direct-deposit-tab" tabindex="0">
                            @if ($d)
                                <h5 class="border-bottom pb-2 mb-3">Direct deposit</h5>
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-2"><span class="text-muted">Include in direct deposit</span><br><strong>{{ $yn($d->include_in_direct_deposit) }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Account type</span><br><strong>{{ $d->account_type ? ucfirst($d->account_type) : '—' }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Routing number</span><br><strong>{{ $d->bank_routing_number ?: '—' }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Account number</span><br><strong>{{ $d->account_number ? str_repeat('•', max(0, strlen($d->account_number) - 4)) . substr($d->account_number, -4) : '—' }}</strong></div>
                                </div>
                            @else
                                <p class="text-muted mb-0">No payroll detail record for this employee.</p>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="employee-show-vacation" role="tabpanel" aria-labelledby="employee-show-vacation-tab" tabindex="0">
                            @if ($d)
                                <h5 class="border-bottom pb-2 mb-3">Vacation / sick</h5>
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-2"><span class="text-muted">Calculation method</span><br><strong>{{ str_replace('_', ' ', $d->vacation_sick_calculation_method ?? '—') }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Vacation hours per unit</span><br><strong>{{ $d->vacation_hours_earned_per_unit }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Max vacation hours / year</span><br><strong>{{ $d->max_vacation_hours_per_year ?? '—' }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Sick hours per unit</span><br><strong>{{ $d->sick_hours_earned_per_unit }}</strong></div>
                                    <div class="col-md-4 mb-2"><span class="text-muted">Max sick hours / year</span><br><strong>{{ $d->max_sick_hours_per_year ?? '—' }}</strong></div>
                                </div>
                            @else
                                <p class="text-muted mb-0">No payroll detail record for this employee.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
