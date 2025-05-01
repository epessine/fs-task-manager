<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')
    ->name('api.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::prefix('v1')
            ->name('v1.')
            ->group(function () {
                Route::apiResource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
                Route::apiResource('tasks', TaskController::class)->only(['index', 'store', 'update', 'destroy']);
            });
    });
