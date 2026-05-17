<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display the settings index page.
     */
    public function index(): View
    {
        $settings = $this->settingService->getAll();
        
        // Hitung pengaturan per grup
        $counts = [
            'profile' => \App\Models\Setting::where('group', 'profile')->count(),
            'security' => \App\Models\Setting::where('group', 'security')->count(),
            'email' => \App\Models\Setting::where('group', 'email')->count(),
            'maintenance' => \App\Models\Setting::where('group', 'maintenance')->count(),
        ];
        
        // Get media
        $model = \App\Models\Setting::where('key', 'app_name')->first() ?? new \App\Models\Setting();
        $logo = $model->getFirstMediaUrl('logo');
        $favicon = $model->getFirstMediaUrl('favicon');

        return view('pages.setting.index', [
            'title' => 'Pengaturan Sistem',
            'subtitle' => 'Konfigurasi aplikasi, keamanan, email & maintenance',
            'icon' => 'bi bi-gear-wide-connected',
            'settings' => $settings,
            'counts' => $counts,
            'logo' => $logo,
            'favicon' => $favicon,
            'backups' => $this->getBackupList()
        ]);
    }

    /**
     * Get list of backup files.
     */
    private function getBackupList(): array
    {
        $backups = \App\Models\Backup::latest()->get();
        
        return $backups->map(function ($bk) {
            return [
                'id' => $bk->id,
                'filename' => $bk->name,
                'size' => $bk->size,
                'date' => $bk->created_at->format('d M Y'),
                'time' => $bk->created_at->format('H:i'),
                'type' => $bk->type,
            ];
        })->toArray();
    }

    /**
     * Format bytes to human readable format.
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Update system profile settings.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'nullable|url',
            'app_email' => 'nullable|email',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:512',
            'favicon' => 'nullable|image|mimes:png,ico|max:100',
        ]);

        try {
            $this->settingService->updateProfile($request->except(['logo', 'favicon']));

            // Handle Media
            $model = \App\Models\Setting::where('key', 'app_name')->first();
            
            if ($request->hasFile('logo')) {
                $model->addMediaFromRequest('logo')->toMediaCollection('logo');
            }

            if ($request->hasFile('favicon')) {
                $model->addMediaFromRequest('favicon')->toMediaCollection('favicon');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Profil sistem berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user avatar.
     */
    public function updateAvatar(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            
            if ($request->hasFile('avatar')) {
                $user->clearMediaCollection('avatars');
                $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Foto profil berhasil diperbarui',
                'avatar_url' => $user->getFirstMediaUrl('avatars')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui foto profil: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update security settings.
     */
    public function updateSecurity(Request $request): JsonResponse
    {
        try {
            $data = $request->except(['_token']);
            
            // Handle checkboxes
            $checkboxes = ['password_require_symbol', 'password_require_number', 'enable_otp', 'enable_google_login', 'allow_registration', 'admin_approval'];
            foreach ($checkboxes as $cb) {
                $data[$cb] = $request->has($cb) ? '1' : '0';
            }

            $this->settingService->updateProfile($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengaturan keamanan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui keamanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update application settings.
     */
    public function updateApp(Request $request): JsonResponse
    {
        try {
            $this->settingService->updateProfile($request->except(['_token']));

            return response()->json([
                'status' => 'success',
                'message' => 'Identitas aplikasi berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui identitas aplikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update email settings.
     */
    public function updateEmail(Request $request): JsonResponse
    {
        try {
            $this->settingService->updateProfile($request->except(['_token']));

            return response()->json([
                'status' => 'success',
                'message' => 'Konfigurasi SMTP berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui SMTP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a test email using dynamic SMTP settings.
     */
    public function sendTestMail(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
        ]);

        try {
            // Override Mail Config at Runtime
            config([
                'mail.mailers.smtp.host' => $request->mail_host,
                'mail.mailers.smtp.port' => $request->mail_port,
                'mail.mailers.smtp.username' => $request->mail_username,
                'mail.mailers.smtp.password' => $request->mail_password,
                'mail.mailers.smtp.encryption' => $request->mail_encryption,
                'mail.from.address' => $request->mail_from_address,
                'mail.from.name' => $request->mail_from_name,
            ]);

            \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\TestConnectionMail());

            return response()->json([
                'status' => 'success',
                'message' => 'Email percobaan berhasil dikirim ke ' . $request->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update maintenance settings.
     */
    public function updateMaintenance(Request $request): JsonResponse
    {
        try {
            $mode = $request->has('maintenance_mode') ? '1' : '0';
            
            $data = [
                'maintenance_mode' => $mode,
                'maintenance_message' => $request->maintenance_message,
                'maintenance_end_time' => $request->maintenance_end_time,
            ];

            $this->settingService->updateProfile($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Status pemeliharaan berhasil diperbarui menjadi ' . ($mode == '1' ? 'AKTIF' : 'NON-AKTIF')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Clear system cache.
     */
    public function clearCache(): JsonResponse
    {
        try {
            // 1. Kosongkan seluruh data cache di Database (Tabel cache)
            \Illuminate\Support\Facades\Cache::flush();
            
            // 2. Bersihkan cache tampilan (Blade views) - Aman untuk AJAX
            \Illuminate\Support\Facades\Artisan::call('view:clear');

            return response()->json([
                'status' => 'success',
                'message' => 'Cache database dan tampilan berhasil dibersihkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get backup history HTML partial.
     */
    public function getBackupHistoryHtml(): string
    {
        $backups = $this->getBackupList();
        return view('pages.setting.partials._backup_list', compact('backups'))->render();
    }

    /**
     * Update auto backup settings.
     */
    public function updateBackup(Request $request): JsonResponse
    {
        try {
            $data = $request->except(['_token']);
            
            // Handle checkboxes
            $checkboxes = ['backup_auto_enabled', 'backup_notification_enabled'];
            foreach ($checkboxes as $cb) {
                $data[$cb] = $request->has($cb) ? '1' : '0';
            }

            $this->settingService->updateProfile($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal backup berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui jadwal backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Run a new system backup.
     */
    public function runBackup(): JsonResponse
    {
        try {
            // Jalankan artisan command di background
            \Illuminate\Support\Facades\Artisan::call('backup:run', ['--only-db' => true]);

            return response()->json([
                'status' => 'success',
                'message' => 'Backup database berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download a backup file.
     */
    public function downloadBackup(int $id)
    {
        $backup = \App\Models\Backup::findOrFail($id);
        $media = $backup->getFirstMedia('backup_files');

        if (!$media || !file_exists($media->getPath())) {
            abort(404, 'File backup tidak ditemukan');
        }

        return response()->download($media->getPath(), $media->file_name);
    }

    /**
     * Delete a backup file.
     */
    public function deleteBackup(int $id): JsonResponse
    {
        try {
            $backup = \App\Models\Backup::findOrFail($id);
            $backup->delete(); // Otomatis menghapus file media

            return response()->json([
                'status' => 'success',
                'message' => 'File backup berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus backup: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get system activities via AJAX.
     */
    public function activities(Request $request): JsonResponse
    {
        $query = \Spatie\Activitylog\Models\Activity::latest()->with('causer');

        // Filter by Event
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by Module/Log Name
        if ($request->filled('module')) {
            $query->where('log_name', $request->module);
        }

        // Filter by Date Range (More robust parsing)
        if ($request->filled('date')) {
            $dateInput = $request->date;
            // Handle both " to " and " - " as separators
            $dates = preg_split('/ (to|-) /', $dateInput);
            
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [trim($dates[0]) . ' 00:00:00', trim($dates[1]) . ' 23:59:59']);
            } else {
                $query->whereDate('created_at', trim($dates[0]));
            }
        }

        $activities = $query->paginate(5);

        return response()->json([
            'status' => 'success',
            'html' => view('pages.setting.partials._activity_log', compact('activities'))->render()
        ]);
    }

    /**
     * Export system activities to Excel (XLS).
     */
    public function exportActivities(Request $request)
    {
        $query = \Spatie\Activitylog\Models\Activity::latest()->with('causer');

        // Filter by Date Range (More robust parsing)
        if ($request->filled('date')) {
            $dateInput = $request->date;
            $dates = preg_split('/ (to|-) /', $dateInput);
            
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [trim($dates[0]) . ' 00:00:00', trim($dates[1]) . ' 23:59:59']);
            } else {
                $query->whereDate('created_at', trim($dates[0]));
            }
        }

        $activities = $query->get();
        $filename = "Laporan_Aktivitas_" . date('d-m-Y_His') . ".xls";

        $headers = [
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($activities) {
            // Output HTML table with Excel-specific XML/CSS for better styling
            echo "<html>
                    <head>
                        <meta charset='utf-8'>
                        <style>
                            .head { background-color: #0d6efd; color: #ffffff; font-weight: bold; text-align: center; }
                            .cell { border: 1px solid #dee2e6; padding: 5px; }
                            .center { text-align: center; }
                            .bold { font-weight: bold; }
                        </style>
                    </head>
                    <body>
                        <table border='1'>
                            <tr>
                                <th class='head cell' width='50'>ID</th>
                                <th class='head cell' width='120'>MODUL</th>
                                <th class='head cell' width='100'>AKSI</th>
                                <th class='head cell' width='200'>SUBJEK</th>
                                <th class='head cell' width='150'>AKTOR</th>
                                <th class='head cell' width='350'>DESKRIPSI</th>
                                <th class='head cell' width='180'>WAKTU</th>
                            </tr>";

            foreach ($activities as $act) {
                $eventLabel = match($act->event) {
                    'created' => 'TAMBAH',
                    'updated' => 'UBAH',
                    'deleted' => 'HAPUS',
                    'login'   => 'LOGIN',
                    'logout'  => 'LOGOUT',
                    default   => strtoupper($act->event)
                };

                $subject = class_basename($act->subject_type ?? 'System');
                $causer = $act->causer ? $act->causer->name : 'System';
                
                echo "<tr>
                        <td class='cell center'>{$act->id}</td>
                        <td class='cell center'>".strtoupper($act->log_name)."</td>
                        <td class='cell center bold'>{$eventLabel}</td>
                        <td class='cell'>{$subject} (#{$act->subject_id})</td>
                        <td class='cell'>{$causer}</td>
                        <td class='cell'>{$act->description}</td>
                        <td class='cell center'>{$act->created_at->format('d/m/Y H:i:s')}</td>
                      </tr>";
            }
            echo "      </table>
                    </body>
                  </html>";
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get latest threat logs.
     */
    public function getThreatLogs(Request $request): JsonResponse
    {
        $limit = min((int)$request->input('limit', 5), 50);
        $logs = \App\Models\SecurityLog::latest()->take($limit)->get()->map(function ($log) {
            return [
                'id' => $log->id,
                'time' => $log->created_at->timezone('Asia/Jakarta')->format('H:i:s'),
                'ip' => $log->ip_address,
                'type' => $log->event_type,
                'url' => $log->url,
                'status' => $log->status,
            ];
        });

        return response()->json($logs);
    }
}
