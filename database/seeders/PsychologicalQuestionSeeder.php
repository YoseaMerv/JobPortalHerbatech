<?php

namespace Database\Seeders;

use App\Models\PsychologicalQuestion;
use Illuminate\Database\Seeder;

class PsychologicalQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // --- DATA SOAL MSDT (64 Soal) ---
        // Referensi: Reddin's 3D Theory (MSDT)
        $msdtQuestions = [
            ['no' => 1, 'a' => 'Saya tidak akan menegur pelanggar peraturan bila saya merasa pasti tidak ada yang tahu.', 'b' => 'Bila saya membuat keputusan kurang menyenangkan, saya jelaskan bahwa keputusan ini dibuat Direktur.'],
            ['no' => 2, 'a' => 'Bila ada karyawan yang hasil kerjanya tidak memuaskan, saya tunggu kesempatan memindahkannya bukan memecatnya.', 'b' => 'Bila ada bawahan dikucilkan kelompoknya, saya cari jalan agar orang lain berteman dengannya.'],
            ['no' => 3, 'a' => 'Bila Direktur memberi perintah kurang menyenangkan, saya sebutkan namanya dan bukan nama saya.', 'b' => 'Saya biasanya membuat keputusan sendiri dan menyampaikannya kepada bawahan.'],
            ['no' => 4, 'a' => 'Bila ditegur atasan, saya memanggil semua bawahan dan menyampaikan teguran itu.', 'b' => 'Saya selalu memberikan tugas sulit kepada karyawan yang paling berpengalaman.'],
            ['no' => 5, 'a' => 'Saya selalu melakukan diskusi untuk mencapai kata sepakat.', 'b' => 'Saya menganjurkan bawahan memberikan usul, tapi kadang saya langsung mengambil tindakan.'],
            // ... (Lanjutkan sampai nomor 64)
        ];

        foreach ($msdtQuestions as $q) {
            PsychologicalQuestion::updateOrCreate(
                ['test_type' => 'msdt', 'question_number' => $q['no']],
                ['option_a' => $q['a'], 'option_b' => $q['b']]
            );
        }

        // --- DATA SOAL PAPI KOSTICK (90 Soal) ---
        // Referensi: Personality and Preference Inventory (PAPI)
        // Pemetaan Dimensi A (Horizontal) dan B (Diagonal) sangat krusial!
        $papiQuestions = [
            ['no' => 1, 'a' => 'Saya seorang pekerja keras.', 'da' => 'G', 'b' => 'Saya bukan seorang pemurung.', 'db' => 'E'],
            ['no' => 2, 'a' => 'Saya suka bekerja lebih baik dari orang lain.', 'da' => 'A', 'b' => 'Saya suka mengerjakan apa yang saya kerjakan sampai selesai.', 'db' => 'N'],
            ['no' => 3, 'a' => 'Saya suka menunjukkan caranya melaksanakan sesuatu hal.', 'da' => 'L', 'b' => 'Saya ingin bekerja sebaik mungkin.', 'db' => 'A'],
            ['no' => 4, 'a' => 'Saya suka berkelakar.', 'da' => 'X', 'b' => 'Saya senang mengatakan kepada orang lain apa yang harus dilakukannya.', 'db' => 'P'],
            ['no' => 5, 'a' => 'Saya suka menggabungkan diri dengan kelompok.', 'da' => 'B', 'b' => 'Saya suka diperhatikan oleh kelompok.', 'db' => 'X'],
            ['no' => 6, 'a' => 'Saya senang bersahabat intim dengan seseorang.', 'da' => 'O', 'b' => 'Saya senang bersahabat dengan sekelompok orang.', 'db' => 'B'],
            ['no' => 7, 'a' => 'Saya cepat berubah bila hal itu diperlukan.', 'da' => 'Z', 'b' => 'Saya berusaha untuk intim dengan teman-teman.', 'db' => 'O'],
            ['no' => 8, 'a' => 'Saya suka membalas jika benar-benar disakiti.', 'da' => 'K', 'b' => 'Saya suka melakukan hal-hal yang baru dan berbeda.', 'db' => 'Z'],
            ['no' => 9, 'a' => 'Saya ingin disukai atasan saya.', 'da' => 'F', 'b' => 'Saya suka memberitahu orang lain jika mereka berbuat salah.', 'db' => 'K'],
            ['no' => 10, 'a' => 'Saya senang mengikuti petunjuk yang diberikan kepada saya.', 'da' => 'W', 'b' => 'Saya ingin menyenangkan atasan saya.', 'db' => 'F'],
            ['no' => 11, 'a' => 'Saya berusaha sekuat tenaga.', 'da' => 'G', 'b' => 'Saya seorang yang tertib, meletakkan sesuatu pada tempatnya.', 'db' => 'C'],
            ['no' => 12, 'a' => 'Saya membuat orang lain melakukan apa yang saya inginkan.', 'da' => 'L', 'b' => 'Saya tidak mudah marah.', 'db' => 'E'],
            // ... (Lanjutkan sampai nomor 90)
        ];

        foreach ($papiQuestions as $q) {
            PsychologicalQuestion::updateOrCreate(
                ['test_type' => 'papi', 'question_number' => $q['no']],
                [
                    'option_a' => $q['a'],
                    'option_b' => $q['b'],
                    'dimension_a' => $q['da'],
                    'dimension_b' => $q['db']
                ]
            );
        }
    }
}
