<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\CheckIfRegistrationIsAllowed;
use App\Http\Middleware\CheckIfTransactionCycleExists;
use App\Http\Middleware\UserHasCompletedSetup;
use App\Http\Middleware\VerifyIfTransactionIsFromUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthenticationController::class, 'register'])->middleware(CheckIfRegistrationIsAllowed::class);
Route::post('/authenticate', [AuthenticationController::class, 'authenticate']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/verify', fn () => null);
    Route::post('/revoke', [AuthenticationController::class, 'revoke']);
});

Route::middleware('auth:web,sanctum')->group(function () {
    Route::prefix('/user')->group(function () {
        Route::get('/index', fn () => User::with(['option'])->find(Auth::id()));
        Route::post('/set-options', [AuthenticationController::class, 'setOptions']);
    });

    Route::middleware([UserHasCompletedSetup::class, CheckIfTransactionCycleExists::class])->group(function () {
        Route::prefix('/transactions')->group(function () {
            Route::post('/index', [TransactionController::class, 'index']);
            Route::post('/per-cycle', [TransactionController::class, 'transactionsPerCycle']);
            Route::post('/create', [TransactionController::class, 'create']);

            Route::middleware([VerifyIfTransactionIsFromUser::class])->group(function () {
                Route::post('/update', [TransactionController::class, 'update']);
                Route::post('/delete', [TransactionController::class, 'delete']);
                Route::post('/post-transaction', [TransactionController::class, 'postTransaction']);
            });
        });

        Route::prefix('/tags')->group(function () {
            Route::post('/index', [TagController::class, 'index']);
            Route::post('/create', [TagController::class, 'create']);
        });

        Route::prefix('/accounts')->group(function () {
            Route::get('/index', [AccountController::class, 'index']);
            Route::post('/create', [AccountController::class, 'create']);
        });
    });
});
