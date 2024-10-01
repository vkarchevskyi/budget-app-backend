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
            Route::patch('{account}', [AccountController::class, 'update']);
        });

    Route::prefix('categories')
        ->name('categories')
        ->group(function (): void {
            Route::post('', [CategoryController::class, 'store']);
            Route::patch('{id}', [CategoryController::class, 'update']);
        });

    Route::prefix('transactions')
        ->name('transactions')
        ->group(function (): void {
            Route::post('', [TransactionController::class, 'store']);
            Route::patch('{id}', [TransactionController::class, 'update']);
        });
});
