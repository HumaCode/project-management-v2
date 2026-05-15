<?php

namespace App\Repositories\Dashboard;

interface DashboardRepositoryInterface
{
    public function getTopStats(): array;
    public function getActiveProjects(int $limit = 5): \Illuminate\Support\Collection;
    public function getUpcomingDeadlines(int $limit = 5): \Illuminate\Support\Collection;
    public function getMonthlyProjectStats(): array;
    public function getRecentActivities(int $limit = 6): \Illuminate\Support\Collection;
}
