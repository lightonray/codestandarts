<?php

use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;

Route::prefix('car')->name('car.')->group(function () {
    Route::get('/management', [CarController::class, 'index'])->name('index');
    Route::get('/createcar', [CarController::class, 'create'])->name('create');
    Route::post('/createcar', [CarController::class, 'store'])->name('store');
    Route::get('/editcar/{id}', [CarController::class, 'edit'])->name('edit');
    Route::post('/editcar/{id}', [CarController::class, 'Update'])->name('update');
    Route::get('/deletecar/{id}', [CarController::class, 'Destroy'])->name('destroy');
});