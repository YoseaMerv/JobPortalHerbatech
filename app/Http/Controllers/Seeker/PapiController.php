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

        $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);

        return view('seeker.papi.test', compact('application', 'questions', 'testResult'));
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

    private function checkAndUpgradeStatus($application)
    {
        // 1. Refresh data lamaran agar record tes terbaru terbaca oleh memori PHP
        $application->refresh();

        $kraepelinDone = $application->kraepelinTest()->whereNotNull('completed_at')->exists();
        $msdtDone = $application->psychologicalResults()->where('test_type', 'msdt')->where('status', 'completed')->exists();
        $papiDone = $application->psychologicalResults()->where('test_type', 'papi')->where('status', 'completed')->exists();

        // 2. Hanya jika KETIGANYA selesai, ubah status ke COMPLETED
        if ($kraepelinDone && $msdtDone && $papiDone) {
            $application->update(['status' => JobApplication::STATUS_TEST_COMPLETED]);
        } else {
            // Tambahan: Pastikan status tetap IN_PROGRESS jika belum semua selesai
            $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);
        }
    }

    private function calculatePapiScore($answers)
    {
        $questions = PsychologicalQuestion::papi()->get()->keyBy('question_number');
        $finalScores = ['G' => 0, 'L' => 0, 'I' => 0, 'T' => 0, 'V' => 0, 'S' => 0, 'R' => 0, 'D' => 0, 'C' => 0, 'E' => 0, 'N' => 0, 'A' => 0, 'P' => 0, 'X' => 0, 'B' => 0, 'O' => 0, 'Z' => 0, 'K' => 0, 'F' => 0, 'W' => 0];
        if (!$answers) return $finalScores;
        foreach ($answers as $num => $choice) {
            if (!isset($questions[$num])) continue;
            $dim = ($choice == 'a') ? $questions[$num]->dimension_a : $questions[$num]->dimension_b;
            if ($dim && isset($finalScores[$dim])) {
                $finalScores[$dim]++;
            }
        }
        return $finalScores;
    }

    public function showCompleted(JobApplication $application)
    {
        return view('seeker.psychological_completed', compact('application'));
    }
}
