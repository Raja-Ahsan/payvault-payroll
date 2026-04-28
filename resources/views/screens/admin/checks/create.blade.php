@section('title', 'Check Details')
@extends('layouts.admin.master')
@section('content')
    @php
        $defaultCheckDate = now()->format('Y-m-d');
    @endphp
    <form id="payroll-check-form" method="post" action="#" onsubmit="return false;"
        data-default-pay-date="{{ $defaultCheckDate }}"
        data-default-period-begin="{{ $defaultCheckDate }}"
        data-default-period-end="{{ $defaultCheckDate }}">
        @csrf
        <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card height-equal mb-3">
                    <div class="card-header card-no-border d-flex justify-content-end justify-content-end">
                        <button type="button" class="btn btn-primary f-w-500" id="check-recalculate">Recalculate</button>
                    </div>
                    <div class="card-body pt-2">
                        <div id="check-calc-warnings" class="alert alert-warning py-2 px-3 small d-none mb-3" role="alert"></div>
                        <div class="row g-3 align-items-end">
                            <div class="col-xl-3 col-md-6">
                                <div class="form-group mb-0">
                                    <label class="form-label">Pay frequency</label>
                                    <input type="text" class="form-control" name="pay_frequency_label" id="pay_frequency_label"
                                        value="" readonly>
                                    <input type="hidden" name="pay_frequency" id="pay_frequency" value="">
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="form-group mb-0">
                                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                                        <label class="form-label mb-0" for="employee_id">Employee</label>
                                        <!-- <button type="button" class="btn btn-link btn-sm py-0 px-0 small" id="check-refresh-employees" title="Reload employee list from server">Refresh list</button> -->
                                    </div>
                                    <select class="form-control" name="employee_id" id="employee_id">
                                        <option value="">Select employee</option>
                                        @foreach ($employees ?? [] as $emp)
                                            @php
                                                $ln = trim((string) $emp->last_name);
                                                $fn = trim((string) $emp->first_name);
                                                $mn = trim((string) ($emp->middle_name ?? ''));
                                                $label = $ln !== '' && ($fn !== '' || $mn !== '')
                                                    ? $ln . ', ' . trim($fn . ' ' . $mn)
                                                    : ($ln !== '' ? $ln : trim($fn . ' ' . $mn));
                                            @endphp
                                            <option value="{{ $emp->id }}">{{ $label !== '' ? $label : 'Employee #' . $emp->id }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-1 col-md-3 col-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="check_number">Check #</label>
                                    <input type="text" class="form-control" name="check_number" id="check_number" value="">
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-3 col-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="pay_date">Pay date</label>
                                    <input type="date" class="form-control" name="pay_date" id="pay_date"
                                        value="{{ $defaultCheckDate }}">
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-3 col-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="period_begin_date" title="First day of the pay period this check covers (same as period start).">Begin date</label>
                                    <input type="date" class="form-control" name="period_begin_date" id="period_begin_date" value="{{ $defaultCheckDate }}">
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-3 col-6">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="period_end_date">End date</label>
                                    <input type="date" class="form-control" name="period_end_date" id="period_end_date" value="{{ $defaultCheckDate }}">
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
                                <div class="table-responsive custom-scrollbar" id="check-income-details">
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
                                                        name="income[regular_hourly][rate]" value=""></td>
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
                                                        name="income[overtime_hourly][rate]" value=""></td>
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
                                                        name="deductions[401k_employee][amount]" value=""></td>
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
                                                        name="summary[this_check][total_incomes]" value="" readonly></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[this_check][total_taxes]" value="" readonly></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[this_check][total_deductions]" value="" readonly></td>
                                                <td><input type="number" step="0.01" class="form-control form-control-sm"
                                                        name="summary[this_check][net_pay]" value="" readonly></td>
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
                    <button type="button" class="btn btn-primary f-w-500" id="check-save-ok">OK</button>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
    <script>
        (function () {
            const INCOME_SLOTS = ['regular_hourly', 'overtime_hourly'];
            const scaffoldUrl = @json($checkScaffoldUrl ?? '');
            const recalculateUrl = @json($checkRecalculateUrl ?? '');
            const storeUrl = @json($checkStoreUrl ?? '');
            const employeesListUrl = @json($employeesListUrl ?? '');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            let incomeRecalcTimer = null;

            function scheduleRecalcFromIncome() {
                const emp = document.getElementById('employee_id');
                if (!emp || !emp.value || !recalculateUrl) return;
                if (incomeRecalcTimer) clearTimeout(incomeRecalcTimer);
                incomeRecalcTimer = setTimeout(function () {
                    incomeRecalcTimer = null;
                    recalculateFetch({ skipDateWarnings: true });
                }, 350);
            }

            function setByName(name, value) {
                const el = document.getElementsByName(name)[0];
                if (!el) return;
                el.value = value === null || value === undefined ? '' : String(value);
            }

            function clearEditableCheckFields() {
                document.querySelectorAll('[name^="taxes["]').forEach(function (el) {
                    if (!el.readOnly) el.value = '';
                });
                document.querySelectorAll('[name^="deductions["]').forEach(function (el) {
                    if (!el.readOnly) el.value = '';
                });
                document.querySelectorAll('[name^="summary["]').forEach(function (el) {
                    el.value = '';
                });
                document.querySelectorAll('[name^="obb["]').forEach(function (el) {
                    el.value = '';
                });
                document.querySelectorAll('[name^="other["]').forEach(function (el) {
                    if (!el.readOnly) el.value = '';
                });
                document.querySelectorAll('[name^="leave["]').forEach(function (el) {
                    if (!el.readOnly) el.value = '';
                });
                INCOME_SLOTS.forEach(function (slot) {
                    setByName('income[' + slot + '][rate]', '');
                    setByName('income[' + slot + '][quantity]', '');
                    setByName('income[' + slot + '][amount]', '');
                    setByName('income[' + slot + '][ytd]', '');
                    const sel = document.getElementsByName('income[' + slot + '][pay_type]')[0];
                    if (sel) sel.value = 'per_hour';
                });
                const memo = document.getElementById('check_memo');
                if (memo) memo.value = '';
            }

            function applyIncomeSlot(slot, row) {
                if (!row) return;
                setByName('income[' + slot + '][rate]', row.rate);
                const sel = document.getElementsByName('income[' + slot + '][pay_type]')[0];
                if (sel && row.pay_type) sel.value = row.pay_type;
                setByName('income[' + slot + '][quantity]', row.quantity);
                setByName('income[' + slot + '][amount]', row.amount);
                setByName('income[' + slot + '][ytd]', row.ytd);
            }

            function hideWarnings() {
                const w = document.getElementById('check-calc-warnings');
                if (!w) return;
                w.classList.add('d-none');
                w.textContent = '';
            }

            function showWarnings(codes) {
                const w = document.getElementById('check-calc-warnings');
                if (!w) return;
                if (!codes || !codes.length) {
                    w.classList.add('d-none');
                    w.textContent = '';
                    return;
                }
                const map = {
                    federal_income_tax_not_computed: 'Federal income tax is not auto-calculated yet (only extra withholding from the employee profile is applied).',
                    state_income_tax_not_computed: 'State income tax is not auto-calculated yet (only extra state withholding from the employee profile is applied).',
                };
                w.textContent = codes.map(function (c) {
                    return map[c] || c;
                }).join(' ');
                w.classList.remove('d-none');
            }

            function applyCalculationResponse(data) {
                INCOME_SLOTS.forEach(function (slot) {
                    const row = (data.income || {})[slot];
                    if (!row) return;
                    setByName('income[' + slot + '][rate]', row.rate);
                    const sel = document.getElementsByName('income[' + slot + '][pay_type]')[0];
                    if (sel && row.pay_type) sel.value = row.pay_type;
                    setByName('income[' + slot + '][quantity]', row.quantity);
                    setByName('income[' + slot + '][amount]', row.amount);
                    setByName('income[' + slot + '][ytd]', row.ytd);
                });
                const te = (data.taxes || {}).employee || {};
                const keys = [
                    ['federal_income', 'taxes[employee][federal_income]'],
                    ['social_security', 'taxes[employee][social_security]'],
                    ['medicare', 'taxes[employee][medicare]'],
                    ['state_income', 'taxes[employee][state_income]'],
                    ['local_income', 'taxes[employee][local_income]'],
                    ['state_disability', 'taxes[employee][state_disability]'],
                ];
                keys.forEach(function (pair) {
                    const row = te[pair[0]] || {};
                    setByName(pair[1] + '[amount]', row.amount);
                    setByName(pair[1] + '[ytd]', row.ytd);
                });
                const tr = (data.taxes || {}).employer || {};
                const keysE = [
                    ['social_security', 'taxes[employer][social_security]'],
                    ['medicare', 'taxes[employer][medicare]'],
                    ['federal_unemployment', 'taxes[employer][federal_unemployment]'],
                    ['state_unemployment', 'taxes[employer][state_unemployment]'],
                    ['state_disability', 'taxes[employer][state_disability]'],
                ];
                keysE.forEach(function (pair) {
                    const row = tr[pair[0]] || {};
                    setByName(pair[1] + '[amount]', row.amount);
                    setByName(pair[1] + '[ytd]', row.ytd);
                });
                const d401 = (data.deductions || {})['401k_employee'] || {};
                setByName('deductions[401k_employee][amount]', d401.amount);
                setByName('deductions[401k_employee][ytd]', d401.ytd);
                const sc = (data.summary || {}).this_check || {};
                const sy = (data.summary || {}).ytd || {};
                setByName('summary[this_check][total_incomes]', sc.total_incomes);
                setByName('summary[this_check][total_taxes]', sc.total_taxes);
                setByName('summary[this_check][total_deductions]', sc.total_deductions);
                setByName('summary[this_check][net_pay]', sc.net_pay);
                setByName('summary[ytd][total_incomes]', sy.total_incomes);
                setByName('summary[ytd][total_taxes]', sy.total_taxes);
                setByName('summary[ytd][total_deductions]', sy.total_deductions);
                setByName('summary[ytd][net_pay]', sy.net_pay);
                const leave = data.leave || {};
                const leaveKeys = [
                    'vacation_hours_earned',
                    'vacation_hours_used',
                    'sick_hours_earned',
                    'sick_hours_used',
                ];
                leaveKeys.forEach(function (k) {
                    const row = leave[k] || {};
                    setByName('leave[' + k + '][amount]', row.amount);
                    setByName('leave[' + k + '][ytd]', row.ytd);
                });
                showWarnings(data.warnings || []);
            }

            function runPayrollCheckDatePreflight() {
                const form = document.getElementById('payroll-check-form');
                if (!form || typeof window.reusableConfirm !== 'function') {
                    return Promise.resolve(true);
                }
                const payEl = document.getElementById('pay_date');
                const beginEl = document.getElementById('period_begin_date');
                const endEl = document.getElementById('period_end_date');
                const pay = payEl ? payEl.value : '';
                const begin = beginEl ? beginEl.value : '';
                const end = endEl ? endEl.value : '';
                const defPay = form.getAttribute('data-default-pay-date') || '';
                const defBegin = form.getAttribute('data-default-period-begin') || '';
                const defEnd = form.getAttribute('data-default-period-end') || '';
                const identicalPeriod = begin !== '' && end !== '' && begin === end;
                const datesStillDefault = defPay !== '' && pay === defPay && begin === defBegin && end === defEnd;

                if (identicalPeriod && datesStillDefault) {
                    return window.reusableConfirm({
                        title: 'Attention',
                        html: '<p class="text-start mb-2"><strong>Begin date</strong> and <strong>end date</strong> are the same. Please confirm this pay period is what you intended.</p>'
                            + '<p class="text-start mb-2">Also, <strong>pay date, begin date, and end date</strong> are still the defaults (today). Leaving default dates can cause checks to appear out of sequential order and may affect tax and deduction calculations for wage bases and limits on this check or on later checks.</p>'
                            + '<p class="text-start mb-0"><strong>Are you sure you want to continue?</strong></p>',
                        confirmText: 'Yes',
                        cancelText: 'No',
                    });
                }
                if (identicalPeriod) {
                    return window.reusableConfirm({
                        title: 'Warning',
                        icon: 'warning',
                        html: '<p class="text-start mb-0">Begin date and end date are identical. Are you sure you want to continue?</p>',
                        confirmText: 'Yes',
                        cancelText: 'No',
                    });
                }
                if (datesStillDefault) {
                    return window.reusableConfirm({
                        title: 'Attention',
                        html: '<p class="text-start mb-2">The <strong>pay date</strong> is still the default (today), together with the default period dates. This can cause checks to appear out of sequential order and may affect tax and deduction calculations for wage bases and cutoffs on this check or on subsequent checks.</p>'
                            + '<p class="text-start mb-0"><strong>Are you sure you want to continue?</strong></p>',
                        confirmText: 'Yes',
                        cancelText: 'No',
                    });
                }
                return Promise.resolve(true);
            }

            function doRecalculateFetch() {
                const form = document.getElementById('payroll-check-form');
                if (!form || !recalculateUrl) return;
                const fd = new FormData(form);
                fetch(recalculateUrl, {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                }).then(function (r) {
                    if (!r.ok) throw new Error('recalc');
                    return r.json();
                }).then(applyCalculationResponse).catch(function () {
                    alert('Recalculate failed. Check employee, pay date, and pay frequency.');
                });
            }

            function recalculateFetch(options) {
                const opts = options || {};
                if (opts.skipDateWarnings === true) {
                    doRecalculateFetch();
                    return;
                }
                runPayrollCheckDatePreflight().then(function (ok) {
                    if (ok) {
                        doRecalculateFetch();
                    }
                });
            }

            (function bindCheckFormSilentRecalc() {
                const formEl = document.getElementById('payroll-check-form');
                if (!formEl) return;

                function shouldTriggerSilentRecalc(el) {
                    if (!el || !el.name || el.readOnly) return false;
                    const n = el.name;
                    if (n.indexOf('income[') === 0) return true;
                    if (n.indexOf('taxes[') === 0 && n.indexOf('][amount]') !== -1) return true;
                    if (n.indexOf('deductions[') === 0 && n.indexOf('][amount]') !== -1) return true;
                    if (n.indexOf('leave[') === 0 && n.indexOf('][amount]') !== -1) return true;
                    if (n === 'pay_date' || n === 'period_begin_date' || n === 'period_end_date' || n === 'check_number') {
                        return true;
                    }
                    return false;
                }

                formEl.addEventListener('input', function (e) {
                    if (shouldTriggerSilentRecalc(e.target)) {
                        scheduleRecalcFromIncome();
                    }
                });
                formEl.addEventListener('change', function (e) {
                    const el = e.target;
                    if (!el || !el.name) return;
                    if (el.tagName === 'SELECT' && el.name.indexOf('income[') === 0) {
                        scheduleRecalcFromIncome();
                        return;
                    }
                    if (shouldTriggerSilentRecalc(el)) {
                        scheduleRecalcFromIncome();
                    }
                });
            })();

            function applyScaffold(data) {
                hideWarnings();
                clearEditableCheckFields();
                window.__checkWithholding = data.withholding || null;
                window.__checkExtraIncomes = data.extra_income_categories || [];

                setByName('pay_frequency', data.pay_frequency || '');
                const label = document.getElementById('pay_frequency_label');
                if (label) label.value = data.pay_frequency_label || '';

                if (data.next_check_number) {
                    setByName('check_number', data.next_check_number);
                }

                const inc = data.income || {};
                applyIncomeSlot('regular_hourly', inc.regular_hourly);
                applyIncomeSlot('overtime_hourly', inc.overtime_hourly);

                const lp = data.leave_policy || {};
                if (Object.keys(lp).length) {
                    setByName('leave[vacation_hours_earned][amount]', lp.vacation_hours_earned_per_unit || '');
                    setByName('leave[sick_hours_earned][amount]', lp.sick_hours_earned_per_unit || '');
                }
                recalculateFetch({ skipDateWarnings: true });
            }

            const employeeSelect = document.getElementById('employee_id');
            if (!employeeSelect) return;

            function loadEmployeeOptions() {
                if (!employeesListUrl) return Promise.resolve();
                const prev = employeeSelect.value;
                return fetch(employeesListUrl, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                }).then(function (r) {
                    if (!r.ok) throw new Error('employees list');
                    return r.json();
                }).then(function (data) {
                    const list = data.employees || [];
                    while (employeeSelect.options.length > 1) {
                        employeeSelect.remove(1);
                    }
                    list.forEach(function (row) {
                        const opt = document.createElement('option');
                        opt.value = String(row.id);
                        opt.textContent = row.label;
                        employeeSelect.appendChild(opt);
                    });
                    const stillThere = prev && Array.prototype.some.call(employeeSelect.options, function (o) {
                        return o.value === prev;
                    });
                    employeeSelect.value = stillThere ? prev : '';
                    if (prev && !stillThere) {
                        employeeSelect.dispatchEvent(new Event('change'));
                    }
                }).catch(function () { /* keep server-rendered options */ });
            }

            loadEmployeeOptions();

            const refreshEmpBtn = document.getElementById('check-refresh-employees');
            if (refreshEmpBtn) {
                refreshEmpBtn.addEventListener('click', function () {
                    loadEmployeeOptions();
                });
            }

            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible' && employeesListUrl) {
                    loadEmployeeOptions();
                }
            });

            if (!scaffoldUrl) return;

            employeeSelect.addEventListener('change', function () {
                const id = employeeSelect.value;
                if (!id) {
                    clearEditableCheckFields();
                    hideWarnings();
                    setByName('pay_frequency', '');
                    const label = document.getElementById('pay_frequency_label');
                    if (label) label.value = '';
                    window.__checkWithholding = null;
                    window.__checkExtraIncomes = [];
                    INCOME_SLOTS.forEach(function (slot) {
                        setByName('income[' + slot + '][rate]', '');
                        const sel = document.getElementsByName('income[' + slot + '][pay_type]')[0];
                        if (sel) sel.value = 'per_hour';
                    });
                    return;
                }

                const url = scaffoldUrl + (scaffoldUrl.indexOf('?') >= 0 ? '&' : '?') + 'employee_id=' + encodeURIComponent(id);
                fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                }).then(function (r) {
                    if (!r.ok) throw new Error('Request failed');
                    return r.json();
                }).then(applyScaffold).catch(function () {
                    alert('Could not load employee check data.');
                });
            });

            const recalcBtn = document.getElementById('check-recalculate');
            if (recalcBtn) {
                recalcBtn.addEventListener('click', function () {
                    hideWarnings();
                    recalculateFetch({ skipDateWarnings: false });
                });
            }

            const saveBtn = document.getElementById('check-save-ok');
            if (saveBtn && storeUrl) {
                saveBtn.addEventListener('click', function () {
                    const form = document.getElementById('payroll-check-form');
                    const emp = document.getElementById('employee_id');
                    if (!form || !emp || !emp.value) {
                        alert('Select an employee first.');
                        return;
                    }
                    runPayrollCheckDatePreflight().then(function (ok) {
                        if (!ok) return;
                        const fd = new FormData(form);
                        fetch(storeUrl, {
                            method: 'POST',
                            body: fd,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            credentials: 'same-origin',
                        }).then(function (r) {
                            if (!r.ok) return r.json().then(function (j) {
                                throw new Error(j.message || 'save');
                            });
                            return r.json();
                        }).then(function (j) {
                            if (j.redirect) window.location.href = j.redirect;
                        }).catch(function () {
                            alert('Could not save check.');
                        });
                    });
                });
            }
        })();
    </script>
@endpush
