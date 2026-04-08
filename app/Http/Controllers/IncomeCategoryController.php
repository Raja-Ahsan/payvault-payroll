<?php

namespace App\Http\Controllers;

use App\Models\DeductionCategory;
use App\Models\IncomeCategory;
use App\Models\IncomeType;
use App\Models\TaxCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeCategoryController extends Controller
{
    public function index()
    {
        $categories = IncomeCategory::with('incomeType')->get();
        $incomeTypes = IncomeType::all();
        return view('screens.admin.income-categories.index', get_defined_vars());
    }

    public function create()
    {
        $incomeTypes = IncomeType::query()->orderBy('title')->get();
        $taxCategories = TaxCategory::query()->orderBy('title')->get();
        $deductionCategories = DeductionCategory::query()->orderBy('title')->get();

        return view('screens.admin.income-categories.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:255',
            'income_type_id' => 'required|exists:income_types,id',
            'w2_box_12_code' => 'nullable|string|max:255',
            'w2_box_14_abbreviation' => 'nullable|string|max:255',
            'tax_category_ids' => 'nullable|array',
            'tax_category_ids.*' => 'integer|exists:tax_categories,id',
            'deduction_category_ids' => 'nullable|array',
            'deduction_category_ids.*' => 'integer|exists:deduction_categories,id',
        ]);

        $userId = current_user()->id;

        $incomeCategory = DB::transaction(function () use ($validated, $request, $userId) {
            $category = IncomeCategory::create([
                'title' => $validated['title'],
                'abbreviation' => $validated['abbreviation'] ?? null,
                'income_type_id' => $validated['income_type_id'],
                'w2_box_12_code' => $validated['w2_box_12_code'] ?? null,
                'w2_box_14_abbreviation' => $validated['w2_box_14_abbreviation'] ?? null,
                'reported_tips' => $request->boolean('reported_tips'),
                'omit_net_pay' => $request->boolean('omit_net_pay'),
                'inactive' => $request->boolean('inactive'),
                'created_by' => $userId,
            ]);

            $taxIds = array_values(array_unique(array_filter($validated['tax_category_ids'] ?? [])));
            $deductionIds = array_values(array_unique(array_filter($validated['deduction_category_ids'] ?? [])));

            $category->taxCategories()->sync($taxIds);
            $category->deductionCategories()->sync($deductionIds);

            return $category->load(['taxCategories', 'deductionCategories']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Income category created successfully',
            'data' => $incomeCategory,
        ]);
    }

    public function edit(IncomeCategory $incomeCategory)
    {
        $incomeCategory->load(['taxCategories', 'deductionCategories']);
        $incomeTypes = IncomeType::query()->orderBy('title')->get();
        $taxCategories = TaxCategory::query()->orderBy('title')->get();
        $deductionCategories = DeductionCategory::query()->orderBy('title')->get();

        return view('screens.admin.income-categories.edit', get_defined_vars());
    }

    public function update(Request $request, IncomeCategory $incomeCategory)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:255',
            'income_type_id' => 'required|exists:income_types,id',
            'w2_box_12_code' => 'nullable|string|max:255',
            'w2_box_14_abbreviation' => 'nullable|string|max:255',
            'tax_category_ids' => 'nullable|array',
            'tax_category_ids.*' => 'integer|exists:tax_categories,id',
            'deduction_category_ids' => 'nullable|array',
            'deduction_category_ids.*' => 'integer|exists:deduction_categories,id',
        ]);

        $incomeCategory = DB::transaction(function () use ($validated, $request, $incomeCategory) {
            $incomeCategory->update([
                'title' => $validated['title'],
                'abbreviation' => $validated['abbreviation'] ?? null,
                'income_type_id' => $validated['income_type_id'],
                'w2_box_12_code' => $validated['w2_box_12_code'] ?? null,
                'w2_box_14_abbreviation' => $validated['w2_box_14_abbreviation'] ?? null,
                'reported_tips' => $request->boolean('reported_tips'),
                'omit_net_pay' => $request->boolean('omit_net_pay'),
                'inactive' => $request->boolean('inactive'),
            ]);

            $taxIds = array_values(array_unique(array_filter($validated['tax_category_ids'] ?? [])));
            $deductionIds = array_values(array_unique(array_filter($validated['deduction_category_ids'] ?? [])));

            $incomeCategory->taxCategories()->sync($taxIds);
            $incomeCategory->deductionCategories()->sync($deductionIds);

            return $incomeCategory->load(['taxCategories', 'deductionCategories', 'incomeType']);
        });

        return response()->json([
            'success' => true,
            'message' => 'Income category updated successfully',
            'data' => $incomeCategory,
        ]);
    }

    public function delete(IncomeCategory $incomeCategory)
    {
        $incomeCategory->delete();
        return response()->json([
            'success' => true,
            'message' => 'Income category deleted successfully',
        ]);
    }
    
}
