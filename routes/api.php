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


Route::post('/register', [AuthenticationController::class, 'register'])->middleware(CheckIfRegistrationIsAllowed::class);
Route::post('/authenticate', [AuthenticationController::class, 'authenticate']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/verify', fn () => null);
    Route::post('/revoke', [AuthenticationController::class, 'revoke']);
});

Route::middleware(['auth:web,sanctuum'])->name('api.')->group(function () {
    Route::prefix('/user')->name('users.')->group(function () {
        Route::get('/', fn () => User::with(['option'])->find(Auth::id()))->name('get');
        Route::post('/update', [UserOptionController::class, 'setOptions'])->name('update_options');
    });

    Route::middleware([UserHasCompletedSetup::class, CheckIfTransactionCycleExists::class])->group(function () {
        Route::prefix('/transactions')->name('transactions.')->group(function () {
            Route::get('/', [TransactionController::class, 'list'])->name('action');
            Route::post('/', [TransactionController::class, 'create'])->name('action');
            Route::patch('/', [TransactionController::class, 'update'])->name('action');
            Route::delete('/', [TransactionController::class, 'delete'])->name('action');
            Route::post('/post', [TransactionController::class, 'post'])->name('post');
            Route::post('/per-cycle', [TransactionController::class, 'transactionsPerCycle'])->name('per_cycle');
        });

        Route::prefix('/tags')->name('tags')->group(function () {
            Route::get('/', [TagController::class, 'list']);
            Route::post('/', [TagController::class, 'create']);
            Route::patch('/', [TagController::class, 'update']);
            Route::delete('/', [TagController::class, 'delete']);
        });
    });
});
