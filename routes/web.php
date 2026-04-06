<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleManagement\PermissionController;
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
        Route::get('roles/{role}/akses', [RoleController::class, 'akses'])->name('roles.akses');
        Route::put('roles/{role}/akses', [RoleController::class, 'aksesedit'])->name('roles.akses.edit');

        Route::resource('roles', RoleController::class);

        // permissions
        Route::get('permissions/getAllPagination', [PermissionController::class, 'getAllPaginated'])->name('permissions.allPagination');
        Route::resource('permissions', PermissionController::class);
    }
);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
