@php
    $detail = $employee->detail;
@endphp
<div class="col-12 mb-4">
    <span class="d-block fw-medium mb-2">Method of Calculating Vacation and Sick Hours:</span>
    <div class="form-check">
        <input class="form-check-input mt-0" type="radio" name="vacation_sick_calculation_method" id="vacation_sick_method_per_check" value="per_check" @checked(old('vacation_sick_calculation_method', $detail?->vacation_sick_calculation_method ?? 'per_check') === 'per_check')>
        <label class="form-check-label" for="vacation_sick_method_per_check">Per Check</label>
    </div>
    <div class="form-check">
        <input class="form-check-input mt-0" type="radio" name="vacation_sick_calculation_method" id="vacation_sick_method_per_total_hours" value="per_total_hours" @checked(old('vacation_sick_calculation_method', $detail?->vacation_sick_calculation_method ?? 'per_check') === 'per_total_hours')>
        <label class="form-check-label" for="vacation_sick_method_per_total_hours">Per Total Hours on Check</label>
    </div>
</div>

<div class="col-12 mb-3 d-flex flex-wrap align-items-center gap-3">
    <label class="mb-0 flex-shrink-0" for="vacation_hours_earned_per_unit" style="min-width: 280px;">
        Vacation Hours Earned <span id="js-vacation-earned-method-label">Per Check</span>
    </label>
    <input type="number"
        name="vacation_hours_earned_per_unit"
        id="vacation_hours_earned_per_unit"
        class="form-control"
        min="0"
        step="0.01"
        value="{{ old('vacation_hours_earned_per_unit', $detail?->vacation_hours_earned_per_unit !== null ? number_format((float) $detail->vacation_hours_earned_per_unit, 2, '.', '') : '0.00') }}"
        style="max-width: 140px;">
</div>

<div class="col-12 mb-3 d-flex flex-wrap align-items-start gap-3">
    <label class="mb-0 flex-shrink-0 pt-1" for="max_vacation_hours_per_year" style="min-width: 280px;">Maximum Vacation Hours Earned Per Year</label>
    <div class="d-flex flex-wrap align-items-center gap-3 flex-grow-1">
        <input type="number"
            name="max_vacation_hours_per_year"
            id="max_vacation_hours_per_year"
            class="form-control"
            min="0"
            step="0.01"
            value="{{ old('max_vacation_hours_per_year', $detail?->max_vacation_hours_per_year !== null ? number_format((float) $detail->max_vacation_hours_per_year, 2, '.', '') : '') }}"
            placeholder="0.00"
            style="max-width: 140px;">
        <small class="text-info mb-0 text-muted" style="max-width: 420px;">Keep blank for unlimited hours per year; fill with 0.00 for zero hours per year</small>
    </div>
</div>

<div class="col-12 mb-3 d-flex flex-wrap align-items-center gap-3">
    <label class="mb-0 flex-shrink-0" for="sick_hours_earned_per_unit" style="min-width: 280px;">
        Sick Hours Earned <span id="js-sick-earned-method-label">Per Check</span>
    </label>
    <input type="number"
        name="sick_hours_earned_per_unit"
        id="sick_hours_earned_per_unit"
        class="form-control"
        min="0"
        step="0.01"
        value="{{ old('sick_hours_earned_per_unit', $detail?->sick_hours_earned_per_unit !== null ? number_format((float) $detail->sick_hours_earned_per_unit, 2, '.', '') : '0.00') }}"
        style="max-width: 140px;">
</div>

<div class="col-12 mb-3 d-flex flex-wrap align-items-start gap-3">
    <label class="mb-0 flex-shrink-0 pt-1" for="max_sick_hours_per_year" style="min-width: 280px;">Maximum Sick Hours Earned Per Year</label>
    <div class="d-flex flex-wrap align-items-center gap-3 flex-grow-1">
        <input type="number"
            name="max_sick_hours_per_year"
            id="max_sick_hours_per_year"
            class="form-control"
            min="0"
            step="0.01"
            value="{{ old('max_sick_hours_per_year', $detail?->max_sick_hours_per_year !== null ? number_format((float) $detail->max_sick_hours_per_year, 2, '.', '') : '') }}"
            placeholder="0.00"
            style="max-width: 140px;">
        <small class="text-info mb-0 text-muted" style="max-width: 420px;">Keep blank for unlimited hours per year; fill with 0.00 for zero hours per year</small>
    </div>
</div>
