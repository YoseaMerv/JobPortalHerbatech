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
            'started_at' => Carbon::now(),
        ]);

        $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);

        return view('seeker.disc.test', compact('application', 'questions', 'testResult'));
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

    private function calculateDiscScore($answers)
    {
        // Inisialisasi skor D, I, S, C untuk P (Most) dan K (Least)
        $scoreP = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0, 'X' => 0];
        $scoreK = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0, 'X' => 0];

        // Ambil kunci jawaban dari database soal
        $questions = PsychologicalQuestion::where('test_type', 'disc')->get()->keyBy('question_number');

        foreach ($answers as $num => $choice) {
            if (!isset($questions[$num])) continue;

            // $choice['p'] adalah pilihan baris (1-4) untuk kolom P
            // $choice['k'] adalah pilihan baris (1-4) untuk kolom K

            $mappingP = json_decode($questions[$num]->dimension_p, true); // Contoh isi: [1=>'D', 2=>'I', 3=>'S', 4=>'C']
            $mappingK = json_decode($questions[$num]->dimension_k, true);

            if (isset($choice['p']) && isset($mappingP[$choice['p']])) {
                $scoreP[$mappingP[$choice['p']]]++;
            }
            if (isset($choice['k']) && isset($mappingK[$choice['k']])) {
                $scoreK[$mappingK[$choice['k']]]++;
            }
        }

        // Skor Akhir = P - K
        $totals = [
            'D' => $scoreP['D'] - $scoreK['D'],
            'I' => $scoreP['I'] - $scoreK['I'],
            'S' => $scoreP['S'] - $scoreK['S'],
            'C' => $scoreP['C'] - $scoreK['C'],
        ];

        return [
            'totals' => $totals,
            'interpretation' => "Analisis kepribadian DISC telah berhasil dikalkulasi berdasarkan 24 indikator perilaku."
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
