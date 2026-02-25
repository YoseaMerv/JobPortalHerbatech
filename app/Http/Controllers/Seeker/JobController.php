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

        $job->increment('views');
        $hasApplied = Auth::user()->applications()->where('job_id', $job->id)->exists();
        $isSaved = Auth::user()->savedJobs()->where('jobs.id', $job->id)->exists();

        return view('seeker.jobs.show', compact('job', 'hasApplied', 'isSaved'));
    }

    public function showApplyForm(Job $job)
    {
        $user = Auth::user();

        // VALIDASI: Cek apakah user sedang memiliki lamaran aktif (status pending)
        $hasActiveApplication = $user->applications()->where('status', 'pending')->exists();
        if ($hasActiveApplication) {
            return redirect()->route('seeker.jobs.show', $job)
                ->with('error', 'Anda memiliki lamaran yang sedang diproses. Tunggu hingga diterima/ditolak untuk melamar posisi lain.');
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

        // 1. Validasi dengan pesan kustom agar user tahu apa yang salah
        $request->validate([
            'resume' => 'required_without:use_existing_resume|file|mimes:pdf,doc,docx,rtf|max:5120',
            'cover_letter_file' => 'required|file|mimes:pdf,doc,docx,rtf|max:5120',
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

        // 2. Tentukan Path Resume
        if ($request->has('use_existing_resume') && $user->seekerProfile->resume_path) {
            $cvPath = $user->seekerProfile->resume_path;
        } else {
            $cvPath = $request->file('resume')->store('resumes', 'public');
        }

        $clPath = $request->file('cover_letter_file')->store('cover_letters', 'public');

        // 3. Simpan Jawaban
        $answers = $request->only(['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12', 'q13', 'q14', 'q15']);

        $user->applications()->create([
            'job_id' => $job->id,
            'cv_path' => $cvPath,
            'cover_letter_path' => $clPath,
            'answers' => $answers,
            'status' => 'pending'
        ]);

        return redirect()->route('seeker.jobs.index')->with('success', 'Lamaran berhasil dikirim!');
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
