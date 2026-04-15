@php
    $savedIncomeIds = $employee->incomeCategories?->pluck('income_category_id')->map(fn ($id) => (string) $id)->all() ?? [];
@endphp
<div class="col-12" id="employee-wizard-income-step">
    <div class="justify-content-between mb-3">
        @foreach ($incomeCategoriesTypes as $incomeType)
            @foreach ($incomeType->categories as $category)
                @php
                    $incomeChecked = collect(old('income_category_id', $savedIncomeIds))->contains(fn ($v) => (int) $v === (int) $category->id);
                @endphp
                <div class="form-group d-flex mb-3 gap-2 toggle-input-wrapper">
                    <input type="checkbox"
                        name="income_category_id[]"
                        value="{{ $category->id }}"
                        class="form-check-input toggle-checkbox mt-0"
                        id="cat_{{ $category->id }}"
                        @checked($incomeChecked)>

                    <label class="form-check-label" for="cat_{{ $category->id }}">
                        {{ $category->title }}
                    </label>
                    <div class="d-flex flex-grow-1 justify-content-end align-items-center gap-2">
                        ({{ $incomeType->title }})
                        <input type="number" min="0" step="0.01" class="form-control toggle-input" name="income_amounts[{{ $category->id }}]" @disabled(! $incomeChecked) @if ($incomeChecked) required @endif value="{{ old('income_amounts.'.$category->id, $employee->incomeCategories?->firstWhere('income_category_id', $category->id)?->amount) }}" style="max-width: 250px;">
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
