<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\DeductionCategory;
use App\Models\IncomeCategory;
use App\Models\PayrollCheck;
use App\Models\TaxCategory;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if($user->hasRole(config('roles.admin'))) {
            $totalCompanies = Company::query()->count();
            $totalCategories = IncomeCategory::query()->count();
            return view('screens.admin.dashboard.admin', get_defined_vars());
        }
        if ($user->hasRole(config('roles.client'))) {
            $totalCompanies = $user->hasRole(config('roles.client'))
                ? Company::query()->count()
                : Company::query()->where('user_id', $user->id)->count();

            $totalCategories = IncomeCategory::query()->count()
                + TaxCategory::query()->count()
                + DeductionCategory::query()->count();

            $totalEmployees = User::query()->role(config('roles.employee'))->count();

            return view('screens.admin.dashboard.admin', get_defined_vars());
        }
        if ($user->hasRole(config('roles.employee'))) {
            $myChecksCount = PayrollCheck::query()
                ->whereHas('employee', fn ($q) => $q->where('user_id', $user->id))
                ->count();

            return view('screens.admin.dashboard.employee', get_defined_vars());
        }
    }
}
