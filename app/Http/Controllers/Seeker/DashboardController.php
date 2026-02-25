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

        // Mengumpulkan data statistik
        $data = [
            'totalApplications' => $user->applications()->count(),
            'pendingApplications' => $user->applications()->where('status', 'pending')->count(),
            'shortlistedApplications' => $user->applications()->where('status', 'shortlisted')->count(),
            'acceptedApplications' => $user->applications()->where('status', 'accepted')->count(),
            'recentApplications' => $user->applications()->with(['job.company'])->latest()->take(5)->get(),
            'savedJobs' => $user->savedJobs()->count(),
        ];

        // Mengambil lowongan unggulan
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
