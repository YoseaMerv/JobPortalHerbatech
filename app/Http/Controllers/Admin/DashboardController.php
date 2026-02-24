<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalSeekers' => User::where('role', 'seeker')->count(),
            'totalJobs' => Job::count(),
            'totalApplications' => JobApplication::count(),
            'pendingJobs' => Job::where('status', 'draft')->count(),
            'recentJobs' => Job::with('company')->latest()->take(5)->get(),
            'recentApplications' => JobApplication::with(['job', 'user'])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard.index', compact('data'));
    }

    public function statistics(Request $request)
    {
        $period = $request->get('period', 'monthly');
        
        // Statistics logic here
        return response()->json([
            'jobs' => $this->getJobStatistics($period),
            'applications' => $this->getApplicationStatistics($period),
            'users' => $this->getUserStatistics($period),
        ]);
    }

    private function getJobStatistics($period)
    {
        // Implement statistics logic
        return [];
    }

    private function getApplicationStatistics($period)
    {
        // Implement statistics logic
        return [];
    }

    private function getUserStatistics($period)
    {
        // Implement statistics logic
        return [];
    }
}