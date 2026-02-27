<?php

namespace App\Http\Controllers\Company\Kraepelin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\KraepelinTest;
use Illuminate\Http\Request;

class KraepelinController extends Controller
{
    public function showInstructions($applicationId)
    {
        $application = JobApplication::with('job.company')->findOrFail($applicationId);

        if (!in_array($application->status, [JobApplication::STATUS_TEST_INVITED, JobApplication::STATUS_TEST_IN_PROGRESS])) {
            return redirect()->route('seeker.dashboard')->with('error', 'Akses tes ditolak atau Anda sudah menyelesaikan tes.');
        }

        return view('seeker.kraepelin.instructions', compact('application'));
    }

    public function startTest($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);

        // Cari sesi yang belum selesai
        $test = KraepelinTest::where('job_application_id', $application->id)
            ->whereNull('completed_at')
            ->first();

        if (!$test) {
            $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);

            // Kraepelin Standar: 50 Kolom, 40 Baris
            $questions = [];
            for ($i = 0; $i < 50; $i++) {
                $column = [];
                for ($j = 0; $j < 40; $j++) {
                    $column[] = rand(0, 9);
                }
                $questions[] = $column;
            }

            $test = KraepelinTest::create([
                'job_application_id' => $application->id,
                'questions' => $questions,
                'started_at' => now(),
            ]);
        }

        $questions = $test->questions;
        return view('seeker.kraepelin.test', compact('application', 'test', 'questions'));
    }

    public function submitTest(Request $request, $testId)
    {
        try {
            $test = KraepelinTest::findOrFail($testId);
            $answers = $request->input('answers', []);
            $questions = $test->questions;

            $totalCorrect = 0;
            $totalAnswered = count($answers);

            foreach ($answers as $key => $userAnswer) {
                [$col, $row] = explode('-', $key);
                $col = (int)$col;
                $row = (int)$row;

                // Cek ketersediaan angka di array soal
                if (isset($questions[$col][$row]) && isset($questions[$col][$row + 1])) {
                    $num1 = $questions[$col][$row];
                    $num2 = $questions[$col][$row + 1];
                    $correctSum = ($num1 + $num2) % 10;

                    if ((int)$userAnswer === $correctSum) {
                        $totalCorrect++;
                    }
                }
            }

            $test->update([
                'answers'         => $answers,
                'total_answered'  => $totalAnswered,
                'total_correct'   => $totalCorrect,
                'total_wrong'     => max(0, $totalAnswered - $totalCorrect),
                'stability_score' => 0, 
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