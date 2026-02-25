<?php

namespace App\Http\Controllers\Company\Kraepelin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\KraepelinTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    // app/Http/Controllers/Company/Kraepelin/KraepelinController.php

    public function startTest($applicationId)
    {
        $application = JobApplication::findOrFail($applicationId);

        // 1. Cari apakah sudah ada sesi tes yang belum selesai (completed_at masih NULL)
        $test = KraepelinTest::where('job_application_id', $application->id)
            ->whereNull('completed_at')
            ->first();

        if (!$test) {
            // PERTAMA KALI: Update status lamaran & Generate Soal
            $application->update(['status' => JobApplication::STATUS_TEST_IN_PROGRESS]);

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
        // Pastikan variabel $test ikut dikirim
        return view('seeker.kraepelin.test', compact('application', 'test', 'questions'));
    }


    /**
     * Menyimpan hasil jawaban dan menghitung skor.
     */
    /**
     * Menyimpan hasil jawaban dan menghitung skor secara otomatis.
     */
    public function submitTest(Request $request, $testId)
    {
        try {
            $test = KraepelinTest::findOrFail($testId);
            $answers = $request->input('answers', []); // Data dari JS: {"0-0":"5", "0-1":"3"}
            $questions = $test->questions; // Ambil soal asli dari DB

            $totalCorrect = 0;
            $totalAnswered = count($answers);

            // LOGIKA PERHITUNGAN SKOR OTOMATIS
            foreach ($answers as $key => $userAnswer) {
                // Pecah key "kolom-baris" (contoh: "0-1")
                [$col, $row] = explode('-', $key);

                // Ambil dua angka yang dijumlahkan berdasarkan posisi soal
                $num1 = $questions[$col][$row];     // Angka pertama
                $num2 = $questions[$col][$row + 1]; // Angka kedua (di atasnya)

                // Digit terakhir dari penjumlahan (Modulus 10)
                $correctSum = ($num1 + $num2) % 10;

                if ((int)$userAnswer === $correctSum) {
                    $totalCorrect++;
                }
            }

            $totalWrong = $totalAnswered - $totalCorrect;

            // Update Database dengan semua kolom yang dibutuhkan
            $test->update([
                'answers'         => $answers, // Cast array otomatis handle JSON
                'total_answered'  => $totalAnswered,
                'total_correct'   => $totalCorrect,
                'total_wrong'     => $totalWrong,
                'stability_score' => 0, // Placeholder jika belum ada rumus stabilitas
                'completed_at'    => now(),
            ]);

            // Update status lamaran kerja menggunakan konstanta Model
            $test->jobApplication->update([
                'status' => JobApplication::STATUS_TEST_COMPLETED
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Tes berhasil disimpan',
                'redirect' => route('seeker.dashboard')
            ]);
        } catch (\Exception $e) {
            // Memberikan detail error jika gagal agar mudah di-debug di Console Browser
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan hasil: ' . $e->getMessage()
            ], 500);
        }
    }
}
