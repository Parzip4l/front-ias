<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// Controller
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\Auth\SppdAuthController;
use App\Http\Controllers\UserManagement\UserController;
use App\Http\Controllers\UserManagement\PosisiController;
use App\Http\Controllers\UserManagement\BudgetCategoryController;
use App\Http\Controllers\UserManagement\BudgetController;
use App\Http\Controllers\Employee\EmployeeController;

use App\Http\Controllers\Companies\CompanyType;
use App\Http\Controllers\Companies\CompanyController;

use App\Http\Controllers\Sppd\SppdController;
use App\Http\Controllers\Sppd\ApprovalController;
use App\Http\Controllers\Finance\MitraSaldoController;

require __DIR__ . '/auth.php';

// Forgot Pass
Route::get('/forgot-password', [SppdAuthController::class, 'ForgotPassword'])->name('sppd.forgotpass');
Route::get('/reset-password', function (Request $request) {
    // Ambil token & email dari query
    $token = $request->query('token');
    $email = $request->query('email');

    // Kirim ke view biar bisa dipakai di form hidden input
    return view('auth.createpw', compact('token', 'email'));
})->name('password.reset.form');

Route::post('/logout', [SppdAuthController::class, 'logout'])->name('logout');

Route::group(['prefix' => '/', 'middleware' => 'jwt.session'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('/home', fn() => view('index'))->name('home');

    // user Management
    Route::prefix('user-management')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users-create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users-store', [UserController::class, 'storeUser'])->name('users.store');

        // Roles
        Route::get('/roles', [UserController::class, 'roles'])->name('roles.index');
        Route::post('/roles-create', [UserController::class, 'storeRoles'])->name('roles.create');
        Route::put('/roles-update', [UserController::class, 'updateRole'])->name('roles.update');
        Route::post('/roles-delete', [UserController::class, 'destroyRole'])->name('roles.delete');

        // Divisi
        Route::prefix('divisi')->group(function () {
            Route::get('/list-data', [UserController::class, 'divisi'])->name('divisi.index');
            Route::post('/store', [UserController::class, 'storeDivisi'])->name('divisi.store');
            Route::put('/update', [UserController::class, 'updateDivisi'])->name('divisi.update');
            Route::post('/delete', [UserController::class, 'destroyDivisi'])->name('divisi.delete');
        });

        Route::prefix('posisi')->group(function () {
            Route::get('/list', [PosisiController::class, 'index'])->name('posisi.index');
            Route::post('/store', [PosisiController::class, 'store'])->name('posisi.store');
            Route::put('/update', [PosisiController::class, 'update'])->name('posisi.update');
            Route::post('/delete', [PosisiController::class, 'destroy'])->name('posisi.delete');
        });

        Route::prefix('budget')->group(function () {
            Route::get('/list', [BudgetController::class, 'index'])->name('budget.index');
            Route::post('/store', [BudgetController::class, 'store'])->name('budget.store');
            Route::get('/{id}/edit', [BudgetController::class, 'edit'])->name('budget.edit');
            Route::put('/update/{id}', [BudgetController::class, 'update'])->name('budget.update');
            Route::post('/delete', [BudgetController::class, 'destroy'])->name('budget.delete');
        });

        Route::prefix('budget-category')->group(function () {
            Route::get('/list', [BudgetCategoryController::class, 'index'])->name('budget-category.index');
            Route::post('/store', [BudgetCategoryController::class, 'store'])->name('budget-category.store');
            Route::put('/update', [BudgetCategoryController::class, 'update'])->name('budget-category.update');
            Route::post('/delete', [BudgetCategoryController::class, 'destroy'])->name('budget-category.delete');
        });

        Route::prefix('karyawan')->group(function () {
            Route::get('/list', [EmployeeController::class, 'index'])->name('employee.index');
            Route::get('/create', [EmployeeController::class, 'create'])->name('employee.create');
            Route::post('/store', [EmployeeController::class, 'store'])->name('employee.store');
            Route::get('/{id}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
            Route::put('/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
            Route::post('/delete', [EmployeeController::class, 'destroy'])->name('employee.delete');
        });
    });

    // Sppd
    Route::prefix('sppd')->group(function () {
        Route::get('/list-data', [SppdController::class, 'index'])->name('sppd.index');
        Route::get('/pengajuan-sppd', [SppdController::class, 'create'])->name('sppd.create');
        Route::get('/preview/{id}', [SppdController::class, 'preview'])->name('sppd.previews');
        // Route::post('/store', [SppdController::class, 'storeDivisi'])->name('divisi.store');
        // Route::put('/update', [SppdController::class, 'updateDivisi'])->name('divisi.update');
        // Route::post('/delete', [SppdController::class, 'destroyDivisi'])->name('divisi.delete');
        Route::prefix('approval')->group(function () {
            Route::get('/list', [ApprovalController::class, 'index'])->name('flow.index');
            Route::post('/store', [ApprovalController::class, 'store'])->name('flow.store');
            Route::put('/update/{id}', [ApprovalController::class, 'update'])->name('flow.update');
            Route::get('/{id}/details', [ApprovalController::class, 'show'])->name('flow.single');
            Route::get('/final-step/{id}', [ApprovalController::class, 'isFinal'])->name('flow.final');
            Route::post('/delete', [ApprovalController::class, 'destroy'])->name('flow.delete');

            // Steps
            Route::post('/store-steps', [ApprovalController::class, 'addStep'])->name('steps.store');
            Route::post('/delete-steps', [ApprovalController::class, 'deleteStep'])->name('steps.delete');

        });
    });

    // Companies-type
    Route::prefix('company')->group(function () {
        Route::prefix('type')->group(function () {
            Route::get('/list', [CompanyType::class, 'index'])->name('companytype.index');
            Route::post('/store', [CompanyType::class, 'store'])->name('companytype.store');
            Route::put('/update', [CompanyType::class, 'update'])->name('companytype.update');
            Route::post('/delete', [CompanyType::class, 'destroy'])->name('companytype.delete');
        });
        Route::get('/list', [CompanyController::class, 'index'])->name('company.index');
        Route::get('/create', [CompanyController::class, 'create'])->name('company.create');
        Route::post('/store', [CompanyController::class, 'store'])->name('company.store');
        Route::get('/{id}/edit', [CompanyController::class, 'edit'])->name('company.edit');
        Route::get('/{id}/details', [CompanyController::class, 'single'])->name('company.single');
        Route::put('/update', [CompanyController::class, 'update'])->name('company.update');
        Route::post('/delete', [CompanyController::class, 'destroy'])->name('company.delete');
    });

    // Finance
    Route::get('/finance/saldo-mitra', [MitraSaldoController::class, 'index'])
    ->name('finance.saldo-mitra');

    Route::get('/preview', [SppdController::class, 'preview'])->name('sppd.preview');

    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});


