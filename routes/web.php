<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\TaxCategoryController;
use App\Http\Controllers\DeductionCategoryController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

// web routes
Route::get('/', function () {
    return view('screens.web.home.index');
})->name('home');


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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware('auth', 'role:admin')->group(function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
});
Route::prefix('admin')->middleware('auth', 'role:admin|client')->group(function () {
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

});
// admin dashboard routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('screens.admin.dashboard.admin');
    })->name('admin.dashboard');
});


require __DIR__ . '/auth.php';
