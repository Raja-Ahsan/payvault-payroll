@php
    $oldInput = session()->get('_old_input', []);
    if (! count($oldInput)) {
        $selectedTaxCategoryIds = $taxCategories->pluck('id')->all();
    } elseif (array_key_exists('tax_category_id', $oldInput)) {
        $selectedTaxCategoryIds = (array) ($oldInput['tax_category_id'] ?? []);
    } else {
        $selectedTaxCategoryIds = [];
    }
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
