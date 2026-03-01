<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\SeekerProfile;
use App\Models\Experience;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // Menggunakan firstOrCreate untuk memastikan hanya ada 1 profil
        $profile = SeekerProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['is_public' => true]
        );

        // Load relasi agar tidak ada N+1 query
        $profile->load(['experiences', 'educations', 'skills']);

        $isLocked = $user->applications()->where('status', 'pending')->exists();

        // Hitung kelengkapan profil
        $points = 0;
        $totalPoints = 5;

        if ($user->avatar) $points++;
        if ($profile->summary) $points++;
        if ($profile->phone && $profile->home_location_details) $points++;
        if ($profile->experiences->count() > 0 || $profile->educations->count() > 0) $points++;
        if ($profile->resume_path) $points++;

        $completeness = ($points / $totalPoints) * 100;

        return view('seeker.profile.edit', compact('user', 'profile', 'isLocked', 'completeness'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->seekerProfile;

        if ($user->applications()->where('status', 'pending')->exists()) {
            return redirect()->back()->with('error', 'Profil dikunci karena Anda memiliki lamaran aktif.');
        }

        $request->validate([
            'name'                  => 'nullable|string|max:255',
            'avatar'                => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone'                 => 'nullable|string|max:20',
            'home_location_details' => 'nullable|string|max:255',
            'date_of_birth'         => 'nullable|date',
            'gender'                => 'nullable|string|in:Laki-laki,Perempuan',
            'linkedin_url'          => 'nullable|url|max:255',
            'expected_salary'       => 'nullable|numeric',
            'about'                 => 'nullable|string', 
            'languages'             => 'nullable|array',
            'resume'                => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // --- UPDATE TABEL USERS ---
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        
        if ($user->isDirty()) {
            $user->save();
        }

        // --- UPDATE FILE RESUME ---
        if ($request->hasFile('resume')) {
            if ($profile->resume_path) {
                Storage::disk('public')->delete($profile->resume_path);
            }
            $file = $request->file('resume');
            $profile->resume_path = $file->store('resumes', 'public');
            $profile->resume_filename = $file->getClientOriginalName();
        }

        // --- UPDATE TABEL SEEKER PROFILES ---
        // Identitas Dasar
        if ($request->has('phone')) $profile->phone = $request->phone;
        if ($request->has('home_location_details')) $profile->home_location_details = $request->home_location_details;
        if ($request->has('date_of_birth')) $profile->birth_date = $request->date_of_birth;
        if ($request->has('linkedin_url')) $profile->linkedin_url = $request->linkedin_url;
        if ($request->has('expected_salary')) $profile->expected_salary = $request->expected_salary;
        
        // Mapping Gender ke Database Enum
        if ($request->has('gender')) {
            $genderMap = ['Laki-laki' => 'male', 'Perempuan' => 'female'];
            $profile->gender = $genderMap[$request->gender] ?? null;
        }

        // Bio & Skill
        if ($request->has('about')) {
            $profile->summary = $request->about;
        }
        
        // Penting: Kosongkan array jika tidak ada bahasa yang dicentang
        if ($request->has('about') || $request->has('languages')) {
            $profile->languages = $request->languages ?? [];
        }

        if ($profile->isDirty()) {
            $profile->save();
        }

        return back()->with('success', 'Perubahan profil berhasil disimpan!');
    }

    public function storeSkill(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string',
        ]);

        Auth::user()->seekerProfile->skills()->create($request->all());
        return back()->with('success', 'Keahlian berhasil ditambahkan!');
    }

    public function storeExperience(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        Auth::user()->seekerProfile->experiences()->create($validated);
        return back()->with('success', 'Riwayat karier berhasil ditambahkan!');
    }

    public function storeEducation(Request $request)
    {
        $validated = $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Auth::user()->seekerProfile->educations()->create($validated);
        return back()->with('success', 'Riwayat pendidikan berhasil ditambahkan!');
    }

    public function destroyExperience(Experience $experience)
    {
        $this->authorizeOwner($experience->seeker_profile_id);
        $experience->delete();
        return back()->with('success', 'Riwayat karier berhasil dihapus.');
    }

    public function destroyEducation(Education $education)
    {
        $this->authorizeOwner($education->seeker_profile_id);
        $education->delete();
        return back()->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }

    // Helper method untuk cek kepemilikan data
    private function authorizeOwner($profileId)
    {
        if ($profileId !== Auth::user()->seekerProfile->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }
    }
}