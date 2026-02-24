<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [
            'totalApplications' => $user->applications()->count(),
            'pendingApplications' => $user->applications()->where('status', 'pending')->count(),
            'shortlistedApplications' => $user->applications()->where('status', 'shortlisted')->count(),
            'acceptedApplications' => $user->applications()->where('status', 'accepted')->count(),
            'recentApplications' => $user->applications()->with(['job.company'])->latest()->take(5)->get(),
            'savedJobs' => $user->savedJobs()->count(),
        ];

        $featuredJobs = Job::featured()->active()->latest()->take(5)->get();

        return view('seeker.dashboard.index', compact('data', 'featuredJobs'));
    }
}