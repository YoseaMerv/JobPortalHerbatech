<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedJobController extends Controller
{
    public function index()
    {
        $savedJobs = Auth::user()->savedJobs()->with('company')->latest()->paginate(10);
        return view('seeker.saved-jobs.index', compact('savedJobs'));
    }
}
