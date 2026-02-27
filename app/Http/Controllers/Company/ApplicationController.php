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
        $application->load([
        'job', 
        'kraepelinTest', 
        'user.seekerProfile.experiences', 
        'user.seekerProfile.educations', 
        'user.seekerProfile.skills'
    ]);
    
    return view('company.applications.show', compact('application'));
    }


    public function updateStatus(Request $request, JobApplication $application)
    {
        try {
            $request->validate([
                'status' => 'required|string',
                'notes' => 'nullable|string'
            ]);

            $application->update([
                'status' => $request->status,
                'notes' => $request->notes ?? $application->notes
            ]);

            // Pastikan mengembalikan JSON dan status 200
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui ke ' . $application->status_label,
                'data' => $application
            ], 200);

        } catch (\Exception $e) {
            // Jika ada error, kembalikan status 422 atau 500
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadCv(JobApplication $application)
    {
        $this->authorizeAccess($application);

        if (!$application->cv_path || !Storage::disk('public')->exists($application->cv_path)) {
            return back()->with('error', 'Resume not found.');
        }

        return Storage::disk('public')->download($application->cv_path);
    }

    public function downloadCover(JobApplication $application)
    {
        if ($application->cover_letter_path && Storage::exists($application->cover_letter_path)) {
            return Storage::download($application->cover_letter_path);
        }
        
        return back()->with('error', 'File tidak ditemukan.');
    }

    private function authorizeAccess(JobApplication $application)
    {
        if ($application->job->company_id !== Auth::user()->company->id) {
            abort(403);
        }
    }
}
