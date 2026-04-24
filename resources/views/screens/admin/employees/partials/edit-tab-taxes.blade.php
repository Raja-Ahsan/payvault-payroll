@php
    $savedTaxIds = $employee?->taxCategories?->pluck('id')->map(fn ($id) => (string) $id)->all() ?? [];
    $selectedTaxCategoryIds = (array) old('tax_category_id', $savedTaxIds);
@endphp
<div class="col-12">
    <div id="employee-wizard-tax-list" class="justify-content-between mb-3">
        @foreach ($taxCategories as $taxCategory)
            <div class="tax-category-wrapper mb-3">
                <input type="checkbox"
                    name="tax_category_id[]"
                    value="{{ $taxCategory->id }}"
                    class="form-check-input toggle-checkbox mt-0"
                    id="tax_category_{{ $taxCategory->id }}"
                    @checked(collect($selectedTaxCategoryIds)->contains(fn ($v) => (int) $v === (int) $taxCategory->id))>
                <label class="form-check-label" for="tax_category_{{ $taxCategory->id }}">{{ $taxCategory->title }}</label>
            </div>
        @endforeach
    </div>
    <p id="employee-wizard-tax-empty" class="d-none mb-0">There are no taxes.</p>
</div>
