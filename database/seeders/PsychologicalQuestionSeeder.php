<?php

namespace Database\Seeders;

use App\Models\PsychologicalQuestion;
use Illuminate\Database\Seeder;

class PsychologicalQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // --- DATA SOAL MSDT (64 Soal) ---
        $msdtQuestions = [
            ['no' => 1, 'a' => 'Saya tidak akan menegur pelanggar peraturan bila saya merasa pasti tidak ada yang tahu.', 'b' => 'Bila saya membuat keputusan kurang menyenangkan, saya jelaskan bahwa keputusan ini dibuat Direktur.'],
            ['no' => 2, 'a' => 'Bila ada karyawan yang hasil kerjanya tidak memuaskan, saya tunggu kesempatan memindahkannya bukan memecatnya.', 'b' => 'Bila ada bawahan dikucilkan kelompoknya, saya cari jalan agar orang lain berteman dengannya.'],
            ['no' => 3, 'a' => 'Bila Direktur memberi perintah kurang menyenangkan, saya sebutkan namanya dan bukan nama saya.', 'b' => 'Saya biasanya membuat keputusan sendiri dan menyampaikannya kepada bawahan.'],
            ['no' => 4, 'a' => 'Bila ditegur atasan, saya memanggil semua bawahan dan menyampaikan teguran itu.', 'b' => 'Saya selalu memberikan tugas sulit kepada karyawan yang paling berpengalaman.'],
            ['no' => 5, 'a' => 'Saya selalu melakukan diskusi untuk mencapai kata sepakat.', 'b' => 'Saya menganjurkan bawahan memberikan usul, tapi kadang saya langsung mengambil tindakan.'],
            // ... (Lanjutkan sesuai data Anda)
        ];

        foreach ($msdtQuestions as $q) {
            PsychologicalQuestion::updateOrCreate(
                ['test_type' => 'msdt', 'question_number' => $q['no']],
                ['option_a' => $q['a'], 'option_b' => $q['b']]
            );
        }

        // --- DATA SOAL PAPI KOSTICK (90 Soal) ---
        $papiQuestions = [
            ['no' => 1, 'a' => 'Saya seorang pekerja keras.', 'da' => 'G', 'b' => 'Saya bukan seorang pemurung.', 'db' => 'E'],
            ['no' => 2, 'a' => 'Saya suka bekerja lebih baik dari orang lain.', 'da' => 'A', 'b' => 'Saya suka mengerjakan apa yang saya kerjakan sampai selesai.', 'db' => 'N'],
            ['no' => 3, 'a' => 'Saya suka menunjukkan caranya melaksanakan sesuatu hal.', 'da' => 'L', 'b' => 'Saya ingin bekerja sebaik mungkin.', 'db' => 'A'],
            ['no' => 4, 'a' => 'Saya suka berkelakar.', 'da' => 'X', 'b' => 'Saya senang mengatakan kepada orang lain apa yang harus dilakukannya.', 'db' => 'P'],
            ['no' => 5, 'a' => 'Saya suka menggabungkan diri dengan kelompok.', 'da' => 'B', 'b' => 'Saya suka diperhatikan oleh kelompok.', 'db' => 'X'],
            // ... (Lanjutkan sesuai data Anda)
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

        // --- DATA SOAL DISC (24 Soal dari Gambar) ---
        // Format: 'text' (4 kalimat), 'p' (Most mapping), 'k' (Least mapping)
        $discQuestions = [
            [
                'no' => 1,
                'text' => ["Gampangan, Mudah setuju", "Percaya, Mudah percaya pada orang", "Petualang, Mengambil resiko", "Toleran, Menghormati"],
                'p' => ["1" => "S", "2" => "I", "3" => "D", "4" => "C"],
                'k' => ["1" => "S", "2" => "I", "3" => "D", "4" => "C"]
            ],
            [
                'no' => 2,
                'text' => ["Lembut suara, Pendiam", "Optimistik, Visioner", "Pusat Perhatian, Suka gaul", "Pendamai, Membawa Harmoni"],
                'p' => ["1" => "S", "2" => "D", "3" => "I", "4" => "C"],
                'k' => ["1" => "S", "2" => "D", "3" => "I", "4" => "C"]
            ],
            [
                'no' => 3,
                'text' => ["Menyemangati orang", "Berusaha sempurna", "Bagian dari kelompok", "Ingin membuat tujuan"],
                'p' => ["1" => "I", "2" => "C", "3" => "S", "4" => "D"],
                'k' => ["1" => "I", "2" => "C", "3" => "S", "4" => "D"]
            ],
            [
                'no' => 4,
                'text' => ["Menjadi frustrasi", "Menyimpan perasaan saya", "Menceritakan sisi saya", "Siap beroposisi"],
                'p' => ["1" => "C", "2" => "S", "3" => "I", "4" => "D"],
                'k' => ["1" => "C", "2" => "S", "3" => "I", "4" => "D"]
            ],
            [
                'no' => 5,
                'text' => ["Hidup, Suka bicara", "Gerak cepat, Tekun", "Usaha menjaga keseimbangan", "Usaha mengikuti aturan"],
                'p' => ["1" => "I", "2" => "D", "3" => "S", "4" => "C"],
                'k' => ["1" => "I", "2" => "D", "3" => "S", "4" => "C"]
            ],
            [
                'no' => 6,
                'text' => ["Kelola waktu secara efisien", "Sering terburu-buru, Merasa tertekan", "Masalah sosial itu penting", "Suka selesaikan apa yang saya mulai"],
                'p' => ["1" => "D", "2" => "C", "3" => "I", "4" => "S"],
                'k' => ["1" => "D", "2" => "C", "3" => "I", "4" => "S"]
            ],
            [
                'no' => 7,
                'text' => ["Tolak perubahan mendadak", "Cenderung janji berlebihan", "Tarik diri di tengah tekanan", "Tidak takut bertempur"],
                'p' => ["1" => "S", "2" => "I", "3" => "C", "4" => "D"],
                'k' => ["1" => "S", "2" => "I", "3" => "C", "4" => "D"]
            ],
            [
                'no' => 8,
                'text' => ["Penyemangat yang baik", "Pendengar yang baik", "Penganalisa yang baik", "Delegator yang baik"],
                'p' => ["1" => "I", "2" => "S", "3" => "C", "4" => "D"],
                'k' => ["1" => "I", "2" => "S", "3" => "C", "4" => "D"]
            ],
            [
                'no' => 9,
                'text' => ["Hasil adalah penting", "Lakukan dengan benar, Akurasi penting", "Dibuat menyenangkan", "Mari kerjakan bersama"],
                'p' => ["1" => "D", "2" => "C", "3" => "I", "4" => "S"],
                'k' => ["1" => "D", "2" => "C", "3" => "I", "4" => "S"]
            ],
            [
                'no' => 10,
                'text' => ["Akan berjalan terus tanpa kontrol diri", "Akan membeli sesuai dorongan hati", "Akan menunggu, Tanpa tekanan", "Akan mengusahakan yang kuinginkan"],
                'p' => ["1" => "D", "2" => "I", "3" => "S", "4" => "C"],
                'k' => ["1" => "D", "2" => "I", "3" => "S", "4" => "C"]
            ],
            [
                'no' => 11,
                'text' => ["Ramah, Mudah bergabung", "Unik, Bosan rutinitas", "Aktif mengubah sesuatu", "Ingin hal-hal yang pasti"],
                'p' => ["1" => "I", "2" => "D", "3" => "D", "4" => "C"],
                'k' => ["1" => "I", "2" => "D", "3" => "D", "4" => "C"]
            ],
            [
                'no' => 12,
                'text' => ["Non-konfrontasi, Menyerah", "Dipenuhi hal detail", "Perubahan pada menit terakhir", "Menuntut, Kasar"],
                'p' => ["1" => "S", "2" => "C", "3" => "I", "4" => "D"],
                'k' => ["1" => "S", "2" => "C", "3" => "I", "4" => "D"]
            ],
            [
                'no' => 13,
                'text' => ["Ingin kemajuan", "Puas dengan segalanya", "Terbuka memperlihatkan perasaan", "Rendah hati, Sederhana"],
                'p' => ["1" => "D", "2" => "S", "3" => "I", "4" => "C"],
                'k' => ["1" => "D", "2" => "S", "3" => "I", "4" => "C"]
            ],
            [
                'no' => 14,
                'text' => ["Tenang, Pendiam", "Bahagia, Tanpa beban", "Menyenangkan, Baik hati", "Tak gentar, Berani"],
                'p' => ["1" => "C", "2" => "I", "3" => "S", "4" => "D"],
                'k' => ["1" => "C", "2" => "I", "3" => "S", "4" => "D"]
            ],
            [
                'no' => 15,
                'text' => ["Menggunakan waktu berkualitas dgn teman", "Rencanakan masa depan, Bersiap", "Bepergian demi petualangan baru", "Menerima ganjaran atas tujuan yg dicapai"],
                'p' => ["1" => "S", "2" => "C", "3" => "I", "4" => "D"],
                'k' => ["1" => "S", "2" => "C", "3" => "I", "4" => "D"]
            ],
            [
                'no' => 16,
                'text' => ["Aturan perlu dipertanyakan", "Aturan membuat adil", "Aturan membuat bosan", "Aturan membuat aman"],
                'p' => ["1" => "D", "2" => "C", "3" => "I", "4" => "S"],
                'k' => ["1" => "D", "2" => "C", "3" => "I", "4" => "S"]
            ],
            [
                'no' => 17,
                'text' => ["Pendidikan, Kebudayaan", "Prestasi, Ganjaran", "Keselamatan, keamanan", "Sosial, Perkumpulan kelompok"],
                'p' => ["1" => "C", "2" => "D", "3" => "S", "4" => "I"],
                'k' => ["1" => "C", "2" => "D", "3" => "S", "4" => "I"]
            ],
            [
                'no' => 18,
                'text' => ["Memimpin, Pendekatan langsung", "Suka bergaul, Antusias", "Dapat diramal, Konsisten", "Waspada, Hati-hati"],
                'p' => ["1" => "D", "2" => "I", "3" => "S", "4" => "C"],
                'k' => ["1" => "D", "2" => "I", "3" => "S", "4" => "C"]
            ],
            [
                'no' => 19,
                'text' => ["Tidak mudah dikalahkan", "Kerjakan sesuai perintah, Ikut pimpinan", "Mudah terangsang, Riang", "Ingin segalanya teratur, Rapi"],
                'p' => ["1" => "D", "2" => "S", "3" => "I", "4" => "C"],
                'k' => ["1" => "D", "2" => "S", "3" => "I", "4" => "C"]
            ],
            [
                'no' => 20,
                'text' => ["Saya akan pimpin mereka", "Saya akan melaksanakan", "Saya akan meyakinkan mereka", "Saya dapatkan fakta"],
                'p' => ["1" => "D", "2" => "S", "3" => "I", "4" => "C"],
                'k' => ["1" => "D", "2" => "S", "3" => "I", "4" => "C"]
            ],
            [
                'no' => 21,
                'text' => ["Memikirkan orang dahulu", "Kompetitif, Suka tantangan", "Optimis, Positif", "Pemikir logis, Sistematik"],
                'p' => ["1" => "S", "2" => "D", "3" => "I", "4" => "C"],
                'k' => ["1" => "S", "2" => "D", "3" => "I", "4" => "C"]
            ],
            [
                'no' => 22,
                'text' => ["Menyenangkan orang, Mudah setuju", "Tertawa lepas, Hidup", "Berani, Tak gentar", "Tenang, Pendiam"],
                'p' => ["1" => "S", "2" => "I", "3" => "D", "4" => "C"],
                'k' => ["1" => "S", "2" => "I", "3" => "D", "4" => "C"]
            ],
            [
                'no' => 23,
                'text' => ["Ingin otoritas lebih", "Ingin kesempatan baru", "Menghindari konflik", "Ingin petunjuk yang jelas"],
                'p' => ["1" => "D", "2" => "I", "3" => "S", "4" => "C"],
                'k' => ["1" => "D", "2" => "I", "3" => "S", "4" => "C"]
            ],
            [
                'no' => 24,
                'text' => ["Dapat diandalkan, Dapat dipercaya", "Kreatif, Unik", "Garis dasar, Orientasi hasil", "Jalankan standar yang tinggi, Akurat"],
                'p' => ["1" => "S", "2" => "I", "3" => "D", "4" => "C"],
                'k' => ["1" => "S", "2" => "I", "3" => "D", "4" => "C"]
            ],
        ];

        foreach ($discQuestions as $q) {
            PsychologicalQuestion::updateOrCreate(
                ['test_type' => 'disc', 'question_number' => $q['no']],
                [
                    'question_text' => json_encode($q['text']),
                    'dimension_p' => json_encode($q['p']),
                    'dimension_k' => json_encode($q['k']),
                ]
            );
        }
    }
}
