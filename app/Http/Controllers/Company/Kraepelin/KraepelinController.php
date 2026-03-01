<?php

namespace App\Http\Controllers\Company\Kraepelin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\KraepelinTest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KraepelinController extends Controller
{
    /**
     * Menampilkan instruksi tes untuk kandidat.
     */
    public function showInstructions($applicationId)
    {
        $application = JobApplication::with('job.company')->findOrFail($applicationId);

        // Cek apakah status mengizinkan akses (Invited atau In Progress)
        if (!in_array($application->status, [JobApplication::STATUS_TEST_INVITED, JobApplication::STATUS_TEST_IN_PROGRESS])) {
            return redirect()->route('seeker.dashboard')->with('error', 'Akses tes ditolak atau Anda sudah menyelesaikan tes.');
        }

        return view('seeker.kraepelin.instructions', compact('application'));
    }

    /**
     * Memulai tes dan generate angka acak.
     */
    public function startTest($applicationId)
    {
        $application = JobApplication::where('id', $applicationId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $allowedStatuses = [
            JobApplication::STATUS_TEST_INVITED,
            JobApplication::STATUS_TEST_IN_PROGRESS
        ];

        if (!in_array($application->status, $allowedStatuses)) {
            return redirect()->route('seeker.dashboard')
                ->with('error', 'Tes tidak tersedia atau sudah diselesaikan.');
        }

        // 1. Cari apakah ada tes yang SEDANG BERLANGSUNG
        $test = KraepelinTest::where('job_application_id', $application->id)
            ->whereNull('completed_at')
            ->first();

        // JIKA TIDAK ADA TES YANG SEDANG BERLANGSUNG
        if (!$test) {
            // Jika pelamar disuruh mengulang, hapus hasil tes yang lama
            KraepelinTest::where('job_application_id', $application->id)
                ->whereNotNull('completed_at')
                ->delete();
                
            $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);

            // Generate Soal Baru (50 Kolom x 40 Baris)
            $generatedQuestions = [];
            for ($i = 0; $i < 50; $i++) {
                $column = [];
                for ($j = 0; $j < 40; $j++) {
                    $column[] = rand(0, 9);
                }
                $generatedQuestions[] = $column;
            }

            // Simpan sesi baru ke database
            $test = KraepelinTest::create([
                'job_application_id' => $application->id,
                'questions' => $generatedQuestions,
                'started_at' => now(),
            ]);
        }

        return view('seeker.kraepelin.test', compact('application', 'test'));
    }

    /**
     * Menyimpan hasil jawaban dan menghitung skor.
     */
    public function submitTest(Request $request, $testId)
    {
        try {
            $test = KraepelinTest::findOrFail($testId);
            $answers = $request->input('answers', []);
            $questions = $test->questions;

            $totalCorrect = 0;
            $totalAnswered = count($answers);

            // Logika hitung skor otomatis
            foreach ($answers as $key => $userAnswer) {
                [$col, $row] = explode('-', $key);
                $col = (int) $col;
                $row = (int) $row;

                // PERBAIKAN: Kraepelin dijumlahkan dari bawah ke atas. 
                // Karena index 0 di bawah (saat render view), maka baris di atasnya adalah $row - 1
                if (isset($questions[$col][$row]) && isset($questions[$col][$row - 1])) {
                    $num1 = $questions[$col][$row];
                    $num2 = $questions[$col][$row - 1]; // Menggunakan - 1 bukan + 1
                    $correctSum = ($num1 + $num2) % 10;

                    if ((int)$userAnswer === $correctSum) {
                        $totalCorrect++;
                    }
                }
            }

            // Simpan semua kolom untuk menghindari Error 500 Database
            $test->update([
                'answers'         => $answers,
                'total_answered'  => $totalAnswered,
                'total_correct'   => $totalCorrect,
                'total_wrong'     => $totalAnswered - $totalCorrect,
                'stability_score' => 0, // Placeholder nilai default
                'completed_at'    => now(),
            ]);

            $test->jobApplication->update(['status' => 'test_completed']);

            return response()->json([
                'status'   => 'success',
                // Pastikan route ini ada di web.php Anda
                'redirect' => route('seeker.kraepelin.completed', $test->jobApplication->id)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan Halaman Selesai (Post-Test) untuk Seeker
     */
    public function showCompleted($applicationId)
    {
        $application = JobApplication::where('id', $applicationId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Hanya boleh diakses jika statusnya sudah selesai
        if ($application->status !== JobApplication::STATUS_TEST_COMPLETED && $application->status !== JobApplication::STATUS_INTERVIEW) {
            return redirect()->route('seeker.dashboard');
        }

        return view('seeker.kraepelin.completed', compact('application'));
    }

    /**
     * Ekspor PDF (Khusus Company)
     */
    public function exportPdf(JobApplication $application)
    {
        $application->load(['kraepelinTest', 'user', 'job']);
        $test = $application->kraepelinTest;

        if (!$test || !$test->completed_at) {
            return back()->with('error', 'Data tes belum tersedia.');
        }

        // Perhitungan Metrik Psikologi Detail
        $totalAnswered = $test->total_answered;
        $totalCorrect = $test->total_correct;
        $accuracy = $totalAnswered > 0 ? round(($totalCorrect / $totalAnswered) * 100, 2) : 0;
        $pankerLabel = $totalAnswered > 1200 ? 'Sangat Tinggi' : ($totalAnswered > 800 ? 'Tinggi' : 'Moderat');
        $tiankerLabel = $accuracy > 95 ? 'Sangat Teliti' : ($accuracy > 85 ? 'Teliti' : 'Cukup Teliti');
        $pdf = Pdf::loadView('company.applications.kraepelin_pdf', compact('application', 'test', 'accuracy', 'pankerLabel', 'tiankerLabel'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Kraepelin_' . str_replace(' ', '_', $application->user->name) . '.pdf');
    }
}