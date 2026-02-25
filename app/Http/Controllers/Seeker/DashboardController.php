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

        $featuredJobs = Job::featured()->active()->latest()->take(5)->get();

        $profile = $user->seekerProfile;

        // Logika perhitungan poin kelengkapan profil
        $points = 0;
        $totalPoints = 5;

        if ($profile->summary) $points++;
        if ($profile->phone) $points++;
        if ($profile->experiences()->exists()) $points++;
        if ($profile->educations()->exists()) $points++;
        if ($profile->resume_path) $points++;

        $profilePercentage = ($points / $totalPoints) * 100;

        // PERBAIKAN: Kirim semua variabel dalam satu return view
        return view('seeker.dashboard.index', compact('data', 'featuredJobs', 'profilePercentage'));
    }
}
