<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JobCategory;
use App\Models\JobLocation;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('company')->where('status', 'published');

        // Filter berdasarkan keyword
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhereHas('company', function ($c) use ($request) {
                        // PERBAIKAN: Ganti 'name' menjadi 'company_name' sesuai tabel companies
                        $c->where('company_name', 'like', '%' . $request->keyword . '%');
                    });
            });
        }

        // Filter Lokasi
        if ($request->filled('location')) {
            $query->where('location_id', $request->location);
        }

        // Filter Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $jobs = $query->latest()->paginate(12);
        $locations = JobLocation::all();

        return view('seeker.jobs.index', compact('jobs', 'locations'));
    }

    public function show(Job $job)
    {
        if ($job->status !== 'published') {
            abort(404);
        }

        $user = auth()->user();
        $profile = $user->seekerProfile;

        // AUDIT DATA: Cek satu per satu ketersediaan data di database
        $profileStatus = [
            'foto'      => !empty($user->avatar),
            'deskripsi' => !empty($profile?->summary),
            'resume'    => !empty($profile?->resume_path),
            'riwayat'   => ($profile?->experiences->count() > 0 || $profile?->educations->count() > 0)
        ];

        $isProfileComplete = $profileStatus['foto'] &&
            $profileStatus['deskripsi'] &&
            $profileStatus['resume'];

        $hasApplied = $user->applications()->where('job_id', $job->id)->exists();
        $isAcceptedSomewhere = $user->applications()->where('status', 'accepted')->exists();
        $hasActiveApplication = $user->applications()->whereNotIn('status', ['rejected', 'accepted'])->exists();

        // --- TAMBAHKAN LOGIKA INI ---
        $isSaved = $user->savedJobs()->where('jobs.id', $job->id)->exists();
        // ----------------------------

        $job->increment('views');

        return view('seeker.jobs.show', compact(
            'job',
            'isProfileComplete',
            'profileStatus',
            'hasApplied',
            'isAcceptedSomewhere',
            'hasActiveApplication',
            'isSaved' // --- PASTIKAN INI MASUK KE COMPACT ---
        ));
    }

    public function showApplyForm(Job $job)
    {
        if ($job->status !== 'published') {
            abort(404);
        }

        $user = Auth::user();
        $profile = $user->seekerProfile;
        $isProfileComplete = $profile &&
            $profile->summary &&
            $profile->resume_path &&
            $user->avatar;

        // Validasi Kelengkapan Profil
        if (!$isProfileComplete) {
            return redirect()->route('seeker.jobs.show', $job)
                ->with('error', 'Lengkapi profil Anda terlebih dahulu sebelum melamar.');
        }

        // VALIDASI: Gunakan logic whereNotIn agar konsisten dengan tombol di View
        $hasActiveApplication = $user->applications()
            ->whereNotIn('status', ['rejected', 'accepted'])
            ->exists();

        if ($hasActiveApplication) {
            return redirect()->route('seeker.jobs.show', $job)
                ->with('error', 'Anda memiliki lamaran yang sedang diproses.');
        }

        // Cek jika sudah pernah melamar pekerjaan ini sebelumnya
        if ($user->applications()->where('job_id', $job->id)->exists()) {
            return redirect()->route('seeker.jobs.show', $job)
                ->with('error', 'Anda sudah melamar posisi ini.');
        }

        $profile = $user->seekerProfile;
        return view('seeker.jobs.apply', compact('job', 'user', 'profile'));
    }

    public function submitApplication(Request $request, Job $job)
    {
        $user = Auth::user();
        $profile = $user->seekerProfile;

        // 1. VALIDASI: Kelengkapan Profil (Foto, About, dan Resume)
        $isProfileComplete = $profile &&
            $profile->summary &&
            $profile->resume_path &&
            $user->avatar;

        if (!$isProfileComplete) {
            return redirect()->route('seeker.jobs.show', $job)
                ->with('error', 'Profil Anda belum lengkap. Mohon lengkapi foto, deskripsi, dan resume sebelum melamar.');
        }

        // 2. VALIDASI: Cek apakah sudah diterima di posisi lain
        $isAcceptedSomewhere = $user->applications()->where('status', 'accepted')->exists();
        if ($isAcceptedSomewhere) {
            return redirect()->route('seeker.dashboard')
                ->with('error', 'Anda sudah diterima di sebuah posisi dan tidak dapat melamar lagi.');
        }

        // 3. VALIDASI: Cek apakah ada lamaran lain yang sedang diproses (Status selain rejected/accepted)
        $hasActiveApplication = $user->applications()
            ->whereNotIn('status', ['rejected', 'accepted'])
            ->exists();

        if ($hasActiveApplication) {
            return redirect()->route('seeker.jobs.show', $job)
                ->with('error', 'Anda memiliki lamaran lain yang sedang diproses. Selesaikan terlebih dahulu.');
        }

        // 4. VALIDASI: Input Form (CV, Cover Letter, dan Jawaban Pertanyaan)
        $request->validate([
            'resume' => 'required_without:use_existing_resume|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'q1' => 'required',
            'q2' => 'required',
            'q3' => 'required',
            'q4' => 'required',
            'q5' => 'required|numeric',
            'q6' => 'required',
            'q7' => 'required',
            'q8' => 'required',
            'q9' => 'required',
            'q10' => 'required',
            'q11' => 'required',
            'q12' => 'required',
            'q13' => 'required',
            'q14' => 'required',
            'q15' => 'required|date',
        ]);

        // 5. PENENTUAN PATH FILE (Resume & Cover Letter)
        if ($request->has('use_existing_resume') && $profile->resume_path) {
            $cvPath = $profile->resume_path;
        } else {
            $cvPath = $request->file('resume')->store('resumes', 'public');
        }

        $clPath = $request->file('cover_letter_file')->store('cover_letters', 'public');

        // 6. EKSEKUSI: Simpan Data Lamaran
        $answers = $request->only(['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12', 'q13', 'q14', 'q15']);

        $user->applications()->create([
            'job_id' => $job->id,
            'cv_path' => $cvPath,
            'cover_letter_path' => $clPath,
            'answers' => $answers,
            'status' => 'pending'
        ]);

        return redirect()->route('seeker.jobs.index')->with('success', 'Lamaran Anda berhasil dikirim!');
    }

    public function save(Job $job)
    {
        Auth::user()->savedJobs()->syncWithoutDetaching([$job->id]);
        return back()->with('success', 'Lowongan berhasil disimpan.');
    }

    public function unsave(Job $job)
    {
        Auth::user()->savedJobs()->detach($job->id);
        return back()->with('success', 'Lowongan dihapus dari daftar simpan.');
    }
}
