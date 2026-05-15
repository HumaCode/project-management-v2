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
    private function applyAccessScope($query)
    {
        $user = auth()->user();
        if ($user->hasRole(['admin', 'dev'])) {
            return $query;
        }

        return $query->where(function($q) use ($user) {
            $q->where('created_by', $user->id)
              ->orWhereHas('team.members', function($sq) use ($user) {
                  $sq->where('users.id', $user->id);
              });
        });
    }

    public function getTopStats(): array
    {
        $now = now();
        $threeDaysFromNow = now()->addDays(3);

        $projectQuery = $this->applyAccessScope(Project::query());
        $projectIds = (clone $projectQuery)->pluck('id');

        return [
            'total_projects' => (clone $projectQuery)->count(),
            'total_in_progress' => (clone $projectQuery)->where('status', 'in_progress')->count(),
            'total_documents' => Dokumen::whereIn('project_id', $projectIds)->count(),
            'upcoming_deadlines_count' => (clone $projectQuery)->where('status', '!=', 'done')
                ->whereBetween('deadline', [$now, $threeDaysFromNow])
                ->count(),
        ];
    }

    public function getActiveProjects(int $perPage = 5)
    {
        $query = Project::with(['team.members'])
            ->withCount('dokumens')
            ->where('status', '!=', 'done')
            ->orderBy('created_at', 'desc');

        $projects = $this->applyAccessScope($query)
            ->paginate($perPage, ['*'], 'proj_page');

        $projects->getCollection()->transform(function($project) {
            $project->members_count = $project->team ? $project->team->members->count() : 0;
            return $project;
        });

        return $projects;
    }

    public function getUpcomingDeadlines(int $limit = 5): \Illuminate\Support\Collection
    {
        $query = Project::where('status', '!=', 'done')
            ->where('deadline', '>=', now())
            ->orderBy('deadline', 'asc');

        return $this->applyAccessScope($query)
            ->limit($limit)
            ->get();
    }

    public function getMonthlyProjectStats(): array
    {
        $user = auth()->user();
        $query = DB::table('projects')
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth());

        if (!$user->hasRole(['admin', 'dev'])) {
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhereExists(function ($sq) use ($user) {
                      $sq->select(DB::raw(1))
                          ->from('teams')
                          ->join('team_user', 'teams.id', '=', 'team_user.team_id')
                          ->whereColumn('teams.id', 'projects.team_id')
                          ->where('team_user.user_id', $user->id);
                  });
            });
        }

        $stats = $query->groupBy('year', 'month')
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

    public function getRecentActivities(int $perPage = 6)
    {
        $user = auth()->user();
        $query = Activity::with(['causer', 'subject'])->latest();

        if (!$user->hasRole(['admin', 'dev'])) {
            $projectIds = $this->applyAccessScope(Project::query())->pluck('id');
            
            $query->where(function($q) use ($projectIds) {
                // Aktivitas pada project itu sendiri
                $q->where(function($sq) use ($projectIds) {
                    $sq->where('subject_type', Project::class)
                       ->whereIn('subject_id', $projectIds);
                })
                // Aktivitas pada dokumen di project tersebut
                ->orWhere(function($sq) use ($projectIds) {
                    $sq->where('subject_type', Dokumen::class)
                       ->whereIn('subject_id', function($ssq) use ($projectIds) {
                           $ssq->select('id')->from('dokumens')->whereIn('project_id', $projectIds);
                       });
                })
                // Aktivitas diskusi (jika ada model Diskusi/Catatan)
                ->orWhere(function($sq) use ($projectIds) {
                    $sq->whereIn('subject_id', function($ssq) use ($projectIds) {
                        $ssq->select('id')->from('diskusis')->whereIn('project_id', $projectIds);
                    });
                });
            });
        }

        return $query->paginate($perPage, ['*'], 'act_page');
    }
}
