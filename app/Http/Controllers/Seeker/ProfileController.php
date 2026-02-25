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

        // Mengambil profil atau membuat baru jika belum ada datanya di database
        $profile = SeekerProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['is_public' => true] // Nilai default
        );

        // Eager load relasi setelah profil dipastikan ada
        $profile->load(['experiences', 'educations', 'skills', 'certificates']);

        return view('seeker.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->seekerProfile;

        // Pastikan validasi mencakup semua field yang mungkin dikirim
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'home_location_details' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'languages' => 'nullable|array',
            'resume' => 'nullable|file|mimes:pdf,doc,docx,txt,rtf|max:5120',
        ]);

        // Update Nama User jika ada di request
        if ($request->has('name')) {
            $user->update(['name' => $validated['name']]);
        }

        // Handle Upload Resume (Poin 10)
        if ($request->hasFile('resume')) {
            if ($profile->resume_path) {
                Storage::disk('public')->delete($profile->resume_path);
            }
            $file = $request->file('resume');
            $path = $file->store('resumes', 'public');
            $profile->resume_path = $path;
            $profile->resume_filename = $file->getClientOriginalName();
            $profile->save();
        }

        // Update data profil dengan aman (hanya data yang ada di request)
        // Ini mencegah error "Undefined array key"
        $profile->update($request->only([
            'phone', 
            'home_location_details', 
            'summary', 
            'languages'
        ]));

        return back()->with('success', 'Profil berhasil diperbarui!');
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

        // Ambil atau buat profil, lalu simpan data sekali saja
        $profile = SeekerProfile::firstOrCreate(['user_id' => Auth::id()]);
        $profile->experiences()->create($validated);

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
        
        // Pastikan profil seeker ada
        $profile = SeekerProfile::firstOrCreate(['user_id' => Auth::id()]);

        // Simpan data melalui relasi
        $profile->educations()->create($validated);

        return back()->with('success', 'Riwayat pendidikan berhasil ditambahkan!');
    }

    public function destroyExperience(Experience $experience)
    {
        // Pastikan hanya pemilik yang bisa menghapus
        if ($experience->seeker_profile_id !== auth()->user()->seekerProfile->id) {
            abort(403);
        }
        
        $experience->delete();
        return back()->with('success', 'Riwayat karier berhasil dihapus.');
    }

    public function destroyEducation(Education $education)
    {
        if ($education->seeker_profile_id !== auth()->user()->seekerProfile->id) {
            abort(403);
        }

        $education->delete();
        return back()->with('success', 'Riwayat pendidikan berhasil dihapus.');
    }
}