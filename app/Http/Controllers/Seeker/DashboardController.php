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

        // 1. Definisikan variabel $data (Penyebab Error Sebelumnya)
        $data = [
            'totalApplications' => $user->applications()->count(),
            'pendingApplications' => $user->applications()->where('status', 'pending')->count(),
            'shortlistedApplications' => $user->applications()
                ->whereIn('status', ['shortlisted', 'test_invited', 'test_in_progress'])
                ->count(),
            'acceptedApplications' => $user->applications()->where('status', 'accepted')->count(),
            'recentApplications' => $user->applications()
                ->with(['job.company'])
                ->latest()
                ->take(5)
                ->get(),
            'savedJobs' => $user->savedJobs()->count(),
        ];

        // 2. Ambil Lowongan Unggulan
        $featuredJobs = Job::with(['company', 'location'])->published()->latest()->take(5)->get();

        // 3. Logika perhitungan profil (dengan pengecekan null)
        $profile = $user->seekerProfile;
        $points = 0;
        $totalPoints = 5;

        if ($profile) {
            if ($profile->summary) $points++;
            if ($profile->phone) $points++;
            if ($profile->experiences()->exists()) $points++;
            if ($profile->educations()->exists()) $points++;
            if ($profile->resume_path) $points++;
        }

        $profilePercentage = ($points / $totalPoints) * 100;

        // 4. Kirim semua variabel ke view
        return view('seeker.dashboard.index', compact('data', 'featuredJobs', 'profilePercentage'));
    }
}
