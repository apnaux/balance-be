<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserOptionController;
use App\Http\Middleware\CheckIfRegistrationIsAllowed;
use App\Http\Middleware\CheckIfTransactionCycleExists;
use App\Http\Middleware\UserHasCompletedSetup;
use App\Http\Middleware\VerifyIfTransactionIsFromUser;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');
    Route::post('/authenticate', [AuthenticationController::class, 'authenticate'])->name('authenticate');

    Route::middleware([CheckIfRegistrationIsAllowed::class])->group(function () {
        Route::get('/register', fn () => Inertia::render('Auth/Register'))->name('register');
        Route::post('/register/create', [AuthenticationController::class, 'register'])->name('register.create');
    });
});

Route::middleware(['auth:web'])->group(function () {
    Route::post('/revoke', [AuthenticationController::class, 'revoke'])->name('revoke');

    Route::get('/hello', fn () => Inertia::render('Setup/Index'))->middleware([UserHasCompletedSetup::class])->name('hello');
    Route::post('/user/update', [UserOptionController::class, 'setOptions'])->name('user.update_info');

    Route::middleware([UserHasCompletedSetup::class, CheckIfTransactionCycleExists::class])->group(function () {
        Route::prefix('/budgets')->name('budgets.')->group(function () {
            Route::get('/', fn () => Inertia::render('Budgets/Index'))->name('index');
        });

        Route::prefix('/transactions')->group(function () {
            //

            Route::middleware([VerifyIfTransactionIsFromUser::class])->group(function () {
                //
            });
        });

        Route::prefix('/tags')->group(function () {
            //
        });

        Route::prefix('/accounts')->group(function () {
            //
        });
    });
});
