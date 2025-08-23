<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\SppdAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [SppdAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [SppdAuthController::class, 'doLogin'])->name('login.post');
Route::get('/register', [SppdAuthController::class, 'showRegister'])->name('register');

// Logout API Call
Route::post('/auth/logout', [AuthenticatedSessionController::class, 'logout'])->name('auth.logout');