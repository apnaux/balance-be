<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserOptionController;
use App\Http\Middleware\CheckIfRegistrationIsAllowed;
use App\Http\Middleware\CheckIfTransactionCycleExists;
use App\Http\Middleware\UserHasCompletedSetup;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post('/reg', [AuthenticationController::class, 'register'])->middleware(CheckIfRegistrationIsAllowed::class);
Route::post('/auth', [AuthenticationController::class, 'authenticate']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/verify', fn () => null);
    Route::post('/revoke', [AuthenticationController::class, 'revoke']);
});

Route::middleware(['auth:web,sanctuum'])->group(function () {
    Route::prefix('/user')->group(function () {
        Route::get('/', fn () => User::with(['option'])->find(Auth::id()));
        Route::post('/update', [UserOptionController::class, 'setOptions']);
    });

    Route::middleware([UserHasCompletedSetup::class, CheckIfTransactionCycleExists::class])->group(function () {
        Route::prefix('/transactions')->group(function () {
            Route::get('/', [TransactionController::class, 'list']);
            Route::post('/', [TransactionController::class, 'create']);
            Route::patch('/', [TransactionController::class, 'update']);
            Route::delete('/', [TransactionController::class, 'delete']);
            Route::post('/post', [TransactionController::class, 'post']);
            Route::post('/per-cycle', [TransactionController::class, 'perCycleData']);
        });

        Route::prefix('/tags')->group(function () {
            Route::get('/', [TagController::class, 'list']);
            Route::post('/', [TagController::class, 'create']);
            Route::patch('/', [TagController::class, 'update']);
            Route::delete('/', [TagController::class, 'delete']);
        });
    });
});
