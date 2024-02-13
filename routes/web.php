<?php

use Illuminate\Support\Facades\Route;
use Adepta\Proton\Http\Controllers\ProtonController;
use Adepta\Proton\Http\Controllers\View\{EntityIndexController, EntityCreateController, EntityUpdateController, EntityViewController};
use Adepta\Proton\Http\Controllers\List\{ListConfigController, ListDataController};
use Adepta\Proton\Http\Controllers\Form\{UpdateConfigController, CreateConfigController, SubmitUpdateController, SubmitCreateController};


Route::prefix('proton')->middleware(['web', 'auth'])->name('proton.')->group(function () {
    Route::prefix('api')->group(function () {
        Route::prefix('config')->name('config.')->group(function () {
            Route::prefix('view')->name('view.')->group(function () {
                Route::get('entity-index/{entity_code}', [EntityIndexController::class, 'getConfig'])->name('index');
                Route::get('entity-update/{entity_code}/{entity_id}', [EntityUpdateController::class, 'getConfig'])->name('update');
                Route::get('entity-create/{entity_code}', [EntityCreateController::class, 'getConfig'])->name('create');
                Route::get('entity-view/{entity_code}/{entity_id}', [EntityViewController::class, 'getConfig'])->name('view');
            });
            Route::get('list/{entity_code}', [ListConfigController::class, 'getConfig'])->name('list');
            Route::get('form-update/{entity_code}/{entity_id}', [UpdateConfigController::class, 'getConfig'])->name('form-update');
            Route::get('form-create/{entity_code}', [CreateConfigController::class, 'getConfig'])->name('form-create');
        });
        Route::prefix('data')->name('data.')->group(function () {
            Route::get('list/{entity_code}/{page}/{items_per_page}/{sort_by}', [ListDataController::class, 'getData'])->name('list');
        });
        Route::prefix('submit')->name('submit.')->group(function () {
            Route::post('form-update/{entity_code}/{entity_id}', [SubmitUpdateController::class, 'submit'])->name('form-update');
            Route::post('form-create/{entity_code}', [SubmitCreateController::class, 'submit'])->name('form-create');
        }); 
    });
    Route::get('/', [ProtonController::class, 'index'])->name('index');
    Route::get('{any}', [ProtonController::class, 'index'])->where('any', '.*');
});


