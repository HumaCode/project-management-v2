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
        if (!$user || $user->hasRole(['admin', 'dev'])) {
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
        $user = auth()->user();

        $projectQuery = $this->applyAccessScope(Project::query());

        // Optimize project counts in one single roundtrip
        $counts = (clone $projectQuery)->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_count,
            SUM(CASE WHEN status != 'done' AND deadline BETWEEN ? AND ? THEN 1 ELSE 0 END) as upcoming_count
        ", [$now->toDateTimeString(), $threeDaysFromNow->toDateTimeString()])->first();

        // Optimize document count using a database subquery
        $totalDocuments = Dokumen::whereIn('project_id', (clone $projectQuery)->select('id'))->count();

        return [
            'total_projects' => (int) ($counts->total ?? 0),
            'total_in_progress' => (int) ($counts->in_progress_count ?? 0),
            'total_documents' => $totalDocuments,
            'upcoming_deadlines_count' => (int) ($counts->upcoming_count ?? 0),
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

        if ($user && !$user->hasRole(['admin', 'dev'])) {
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

        if ($user && !$user->hasRole(['admin', 'dev'])) {
            $projectQuery = $this->applyAccessScope(Project::query())->select('id');
            
            $query->where(function($q) use ($projectQuery) {
                // Aktivitas pada project itu sendiri
                $q->where(function($sq) use ($projectQuery) {
                    $sq->where('subject_type', Project::class)
                       ->whereIn('subject_id', $projectQuery);
                })
                // Aktivitas pada dokumen di project tersebut
                ->orWhere(function($sq) use ($projectQuery) {
                    $sq->where('subject_type', Dokumen::class)
                       ->whereIn('subject_id', function($ssq) use ($projectQuery) {
                           $ssq->select('id')->from('dokumens')->whereIn('project_id', $projectQuery);
                       });
                })
                // Aktivitas diskusi
                ->orWhere(function($sq) use ($projectQuery) {
                    $sq->where('subject_type', Diskusi::class)
                       ->whereIn('subject_id', function($ssq) use ($projectQuery) {
                           $ssq->select('id')->from('diskusis')->whereIn('project_id', $projectQuery);
                       });
                });
            });
        }

        return $query->paginate($perPage, ['*'], 'act_page');
    }
}
