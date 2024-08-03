<?php

declare(strict_types=1);

use App\Http\Controllers\AccountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('accounts')->group(function () {
        Route::post('create', [AccountController::class, 'create']);
        Route::patch('{id}', [AccountController::class, 'update']);
    });
});
