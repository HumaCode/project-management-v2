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
        try {
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
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard index error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            $data = [
                'top_stats' => ['total_projects' => 0, 'total_in_progress' => 0, 'total_documents' => 0, 'upcoming_deadlines_count' => 0],
                'active_projects' => collect(),
                'upcoming_deadlines' => collect(),
                'monthly_projects' => [0, 0, 0, 0, 0, 0],
                'recent_activities' => collect(),
            ];
        }

        $data['title'] = 'Dashboard';
        $data['subtitle'] = 'Ringkasan aktivitas dan performa project Anda';
        
        return view('pages.dashboard.dashboard-dev', $data);
    }
}
