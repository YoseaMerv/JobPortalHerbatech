<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\JobLocation;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with(['company', 'category', 'location'])
            ->latest()
            ->paginate(10);
        
        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $companies = \App\Models\Company::all();
        $categories = JobCategory::active()->get();
        $locations = JobLocation::active()->get();
        
        return view('admin.jobs.create', compact('companies', 'categories', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
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
            'is_featured' => 'boolean',
            'is_remote' => 'boolean',
            'status' => 'required|in:draft,published,closed',
        ]);

        Job::create([
            'company_id' => $request->company_id,
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->title) . '-' . time(),
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
            'is_featured' => $request->has('is_featured'),
            'is_remote' => $request->has('is_remote'),
        ]);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job created successfully.');
    }

    public function show(Job $job)
    {
        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $companies = \App\Models\Company::all();
        $categories = JobCategory::active()->get();
        $locations = JobLocation::active()->get();
        
        return view('admin.jobs.edit', compact('job', 'companies', 'categories', 'locations'));
    }

    public function update(Request $request, Job $job)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
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
            'is_featured' => 'boolean',
            'is_remote' => 'boolean',
            'status' => 'required|in:draft,published,closed,expired',
        ]);

        $job->update([
            'company_id' => $request->company_id,
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
            'is_featured' => $request->has('is_featured'),
            'is_remote' => $request->has('is_remote'),
        ]);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }

    public function approve(Job $job)
    {
        $job->update(['status' => 'published']);
        return back()->with('success', 'Job approved and published.');
    }

    public function reject(Job $job)
    {
        $job->update(['status' => 'closed']);
        return back()->with('success', 'Job rejected and closed.');
    }
}