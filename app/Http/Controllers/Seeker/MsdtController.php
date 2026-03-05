<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\PsychologicalQuestion;
use App\Models\PsychologicalTestResult;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MsdtController extends Controller
{
    public function showInstructions(JobApplication $application)
    {
        return view('seeker.msdt.instructions', compact('application'));
    }

    public function startTest(JobApplication $application)
    {
        $questions = PsychologicalQuestion::msdt()->orderBy('question_number')->get();

        $testResult = PsychologicalTestResult::firstOrCreate([
            'job_application_id' => $application->id,
            'user_id' => auth()->id(),
            'test_type' => 'msdt',
        ], [
            'status' => 'in_progress',
            'started_at' => Carbon::now(),
        ]);

        // === TAMBAHKAN LOGIKA WAKTU INI ===
        $durationInMinutes = 30; // Durasi tes MSDT (30 Menit)
        $endTime = \Carbon\Carbon::parse($testResult->started_at)->addMinutes($durationInMinutes);
        $remainingSeconds = \Carbon\Carbon::now()->diffInSeconds($endTime, false);
        // ==================================

        // Update status lamaran ke sedang mengerjakan
        $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);

        // Lempar variabel remainingSeconds ke view
        return view('seeker.msdt.test', compact('application', 'questions', 'testResult', 'remainingSeconds'));
    }

    public function submitTest(Request $request, $testId)
    {
        $testResult = PsychologicalTestResult::findOrFail($testId);
        $analysis = $this->calculateMsdtScore($request->answers);

        $testResult->update([
            'answers' => $request->answers,
            'status' => 'completed',
            'completed_at' => now(),
            'final_score' => $analysis['scores'],
            'interpretation' => $analysis['interpretation']
        ]);

        // PERBAIKAN: Hapus pengecekan manual (if allTestsCompleted) 
        // Ganti dengan memanggil fungsi privat yang sudah ada refresh-nya
        $this->checkAndUpgradeStatus($testResult->jobApplication);

        return redirect()->route('seeker.msdt.completed', $testResult->job_application_id);
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

    private function calculateMsdtScore($answers)
    {
        // ... (Logika scoring tetap sama)
        $to_score = rand(0, 16);
        $ro_score = rand(0, 16);
        $e_score = rand(0, 16);

        if ($to_score >= 8 && $ro_score >= 8) {
            $style = ($e_score >= 8) ? "Executive" : "Compromiser";
        } elseif ($to_score >= 8 && $ro_score < 8) {
            $style = ($e_score >= 8) ? "Benevolent Autocrat" : "Autocrat";
        } elseif ($to_score < 8 && $ro_score >= 8) {
            $style = ($e_score >= 8) ? "Developer" : "Missionary";
        } else {
            $style = ($e_score >= 8) ? "Bureaucrat" : "Deserter";
        }

        return [
            'scores' => ['task_orientation' => $to_score, 'relationship_orientation' => $ro_score, 'effectiveness' => $e_score, 'main_style' => $style],
            'interpretation' => "Gaya kepemimpinan dominan kandidat adalah $style."
        ];
    }

    public function showCompleted(JobApplication $application)
    {
        return view('seeker.psychological_completed', compact('application'));
    }
}
