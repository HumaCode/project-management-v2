<?php

namespace App\Interfaces\Dashboard;

interface DashboardServiceInterface
{
    public function getDashboardData(): array;
    public function getRecentActivities(int $perPage = 6);
    public function getActiveProjects(int $perPage = 5);
}
