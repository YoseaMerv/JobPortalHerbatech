<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\JobLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Auth::user()->company->jobs()->latest()->paginate(10);
        return view('company.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $categories = JobCategory::active()->get();
        $locations = JobLocation::active()->get();
        
        return view('company.jobs.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:job_categories,id',
            'location_id' => 'required|exists:job_locations,id',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_type' => 'required|in:monthly,yearly,hourly,project',
            'job_type' => 'required|in:full_time,part_time,contract,freelance,internship',
            'experience_level' => 'required|string|max:255',
            'education_level' => 'nullable|in:sd,smp,sma,d3,s1,s2,s3',
            'deadline' => 'nullable|date|after:today',
            'vacancy' => 'required|integer|min:1',
            'is_remote' => 'boolean',
        ]);

        $company = Auth::user()->company;

        $job = $company->jobs()->create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'category_id' => $request->category_id,
            'location_id' => $request->location_id,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'responsibilities' => $request->responsibilities,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'salary_type' => $request->salary_type,
            'job_type' => $request->job_type,
            'experience_level' => $request->experience_level,
            'education_level' => $request->education_level,
            'deadline' => $request->deadline,
            'vacancy' => $request->vacancy,
            'status' => 'draft',
            'is_remote' => $request->has('is_remote'),
        ]);

        return redirect()->route('company.jobs.index')
            ->with('success', 'Job created successfully. It will be reviewed by admin.');
    }

    public function edit(Job $job)
    {
        $this->authorize('update', $job);
        
        $categories = JobCategory::active()->get();
        $locations = JobLocation::active()->get();
        
        return view('company.jobs.edit', compact('job', 'categories', 'locations'));
    }

    public function update(Request $request, Job $job)
    {
        $this->authorize('update', $job);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:job_categories,id',
            'location_id' => 'required|exists:job_locations,id',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_type' => 'required|in:monthly,yearly,hourly,project',
            'job_type' => 'required|in:full_time,part_time,contract,freelance,internship',
            'experience_level' => 'required|string|max:255',
            'education_level' => 'nullable|in:sd,smp,sma,d3,s1,s2,s3',
            'deadline' => 'nullable|date|after:today',
            'vacancy' => 'required|integer|min:1',
            'is_remote' => 'boolean',
            'status' => 'required|in:draft,published,closed,expired',
        ]);

        $job->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'location_id' => $request->location_id,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'responsibilities' => $request->responsibilities,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'salary_type' => $request->salary_type,
            'job_type' => $request->job_type,
            'experience_level' => $request->experience_level,
            'education_level' => $request->education_level,
            'deadline' => $request->deadline,
            'vacancy' => $request->vacancy,
            'status' => $request->status,
            'is_remote' => $request->has('is_remote'),
        ]);

        return redirect()->route('company.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $this->authorize('delete', $job);
        
        $job->delete();
        return redirect()->route('company.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    public function publish(Job $job)
    {
        $this->authorize('update', $job);
        
        $job->update(['status' => 'published']);
        return back()->with('success', 'Job published successfully.');
    }

    public function close(Job $job)
    {
        $this->authorize('update', $job);
        
        $job->update(['status' => 'closed']);
        return back()->with('success', 'Job closed successfully.');
    }
}