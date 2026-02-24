<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Auth::user()->applications()->with('job.company')->latest()->paginate(10);
        return view('seeker.applications.index', compact('applications'));
    }

    public function show(JobApplication $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }
        return view('seeker.applications.show', compact('application'));
    }

    public function destroy(JobApplication $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        if ($application->status !== 'pending') {
            return back()->with('error', 'Cannot cancel application that is already processed.');
        }

        $application->delete();
        return back()->with('success', 'Application cancelled.');
    }
}
