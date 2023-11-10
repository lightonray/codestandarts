<?php 

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/management', [UserController::class, 'index'])->name('index');
    Route::get('/createuser', [UserController::class, 'create'])->name('create');
    Route::post('/createuser', [UserController::class, 'store'])->name('store');
    Route::get('/edituser/{id}', [UserController::class, 'edit'])->name('edit');
    Route::post('/edituser/{id}', [UserController::class, 'update'])->name('update');
    Route::get('/deleteuser/{id}', [UserController::class, 'destroy'])->name('destroy');
});
