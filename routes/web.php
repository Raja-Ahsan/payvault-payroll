<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CompanyController;
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
    Route::get('/companies/{company}/edit/', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::post('/companies/update', [CompanyController::class, 'update'])->name('companies.update');
    Route::post('/companies', [CompanyController::class, 'general'])->name('companies.general');
});
// admin dashboard routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('screens.admin.dashboard.admin');
    })->name('admin.dashboard');
});


require __DIR__ . '/auth.php';
