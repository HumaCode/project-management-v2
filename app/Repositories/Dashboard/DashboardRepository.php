<?php

namespace App\Repositories\Dashboard;

use App\Models\Project;
use App\Models\Dokumen;
use App\Models\Diskusi;
use App\Models\Team;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getTopStats(): array
    {
        $now = now();
        $threeDaysFromNow = now()->addDays(3);

        return [
            'total_projects' => Project::count(),
            'total_in_progress' => Project::where('status', 'in_progress')->count(),
            'total_documents' => Dokumen::count(),
            'upcoming_deadlines_count' => Project::where('status', '!=', 'done')
                ->whereBetween('deadline', [$now, $threeDaysFromNow])
                ->count(),
        ];
    }

    public function getActiveProjects(int $limit = 5): \Illuminate\Support\Collection
    {
        return Project::with(['team.members'])
            ->withCount('dokumens')
            ->where('status', '!=', 'done')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($project) {
                $project->members_count = $project->team ? $project->team->members->count() : 0;
                return $project;
            });
    }

    public function getUpcomingDeadlines(int $limit = 5): \Illuminate\Support\Collection
    {
        return Project::where('status', '!=', 'done')
            ->where('deadline', '>=', now())
            ->orderBy('deadline', 'asc')
            ->limit($limit)
            ->get();
    }

    public function getMonthlyProjectStats(): array
    {
        $stats = DB::table('projects')
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('year', 'month')
            ->get()
            ->keyBy(function($item) {
                return $item->year . '-' . $item->month;
            });

        // Fill missing months
        $result = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = now()->subMonths($i);
            $key = $dt->year . '-' . $dt->month;
            $result[] = $stats[$key]->count ?? 0;
        }

        return $result;
    }

    public function getRecentActivities(int $limit = 6): \Illuminate\Support\Collection
    {
        return Activity::with('causer')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
