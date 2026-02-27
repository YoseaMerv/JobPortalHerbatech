<?php

namespace App\Http\Controllers\Company\Kraepelin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\KraepelinTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            return redirect()->route('seeker.kraepelin.start')->with('error', 'Akses tes ditolak atau Anda sudah menyelesaikan tes.');
        }

        return view('seeker.kraepelin.instructions', compact('application'));
        
        }

    /**
     * Memulai tes dan generate angka acak.
     */
    // app/Http/Controllers/Company/Kraepelin/KraepelinController.php

    public function startTest($applicationId)
{
    $application = JobApplication::where('id', $applicationId)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $test = KraepelinTest::where('job_application_id', $application->id)
        ->whereNull('completed_at')
        ->first();

    if (!$test) {
        // ... (Logika generate soal tetap sama) ...
        $test = KraepelinTest::create([
            'job_application_id' => $application->id,
            'questions' => $questions, 
            'started_at' => now(),
        ]);
    }

    // KIRIM VARIABEL $test. $questions akan diambil dari $test->questions di View.
    return view('seeker.kraepelin.test', compact('application', 'test'));
}
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
    
    // Klasifikasi Kecepatan (PANKER)
    $pankerLabel = $totalAnswered > 1200 ? 'Sangat Tinggi' : ($totalAnswered > 800 ? 'Tinggi' : 'Moderat');
    
    // Klasifikasi Ketelitian (TIANKER)
    $tiankerLabel = $accuracy > 95 ? 'Sangat Teliti' : ($accuracy > 85 ? 'Teliti' : 'Cukup Teliti');

    $pdf = Pdf::loadView('company.applications.kraepelin_pdf', compact('application', 'test', 'accuracy', 'pankerLabel', 'tiankerLabel'));
    
    // Set ukuran kertas A4
    $pdf->setPaper('a4', 'portrait');

    $pdf = Pdf::loadView('company.applications.kraepelin_pdf', compact('application', 'test', 'accuracy'));
    return $pdf->download('Laporan_Kraepelin_' . str_replace(' ', '_', $application->user->name) . '.pdf');
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
                $num1 = $questions[$col][$row];
                $num2 = $questions[$col][$row + 1];
                $correctSum = ($num1 + $num2) % 10;

                if ((int)$userAnswer === $correctSum) {
                    $totalCorrect++;
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
                'redirect' => route('seeker.dashboard')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
