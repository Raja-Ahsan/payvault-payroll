<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use App\Models\IncomeType;
use Illuminate\Http\Request;

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
        return view('screens.admin.income-categories.create');
    }
    
}
