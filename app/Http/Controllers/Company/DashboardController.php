<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company;
        $data = [
            'totalJobs' => $company->jobs()->count(),
            'activeJobs' => $company->activeJobs()->count(),
            'totalApplications' => JobApplication::whereIn('job_id', $company->jobs()->pluck('id'))->count(),
            'pendingApplications' => JobApplication::whereIn('job_id', $company->jobs()->pluck('id'))
                ->where('status', 'pending')
                ->count(),
            'recentApplications' => JobApplication::whereIn('job_id', $company->jobs()->pluck('id'))
                ->with(['job', 'user'])
                ->latest()
                ->take(5)
                ->get(),
            'recentJobs' => $company->jobs()->latest()->take(5)->get(),
        ];

        return view('company.dashboard.index', compact('data'));
    }
}