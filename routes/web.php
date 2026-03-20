<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserOptionController;
use App\Http\Middleware\CheckIfRegistrationIsAllowed;
use App\Http\Middleware\CheckIfTransactionCycleExists;
use App\Http\Middleware\UserHasCompletedSetup;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware(['guest'])->group(function () {
    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/authenticate', [AuthenticationController::class, 'authenticate'])->name('login.set');

    Route::middleware([CheckIfRegistrationIsAllowed::class])->group(function () {
        Route::inertia('/register', 'Auth/Register')->name('register');
        Route::post('/register/create', [AuthenticationController::class, 'register'])->name('register.create');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::inertia('/hello', 'Setup')->middleware([UserHasCompletedSetup::class])->name('hello.show');
    Route::post('/hello', [UserOptionController::class, 'setOptions'])->middleware([UserHasCompletedSetup::class])->name('hello.store');

    Route::middleware([UserHasCompletedSetup::class, CheckIfTransactionCycleExists::class])->group(function () {
        Route::inertia('/home', 'Dashboard')->name('home');
        Route::get('/test', fn () => redirect()->route('home'))->name('testing');
        // Route::get('/tags', fn () => Inertia::render(component: 'Tags/Index'));
    });

    Route::post('/revoke', [AuthenticationController::class, 'revoke'])->name('revoke');
});
