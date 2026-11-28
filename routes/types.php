<?php

use App\Http\Controllers\Types\TypeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'permission:administration.view'])
    ->prefix('types')
    ->name('types.')
    ->group(function () {
        Route::get('/', [TypeController::class, 'index'])
            ->middleware('permission:types.view')
            ->name('index');
        Route::post('/', [TypeController::class, 'store'])
            ->middleware('permission:types.create')
            ->name('store');
        Route::put('/{type}', [TypeController::class, 'update'])
            ->middleware('permission:types.update')
            ->name('update');
        Route::delete('/{type}', [TypeController::class, 'destroy'])
            ->middleware('permission:types.delete')
            ->name('destroy');
    });
