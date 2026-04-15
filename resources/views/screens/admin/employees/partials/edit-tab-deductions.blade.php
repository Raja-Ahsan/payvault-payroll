<div class="col-12">
    <div id="employee-wizard-deduction-list" class="justify-content-between mb-3">
        @foreach ($deductionCategories as $deductionCategory)
            @php
                $deductionChecked = collect(old('deduction_category_id', []))->contains(fn ($v) => (int) $v === (int) $deductionCategory->id);
            @endphp
            <div class="deduction-category-wrapper mb-3 d-flex toggle-input-wrapper">
                <input type="checkbox"
                    name="deduction_category_id[]"
                    value="{{ $deductionCategory->id }}"
                    class="form-check-input toggle-checkbox mt-0"
                    id="deduction_category_{{ $deductionCategory->id }}"
                    @checked($deductionChecked)>
                <label for="deduction_category_{{ $deductionCategory->id }}">{{ $deductionCategory->title }}</label>
                <div class="d-flex flex-grow-1 justify-content-end align-items-center gap-2">
                    <label for="deduction_category_{{ $deductionCategory->id }}">{{ $deductionCategory->incomeType->title }}</label>
                    <input type="number" min="0" step="0.01" class="form-control toggle-input" name="deduction_amounts[{{ $deductionCategory->id }}]" @disabled(! $deductionChecked) @if ($deductionChecked) required @endif value="{{ old('deduction_amounts.'.$deductionCategory->id, '') }}" style="max-width: 250px;">
                </div>
            </div>
        @endforeach
    </div>
    <p id="employee-wizard-deduction-empty" class="d-none mb-0">There are no deductions.</p>
</div>
