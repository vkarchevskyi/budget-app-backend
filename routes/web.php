<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', static fn () => ['Budget app API' => '1.0.0']);

require __DIR__.'/auth.php';
