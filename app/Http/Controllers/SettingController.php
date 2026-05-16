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
        
        // Get media
        $model = Setting::where('key', 'app_name')->first() ?? new Setting();
        $logo = $model->getFirstMediaUrl('logo');
        $favicon = $model->getFirstMediaUrl('favicon');

        return view('pages.setting.index', [
            'title' => 'Pengaturan Sistem',
            'subtitle' => 'Konfigurasi aplikasi, keamanan, email & maintenance',
            'icon' => 'bi bi-gear-wide-connected',
            'settings' => $settings,
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
            $model = Setting::where('key', 'app_name')->first();
            
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
}
