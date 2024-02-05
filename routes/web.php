<?php

use Illuminate\Support\Facades\Route;
use Adepta\Proton\Http\Controllers\ProtonController;
use Adepta\Proton\Http\Controllers\View\EntityIndexController;
use Adepta\Proton\Http\Controllers\View\EntityCreateController;
use Adepta\Proton\Http\Controllers\View\EntityUpdateController;
use Adepta\Proton\Http\Controllers\List\ListConfigController;
use Adepta\Proton\Http\Controllers\Form\UpdateConfigController;
use Adepta\Proton\Http\Controllers\Form\CreateConfigController;
use Adepta\Proton\Http\Controllers\Form\SubmitUpdateController;
use Adepta\Proton\Http\Controllers\Form\SubmitCreateController;
use Adepta\Proton\Http\Controllers\List\ListDataController;

Route::prefix('proton')->middleware(['web', 'auth'])->name('proton.')->group(function () {
    Route::prefix('api')->group(function () {
        Route::prefix('config')->name('config.')->group(function () {
            Route::prefix('view')->name('view.')->group(function () {
                Route::get('entity-index/{entity_code}', [EntityIndexController::class, 'getConfig'])->name('index');
                Route::get('entity-update/{entity_code}/{entity_id}', [EntityUpdateController::class, 'getConfig'])->name('update');
                Route::get('entity-create/{entity_code}', [EntityCreateController::class, 'getConfig'])->name('create');
            });
            Route::get('list/{entity_code}', [ListConfigController::class, 'getConfig'])->name('list');
            Route::get('form-update/{entity_code}/{entity_id}', [UpdateConfigController::class, 'getConfig'])->name('form.update');
            Route::get('form-create/{entity_code}', [CreateConfigController::class, 'getConfig'])->name('form.create');
        });
        Route::prefix('data')->name('data.')->group(function () {
            Route::get('list/{entity_code}/{page}/{items_per_page}/{sort_by}', [ListDataController::class, 'getData'])->name('list');
        });
        Route::prefix('submit')->name('submit.')->group(function () {
            Route::post('form-update/{entity_code}/{entity_id}', [SubmitUpdateController::class, 'submit'])->name('update');
            Route::post('form-create/{entity_code}', [SubmitCreateController::class, 'submit'])->name('create');
        }); 
    });
    Route::get('/', [ProtonController::class, 'index'])->name('index');
    Route::get('{any}', [ProtonController::class, 'index'])->where('any', '.*');
});


