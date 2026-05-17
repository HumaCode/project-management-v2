<?php

namespace App\Http\Controllers\Setting;

use App\Constants\Setting\ProfileMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\ProfileUpdatePasswordRequest;
use App\Http\Requests\Setting\ProfileUpdateRequest;
use App\Interface\Setting\ProfileRepositoryInterface;
use App\Models\User;

class ProfileController extends Controller
{
    private string $title = ProfileMessages::TITLE;

    private string $subtitle = ProfileMessages::SUBTITLE;

    private string $indexView = ProfileMessages::INDEXVIEW;

    private string $icon = ProfileMessages::ICON;

    private string $aksesPermission = ProfileMessages::AKSES_PERMISSION;

    private ProfileRepositoryInterface $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function index()
    {
        $userId = user('id');
        $user = auth()->user();

        // Mengumpulkan statistik real-time proyek, task, dokumen, & tim user ini (dinamis & sesuai anggota)
        $projectQuery = \App\Models\Project::where(function ($q) use ($userId) {
            $q->where('created_by', $userId)
                ->orWhereHas('team.members', function ($sq) use ($userId) {
                    $sq->where('users.id', $userId);
                })
                ->orWhereHas('pics', function ($sq) use ($userId) {
                    $sq->where('users.id', $userId);
                });
        });

        $userProjectsCount = (clone $projectQuery)->count();
        $completedTasksCount = (clone $projectQuery)->where('status', 'done')->count();
        $userDocumentsCount = \App\Models\Dokumen::whereIn('project_id', $projectQuery->select('id'))->count();
        $userTeamsCount = $user->teams()->count();

        $activities = \Spatie\Activitylog\Models\Activity::latest()
            ->where('causer_id', $userId)
            ->where('causer_type', \App\Models\User::class)
            ->paginate(6, ['*'], 'act_page');

        // Mengumpulkan statistik real-time kontribusi & tindakan user ini
        $activityStats = [
            'total' => \Spatie\Activitylog\Models\Activity::where('causer_id', $userId)->where('causer_type', \App\Models\User::class)->count(),
            'created' => \Spatie\Activitylog\Models\Activity::where('causer_id', $userId)->where('causer_type', \App\Models\User::class)->where('event', 'created')->count(),
            'updated' => \Spatie\Activitylog\Models\Activity::where('causer_id', $userId)->where('causer_type', \App\Models\User::class)->where('event', 'updated')->count(),
            'deleted' => \Spatie\Activitylog\Models\Activity::where('causer_id', $userId)->where('causer_type', \App\Models\User::class)->where('event', 'deleted')->count(),
            'auth' => \Spatie\Activitylog\Models\Activity::where('causer_id', $userId)->where('causer_type', \App\Models\User::class)->whereIn('event', ['login', 'logout'])->count(),
        ];

        // Mengumpulkan daftar sesi login aktif di database
        $sessions = \DB::table('sessions')
            ->where('user_id', $userId)
            ->orderBy('last_activity', 'desc')
            ->get();

        $activeSessions = [];
        $currentSessionId = request()->session()->getId();

        foreach ($sessions as $s) {
            $userAgent = $s->user_agent ?? '';
            
            // Parse OS / Platform
            $platform = 'OS Tidak Dikenal';
            $icon = 'bi-display'; // default PC
            
            if (preg_match('/Windows NT 10.0/i', $userAgent)) {
                $platform = 'Windows 10/11';
            } elseif (preg_match('/Windows NT 6.3/i', $userAgent)) {
                $platform = 'Windows 8.1';
            } elseif (preg_match('/Windows NT 6.2/i', $userAgent)) {
                $platform = 'Windows 8';
            } elseif (preg_match('/Windows NT 6.1/i', $userAgent)) {
                $platform = 'Windows 7';
            } elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
                $platform = 'macOS';
                if (preg_match('/iPad/i', $userAgent)) {
                    $platform = 'iPadOS';
                    $icon = 'bi-tablet';
                }
            } elseif (preg_match('/iPhone/i', $userAgent)) {
                $platform = 'iPhone';
                $icon = 'bi-phone';
            } elseif (preg_match('/iPad/i', $userAgent)) {
                $platform = 'iPad';
                $icon = 'bi-tablet';
            } elseif (preg_match('/Android/i', $userAgent)) {
                $platform = 'Android';
                $icon = 'bi-phone';
            } elseif (preg_match('/Linux/i', $userAgent)) {
                $platform = 'Linux';
            }

            // Parse Browser
            $browser = 'Browser Tidak Dikenal';
            if (preg_match('/chrome\/([0-9\.]+)/i', $userAgent, $matches)) {
                $browser = 'Chrome ' . explode('.', $matches[1])[0];
            } elseif (preg_match('/safari\/([0-9\.]+)/i', $userAgent, $matches) && !preg_match('/chrome/i', $userAgent)) {
                $browser = 'Safari';
                if (preg_match('/version\/([0-9\.]+)/i', $userAgent, $vMatches)) {
                    $browser = 'Safari ' . explode('.', $vMatches[1])[0];
                }
            } elseif (preg_match('/firefox\/([0-9\.]+)/i', $userAgent, $matches)) {
                $browser = 'Firefox ' . explode('.', $matches[1])[0];
            } elseif (preg_match('/edge\/([0-9\.]+)/i', $userAgent, $matches) || preg_match('/edg\/([0-9\.]+)/i', $userAgent, $matches)) {
                $browser = 'Edge ' . explode('.', $matches[1])[0];
            } elseif (preg_match('/opera\/([0-9\.]+)/i', $userAgent, $matches) || preg_match('/opr\/([0-9\.]+)/i', $userAgent, $matches)) {
                $browser = 'Opera';
            }

            // Fallback lokasi lokal
            $ip = $s->ip_address ?? '127.0.0.1';
            $location = 'Indonesia';
            if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '172.') || str_starts_with($ip, '10.')) {
                $location = 'Jaringan Lokal';
            }

            $activeSessions[] = (object)[
                'id' => $s->id,
                'ip_address' => $ip,
                'device_name' => $browser . ' · ' . $platform,
                'location' => $location,
                'icon' => $icon,
                'is_current' => ($s->id === $currentSessionId),
                'last_active' => \Carbon\Carbon::createFromTimestamp($s->last_activity),
            ];
        }

        $data = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'permissionAkses' => $this->aksesPermission,
            'profile' => $this->profileRepository->getProfileByUserId($userId),
            'activities' => $activities,
            'activityStats' => $activityStats,
            'activeSessions' => $activeSessions,
            'stats' => [
                'projects_count' => $userProjectsCount,
                'completed_tasks_count' => $completedTasksCount,
                'documents_count' => $userDocumentsCount,
                'teams_count' => $userTeamsCount,
            ],
        ];

        return view($this->indexView, $data);
    }

    public function update(ProfileUpdateRequest $request, User $user)
    {
        try {
            // 1. Ambil semua data yang sudah lulus validasi (termasuk file gambar jika ada)
            $data = $request->validated();

            // 2. Lempar proses update sepenuhnya ke Repository
            $this->profileRepository->update($user->id, $data);

            // 3. Return response JSON untuk jQuery AJAX
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
            ]);

        } catch (\Exception $e) {
            // 4. Return pesan error JSON jika terjadi kegagalan
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil: '.$e->getMessage(),
            ], 500);
        }
    }

    public function updatePassword(ProfileUpdatePasswordRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            $this->profileRepository->updatePassword($user->id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diperbarui!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui password: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user activities timeline via AJAX.
     */
    public function activities(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $userId = auth()->id();
            
            $query = \Spatie\Activitylog\Models\Activity::latest()
                ->where('causer_id', $userId)
                ->where('causer_type', \App\Models\User::class);

            $activities = $query->paginate(6, ['*'], 'act_page');

            return response()->json([
                'success' => true,
                'html' => view('pages.setting.profile.partials._activity_list', compact('activities'))->render()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat riwayat aktivitas: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Export logged-in user activities to Excel.
     */
    public function exportActivities()
    {
        try {
            $userId = auth()->id();
            
            $activities = \Spatie\Activitylog\Models\Activity::latest()
                ->where('causer_id', $userId)
                ->where('causer_type', \App\Models\User::class)
                ->get();

            $filename = "Laporan_Aktivitas_Saya_" . date('d-m-Y_His') . ".xls";

            $headers = [
                "Content-type"        => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use($activities) {
                // Output HTML table with Excel-specific styling for cleaner looks
                echo "<html>
                        <head>
                            <meta charset='utf-8'>
                            <style>
                                .head { background-color: #00c8ff; color: #ffffff; font-weight: bold; text-align: center; }
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
                    
                    echo "<tr>
                            <td class='cell center'>{$act->id}</td>
                            <td class='cell center'>".strtoupper($act->log_name)."</td>
                            <td class='cell center bold'>{$eventLabel}</td>
                            <td class='cell'>{$act->description}</td>
                            <td class='cell center'>{$act->created_at->format('d/m/Y H:i:s')}</td>
                          </tr>";
                }
                echo "      </table>
                        </body>
                      </html>";
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            abort(500, 'Gagal mengekspor riwayat aktivitas: ' . $e->getMessage());
        }
    }

    /**
     * Cabut (Revoke) sesi login tertentu dari database.
     */
    public function revokeSession(string $id)
    {
        try {
            $deleted = \DB::table('sessions')
                ->where('user_id', user('id'))
                ->where('id', $id)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sesi login berhasil dicabut!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Sesi tidak ditemukan atau sudah tidak aktif.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencabut sesi login: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cabut semua sesi login lain selain sesi yang sedang aktif saat ini.
     */
    public function revokeOtherSessions()
    {
        try {
            $currentSessionId = request()->session()->getId();

            $deleted = \DB::table('sessions')
                ->where('user_id', user('id'))
                ->where('id', '!=', $currentSessionId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Semua sesi login lain berhasil dicabut!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencabut sesi login lain: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menonaktifkan akun sendiri secara sementara.
     */
    public function deactivate(\Illuminate\Http\Request $request)
    {
        try {
            $user = User::findOrFail(user('id'));
            $user->is_active = 0;
            $user->email_verified_at = null;
            $user->save();

            // Log activity
            activity()
                ->performedOn($user)
                ->causedBy($user)
                ->log('Menonaktifkan akun sendiri secara sementara');

            // Log out user
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Akun Anda berhasil dinonaktifkan. Anda akan dialihkan keluar.',
                'redirect' => route('login')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan akun: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download (ekspor) semua data profil, proyek, dokumen, catatan, dan aktivitas milik user.
     */
    public function downloadData()
    {
        try {
            $userId = user('id');
            $user = User::with(['roles', 'permissions'])->findOrFail($userId);

            // Fetch related data
            $projects = \App\Models\Project::where('created_by', $userId)->get();
            $documents = \App\Models\Dokumen::where('created_by', $userId)->get();
            $notes = \App\Models\Catatan::where('user_id', $userId)->get();
            $activities = \Spatie\Activitylog\Models\Activity::where('causer_id', $userId)->where('causer_type', User::class)->get();

            $exportData = [
                'exported_at' => now()->toIso8601String(),
                'account_info' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'gender' => $user->gender,
                    'city' => $user->city,
                    'bio' => $user->bio,
                    'is_active' => $user->is_active,
                    'created_at' => $user->created_at->toIso8601String(),
                    'roles' => $user->roles->pluck('name'),
                    'permissions' => $user->permissions->pluck('name'),
                ],
                'projects' => $projects->map(fn($p) => [
                    'name' => $p->name,
                    'description' => $p->description,
                    'status' => $p->status,
                    'created_at' => $p->created_at->toIso8601String(),
                ]),
                'documents' => $documents->map(fn($d) => [
                    'title' => $d->title,
                    'description' => $d->description,
                    'created_at' => $d->created_at->toIso8601String(),
                ]),
                'notes' => $notes->map(fn($n) => [
                    'title' => $n->title,
                    'category' => $n->category,
                    'priority' => $n->priority,
                    'content' => $n->content,
                    'created_at' => $n->created_at->toIso8601String(),
                ]),
                'activities' => $activities->map(fn($a) => [
                    'event' => $a->event,
                    'description' => $a->description,
                    'created_at' => $a->created_at->toIso8601String(),
                ]),
            ];

            $fileName = 'backup_data_user_' . $user->username . '_' . date('Ymd_His') . '.json';
            
            return response()->streamDownload(function () use ($exportData) {
                echo json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }, $fileName, [
                'Content-Type' => 'application/json',
            ]);
        } catch (\Exception $e) {
            abort(500, 'Gagal mengekspor data Anda: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus akun secara permanen.
     */
    public function deleteAccount(\Illuminate\Http\Request $request)
    {
        try {
            $user = User::findOrFail(user('id'));

            // Log activity before deleting
            activity()
                ->performedOn($user)
                ->causedBy($user)
                ->log('Menghapus akun secara permanen');

            // Hapus data terkait dengan transaksi database agar aman
            \DB::transaction(function() use ($user) {
                // Hapus catatan milik user
                \App\Models\Catatan::where('user_id', $user->id)->delete();
                // Hapus laporan milik user
                \App\Models\Laporan::where('user_id', $user->id)->delete();
                // Hapus diskusi milik user
                \App\Models\Diskusi::where('user_id', $user->id)->delete();
                // Putuskan keterkaitan proyek yang dibuat user (set null agar proyek tim tidak hilang)
                \App\Models\Project::where('created_by', $user->id)->update(['created_by' => null]);
                
                // Hapus media terkait jika ada
                $user->clearMediaCollection('avatar');

                // Hapus user
                $user->delete();
            });

            // Log out user
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Akun Anda telah berhasil dihapus secara permanen.',
                'redirect' => route('register')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus akun secara permanen: ' . $e->getMessage()
            ], 500);
        }
    }
}
