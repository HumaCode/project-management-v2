<?php

namespace App\Http\Controllers;

use App\Interfaces\Dashboard\DashboardServiceInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardServiceInterface $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        // AJAX Pagination for Activities
        if ($request->ajax() && $request->has('act_page')) {
            $activities = $this->dashboardService->getRecentActivities();
            return view('pages.dashboard.partials.recent-activities', [
                'recent_activities' => $activities
            ])->render();
        }

        // AJAX Pagination for Active Projects
        if ($request->ajax() && $request->has('proj_page')) {
            $projects = $this->dashboardService->getActiveProjects();
            return view('pages.dashboard.partials.active-projects', [
                'active_projects' => $projects
            ])->render();
        }

        $data = $this->dashboardService->getDashboardData();
        $data['title'] = 'Dashboard';
        $data['subtitle'] = 'Ringkasan aktivitas dan performa project Anda';
        
        return view('pages.dashboard.dashboard-dev', $data);
    }
}
