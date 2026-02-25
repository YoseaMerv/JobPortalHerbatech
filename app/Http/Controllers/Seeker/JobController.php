<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobCategory; 
use App\Models\JobLocation;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // Eager load company untuk menghindari N+1 query
        $query = Job::with('company')->where('status', 'published');

        // Filter berdasarkan keyword judul atau nama perusahaan
        if ($request->filled('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                  ->orWhereHas('company', function ($c) use ($request) {
                      $c->where('name', 'like', '%' . $request->keyword . '%');
                  });
            });
        }

        // Filter Lokasi
        if ($request->filled('location')) {
            $query->where('location_id', $request->location);
        }

        // Filter Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $jobs = $query->latest()->paginate(12);

        return view('seeker.jobs.index', compact('jobs'));
    }

    public function show(Job $job)
    {
        if ($job->status !== 'published') {
            abort(404);
        }

        $job->increment('views');
        $hasApplied = Auth::user()->applications()->where('job_id', $job->id)->exists();
        $isSaved = Auth::user()->savedJobs()->where('jobs.id', $job->id)->exists();

        return view('seeker.jobs.show', compact('job', 'hasApplied', 'isSaved'));
    }

    
    public function apply(Request $request, Job $job)
    {   
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->applications()->where('job_id', $job->id)->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // Check if user has a resume
        if (!$user->seekerProfile || !$user->seekerProfile->resume_path) {
            return redirect()->route('seeker.profile.edit')->with('error', 'Please upload your resume before applying.');
        }

        $user->applications()->create([
            'job_id' => $job->id,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending',
            'cv_path' => $user->seekerProfile->resume_path // Snapshot resume at time of application
        ]);

        return back()->with('success', 'Application submitted successfully.');
    }

    public function save(Job $job)
    {
        Auth::user()->savedJobs()->syncWithoutDetaching([$job->id]);
        return back()->with('success', 'Job saved.');
    }

    public function unsave(Job $job)
    {
        Auth::user()->savedJobs()->detach($job->id);
        return back()->with('success', 'Job removed from saved list.');
    }
}
