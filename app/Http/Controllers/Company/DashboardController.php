<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Company; // Pastikan ini diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Gunakan firstOrCreate agar jika data company belum ada, sistem akan membuatnya otomatis
        // Pastikan menyertakan 'company_email' agar tidak terkena error SQL Field 'company_email' doesn't have a default value
        $company = Company::firstOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $user->name,
                'company_email' => $user->email,
                'slug' => Str::slug($user->name . '-' . uniqid()),
                'company_profile_url' => Str::slug($user->name . '-' . uniqid()), // TAMBAHKAN BARIS INI
                'status' => 'pending'
            ]
        );

        // Ambil ID semua pekerjaan milik perusahaan ini untuk mempermudah filter aplikasi
        $jobIds = $company->jobs()->pluck('id');

        $data = [
            'totalJobs' => $company->jobs()->count(),
            // Menggunakan where status published jika scopeActiveJobs belum ada di model Company
            'activeJobs' => $company->jobs()->where('status', 'published')->count(),
            
            'totalApplications' => JobApplication::whereIn('job_id', $jobIds)->count(),
            
            'pendingApplications' => JobApplication::whereIn('job_id', $jobIds)
                ->where('status', 'pending')
                ->count(),
                
            'recentApplications' => JobApplication::whereIn('job_id', $jobIds)
                ->with(['job', 'user'])
                ->latest()
                ->take(5)
                ->get(),
                
            'recentJobs' => $company->jobs()->latest()->take(5)->get(),
            'company' => $company
        ];

        // Pastikan path view sesuai, jika file Anda ada di company/dashboard/index.blade.php
        return view('company.dashboard.index', compact('data'));
    }
}