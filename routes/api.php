<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\TransactionController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function (): void {
    Route::get('/user', function (Request $request): User {
        return $request->user();
    });

    Route::prefix('accounts')
        ->name('accounts.*')
        ->group(function (): void {
            Route::get('', [AccountController::class, 'index'])->name('index');
            Route::post('', [AccountController::class, 'store'])->name('store');
            Route::get('{account}', [AccountController::class, 'show'])->name('show');
            Route::patch('{account}', [AccountController::class, 'update'])->name('update');
            Route::delete('{account}', [AccountController::class, 'destroy'])->name('delete');
        });

    Route::prefix('categories')
        ->name('categories.*')
        ->group(function (): void {
            Route::get('', [CategoryController::class, 'index'])->name('index')->name('index');
            Route::post('', [CategoryController::class, 'store'])->name('store');
            Route::get('{category}', [CategoryController::class, 'show'])->name('show');
            Route::patch('{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('{category}', [CategoryController::class, 'destroy'])->name('delete');
        });

    Route::prefix('transactions')
        ->name('transactions.*')
        ->group(function (): void {
            Route::get('', [TransactionController::class, 'index'])->name('index');
            Route::post('', [TransactionController::class, 'store'])->name('store');
            Route::get('{transaction}', [TransactionController::class, 'show'])->name('show');
            Route::patch('{transaction}', [TransactionController::class, 'update'])->name('update');
            Route::delete('{transaction}', [TransactionController::class, 'destroy'])->name('delete');
        });

    Route::prefix('charts')
        ->name('charts.*')
        ->group(function (): void {
            Route::get('expenses', [ChartController::class, 'expenses'])->name('expenses');
        });
});
