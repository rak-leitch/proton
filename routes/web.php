<?php

use Illuminate\Support\Facades\Route;
use Adepta\Proton\Http\Controllers\ProtonController;

//Main entry point
Route::get('proton', [ProtonController::class, 'index'])->name('proton.index');
