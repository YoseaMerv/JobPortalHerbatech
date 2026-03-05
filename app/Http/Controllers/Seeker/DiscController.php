<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\PsychologicalQuestion;
use App\Models\PsychologicalTestResult;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiscController extends Controller
{
    public function showInstructions(JobApplication $application)
    {
        return view('seeker.disc.instructions', compact('application'));
    }

    public function startTest(JobApplication $application)
    {
        // Pastikan scope disc() sudah ada di model PsychologicalQuestion
        $questions = PsychologicalQuestion::where('test_type', 'disc')
            ->orderBy('question_number')
            ->get();

        $testResult = PsychologicalTestResult::firstOrCreate([
            'job_application_id' => $application->id,
            'user_id' => auth()->id(),
            'test_type' => 'disc',
        ], [
            'status' => 'in_progress',
            'started_at' => \Carbon\Carbon::now(),
        ]);

        // === TAMBAHKAN LOGIKA WAKTU INI ===
        $durationInMinutes = 15; // Durasi tes D.I.S.C
        $endTime = \Carbon\Carbon::parse($testResult->started_at)->addMinutes($durationInMinutes);
        $remainingSeconds = \Carbon\Carbon::now()->diffInSeconds($endTime, false);
        // ==================================

        $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);

        // === PERHATIKAN BARIS INI: Tambahkan 'remainingSeconds' di dalam compact ===
        return view('seeker.disc.test', compact('application', 'questions', 'testResult', 'remainingSeconds'));
    }

    public function submitTest(Request $request, $testId)
    {
        $testResult = PsychologicalTestResult::findOrFail($testId);

        // Validasi: Pastikan semua 24 soal diisi P dan K nya
        // Logic hitung skor
        $analysis = $this->calculateDiscScore($request->answers);

        $testResult->update([
            'answers' => $request->answers,
            'status' => 'completed',
            'completed_at' => Carbon::now(),
            'final_score' => $analysis['totals'],
            'interpretation' => $analysis['interpretation']
        ]);

        $this->checkAndUpgradeStatus($testResult->jobApplication);

        return redirect()->route('seeker.disc.completed', $testResult->job_application_id);
    }

    public function autoSave(Request $request, $testId)
    {
        $testResult = \App\Models\PsychologicalTestResult::find($testId);

        // Pastikan tes ditemukan dan belum berstatus completed
        if ($testResult && $testResult->status !== 'completed') {
            // Update HANYA kolom answers saja, status tetap in_progress
            $testResult->update([
                'answers' => $request->answers
            ]);

            return response()->json(['status' => 'success', 'message' => 'Draft tersimpan di database']);
        }

        return response()->json(['status' => 'error', 'message' => 'Tes tidak valid atau sudah selesai'], 400);
    }

    private function calculateDiscScore($answers)
    {
        $scoreP = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0, 'X' => 0];
        $scoreK = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0, 'X' => 0];

        $questions = PsychologicalQuestion::where('test_type', 'disc')->get()->keyBy('question_number');

        if (!$answers) return ['totals' => ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0], 'interpretation' => 'Data jawaban kosong'];

        foreach ($answers as $num => $choice) {
            if (!isset($questions[$num])) continue;

            $mappingP = json_decode($questions[$num]->dimension_p, true);
            $mappingK = json_decode($questions[$num]->dimension_k, true);

            // Menghitung P (Paling)
            if (isset($choice['p'])) {
                $valP = $mappingP[$choice['p']] ?? 'X';
                if (isset($scoreP[$valP])) $scoreP[$valP]++;
            }

            // Menghitung K (Paling Tidak Menggambarkan Diri)
            if (isset($choice['k'])) {
                $valK = $mappingK[$choice['k']] ?? 'X';
                if (isset($scoreK[$valK])) $scoreK[$valK]++;
            }
        }

        // Skor Akhir: Selisih antara Paling dan PALING TIDAK MENGGAMBARKAN DIRI 
        $totals = [
            'D' => $scoreP['D'] - $scoreK['D'],
            'I' => $scoreP['I'] - $scoreK['I'],
            'S' => $scoreP['S'] - $scoreK['S'],
            'C' => $scoreP['C'] - $scoreK['C'],
        ];

        return [
            'totals' => $totals,
            'interpretation' => "Analisis DISC telah diperbarui dengan profil skor: D=" . $totals['D'] . ", I=" . $totals['I'] . ", S=" . $totals['S'] . ", C=" . $totals['C']
        ];
    }

    private function checkAndUpgradeStatus($application)
    {
        $application->refresh();

        $kraepelinDone = $application->kraepelinTest()->whereNotNull('completed_at')->exists();

        // Ambil semua hasil tes psikotes
        $results = $application->psychologicalResults()->where('status', 'completed')->pluck('test_type')->toArray();

        // Syarat: Kraepelin + MSDT + PAPI + DISC harus Selesai
        $othersDone = in_array('msdt', $results) && in_array('papi', $results) && in_array('disc', $results);

        if ($kraepelinDone && $othersDone) {
            $application->update(['status' => JobApplication::STATUS_TEST_COMPLETED]);
        } else {
            $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);
        }
    }

    public function showCompleted(JobApplication $application)
    {
        // Menggunakan view yang sama dengan MSDT & PAPI untuk efisiensi
        return view('seeker.psychological_completed', compact('application'));
    }
}
