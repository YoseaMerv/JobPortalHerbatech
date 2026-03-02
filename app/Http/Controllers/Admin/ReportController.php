<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function jobs(Request $request)
    {
        $stats = [
            'total' => Job::count(),
            'active' => Job::where('status', 'published')->where('deadline', '>=', now())->count(),
            'expired' => Job::where('deadline', '<', now())->count(),
            'by_category' => DB::table('jobs')
                ->join('job_categories', 'jobs.category_id', '=', 'job_categories.id')
                ->select('job_categories.name', DB::raw('count(*) as total'))
                ->groupBy('job_categories.name')
                ->get(),
        ];
        
        return view('admin.reports.jobs', compact('stats'));
    }

    public function applications(Request $request)
    {
        $stats = [
            'total' => JobApplication::count(),
            'pending' => JobApplication::where('status', 'pending')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
            'accepted' => JobApplication::where('status', 'accepted')->count(),
            'daily_applications' => JobApplication::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get(),
        ];

        return view('admin.reports.applications', compact('stats'));
    }

    public function users(Request $request)
    {
        $stats = [
            'total' => User::count(),
            'seekers' => User::where('role', 'seeker')->count(),
            'companies' => User::where('role', 'company')->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.reports.users', compact('stats'));
    }
}