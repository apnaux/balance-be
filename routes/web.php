<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Middleware\CheckIfRegistrationIsAllowed;
use App\Http\Middleware\CheckIfTransactionCycleExists;
use App\Http\Middleware\UserHasCompletedSetup;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware(['guest'])->group(function () {
    Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');
    Route::post('/authenticate', [AuthenticationController::class, 'authenticate'])->name('authenticate');

    Route::middleware([CheckIfRegistrationIsAllowed::class])->group(function () {
        Route::get('/register', fn () => Inertia::render('Auth/Register'))->name('register');
        Route::post('/register/create', [AuthenticationController::class, 'register'])->name('register.create');
    });
});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/hello', fn () => Inertia::render('Setup'))->middleware([UserHasCompletedSetup::class])->name('hello');
    Route::post('/revoke', [AuthenticationController::class, 'revoke'])->name('revoke');

    Route::middleware([UserHasCompletedSetup::class, CheckIfTransactionCycleExists::class])->group(function () {
        // Route::get('/home', fn () => Inertia::render('Budgets/Index'))->name('home');
        // Route::get('/tags', fn () => Inertia::render(component: 'Tags/Index'));
    });
});
