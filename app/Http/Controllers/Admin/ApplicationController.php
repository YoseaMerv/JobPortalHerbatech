<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applications = JobApplication::with(['job', 'user', 'job.company'])
            ->latest()
            ->paginate(15);
            
        return view('admin.applications.index', compact('applications'));
    }

    public function create()
    {
        $jobs = \App\Models\Job::where('status', 'published')->get();
        $users = \App\Models\User::where('role', 'seeker')->get();
        return view('admin.applications.create', compact('jobs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'user_id' => 'required|exists:users,id',
            'cv_path' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|string',
        ]);

        // Check if already applied
        if (JobApplication::where('job_id', $request->job_id)->where('user_id', $request->user_id)->exists()) {
             return back()->with('error', 'This user has already applied for this job.');
        }

        $path = $request->file('cv_path')->store('resumes', 'public');

        JobApplication::create([
            'job_id' => $request->job_id,
            'user_id' => $request->user_id,
            'cv_path' => $path,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return redirect()->route('admin.applications.index')->with('success', 'Application created successfully.');
    }
    
    public function show(JobApplication $application)
    {
        $application->load(['job', 'user.seekerProfile.experiences', 'user.seekerProfile.educations', 'user.seekerProfile.skills', 'job.company']);
        return view('admin.applications.show', compact('application'));
    }

    public function edit(JobApplication $application)
    {
        $jobs = \App\Models\Job::all(); // Admin can select any job
        $users = \App\Models\User::where('role', 'seeker')->get();
        return view('admin.applications.edit', compact('application', 'jobs', 'users'));
    }

    public function update(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,interview,rejected,accepted',
            'cover_letter' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $application->update([
            'status' => $request->status,
            'cover_letter' => $request->cover_letter,
            'notes' => $request->notes,
        ]);
        
        // Handle CV update if provided? Not prioritizing replacing CV unless requested, keeping it simple.

        return redirect()->route('admin.applications.index')->with('success', 'Application updated successfully.');
    }

    public function destroy(JobApplication $application)
    {
        // Delete CV file
        if ($application->cv_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($application->cv_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($application->cv_path);
        }
        
        $application->delete();
        return redirect()->route('admin.applications.index')->with('success', 'Application deleted successfully.');
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,interview,rejected,accepted',
        ]);

        $application->update(['status' => $request->status]);

        return back()->with('success', 'Application status updated to ' . ucfirst($request->status));
    }

    public function downloadCv(JobApplication $application)
    {
        if (!$application->cv_path || !storage_path('app/public/' . $application->cv_path)) {
            return back()->with('error', 'CV file not found.');
        }

        return response()->download(storage_path('app/public/' . $application->cv_path));
    }
}
