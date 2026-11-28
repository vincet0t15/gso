<?php

use App\Http\Controllers\Items\ItemController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'permission:administration.view'])
    ->prefix('items')
    ->name('items.')
    ->group(function () {
        Route::get('/', [ItemController::class, 'index'])
            ->middleware('permission:items.view')
            ->name('index');
        Route::post('/', [ItemController::class, 'store'])
            ->middleware('permission:items.create')
            ->name('store');
        Route::put('/{item}', [ItemController::class, 'update'])
            ->middleware('permission:items.update')
            ->name('update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])
            ->middleware('permission:items.delete')
            ->name('destroy');
    });
