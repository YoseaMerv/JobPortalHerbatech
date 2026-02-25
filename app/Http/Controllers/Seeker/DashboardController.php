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

        // Mengambil data statistik dan lamaran terbaru
        $data = [
            'totalApplications' => $user->applications()->count(),
            'pendingApplications' => $user->applications()->where('status', JobApplication::STATUS_PENDING)->count(),
            // Shortlisted sekarang mencakup status 'shortlisted' asli dan status tahap tes
            'shortlistedApplications' => $user->applications()
                ->whereIn('status', [
                    JobApplication::STATUS_SHORTLISTED, 
                    JobApplication::STATUS_TEST_INVITED, 
                    JobApplication::STATUS_TEST_IN_PROGRESS
                ])->count(),
            'acceptedApplications' => $user->applications()->where('status', JobApplication::STATUS_ACCEPTED)->count(),
            
            // Recent applications harus memuat status terbaru agar banner Kraepelin muncul di Blade
            'recentApplications' => $user->applications()
                ->with(['job.company'])
                ->latest()
                ->take(5)
                ->get(),
                
            'savedJobs' => $user->savedJobs()->count(),
        ];

        // Mengambil lowongan unggulan untuk sidebar
        $featuredJobs = Job::featured()->active()->latest()->take(5)->get();

        return view('seeker.dashboard.index', compact('data', 'featuredJobs'));
    }
}