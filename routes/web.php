<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleManagement\PermissionController;
use App\Http\Controllers\RoleManagement\RoleController;
use App\Http\Controllers\RoleManagement\UserController;
use App\Http\Controllers\Setting\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'user.active'])->group(
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

        // users
        Route::get('users/getAllPagination', [UserController::class, 'getAllPaginated'])->name('users.allPagination');
        Route::put('users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::put('users/{id}/reject', [UserController::class, 'reject'])->name('users.reject');
        Route::put('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('users', UserController::class);

        Route::get('profil', [ProfileController::class, 'index'])->name('profil.index');

    }
);

require __DIR__.'/auth.php';
