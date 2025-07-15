<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\API\AuthenticationController;
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
Route::post('/authenticate', [AuthenticationController::class, 'authenticateViaAPI']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/verify', fn () => null);
    Route::post('/revoke', [AuthenticationController::class, 'revokeViaAPI']);
});

Route::middleware(['auth:sanctum'])->name('api.')->group(function () {
    Route::prefix('/user')->name('users.')->group(function () {
        Route::get('/index', fn () => User::with(['option'])->find(Auth::id()))->name('get');
        Route::post('/set-options', [AuthenticationController::class, 'setOptions'])->name('set_options');
    });

    Route::middleware([UserHasCompletedSetup::class, CheckIfTransactionCycleExists::class])->group(function () {
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

        Route::prefix('/accounts')->name('accounts.')->group(function () {
            Route::get('/list', [AccountController::class, 'list'])->name('list');
            Route::post('/create', [AccountController::class, 'create'])->name('create');
        });
    });
});
