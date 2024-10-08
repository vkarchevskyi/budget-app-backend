<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/user', function (Request $request): User {
        return $request->user();
    });

    Route::prefix('accounts')
        ->name('accounts')
        ->group(function (): void {
            Route::get('', [AccountController::class, 'index']);
            Route::post('', [AccountController::class, 'store']);
            Route::get('{account}', [AccountController::class, 'show']);
            Route::patch('{account}', [AccountController::class, 'update']);
            Route::delete('{account}', [AccountController::class, 'destroy']);
        });

    Route::prefix('categories')
        ->name('categories')
        ->group(function (): void {
            Route::get('', [CategoryController::class, 'index']);
            Route::post('', [CategoryController::class, 'store']);
            Route::get('{category}', [CategoryController::class, 'show']);
            Route::patch('{category}', [CategoryController::class, 'update']);
            Route::delete('{category}', [CategoryController::class, 'destroy']);
        });

    Route::prefix('transactions')
        ->name('transactions')
        ->group(function (): void {
            Route::get('', [TransactionController::class, 'index']);
            Route::post('', [TransactionController::class, 'store']);
            Route::get('{transaction}', [TransactionController::class, 'show']);
            Route::patch('{transaction}', [TransactionController::class, 'update']);
            Route::delete('{transaction}', [TransactionController::class, 'destroy']);
        });
});
