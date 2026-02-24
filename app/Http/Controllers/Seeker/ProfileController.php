<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\SeekerProfile;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = $user->seekerProfile ?? SeekerProfile::create(['user_id' => $user->id]);
        $educations = $profile->educations ?? [];
        $experiences = $profile->experiences ?? [];
        $skills = $profile->skills()->get();
        $certificates = $profile->certificates()->get();
        
        return view('seeker.profile.edit', compact('user', 'profile', 'educations', 'experiences', 'skills', 'certificates'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $profile = $user->seekerProfile ?? SeekerProfile::create(['user_id' => $user->id]);

        if ($request->hasFile('profile_picture')) {
            if ($profile->profile_picture) {
                Storage::disk('public')->delete($profile->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $profile->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function uploadResume(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $user = auth()->user();
        $profile = $user->seekerProfile ?? SeekerProfile::create(['user_id' => $user->id]);

        if ($profile->resume_path) {
            Storage::disk('public')->delete($profile->resume_path);
        }

        $path = $request->file('resume')->store('resumes', 'public');
        $profile->update(['resume_path' => $path]);

        return back()->with('success', 'Resume uploaded successfully.');
    }

    public function storeEducation(Request $request)
    {
        $validated = $request->validate([
            'degree' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $profile = auth()->user()->seekerProfile ?? SeekerProfile::create(['user_id' => auth()->id()]);
        $profile->educations()->create($validated);

        return back()->with('success', 'Education added successfully.');
    }

    public function destroyEducation(Education $education)
    {
        $education->delete();
        return back()->with('success', 'Education deleted successfully.');
    }

    public function storeExperience(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        $profile = auth()->user()->seekerProfile ?? SeekerProfile::create(['user_id' => auth()->id()]);
        $profile->experiences()->create($validated);

        return back()->with('success', 'Experience added successfully.');
    }

    public function destroyExperience(Experience $experience)
    {
        $experience->delete();
        return back()->with('success', 'Experience deleted successfully.');
    }

    public function storeSkill(Request $request)
    {
        $validated = $request->validate([
            'skill_name' => 'required|string|max:255',
            'proficiency_level' => 'nullable|in:beginner,intermediate,advanced,expert',
        ]);

        $profile = auth()->user()->seekerProfile ?? SeekerProfile::create(['user_id' => auth()->id()]);
        $profile->skills()->create($validated);

        return back()->with('success', 'Skill added successfully.');
    }

    public function destroySkill(Skill $skill)
    {
        $skill->delete();
        return back()->with('success', 'Skill deleted successfully.');
    }

    public function storeCertificate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issued_date' => 'required|date',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $profile = auth()->user()->seekerProfile ?? SeekerProfile::create(['user_id' => auth()->id()]);
        
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('certificates', 'public');
        }

        $profile->certificates()->create($validated);

        return back()->with('success', 'Certificate added successfully.');
    }

    public function destroyCertificate(Certificate $certificate)
    {
        if ($certificate->file_path) {
            Storage::disk('public')->delete($certificate->file_path);
        }
        $certificate->delete();
        return back()->with('success', 'Certificate deleted successfully.');
    }
}
