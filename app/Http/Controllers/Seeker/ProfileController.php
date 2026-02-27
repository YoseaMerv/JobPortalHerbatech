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

        $profile = SeekerProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['is_public' => true]
        );

        $profile->load(['experiences', 'educations', 'skills', 'certificates']);

        // Gabungkan isLocked dan profile ke dalam satu return
        $isLocked = $user->applications()->where('status', 'pending')->exists();

        return view('seeker.profile.edit', compact('user', 'profile', 'isLocked'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->seekerProfile;

        // 1. Proteksi penguncian profil (jika ada lamaran pending)
        if ($user->applications()->where('status', 'pending')->exists()) {
            return redirect()->back()->with('error', 'Profil dikunci karena ada lamaran aktif.');
        }

        // 2. Validasi (Semua field dibuat nullable agar tidak error saat kirim form terpisah)
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'nullable|string|max:20',
            'home_location_details' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'languages' => 'nullable|array',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // 3. Update User (Hanya jika input dikirim)
        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        $user->save();

        // 4. Update Resume (Hanya jika ada file baru)
        if ($request->hasFile('resume')) {
            if ($profile->resume_path) {
                \Storage::disk('public')->delete($profile->resume_path);
            }
            $file = $request->file('resume');
            $profile->resume_path = $file->store('resumes', 'public');
            $profile->resume_filename = $file->getClientOriginalName();
        }

        // 5. SOLUSI DATA NULL: Ambil input dan buang yang kosong
        $profileData = collect([
            'phone' => $request->phone,
            'home_location_details' => $request->home_location_details,
            'summary' => $request->about, // Simpan input 'about' ke kolom 'summary'
            'languages' => $request->languages,
        ])->filter(fn($value) => !is_null($value))->toArray();

        // Hanya jalankan update jika ada data yang dikirim dari form tersebut
        if (!empty($profileData)) {
            $profile->update($profileData);
        }

        return back()->with('success', 'Perubahan berhasil disimpan!');
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
