@section('title', 'Check Details')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card height-equal mb-3">
                    <div class="card-header card-no-border d-flex flex-wrap align-items-end justify-content-between gap-3">
                        <h2 class="form-heading mb-0">Check Details</h2>
                        <button type="button" class="btn btn-primary f-w-500" id="check-recalculate">Recalculate</button>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 align-items-end">
                            <div class="col-xl-3 col-md-6">
                                <div class="form-group mb-0">
                                    <label class="form-label">Pay frequency</label>
                                    <input type="text" class="form-control" name="pay_frequency_label" id="pay_frequency_label"
                                        value="Bi-Weekly (26 Pay Periods)" readonly>
                                    <input type="hidden" name="pay_frequency" id="pay_frequency" value="biweekly_26">
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="employee_id">Employee</label>
                                    <select class="form-control" name="employee_id" id="employee_id">
                                        <option value="">Select employee</option>
                                        <option value="1">ad, arsalan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-1 col-md-3 col-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="check_number">Check #</label>
                                    <input type="text" class="form-control" name="check_number" id="check_number" value="1">
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-3 col-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="period_begin_date">Begin date</label>
                                    <input type="date" class="form-control" name="period_begin_date" id="period_begin_date"
                                        value="2026-04-23">
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-3 col-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="pay_date">Pay date</label>
                                    <input type="date" class="form-control" name="pay_date" id="pay_date" value="2026-04-09">
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-3 col-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="period_end_date">End date</label>
                                    <input type="date" class="form-control" name="period_end_date" id="period_end_date"
                                        value="2026-04-30">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-lg-7">
                        <div class="card height-equal h-100">
                            <div class="card-header card-no-border">
                                <h2 class="form-heading mb-0">Income details</h2>
                            </div>
                            <div class="card-body pt-0 px-0">
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th><span class="c-o-light f-w-600">Income</span></th>
                                                <th style="width: 110px;"><span class="c-o-light f-w-600">Rate</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">Type</span></th>
                                                <th style="width: 110px;"><span class="c-o-light f-w-600">Quantity</span></th>
                                                <th style="width: 110px;"><span class="c-o-light f-w-600">Amount</span></th>
                                                <th style="width: 110px;"><span class="c-o-light f-w-600">YTD</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Regular hourly pay</td>
                                                <td><input type="number" step="0.0001" class="form-control form-control-sm"
                                                        name="income[regular_hourly][rate]" value="20.0000"></td>
                                                <td>
                                                    <select class="form-control form-control-sm" name="income[regular_hourly][pay_type]">
                                                        <option value="per_hour" selected>Per hour</option>
                                                        <option value="per_year">Per year</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="income[regular_hourly][quantity]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="income[regular_hourly][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="income[regular_hourly][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Overtime hourly pay</td>
                                                <td><input type="number" step="0.0001" class="form-control form-control-sm"
                                                        name="income[overtime_hourly][rate]" value="25.0000"></td>
                                                <td>
                                                    <select class="form-control form-control-sm" name="income[overtime_hourly][pay_type]">
                                                        <option value="per_hour" selected>Per hour</option>
                                                        <option value="per_year">Per year</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="income[overtime_hourly][quantity]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="income[overtime_hourly][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="income[overtime_hourly][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Yearly salary</td>
                                                <td><input type="number" step="0.0001" class="form-control form-control-sm"
                                                        name="income[yearly_salary][rate]" value="20.0000"></td>
                                                <td>
                                                    <select class="form-control form-control-sm" name="income[yearly_salary][pay_type]">
                                                        <option value="per_hour">Per hour</option>
                                                        <option value="per_year" selected>Per year</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="income[yearly_salary][quantity]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="income[yearly_salary][amount]" value="0.77"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="income[yearly_salary][ytd]" value="" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card height-equal h-100">
                            <div class="card-header card-no-border">
                                <h2 class="form-heading mb-0">Vacation / sick hours</h2>
                            </div>
                            <div class="card-body pt-0 px-0">
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th><span class="c-o-light f-w-600">Description</span></th>
                                                <th style="width: 110px;"><span class="c-o-light f-w-600">Amount</span></th>
                                                <th style="width: 110px;"><span class="c-o-light f-w-600">YTD</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Vac. hours earned</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="leave[vacation_hours_earned][amount]" value="0.00"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="leave[vacation_hours_earned][ytd]" value="0.00" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Vac. hours used</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="leave[vacation_hours_used][amount]" value="0.00"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="leave[vacation_hours_used][ytd]" value="0.00" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Sick hours earned</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="leave[sick_hours_earned][amount]" value="0.00"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="leave[sick_hours_earned][ytd]" value="0.00" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Sick hours used</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="leave[sick_hours_used][amount]" value="0.00"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="leave[sick_hours_used][ytd]" value="0.00" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="px-3 pb-3">
                                    <a href="#" class="text-primary small">Learn how to rollover hours from last year</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-0">
                    <div class="col-lg-7">
                        <div class="card height-equal h-100">
                            <div class="card-header card-no-border">
                                <h2 class="form-heading mb-0">Tax details</h2>
                            </div>
                            <div class="card-body pt-0 px-0">
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th><span class="c-o-light f-w-600">Tax</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">Amount</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">YTD</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Federal income tax</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][federal_income][amount]" value="0.00"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][federal_income][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Social security (employee)</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][social_security][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][social_security][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Medicare (employee)</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][medicare][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][medicare][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>State income tax</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][state_income][amount]" value="0.00"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][state_income][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Local income tax</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][local_income][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][local_income][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>State disability insurance (employee)</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][state_disability][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employee][state_disability][ytd]" value="" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <p class="px-3 text-muted small mb-2 mt-3">Employer taxes</p>
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th><span class="c-o-light f-w-600">Tax</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">Amount</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">YTD</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Social security (employer)</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employer][social_security][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employer][social_security][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Medicare (employer)</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employer][medicare][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employer][medicare][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Fed unemployment (employer)</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employer][federal_unemployment][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employer][federal_unemployment][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>State unemployment (employer)</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employer][state_unemployment][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employer][state_unemployment][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>State disability insurance (employer)</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employer][state_disability][amount]" value=""></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="taxes[employer][state_disability][ytd]" value="" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card height-equal h-100">
                            <div class="card-header card-no-border">
                                <h2 class="form-heading mb-0">Deduction details</h2>
                            </div>
                            <div class="card-body pt-0 px-0">
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th><span class="c-o-light f-w-600">Deduction</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">Amount</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">YTD</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>401K (employee)</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="deductions[401k_employee][amount]" value="1.54"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="deductions[401k_employee][ytd]" value="" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card height-equal mt-3">
                    <div class="card-header card-no-border d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h2 class="form-heading mb-0">One Big Beautiful Bill Act tax deductions</h2>
                        <button type="button" class="btn btn-outline-primary btn-sm">Help me calculate…</button>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="obb_qualified_overtime_compensation">Qualified overtime
                                        compensation</label>
                                    <input type="number" step="0.01" class="form-control" name="obb[qualified_overtime_compensation]"
                                        id="obb_qualified_overtime_compensation" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="obb_qualified_tips">Qualified tips</label>
                                    <input type="number" step="0.01" class="form-control" name="obb[qualified_tips]"
                                        id="obb_qualified_tips" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-0">
                    <div class="col-lg-6">
                        <div class="card height-equal h-100">
                            <div class="card-header card-no-border">
                                <h2 class="form-heading mb-0">Other optional details</h2>
                            </div>
                            <div class="card-body pt-0 px-0">
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th><span class="c-o-light f-w-600">Description</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">Amount</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">YTD</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Hours worked</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="other[hours_worked][amount]" value="0.00"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="other[hours_worked][ytd]" value="" readonly></td>
                                            </tr>
                                            <tr>
                                                <td>Weeks worked</td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="other[weeks_worked][amount]" value="0.00"></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="other[weeks_worked][ytd]" value="" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card height-equal h-100">
                            <div class="card-header card-no-border">
                                <h2 class="form-heading mb-0">Check summary</h2>
                            </div>
                            <div class="card-body pt-0 px-0">
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th><span class="c-o-light f-w-600">&nbsp;</span></th>
                                                <th style="width: 130px;"><span class="c-o-light f-w-600">Total incomes</span></th>
                                                <th style="width: 130px;"><span class="c-o-light f-w-600">Total taxes</span></th>
                                                <th style="width: 140px;"><span class="c-o-light f-w-600">Total deductions</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">Net pay</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="f-w-600">This check</span></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[this_check][total_incomes]" value="0.77" readonly></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[this_check][total_taxes]" value="0.06" readonly></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[this_check][total_deductions]" value="1.54" readonly></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[this_check][net_pay]" value="-0.83" readonly></td>
                                            </tr>
                                            <tr>
                                                <td><span class="f-w-600">YTD</span></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[ytd][total_incomes]" value="" readonly></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[ytd][total_taxes]" value="" readonly></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[ytd][total_deductions]" value="" readonly></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[ytd][net_pay]" value="" readonly></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card height-equal mt-3">
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label class="form-label" for="check_memo">Memo</label>
                            <textarea class="form-control" name="check_memo" id="check_memo" rows="2"
                                placeholder="Optional note for this check"></textarea>
                        </div>
                    </div>
                </div>

                <div class="wizard-footer d-flex gap-2 justify-content-end mt-4 mb-4">
                    <a href="{{ route('checks.index') }}" class="btn btn-outline-secondary f-w-500 me-2">Cancel</a>
                    <button type="button" class="btn btn-primary f-w-500">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection
