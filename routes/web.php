<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserOptionController;
use App\Http\Middleware\CheckIfRegistrationIsAllowed;
use App\Http\Middleware\CheckIfTransactionCycleExists;
use App\Http\Middleware\UserHasCompletedSetup;
use App\Http\Middleware\VerifyIfTransactionIsFromUser;
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
    Route::get('/test', fn () => Inertia::render('TestView'));
    Route::post('/revoke', [AuthenticationController::class, 'revoke'])->name('revoke');

    Route::get('/hello', fn () => Inertia::render('Setup/Index'))->middleware([UserHasCompletedSetup::class])->name('hello');
    Route::post('/user/update', [UserOptionController::class, 'setOptions'])->name('user.update_info');

    Route::middleware([UserHasCompletedSetup::class, CheckIfTransactionCycleExists::class])->group(function () {
        Route::prefix('/budgets')->name('budgets.')->group(function () {
            Route::get('/', fn () => Inertia::render('Budgets/Index'))->name('index');
        });

        Route::prefix('/transactions')->name('transactions.')->group(function () {
            Route::post('/list', [TransactionController::class, 'list'])->name('list');
            Route::post('/per-cycle', [TransactionController::class, 'transactionsPerCycle'])->name('per_cycle');
            Route::post('/create', [TransactionController::class, 'create'])->name('create');

            Route::middleware([VerifyIfTransactionIsFromUser::class])->group(function () {
                Route::post('/update', [TransactionController::class, 'update'])->name('update');
                Route::post('/delete', [TransactionController::class, 'delete'])->name('delete');
                Route::post('/post-transaction', [TransactionController::class, 'postTransaction'])->name('post');
            });
        });

        Route::prefix('/tags')->name('tags.')->group(function () {
            Route::post('/list', [TagController::class, 'list'])->name('list');
            Route::post('/create', [TagController::class, 'create'])->name('create');
        });
    });
});
