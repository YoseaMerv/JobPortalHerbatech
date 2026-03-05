<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\PsychologicalQuestion;
use App\Models\PsychologicalTestResult;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PapiController extends Controller
{
    public function showInstructions(JobApplication $application)
    {
        return view('seeker.papi.instructions', compact('application'));
    }

    public function startTest(JobApplication $application)
    {
        $questions = PsychologicalQuestion::papi()->orderBy('question_number')->get();

        $testResult = PsychologicalTestResult::firstOrCreate([
            'job_application_id' => $application->id,
            'user_id' => auth()->id(),
            'test_type' => 'papi',
        ], [
            'status' => 'in_progress',
            'started_at' => Carbon::now(),
        ]);

        // === TAMBAHKAN LOGIKA WAKTU INI ===
        $durationInMinutes = 30; // Durasi tes PAPI (30 Menit)
        $endTime = \Carbon\Carbon::parse($testResult->started_at)->addMinutes($durationInMinutes);
        $remainingSeconds = \Carbon\Carbon::now()->diffInSeconds($endTime, false);
        // ==================================

        $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);

        // Lempar variabel remainingSeconds ke view
        return view('seeker.papi.test', compact('application', 'questions', 'testResult', 'remainingSeconds'));
    }

    public function submitTest(Request $request, $testId)
    {
        $testResult = PsychologicalTestResult::findOrFail($testId);
        $scores = $this->calculatePapiScore($request->answers);

        $testResult->update([
            'answers' => $request->answers,
            'status' => 'completed',
            'completed_at' => now(),
            'final_score' => $scores,
            'interpretation' => "Profil kepribadian PAPI Kostick telah dianalisis sesuai standar dimensi."
        ]);

        // GANTI LOGIKA LAMA DENGAN INI:
        $this->checkAndUpgradeStatus($testResult->jobApplication);

        return redirect()->route('seeker.papi.completed', $testResult->job_application_id);
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

    private function checkAndUpgradeStatus($application)
    {
        $application->refresh();

        // Cek Kraepelin
        $kraepelinDone = $application->kraepelinTest()->whereNotNull('completed_at')->exists();

        // Cek MSDT, PAPI, DISC
        $results = $application->psychologicalResults()->where('status', 'completed')->pluck('test_type')->toArray();

        $othersDone = in_array('msdt', $results) && in_array('papi', $results) && in_array('disc', $results);

        if ($kraepelinDone && $othersDone) {
            $application->update(['status' => JobApplication::STATUS_TEST_COMPLETED]);
        } else {
            $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);
        }
    }

    private function calculatePapiScore($answers)
    {
        // 20 Dimensi PAPI Kostick
        $finalScores = [
            'G' => 0,
            'L' => 0,
            'I' => 0,
            'T' => 0,
            'V' => 0,
            'S' => 0,
            'R' => 0,
            'D' => 0,
            'C' => 0,
            'E' => 0, // Peran (Role)
            'N' => 0,
            'A' => 0,
            'P' => 0,
            'X' => 0,
            'B' => 0,
            'O' => 0,
            'Z' => 0,
            'K' => 0,
            'F' => 0,
            'W' => 0  // Kebutuhan (Need)
        ];

        if (!$answers) return $finalScores;

        // Ambil mapping dimensi dari database soal
        $questions = PsychologicalQuestion::where('test_type', 'papi')->get()->keyBy('question_number');

        foreach ($answers as $num => $choice) {
            if (!isset($questions[$num])) continue;

            // Jika user pilih A (Panah Horizontal), tambah skor untuk Dimensi A
            // Jika user pilih B (Panah Diagonal), tambah skor untuk Dimensi B
            $dimension = ($choice === 'a') ? $questions[$num]->dimension_a : $questions[$num]->dimension_b;

            if ($dimension && isset($finalScores[$dimension])) {
                $finalScores[$dimension]++;
            }
        }

        return $finalScores;
    }

    public function showCompleted(JobApplication $application)
    {
        return view('seeker.psychological_completed', compact('application'));
    }
}
