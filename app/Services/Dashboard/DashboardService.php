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
        
        // Cache duration: 10 minutes
        return Cache::remember("dashboard_data_v{$version}_" . auth()->id(), 600, function () {
            return [
                'top_stats' => $this->repository->getTopStats(),
                'active_projects' => $this->repository->getActiveProjects(),
                'upcoming_deadlines' => $this->repository->getUpcomingDeadlines(),
                'monthly_projects' => $this->repository->getMonthlyProjectStats(),
                'recent_activities' => $this->repository->getRecentActivities(),
            ];
        });
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
