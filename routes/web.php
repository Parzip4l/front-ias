<?php

use App\Http\Controllers\RoutingController;
use App\Http\Controllers\Auth\SppdAuthController;
use App\Http\Controllers\UserManagement\UserController;
use App\Http\Controllers\Sppd\SppdController;
use App\Http\Controllers\Finance\MitraSaldoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        Route::get('/roles', [UserController::class, 'roles'])->name('roles.index');
        Route::post('/roles-create', [UserController::class, 'storeRoles'])->name('roles.create');
        Route::put('/roles-update', [UserController::class, 'updateRole'])->name('roles.update');
        Route::post('/roles-delete', [UserController::class, 'destroyRole'])->name('roles.delete');
    });

    Route::prefix('sppd')->group(function () {
        Route::get('/pengajuan-sppd', [SppdController::class, 'create'])->name('sppd.create');
    });

    // Finance
    Route::get('/finance/saldo-mitra', [MitraSaldoController::class, 'index'])
    ->name('finance.saldo-mitra');

    Route::get('/preview', [SppdController::class, 'preview'])->name('sppd.preview');

    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});


