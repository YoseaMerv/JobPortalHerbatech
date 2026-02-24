<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index()
    {
        $company = Auth::user()->company;
        $applications = JobApplication::whereIn('job_id', $company->jobs()->pluck('id'))
            ->with(['job', 'user'])
            ->latest()
            ->paginate(15);

        return view('company.applications.index', compact('applications'));
    }

    public function show(JobApplication $application)
    {
        $this->authorizeAccess($application);
        $application->load(['job', 'user.seekerProfile.experiences', 'user.seekerProfile.educations', 'user.seekerProfile.skills']);
        return view('company.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $this->authorizeAccess($application);
        
        $request->validate([
            'status' => 'required|in:pending,shortlisted,rejected,accepted',
        ]);

        $application->update(['status' => $request->status]);

        return back()->with('success', 'Application status updated.');
    }

    public function downloadCv(JobApplication $application)
    {
        $this->authorizeAccess($application);

        if (!$application->cv_path || !Storage::disk('public')->exists($application->cv_path)) {
            return back()->with('error', 'Resume not found.');
        }

        return Storage::disk('public')->download($application->cv_path);
    }

    private function authorizeAccess(JobApplication $application)
    {
        if ($application->job->company_id !== Auth::user()->company->id) {
            abort(403);
        }
    }
}
