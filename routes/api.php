<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BudgetController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/user', function (Request $request): User {
        return $request->user();
    });

    Route::prefix('accounts')->group(function (): void {
        Route::post('create', [AccountController::class, 'create']);
        Route::patch('{id}', [AccountController::class, 'update']);
    });

    Route::prefix('budgets')->group(function (): void {
        Route::post('create', [BudgetController::class, 'create']);
        Route::patch('{id}', [BudgetController::class, 'update']);
    });
});
