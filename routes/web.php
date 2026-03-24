<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleManagement\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(
    function () {
        Route::controller(DashboardController::class)
            ->group(function () {
                Route::get('/dashboard', 'index')->name('dashboard');
            });


        // role
        Route::get('roles/getAllPagination', [RoleController::class, 'getAllPaginated'])->name('roles.allPagination');
        Route::resource('roles', RoleController::class);
    }
);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
