<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Helpers\ResponseHelper;
use Carbon\Carbon;

class ProjectRequestController extends Controller
{
    /**
     * Show the form for creating a new project request.
     */
    public function create()
    {
        $data = [
            'title' => 'Permohonan Aplikasi Baru',
            'subtitle' => 'Ajukan permintaan pembuatan aplikasi atau sistem baru',
            'icon' => 'bi bi-patch-plus-fill',
        ];

        return view('pages.project.request', $data);
    }

    /**
     * Store a newly created project request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
            'start_date' => ['required', 'date_format:d-m-Y'],
            'deadline' => ['nullable', 'date_format:d-m-Y', 'after_or_equal:start_date'],
            'color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'reference_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB max
            'reference_file' => ['nullable', 'file', 'mimes:pdf,docx,xlsx,zip,rar,png,jpg,jpeg', 'max:20480'], // 20MB max
            'app_type' => ['required', 'in:website,android,website_android'],
            'g-recaptcha-response' => (app()->environment('testing') || empty(config('services.recaptcha.site_key'))) ? ['nullable'] : ['required', new \App\Rules\Recaptcha],
        ], [
            'start_date.required' => 'Tanggal wajib diisi.',
            'start_date.date_format' => 'Format tanggal tidak valid.',
            'app_type.required' => 'Jenis aplikasi wajib dipilih.',
            'app_type.in' => 'Jenis aplikasi tidak valid.',
        ]);

        try {
            $project = DB::transaction(function () use ($validated) {
                // Transform dates
                $startDate = Carbon::createFromFormat('d-m-Y', $validated['start_date'])->format('Y-m-d');
                $deadline = !empty($validated['deadline']) ? Carbon::createFromFormat('d-m-Y', $validated['deadline'])->format('Y-m-d') : null;

                $project = Project::create([
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                    'status' => 'to_do',
                    'priority' => $validated['priority'] ?? 'medium',
                    'start_date' => $startDate,
                    'deadline' => $deadline,
                    'progress' => 0,
                    'notes' => 'Dibuat melalui form permohonan.',
                    'created_by' => auth()->id(),
                    'team_id' => null, // tim dinullkan dulu sesuai request
                    'color' => $validated['color'] ?? '#4f46e5',
                    'source' => 'request', // membedakan project dibuat dari permohonan
                    'app_type' => $validated['app_type'],
                ]);

                // Handle Reference Image (juga dijadikan thumbnail agar muncul di dashboard)
                if (isset($validated['reference_image'])) {
                    $project->addMedia($validated['reference_image'])
                        ->toMediaCollection('thumbnail');
                }

                // Handle Reference File
                if (isset($validated['reference_file'])) {
                    $project->addMedia($validated['reference_file'])
                        ->toMediaCollection('reference_file');
                }

                return $project;
            });

            // Clear Cache
            $cacheVersion = Cache::get('project_cache_version', 1);
            Cache::forever('project_cache_version', $cacheVersion + 1);
            Cache::forget('dashboard_data_' . auth()->id());

            return ResponseHelper::success('Permohonan pembuatan aplikasi berhasil diajukan.', $project);

        } catch (\Exception $e) {
            return ResponseHelper::error($e->getMessage(), 500);
        }
    }
}
