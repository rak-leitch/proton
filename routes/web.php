<?php

use Illuminate\Support\Facades\Route;
use Adepta\Proton\Http\Controllers\ProtonController;
use Adepta\Proton\Http\Controllers\View\EntityIndexController;
use Adepta\Proton\Http\Controllers\List\ListConfigController;
use Adepta\Proton\Http\Controllers\List\ListDataController;

Route::prefix('proton')->middleware(['web', 'auth'])->name('proton.')->group(function () {
    Route::prefix('api')->group(function () {
        Route::prefix('config')->name('config.')->group(function () {
            Route::prefix('view')->name('view.')->group(function () {
                Route::get('entity-index/{entity_code}', [EntityIndexController::class, 'getConfig'])->name('index');
            });
            Route::prefix('list')->name('list.')->group(function () {
                Route::get('{view_type}/{entity_code}', [ListConfigController::class, 'getConfig'])->name('config');
            });
        });
        Route::prefix('data')->name('data.')->group(function () {
            Route::get('list/{entity_code}/{page}/{items_per_page}/{sort_by}', [ListDataController::class, 'getData'])->name('data');
        }); 
    });
    Route::get('/', [ProtonController::class, 'index'])->name('index');
    Route::get('{any}', [ProtonController::class, 'index'])->where('any', '.*');
});


