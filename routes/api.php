<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TransactionController;
use App\Http\Middleware\UserHasCompletedSetup;
use App\Http\Middleware\VerifyIfUserIsAllowed;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/authenticate', [AuthenticationController::class, 'authenticate']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/revoke', [AuthenticationController::class, 'revoke']);
    Route::prefix('/user')->group(function () {
        Route::get('/index', fn () => User::with(['option'])->find(Auth::id()));
        Route::post('/set-options', [AuthenticationController::class, 'setOptions']);
    });

    Route::middleware([UserHasCompletedSetup::class])->group(function () {
        Route::prefix('/transactions')->group(function () {
            Route::get('/summary', [TransactionController::class, 'summary']);
            Route::get('/index', [TransactionController::class, 'index']);
            Route::get('/per-cycle', [TransactionController::class, 'transactionsPerCycle']);
            Route::post('/create', [TransactionController::class, 'create']);

            Route::middleware([VerifyIfUserIsAllowed::class])->group(function () {
                Route::post('/update', [TransactionController::class, 'update']);
                Route::post('/delete', [TransactionController::class, 'delete']);
                Route::post('/post-transaction', [TransactionController::class, 'postTransaction']);
            });
        });

        Route::prefix('/accounts')->group(function () {
            Route::get('/index', [AccountController::class, 'index']);
            Route::post('/create', [AccountController::class, 'create']);
        });
    });
});
