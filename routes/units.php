<?php

use App\Http\Controllers\Units\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'permission:administration.view'])
    ->prefix('units')
    ->name('units.')
    ->group(function () {
        Route::get('/', [UnitController::class, 'index'])
            ->middleware('permission:units.view')
            ->name('index');
        Route::post('/', [UnitController::class, 'store'])
            ->middleware('permission:units.create')
            ->name('store');
        Route::put('/{unit}', [UnitController::class, 'update'])
            ->middleware('permission:units.update')
            ->name('update');
        Route::delete('/{unit}', [UnitController::class, 'destroy'])
            ->middleware('permission:units.delete')
            ->name('destroy');
    });
