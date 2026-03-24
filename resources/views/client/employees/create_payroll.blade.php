@extends('layouts.client')

@php
    $regRate = (float) ($employee->regular_hourly_rate ?? 0);
    $otRate = (float) ($employee->overtime_hourly_rate ?? 0);
    $k401Percent = $employee->effective401kContributionPercent();
    $defaultInsurance = (float) ($employee->insurance_deduction ?? 0);
    $defaultOther = (float) ($employee->other_deductions ?? 0);
@endphp

@section('title', 'Register Payroll')
@section('page-title', 'Register Payroll')
@section('page-description', 'Add a payroll entry for this employee')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6"
        id="payroll-register-form-root"
        data-regular-rate="{{ $regRate }}"
        data-overtime-rate="{{ $otRate }}"
        data-k401-percent="{{ $k401Percent }}">
        <div class="mb-6 pb-4 border-b border-gray-200">
            <p class="text-sm text-gray-500">Employee</p>
            <p class="text-lg font-semibold text-gray-800">{{ $employee->name }}</p>
            <p class="text-sm text-gray-600">ID: {{ $employee->employee_id }}</p>
            <p class="text-sm text-gray-600 mt-1">401(k): <span class="font-medium">{{ number_format($k401Percent, 2) }}%</span> of gross (employee profile — percentage, not dollars). Deduction = gross × ({{ number_format($k401Percent, 2) }} ÷ 100).</p>
        </div>

        <form action="{{ route('client.employees.create-payroll.store', $employee) }}" method="POST" id="payroll-form">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pay date *</label>
                    <input type="date" name="pay_date" value="{{ old('pay_date') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 @error('pay_date') border-red-500 @enderror">
                    @error('pay_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pay period *</label>
                    <input type="text" name="pay_period" id="pay_period" value="{{ old('pay_period') }}" required placeholder="e.g. 7/5/16 - 7/18/16"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 @error('pay_period') border-red-500 @enderror">
                    @error('pay_period')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Regular hours *</label>
                    <input type="number" step="0.01" name="regular_hours" id="regular_hours" value="{{ old('regular_hours', 0) }}" required
                        class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vacation hours *</label>
                    <input type="number" step="0.01" name="vacation_hours" id="vacation_hours" value="{{ old('vacation_hours', 0) }}" required
                        class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sick hours *</label>
                    <input type="number" step="0.01" name="sick_hours" id="sick_hours" value="{{ old('sick_hours', 0) }}" required
                        class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Holiday hours *</label>
                    <input type="number" step="0.01" name="holidays_hours" id="holidays_hours" value="{{ old('holidays_hours', 0) }}" required
                        class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Personal hours *</label>
                    <input type="number" step="0.01" name="personal_hours" id="personal_hours" value="{{ old('personal_hours', 0) }}" required
                        class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overtime hours *</label>
                    <input type="number" step="0.01" name="overtime_hours" id="overtime_hours" value="{{ old('overtime_hours', 0) }}" required
                        class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <div class="md:col-span-2 p-4 rounded-lg border border-green-200 bg-green-50">
                    <p class="text-sm text-gray-600">Gross pay <span class="text-gray-500">(same logic as register: regular-rate × reg/vac/sick/holiday/personal hours + OT rate × OT hours)</span></p>
                    <p class="text-2xl font-bold text-green-800 mt-1" id="gross_pay_display">$0.00</p>
                </div>

                <div class="md:col-span-2 grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    <div class="space-y-4">
                        <p class="text-sm font-semibold text-gray-800">Withholdings &amp; deductions <span class="font-normal text-gray-500">(enter tax amounts; 401(k) is computed from gross)</span></p>
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-600">401(k) contribution</p>
                            <p class="text-lg font-mono font-semibold text-gray-900" id="k401_display">$0.00</p>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($k401Percent, 2) }}% × gross pay</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Federal tax *</label>
                            <input type="number" step="0.01" name="fed_tax" id="fed_tax" value="{{ old('fed_tax', 0) }}" required
                                class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State tax *</label>
                            <input type="number" step="0.01" name="state_tax" id="state_tax" value="{{ old('state_tax', 0) }}" required
                                class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Local tax *</label>
                            <input type="number" step="0.01" name="local_tax" id="local_tax" value="{{ old('local_tax', 0) }}" required
                                class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Social Security *</label>
                            <input type="number" step="0.01" name="social_security" id="social_security" value="{{ old('social_security', 0) }}" required
                                class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medicare *</label>
                            <input type="number" step="0.01" name="medi_care" id="medi_care" value="{{ old('medi_care', 0) }}" required
                                class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Insurance deduction *</label>
                            <input type="number" step="0.01" name="insurance_deduction" id="insurance_deduction" value="{{ old('insurance_deduction', $defaultInsurance) }}" required
                                class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                            <p class="text-xs text-gray-500 mt-1">Default from employee profile; adjust if needed</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Other deductions *</label>
                            <input type="number" step="0.01" name="other_deductions" id="other_deductions" value="{{ old('other_deductions', $defaultOther) }}" required
                                class="js-payroll-recalc w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                        <div class="px-4 py-3 bg-gray-100 border-b border-gray-200">
                            <span class="text-lg font-semibold text-gray-800">Deductions (register style)</span>
                        </div>
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 bg-white">
                                    <th class="text-left py-2 px-4 font-medium text-gray-600">Type</th>
                                    <th class="text-right py-2 px-4 font-medium text-gray-600">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <tr><td class="py-2 px-4 text-gray-700">401(k)</td><td class="py-2 px-4 text-right font-mono text-gray-900" id="tbl-k401">$0.00</td></tr>
                                <tr><td class="py-2 px-4 text-gray-700">Federal Tax</td><td class="py-2 px-4 text-right font-mono text-gray-900" id="tbl-fed">$0.00</td></tr>
                                <tr><td class="py-2 px-4 text-gray-700">State Tax</td><td class="py-2 px-4 text-right font-mono text-gray-900" id="tbl-state">$0.00</td></tr>
                                <tr><td class="py-2 px-4 text-gray-700">Local Tax</td><td class="py-2 px-4 text-right font-mono text-gray-900" id="tbl-local">$0.00</td></tr>
                                <tr><td class="py-2 px-4 text-gray-700">Social Security</td><td class="py-2 px-4 text-right font-mono text-gray-900" id="tbl-ss">$0.00</td></tr>
                                <tr><td class="py-2 px-4 text-gray-700">Medicare</td><td class="py-2 px-4 text-right font-mono text-gray-900" id="tbl-medi">$0.00</td></tr>
                                <tr><td class="py-2 px-4 text-gray-700">Insurance</td><td class="py-2 px-4 text-right font-mono text-gray-900" id="tbl-ins">$0.00</td></tr>
                                <tr><td class="py-2 px-4 text-gray-700">Other</td><td class="py-2 px-4 text-right font-mono text-gray-900" id="tbl-other">$0.00</td></tr>
                            </tbody>
                            <tfoot class="bg-amber-50 border-t-2 border-amber-200">
                                <tr>
                                    <td class="py-2 px-4 font-semibold text-gray-800">Total deductions</td>
                                    <td class="py-2 px-4 text-right font-mono font-semibold text-amber-900" id="tbl-total-ded">$0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="md:col-span-2 p-4 rounded-lg border-2 border-green-600 bg-green-50">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Net pay <span class="text-gray-500 font-normal">(Gross − total deductions)</span></label>
                    <p class="text-3xl font-bold text-green-800" id="net_pay_display">$0.00</p>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-4">
                <a href="{{ route('client.employees.show', $employee) }}"
                    class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-6 py-2 btn-gradient text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition">
                    <i class="fas fa-save mr-2"></i>Save payroll
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    var root = document.getElementById('payroll-register-form-root');
    if (!root) return;

    function money(n) {
        var v = parseFloat(n);
        if (isNaN(v)) return '$0.00';
        return '$' + v.toFixed(2);
    }

    function parseId(id) {
        var el = document.getElementById(id);
        if (!el) return 0;
        var v = parseFloat(el.value);
        return isNaN(v) ? 0 : v;
    }

    var regularRate = parseFloat(root.dataset.regularRate) || 0;
    var overtimeRate = parseFloat(root.dataset.overtimeRate) || 0;
    var k401Percent = parseFloat(root.dataset.k401Percent) || 0;

    function recalc() {
        var hoursRegular =
            parseId('regular_hours') + parseId('vacation_hours') + parseId('sick_hours') +
            parseId('holidays_hours') + parseId('personal_hours');
        var ot = parseId('overtime_hours');
        var gross = Math.round((hoursRegular * regularRate + ot * overtimeRate) * 100) / 100;
        var k401 = Math.round(gross * (k401Percent / 100) * 100) / 100;

        var fed = parseId('fed_tax');
        var state = parseId('state_tax');
        var local = parseId('local_tax');
        var ss = parseId('social_security');
        var medi = parseId('medi_care');
        var ins = parseId('insurance_deduction');
        var other = parseId('other_deductions');

        var totalDed = k401 + fed + state + local + ss + medi + ins + other;
        totalDed = Math.round(totalDed * 100) / 100;
        var net = Math.round((gross - totalDed) * 100) / 100;

        var gp = document.getElementById('gross_pay_display');
        if (gp) gp.textContent = money(gross);

        var k401d = document.getElementById('k401_display');
        if (k401d) k401d.textContent = money(k401);

        var map = [
            ['tbl-k401', k401], ['tbl-fed', fed], ['tbl-state', state], ['tbl-local', local],
            ['tbl-ss', ss], ['tbl-medi', medi], ['tbl-ins', ins], ['tbl-other', other]
        ];
        map.forEach(function (row) {
            var el = document.getElementById(row[0]);
            if (el) el.textContent = money(row[1]);
        });

        var tt = document.getElementById('tbl-total-ded');
        if (tt) tt.textContent = money(totalDed);

        var nd = document.getElementById('net_pay_display');
        if (nd) {
            nd.textContent = money(net);
            nd.classList.toggle('text-red-700', net < 0);
            nd.classList.toggle('text-green-800', net >= 0);
        }
    }

    document.querySelectorAll('.js-payroll-recalc').forEach(function (el) {
        el.addEventListener('input', recalc);
        el.addEventListener('change', recalc);
    });
    recalc();
})();
</script>
@endpush
