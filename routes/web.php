<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DeductionCategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackagePurchaseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TaxCategoryController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// web routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:client'])->group(function () {
    Route::get('packages/{package}/checkout', [PackagePurchaseController::class, 'show'])->name('packages.checkout.show');
    Route::post('packages/{package}/checkout', [PackagePurchaseController::class, 'store'])->name('packages.checkout.store');
});

Route::get('/how-it-work', function () {
    return view('screens.web.inner-pages.how-it-work');
})->name('how-it-work');

Route::get('/features', function () {
    return view('screens.web.inner-pages.features');
})->name('features');

Route::get('/security', function () {
    return view('screens.web.inner-pages.security');
})->name('security');

Route::get('/about', function () {
    return view('screens.web.inner-pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('screens.web.inner-pages.contact');
})->name('contact');

// web routes end

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware('auth', 'role:admin')->group(function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/subscriptions/overview', [UsersController::class, 'packageSubscriptions'])->name('users.subscriptions.index');
    Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');

    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{package}/update', [PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}/delete', [PackageController::class, 'delete'])->name('packages.delete');
});
Route::prefix('admin')->middleware('auth', 'role:admin|client')->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');

    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}/edit/', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{company}/update', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{company}/delete', [CompanyController::class, 'delete'])->name('companies.delete');

    Route::get('/categories/income', [IncomeCategoryController::class, 'index'])->name('categories.income.index');
    Route::get('/categories/income/create', [IncomeCategoryController::class, 'create'])->name('categories.income.create');
    Route::post('/categories/income', [IncomeCategoryController::class, 'store'])->name('categories.income.store');
    Route::get('/categories/income/{incomeCategory}/edit', [IncomeCategoryController::class, 'edit'])->name('categories.income.edit');
    Route::put('/categories/income/{incomeCategory}/update', [IncomeCategoryController::class, 'update'])->name('categories.income.update');
    Route::delete('/categories/income/{incomeCategory}/delete', [IncomeCategoryController::class, 'delete'])->name('categories.income.delete');

    Route::get('/categories/tax', [TaxCategoryController::class, 'index'])->name('categories.tax.index');
    Route::get('/categories/tax/create', [TaxCategoryController::class, 'create'])->name('categories.tax.create');
    Route::post('/categories/tax', [TaxCategoryController::class, 'store'])->name('categories.tax.store');
    Route::get('/categories/tax/{taxCategory}/edit', [TaxCategoryController::class, 'edit'])->name('categories.tax.edit');
    Route::put('/categories/tax/{taxCategory}/update', [TaxCategoryController::class, 'update'])->name('categories.tax.update');
    Route::delete('/categories/tax/{taxCategory}/delete', [TaxCategoryController::class, 'delete'])->name('categories.tax.delete');

    Route::get('/categories/deduction', [DeductionCategoryController::class, 'index'])->name('categories.deduction.index');
    Route::get('/categories/deduction/create', [DeductionCategoryController::class, 'create'])->name('categories.deduction.create');
    Route::post('/categories/deduction', [DeductionCategoryController::class, 'store'])->name('categories.deduction.store');
    Route::get('/categories/deduction/{deductionCategory}/edit', [DeductionCategoryController::class, 'edit'])->name('categories.deduction.edit');
    Route::put('/categories/deduction/{deductionCategory}/update', [DeductionCategoryController::class, 'update'])->name('categories.deduction.update');
    Route::delete('/categories/deduction/{deductionCategory}/delete', [DeductionCategoryController::class, 'delete'])->name('categories.deduction.delete');

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::delete('/employees/{employee}/delete', [EmployeeController::class, 'delete'])->name('employees.delete');
    Route::get('/forms', [FormController::class, 'index'])->name('admin.forms.index');
    Route::get('/forms/w-2', [FormController::class, 'w2'])->name('admin.forms.w2');
    Route::post('/forms/w-2/pdf', [FormController::class, 'w2Pdf'])->name('admin.forms.w2.pdf');
    Route::post('/forms/w-3/pdf', [FormController::class, 'w3Pdf'])->name('admin.forms.w3.pdf');
    Route::get('/forms/940', [FormController::class, 'form940'])->name('admin.forms.940');
    Route::get('/forms/944', [FormController::class, 'form944'])->name('admin.forms.944');
    Route::get('/forms/941', [FormController::class, 'form941'])->name('admin.forms.form-941');
    Route::post('/forms/941/pdf', [FormController::class, 'form941Pdf'])->name('admin.forms.form-941.pdf');
    Route::get('/forms/941-x', [FormController::class, 'form941X'])->name('admin.forms.form-941-x');
    Route::post('/forms/941-x/pdf', [FormController::class, 'form941XPdf'])->name('admin.forms.form-941-x.pdf');
    Route::get('/forms/941/schedule-b', [FormController::class, 'form941ScheduleB'])->name('admin.forms.form-941-schedule-b');
    Route::post('/forms/941/schedule-b/pdf', [FormController::class, 'form941ScheduleBPdf'])->name('admin.forms.form-941-schedule-b.pdf');
    Route::get('/forms/941/schedule-r', [FormController::class, 'form941ScheduleR'])->name('admin.forms.form-941-schedule-r');
    Route::post('/forms/941/schedule-r/pdf', [FormController::class, 'form941ScheduleRPdf'])->name('admin.forms.form-941-schedule-r.pdf');
});

Route::prefix('admin')->middleware('auth', 'role:admin|client|employee')->group(function () {
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}/update', [EmployeeController::class, 'update'])->name('employees.update');
});
// admin dashboard routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

require __DIR__.'/auth.php';
