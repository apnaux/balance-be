<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TransactionController;
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

Route::middleware(['auth:web', UserHasCompletedSetup::class])->group(function () {
    Route::inertia('/hello', 'Setup')->name('hello.show');
    Route::post('/hello', [UserOptionController::class, 'setOptions'])->name('hello.store');

    Route::middleware([CheckIfTransactionCycleExists::class])->group(function () {
        Route::inertia('/home', 'Dashboard/Index')->name('home');
        Route::get('/test', fn () => redirect()->route('home'))->name('testing');

        Route::prefix('/tags')->name('tags.')->group(function () {
            Route::inertia('/', 'Tags.index')->name('tags.index');
            Route::get('/list', [TagController::class, 'list'])->name('list');
        });

        Route::prefix('/transactions')->name('transactions.')->group(function () {
            Route::get('/list', [TransactionController::class, 'list'])->name('list');
            Route::post('/', [TransactionController::class, 'create'])->name('create');
            Route::get('/per-cycle', [TransactionController::class, 'perCycleData'])->name('cycle-data');
        });

        Route::prefix('/accounts')->name('accounts.')->group(function () {
            Route::get('/list', [AccountController::class, 'list'])->name('list');
            Route::post('/', [AccountController::class, 'create'])->name('create');
            Route::patch('/', [AccountController::class, 'update'])->name('update');
        });
    });

    Route::post('/revoke', [AuthenticationController::class, 'revoke'])->name('revoke');
});
