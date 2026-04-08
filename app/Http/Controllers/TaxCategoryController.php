<?php

namespace App\Http\Controllers;

use App\Models\IncomeType;
use App\Models\TaxCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxCategoryController extends Controller
{
    /**
     * @return array<string, string>
     */
    protected function calculationOptions(): array
    {
        return [
            'percentage' => 'Percentage',
            'fixed' => 'Fixed',
            'per_hour' => 'Per Hour',
            'per_piece' => 'Per Piece',
            'per_mile' => 'Per Mile',
            'percentage_of_tax' => 'Percentage of Tax',
        ];
    }

    public function index()
    {
        $taxCategories = TaxCategory::query()->with('incomeType')->orderBy('title')->get();
        $calculationOptions = $this->calculationOptions();

        return view('screens.admin.tax-categories.index', get_defined_vars());
    }

    public function create()
    {
        $incomeTypes = IncomeType::query()->orderBy('title')->get();
        $calculationOptions = $this->calculationOptions();
        $paidByOptions = [
            'employee' => 'Employee',
            'employer' => 'Employer',
        ];

        return view('screens.admin.tax-categories.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:255',
            'income_type_id' => 'required|exists:income_types,id',
            'calculation' => ['required', 'string', Rule::in(array_keys($this->calculationOptions()))],
            'quarterly_rate_q1' => 'nullable|numeric|min:0',
            'quarterly_rate_q2' => 'nullable|numeric|min:0',
            'quarterly_rate_q3' => 'nullable|numeric|min:0',
            'quarterly_rate_q4' => 'nullable|numeric|min:0',
            'wage_base' => 'nullable|numeric|min:0',
            'max_amount_per_check' => 'nullable|numeric|min:0',
            'paid_by' => 'required|string|in:employee,employer',
            'w2_box_12_code' => 'nullable|string|max:255',
            'w2_box_14_abbreviation' => 'nullable|string|max:255',
        ]);

        $taxCategory = TaxCategory::create([
            'title' => $validated['title'],
            'abbreviation' => $validated['abbreviation'] ?? null,
            'income_type_id' => $validated['income_type_id'],
            'calculation' => $validated['calculation'],
            'quarterly_rate_q1' => $validated['quarterly_rate_q1'] ?? null,
            'quarterly_rate_q2' => $validated['quarterly_rate_q2'] ?? null,
            'quarterly_rate_q3' => $validated['quarterly_rate_q3'] ?? null,
            'quarterly_rate_q4' => $validated['quarterly_rate_q4'] ?? null,
            'wage_base' => $validated['wage_base'] ?? null,
            'max_amount_per_check' => $validated['max_amount_per_check'] ?? null,
            'paid_by' => $validated['paid_by'],
            'w2_box_12_code' => $validated['w2_box_12_code'] ?? null,
            'w2_box_14_abbreviation' => $validated['w2_box_14_abbreviation'] ?? null,
            'use_box_19' => $request->boolean('use_box_19'),
            'inactive' => $request->boolean('inactive'),
            'created_by' => current_user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tax category created successfully',
            'data' => $taxCategory->load('incomeType'),
        ]);
    }

    public function edit(TaxCategory $taxCategory)
    {
        $incomeTypes = IncomeType::query()->orderBy('title')->get();
        $calculationOptions = $this->calculationOptions();
        $paidByOptions = [
            'employee' => 'Employee',
            'employer' => 'Employer',
        ];

        return view('screens.admin.tax-categories.edit', get_defined_vars());
    }

    public function update(Request $request, TaxCategory $taxCategory)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:255',
            'income_type_id' => 'required|exists:income_types,id',
            'calculation' => ['required', 'string', Rule::in(array_keys($this->calculationOptions()))],
            'quarterly_rate_q1' => 'nullable|numeric|min:0',
            'quarterly_rate_q2' => 'nullable|numeric|min:0',
            'quarterly_rate_q3' => 'nullable|numeric|min:0',
            'quarterly_rate_q4' => 'nullable|numeric|min:0',
            'wage_base' => 'nullable|numeric|min:0',
            'max_amount_per_check' => 'nullable|numeric|min:0',
            'paid_by' => 'required|string|in:employee,employer',
            'w2_box_12_code' => 'nullable|string|max:255',
            'w2_box_14_abbreviation' => 'nullable|string|max:255',
        ]);

        $taxCategory->update([
            'title' => $validated['title'],
            'abbreviation' => $validated['abbreviation'] ?? null,
            'income_type_id' => $validated['income_type_id'],
            'calculation' => $validated['calculation'],
            'quarterly_rate_q1' => $validated['quarterly_rate_q1'] ?? null,
            'quarterly_rate_q2' => $validated['quarterly_rate_q2'] ?? null,
            'quarterly_rate_q3' => $validated['quarterly_rate_q3'] ?? null,
            'quarterly_rate_q4' => $validated['quarterly_rate_q4'] ?? null,
            'wage_base' => $validated['wage_base'] ?? null,
            'max_amount_per_check' => $validated['max_amount_per_check'] ?? null,
            'paid_by' => $validated['paid_by'],
            'w2_box_12_code' => $validated['w2_box_12_code'] ?? null,
            'w2_box_14_abbreviation' => $validated['w2_box_14_abbreviation'] ?? null,
            'use_box_19' => $request->boolean('use_box_19'),
            'inactive' => $request->boolean('inactive'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tax category updated successfully',
            'data' => $taxCategory->fresh()->load('incomeType'),
        ]);
    }

    public function delete(TaxCategory $taxCategory)
    {
        $taxCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tax category deleted successfully',
        ]);
    }
}
