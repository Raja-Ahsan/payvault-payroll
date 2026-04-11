<?php

namespace App\Http\Controllers;

use App\Models\DeductionCategory;
use App\Models\IncomeType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeductionCategoryController extends Controller
{
    /**
     * @return array<string, string>
     */

    public function index()
    {
        $deductionCategories = DeductionCategory::query()->with('incomeType')->orderBy('title')->get();

        return view('screens.admin.deduction-categories.index', get_defined_vars());
    }

    public function create()
    {
        $incomeTypes = IncomeType::query()->orderBy('title')->get();

        $paidByOptions = [
            'employee' => 'Employee',
            'employer' => 'Employer',
        ];

        return view('screens.admin.deduction-categories.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:255',
            'income_type_id' => 'required|exists:income_types,id',
            'quarterly_rate_q1' => 'nullable|numeric|min:0',
            'quarterly_rate_q2' => 'nullable|numeric|min:0',
            'quarterly_rate_q3' => 'nullable|numeric|min:0',
            'quarterly_rate_q4' => 'nullable|numeric|min:0',
            'cutoff' => 'nullable|numeric|min:0',
            'paid_by' => 'required|string|in:employee,employer',
            'w2_box_12_code' => 'nullable|string|max:255',
        ]);

        $deductionCategory = DeductionCategory::create([
            'title' => $validated['title'],
            'abbreviation' => $validated['abbreviation'] ?? null,
            'income_type_id' => $validated['income_type_id'],
            'quarterly_rate_q1' => $validated['quarterly_rate_q1'] ?? null,
            'quarterly_rate_q2' => $validated['quarterly_rate_q2'] ?? null,
            'quarterly_rate_q3' => $validated['quarterly_rate_q3'] ?? null,
            'quarterly_rate_q4' => $validated['quarterly_rate_q4'] ?? null,
            'cutoff' => $validated['cutoff'] ?? null,
            'paid_by' => $validated['paid_by'],
            'use_w2_box_10' => $request->boolean('use_w2_box_10'),
            'use_w2_box_12' => $request->boolean('use_w2_box_12'),
            'w2_box_12_code' => $validated['w2_box_12_code'] ?? null,
            'use_w2_box_14' => $request->boolean('use_w2_box_14'),
            'inactive' => $request->boolean('inactive'),
            'created_by' => current_user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Deduction category created successfully',
            'data' => $deductionCategory->load('incomeType'),
        ]);
    }

    public function edit(DeductionCategory $deductionCategory)
    {
        $incomeTypes = IncomeType::query()->orderBy('title')->get();
        $paidByOptions = [
            'employee' => 'Employee',
            'employer' => 'Employer',
        ];

        return view('screens.admin.deduction-categories.edit', get_defined_vars());
    }

    public function update(Request $request, DeductionCategory $deductionCategory)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abbreviation' => 'nullable|string|max:255',
            'income_type_id' => 'required|exists:income_types,id',
            'quarterly_rate_q1' => 'nullable|numeric|min:0',
            'quarterly_rate_q2' => 'nullable|numeric|min:0',
            'quarterly_rate_q3' => 'nullable|numeric|min:0',
            'quarterly_rate_q4' => 'nullable|numeric|min:0',
            'cutoff' => 'nullable|numeric|min:0',
            'paid_by' => 'required|string|in:employee,employer',
            'w2_box_12_code' => 'nullable|string|max:255',
        ]);

        $deductionCategory->update([
            'title' => $validated['title'],
            'abbreviation' => $validated['abbreviation'] ?? null,
            'income_type_id' => $validated['income_type_id'],
            'quarterly_rate_q1' => $validated['quarterly_rate_q1'] ?? null,
            'quarterly_rate_q2' => $validated['quarterly_rate_q2'] ?? null,
            'quarterly_rate_q3' => $validated['quarterly_rate_q3'] ?? null,
            'quarterly_rate_q4' => $validated['quarterly_rate_q4'] ?? null,
            'cutoff' => $validated['cutoff'] ?? null,
            'paid_by' => $validated['paid_by'],
            'use_w2_box_10' => $request->boolean('use_w2_box_10'),
            'use_w2_box_12' => $request->boolean('use_w2_box_12'),
            'w2_box_12_code' => $validated['w2_box_12_code'] ?? null,
            'use_w2_box_14' => $request->boolean('use_w2_box_14'),
            'inactive' => $request->boolean('inactive'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Deduction category updated successfully',
            'data' => $deductionCategory->fresh()->load('incomeType'),
        ]);
    }

    public function delete(DeductionCategory $deductionCategory)
    {
        $deductionCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deduction category deleted successfully',
        ]);
    }
}
