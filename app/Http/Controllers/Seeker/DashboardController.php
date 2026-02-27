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
        $profile = $user->seekerProfile;

        $recentApplications = $user->applications()
            ->with(['job.company'])
            ->latest()
            ->take(5)
            ->get();

        $testInvitation = $recentApplications
            ->whereIn('status', ['test_invited', 'test_in_progress'])
            ->first();

        // 1. Logika perhitungan skor (Pastikan sinkron dengan halaman edit)
        $profileScore = 0;
        if ($user->avatar) $profileScore += 25;
        if ($profile?->summary) $profileScore += 25;
        if ($profile?->resume_path) $profileScore += 25;
        if ($profile && ($profile->experiences->count() > 0 || $profile->educations->count() > 0)) {
            $profileScore += 25;
        }

        // 2. Definisikan data dashboard
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
            'profileScore' => $profileScore, // Masukkan score ke sini
        ];

        // 3. Ambil lowongan unggulan (Pastikan relasi company & location ada)
        $featuredJobs = Job::with(['company', 'location'])
            ->published()
            ->where('is_featured', true)
            ->latest()
            ->take(3)
            ->get();

        // 4. Perbaikan return view (Gunakan titik '.' untuk folder)
        // Jika file Anda berada di resources/views/seeker/dashboard.blade.php gunakan 'seeker.dashboard'
        // Jika file Anda berada di resources/views/seeker/dashboard/index.blade.php gunakan 'seeker.dashboard.index'

        return view('seeker.dashboard.index', compact('data', 'featuredJobs'));
    }
}
