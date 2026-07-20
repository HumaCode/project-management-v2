<?php

namespace App\Services\Dashboard;

use App\Interfaces\Dashboard\DashboardServiceInterface;
use App\Repositories\Dashboard\DashboardRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class DashboardService implements DashboardServiceInterface
{
    protected $repository;

    public function __construct(DashboardRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getDashboardData(): array
    {
        $version = Cache::get('project_cache_version', 1);
        $userId = auth()->id() ?? 'guest';
        
        // Cache duration: 10 minutes
        $data = Cache::remember("dashboard_data_v{$version}_" . $userId, 600, function () {
            return [
                'top_stats' => $this->repository->getTopStats(),
                'active_projects' => $this->repository->getActiveProjects(),
                'upcoming_deadlines' => $this->repository->getUpcomingDeadlines(),
                'monthly_projects' => $this->repository->getMonthlyProjectStats(),
                'recent_activities' => $this->repository->getRecentActivities(),
            ];
        });

        return array_merge([
            'top_stats' => ['total_projects' => 0, 'total_in_progress' => 0, 'total_documents' => 0, 'upcoming_deadlines_count' => 0],
            'active_projects' => collect(),
            'upcoming_deadlines' => collect(),
            'monthly_projects' => [0, 0, 0, 0, 0, 0],
            'recent_activities' => collect(),
        ], is_array($data) ? $data : []);
    }

    public function getRecentActivities(int $perPage = 6)
    {
        // We don't cache this as it's often paginated and dynamic
        return $this->repository->getRecentActivities($perPage);
    }

    public function getActiveProjects(int $perPage = 5)
    {
        // We don't cache this as it's often paginated and dynamic
        return $this->repository->getActiveProjects($perPage);
    }
}
