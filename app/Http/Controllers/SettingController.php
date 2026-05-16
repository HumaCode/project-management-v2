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
            'favicon' => $favicon
        ]);
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
}
