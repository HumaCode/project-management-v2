<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleManagement\PermissionController;
use App\Http\Controllers\RoleManagement\RoleController;
use App\Http\Controllers\RoleManagement\UserController;
use App\Http\Controllers\Setting\ProfileController;
use App\Http\Controllers\Auth\InactiveUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $totalProjects = \App\Models\Project::count();
    $totalUsers = \App\Models\User::where('is_active', '1')->count();
    $totalTeams = \App\Models\Team::count();
    $totalDocuments = \App\Models\Dokumen::count();
    $totalCompletedProjects = \App\Models\Project::where('status', 'done')->count();

    return view('welcome', compact(
        'totalProjects',
        'totalUsers',
        'totalTeams',
        'totalDocuments',
        'totalCompletedProjects'
    ));
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
            Route::get('profil/activities', [ProfileController::class, 'activities'])->name('profil.activities');
            Route::get('profil/activities/export', [ProfileController::class, 'exportActivities'])->name('profil.activities.export');
            Route::delete('profil/sessions/{id}', [ProfileController::class, 'revokeSession'])->name('profil.sessions.revoke');
            Route::post('profil/sessions/revoke-others', [ProfileController::class, 'revokeOtherSessions'])->name('profil.sessions.revoke-others');
            Route::post('profil/deactivate', [ProfileController::class, 'deactivate'])->name('profil.deactivate');
            Route::get('profil/download-data', [ProfileController::class, 'downloadData'])->name('profil.download-data');
            Route::post('profil/delete-account', [ProfileController::class, 'deleteAccount'])->name('profil.delete-account');

            // Project Request
            Route::get('permohonan-aplikasi', [\App\Http\Controllers\Project\ProjectRequestController::class, 'create'])->name('project-request.create');
            Route::post('permohonan-aplikasi', [\App\Http\Controllers\Project\ProjectRequestController::class, 'store'])->name('project-request.store');

            // projects
            Route::redirect('project', 'projects');
            Route::get('projects/getAllPagination', [\App\Http\Controllers\Project\ProjectController::class, 'getAllPaginated'])->name('projects.allPagination');
            Route::get('projects/{project}/detail-data', [\App\Http\Controllers\Project\ProjectController::class, 'getDetailData'])->name('projects.detailData');
            Route::get('projects/{project}/activities', [\App\Http\Controllers\Project\ProjectController::class, 'getActivities'])->name('projects.activities');
            Route::get('projects/{project}/dokumens', [\App\Http\Controllers\Project\ProjectController::class, 'getDokumens'])->name('projects.dokumens');
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

            // reports
            Route::redirect('reports', 'laporan');
            Route::get('laporan', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
            Route::get('laporan/assets', [\App\Http\Controllers\ReportController::class, 'getAssets'])->name('reports.assets');
            Route::get('laporan/history', [\App\Http\Controllers\ReportController::class, 'history'])->name('reports.history');
            Route::get('laporan/download/{id}', [\App\Http\Controllers\ReportController::class, 'download'])->name('reports.download');
            Route::post('laporan/preview', [\App\Http\Controllers\ReportController::class, 'preview'])->name('reports.preview');
            Route::post('laporan/generate', [\App\Http\Controllers\ReportController::class, 'generate'])->name('reports.generate');
            Route::delete('laporan/{id}', [\App\Http\Controllers\ReportController::class, 'destroy'])->name('reports.destroy');
            
            // Diagrams
            Route::get('diagrams', [\App\Http\Controllers\DiagramController::class, 'index'])->name('diagrams.index');
            Route::get('diagrams/pagination', [\App\Http\Controllers\DiagramController::class, 'getAllPaginated'])->name('diagrams.pagination');
            Route::post('diagrams', [\App\Http\Controllers\DiagramController::class, 'store'])->name('diagrams.store');
            Route::get('diagrams/{id}/builder', [\App\Http\Controllers\DiagramController::class, 'builder'])->name('diagrams.builder');
            Route::put('diagrams/{id}', [\App\Http\Controllers\DiagramController::class, 'update'])->name('diagrams.update');
            Route::delete('diagrams/{id}', [\App\Http\Controllers\DiagramController::class, 'destroy'])->name('diagrams.destroy');
            
            // Settings
            Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
            Route::post('settings/profile', [\App\Http\Controllers\SettingController::class, 'updateProfile'])->name('settings.update-profile');
            Route::post('settings/profile/avatar', [\App\Http\Controllers\SettingController::class, 'updateAvatar'])->name('settings.update-avatar');
            Route::post('settings/security', [\App\Http\Controllers\SettingController::class, 'updateSecurity'])->name('settings.update-security');
            Route::post('settings/app', [\App\Http\Controllers\SettingController::class, 'updateApp'])->name('settings.update-app');
            Route::post('settings/email', [\App\Http\Controllers\SettingController::class, 'updateEmail'])->name('settings.update-mail');
            Route::post('settings/email/test', [\App\Http\Controllers\SettingController::class, 'sendTestMail'])->name('settings.send-test-mail');
            Route::post('settings/maintenance', [\App\Http\Controllers\SettingController::class, 'updateMaintenance'])->name('settings.update-maintenance');
            Route::get('settings/clear-cache', [\App\Http\Controllers\SettingController::class, 'clearCache'])->name('settings.clear-cache');
            
            // Backups
            Route::post('settings/backups/settings', [\App\Http\Controllers\SettingController::class, 'updateBackup'])->name('settings.update-backup');
            Route::post('settings/backups', [\App\Http\Controllers\SettingController::class, 'runBackup'])->name('settings.run-backup');
            Route::get('settings/backups/history', [\App\Http\Controllers\SettingController::class, 'getBackupHistoryHtml'])->name('settings.backup-history');
            Route::get('settings/activities', [\App\Http\Controllers\SettingController::class, 'activities'])->name('settings.activities');
            Route::get('settings/activities/export', [\App\Http\Controllers\SettingController::class, 'exportActivities'])->name('settings.activities.export');
            Route::get('settings/backups/download/{id}', [\App\Http\Controllers\SettingController::class, 'downloadBackup'])->name('settings.download-backup');
            Route::delete('settings/backups/delete/{id}', [\App\Http\Controllers\SettingController::class, 'deleteBackup'])->name('settings.delete-backup');
            Route::get('settings/threats', [\App\Http\Controllers\SettingController::class, 'getThreatLogs'])->name('settings.threat-logs');
            
            // Global Search
            Route::get('/global-search', [\App\Http\Controllers\GlobalSearchController::class, 'search'])->name('global.search');

            // Notifications
            Route::get('/notifications/recent', [\App\Http\Controllers\NotificationController::class, 'getRecent'])->name('notifications.recent');
            Route::post('/notifications/mark-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all');
            Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');


        }
    );
});

require __DIR__.'/auth.php';
