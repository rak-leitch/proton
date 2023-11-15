<?php

use Illuminate\Support\Facades\Route;
use Adepta\Proton\Http\Controllers\ProtonController;
use Adepta\Proton\Http\Controllers\EntityIndexController;

Route::prefix('proton')->middleware('web')->name('proton.')->group(function () {
    Route::get('/', [ProtonController::class, 'index'])->name('index');
    Route::prefix('api')->group(function () {
        Route::prefix('config')->name('config.')->group(function () {
            Route::get('index/{entity_code}', [EntityIndexController::class, 'getConfig'])->name('index');
        });
    });
});


