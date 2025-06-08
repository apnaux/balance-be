<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/authenticate', [AuthenticationController::class, 'authenticate']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('/transactions')->group(function () {
        Route::get('/index', [TransactionController::class, 'index']);
        Route::post('/create', [TransactionController::class, 'create']);
    });

    Route::prefix('/accounts')->group(function () {
        Route::get('/index', [AccountController::class, 'index']);
        Route::post('/create', [AccountController::class, 'create']);
    });

    Route::post('/revoke', [AuthenticationController::class, 'revoke']);
});
