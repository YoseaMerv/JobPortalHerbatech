<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        // Eager load relasi untuk menghindari N+1 Query
        $query = JobApplication::with(['job.company', 'user']);

        // Filter jika datang dari tombol "Lihat Lamaran" di detail lowongan
        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        // Fitur Pencarian berdasarkan nama pelamar atau judul posisi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('job', function($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Tampilkan 15 data dan bawa parameter query (search/job_id) ke pagination
        $applications = $query->latest()->paginate(15)->appends($request->query());
            
        return view('admin.applications.index', compact('applications'));
    }

    public function create()
    {
        // Hanya ambil lowongan yang statusnya 'published' (Tayang)
        $jobs = Job::with('company')->where('status', 'published')->latest()->get();
        // Hanya ambil user dengan role 'seeker'
        $users = User::where('role', 'seeker')->orderBy('name')->get();
        
        return view('admin.applications.create', compact('jobs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_id'       => 'required|exists:jobs,id',
            'user_id'      => 'required|exists:users,id',
            'cv_path'      => 'required|file|mimes:pdf,doc,docx|max:2048',
            'cover_letter' => 'nullable|string',
        ]);

        // Cek apakah user sudah pernah melamar di posisi ini (mencegah double data)
        if (JobApplication::where('job_id', $request->job_id)->where('user_id', $request->user_id)->exists()) {
             return back()->withInput()->with('error', 'Gagal! Kandidat ini sudah melamar pada lowongan tersebut.');
        }

        $path = $request->file('cv_path')->store('resumes', 'public');

        JobApplication::create([
            'job_id'       => $request->job_id,
            'user_id'      => $request->user_id,
            'cv_path'      => $path,
            'cover_letter' => $request->cover_letter,
            'status'       => 'pending',
        ]);

        return redirect()->route('admin.applications.index')->with('success', 'Lamaran berhasil ditambahkan secara manual.');
    }
    
    public function show(JobApplication $application)
    {
        // Load seluruh relasi profil dan tes kraepelin untuk direview di halaman detail
        $application->load([
            'job.company', 
            'user.seekerProfile.experiences', 
            'user.seekerProfile.educations', 
            'user.seekerProfile.skills',
            'kraepelinTest'
        ]);
        
        return view('admin.applications.show', compact('application'));
    }

    public function edit(JobApplication $application)
    {
        // Karena di UI Edit yang baru kita HANYA mengubah status dan catatan, 
        // kita tidak perlu lagi memuat seluruh data $jobs dan $users (lebih efisien).
        $application->load(['user', 'job']);
        return view('admin.applications.edit', compact('application'));
    }

    public function update(Request $request, JobApplication $application)
    {
        $request->validate([
            'status'       => 'required|in:pending,reviewed,shortlisted,interview,rejected,accepted',
            'cover_letter' => 'nullable|string',
            'notes'        => 'nullable|string',
        ]);

        $application->update([
            'status'       => $request->status,
            'cover_letter' => $request->cover_letter,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('admin.applications.index')->with('success', 'Status dan catatan lamaran berhasil diperbarui.');
    }

    public function destroy(JobApplication $application)
    {
        // Hapus file CV dari disk lokal untuk menghemat memori server
        if ($application->cv_path && Storage::disk('public')->exists($application->cv_path)) {
            Storage::disk('public')->delete($application->cv_path);
        }
        
        // Hapus juga data hasil tes Kraepelin jika lamaran ini dihapus
        if ($application->kraepelinTest) {
            $application->kraepelinTest->delete();
        }
        
        $application->delete();
        return redirect()->route('admin.applications.index')->with('success', 'Lamaran beserta file dokumennya berhasil dihapus.');
    }

    // --- Method Tambahan (Action Singkat) ---

    public function updateStatus(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,interview,rejected,accepted',
        ]);

        $application->update(['status' => $request->status]);

        $statusText = match($request->status) {
            'pending'     => 'Menunggu',
            'reviewed'    => 'Ditinjau',
            'shortlisted' => 'Terpilih',
            'interview'   => 'Wawancara',
            'accepted'    => 'Diterima',
            'rejected'    => 'Ditolak',
            default       => ucfirst($request->status)
        };

        return back()->with('success', 'Status lamaran berhasil diubah menjadi: ' . $statusText);
    }

    public function downloadCv(JobApplication $application)
    {
        if (!$application->cv_path || !Storage::disk('public')->exists($application->cv_path)) {
            return back()->with('error', 'File CV tidak ditemukan di dalam server.');
        }

        // Menggunakan Storage download agar lebih aman dari serangan path traversal
        return Storage::disk('public')->download($application->cv_path);
    }
}