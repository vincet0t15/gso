<?php

use App\Http\Controllers\PpeClassifications\PpeClassificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'permission:administration.view'])
    ->prefix('ppe-classifications')
    ->name('ppe-classifications.')
    ->group(function () {
        Route::get('/', [PpeClassificationController::class, 'index'])
            ->middleware('permission:ppe-classifications.view')
            ->name('index');
        Route::post('/', [PpeClassificationController::class, 'store'])
            ->middleware('permission:ppe-classifications.create')
            ->name('store');
        Route::put('/{ppe_classification}', [PpeClassificationController::class, 'update'])
            ->middleware('permission:ppe-classifications.update')
            ->name('update');
        Route::delete('/{ppe_classification}', [PpeClassificationController::class, 'destroy'])
            ->middleware('permission:ppe-classifications.delete')
            ->name('destroy');
    });
