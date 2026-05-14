<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleManagement\PermissionController;
use App\Http\Controllers\RoleManagement\RoleController;
use App\Http\Controllers\RoleManagement\UserController;
use App\Http\Controllers\Setting\ProfileController;
use App\Http\Controllers\Auth\InactiveUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auto Login (Signed URL) - Harus di luar middleware auth agar bisa login otomatis
Route::get('/auth/auto-login/{admin_id}', [\App\Http\Controllers\Auth\AutoLoginController::class, 'login'])
    ->name('auto-login')
    ->middleware('signed');

Route::middleware(['auth'])->group(function () {
    
    // Halaman Inactive (Untuk user yang belum diaktivasi)
    Route::get('/inactive', function () {
        // Jika user sudah aktif, kembalikan ke dashboard
        if (auth()->user()->is_active == '1') {
            return redirect()->route('dashboard');
        }
        
        $isComplete = !empty(auth()->user()->gender) && 
                      !empty(auth()->user()->city) && 
                      !empty(auth()->user()->phone) && 
                      !empty(auth()->user()->username) &&
                      !empty(auth()->user()->bio);

        return view('auth.inactive', compact('isComplete'));
    })->name('inactive');

    Route::post('/inactive/update', [InactiveUserController::class, 'update'])->name('inactive.update');

    // Group untuk user yang SUDAH aktif
    Route::middleware(['user.active'])->group(
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
            Route::put('profil/{user}', [ProfileController::class, 'update'])->name('profil.update');
            Route::put('profil/{user}/ubah-password', [ProfileController::class, 'updatePassword'])->name('profil.update-password');

            // projects
            Route::redirect('project', 'projects');
            Route::get('projects/getAllPagination', [\App\Http\Controllers\Project\ProjectController::class, 'getAllPaginated'])->name('projects.allPagination');
            Route::get('projects/{project}/detail-data', [\App\Http\Controllers\Project\ProjectController::class, 'getDetailData'])->name('projects.detailData');
            Route::post('projects/{project}/diskusi', [\App\Http\Controllers\Project\ProjectController::class, 'storeDiskusi'])->name('projects.diskusi.store');
            Route::put('projects/diskusi/{id}', [\App\Http\Controllers\Project\ProjectController::class, 'updateDiskusi'])->name('projects.diskusi.update');
            Route::delete('projects/diskusi/{id}', [\App\Http\Controllers\Project\ProjectController::class, 'destroyDiskusi'])->name('projects.diskusi.destroy');
            Route::get('projects/diskusi/media/{mediaId}', [\App\Http\Controllers\Project\ProjectController::class, 'showMedia'])->name('projects.diskusi.media');
            Route::resource('projects', \App\Http\Controllers\Project\ProjectController::class);

            Route::get('dokumen', [\App\Http\Controllers\Dokumen\DokumenController::class, 'index'])->name('dokumen.index');
            Route::post('dokumen', [\App\Http\Controllers\Dokumen\DokumenController::class, 'store'])->name('dokumen.store');
            Route::get('dokumen/pagination', [\App\Http\Controllers\Dokumen\DokumenController::class, 'getAllPaginated'])->name('dokumen.pagination');
            Route::get('dokumen/{id}', [\App\Http\Controllers\Dokumen\DokumenController::class, 'show'])->name('dokumen.show');
            Route::put('dokumen/{id}', [\App\Http\Controllers\Dokumen\DokumenController::class, 'update'])->name('dokumen.update');
            Route::get('dokumen/{id}/builder', [\App\Http\Controllers\Dokumen\DokumenController::class, 'builder'])->name('dokumen.builder');
            Route::post('dokumen/{id}/builder', [\App\Http\Controllers\Dokumen\DokumenController::class, 'saveBuilder'])->name('dokumen.builder.save');
            Route::delete('dokumen/{id}', [\App\Http\Controllers\Dokumen\DokumenController::class, 'destroy'])->name('dokumen.destroy');

            // catatan
            Route::get('catatan', [\App\Http\Controllers\Catatan\CatatanController::class, 'index'])->name('catatan.index');
            Route::get('catatan/pagination', [\App\Http\Controllers\Catatan\CatatanController::class, 'getAllPaginated'])->name('catatan.pagination');
            Route::post('catatan', [\App\Http\Controllers\Catatan\CatatanController::class, 'store'])->name('catatan.store');
            Route::get('catatan/{id}', [\App\Http\Controllers\Catatan\CatatanController::class, 'show'])->name('catatan.show');
            Route::put('catatan/{id}', [\App\Http\Controllers\Catatan\CatatanController::class, 'update'])->name('catatan.update');
            Route::delete('catatan/{id}', [\App\Http\Controllers\Catatan\CatatanController::class, 'destroy'])->name('catatan.destroy');

            // teams
            Route::redirect('tim', 'teams');
            Route::get('teams/getData', [\App\Http\Controllers\Team\TeamController::class, 'getData'])->name('teams.getData');
            Route::get('teams/getUsers', [\App\Http\Controllers\Team\TeamController::class, 'getUsers'])->name('teams.getUsers');
            Route::resource('teams', \App\Http\Controllers\Team\TeamController::class);

            // kategori dokumen
            Route::get('kategori-dokumen/getData', [\App\Http\Controllers\KategoriDokumen\KategoriDokumenController::class, 'getData'])->name('kategori-dokumen.getData');
            Route::resource('kategori-dokumen', \App\Http\Controllers\KategoriDokumen\KategoriDokumenController::class);

        }
    );
});

require __DIR__.'/auth.php';
