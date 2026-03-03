<?php

namespace Database\Seeders;

use App\Models\PsychologicalQuestion;
use Illuminate\Database\Seeder;

class PsychologicalQuestionSeeder extends Seeder
{
    public function run(): void
    {
        // --- DATA SOAL MSDT (64 Soal) ---
        // --- DATA SOAL MSDT (64 Soal Lengkap berdasarkan PDF) ---
        $msdtQuestions = [
            ['no' => 1, 'a' => 'Saya tidak akan menegur pelanggar peraturan bila saya merasa pasti tidak ada satu orangpun yang mengetahui pelanggaran tersebut.', 'b' => 'Bila saya mengumumkan suatu keputusan yang kurang menyenangkan, saya akan menjelaskan bahwa keputusan ini dibuat oleh direktur.'],
            ['no' => 2, 'a' => 'Bila ada karyawan yang hasil kerjanya selalu tidak memuaskan, saya akan menunggu kesempatan untuk memindahkannya dan bukan memecatnya.', 'b' => 'Bila ada bawahan yang dikucilkan kelompok kerjanya, saya akan mencari jalan agar orang lain dapat berteman dengannya.'],
            ['no' => 3, 'a' => 'Bila direktur memberikan perintah yang kurang menyenangkan, saya pikir bijaksana bila menyebutkan namanya dan bukan nama saya.', 'b' => 'Saya biasanya membuat keputusan-keputusan sendiri dan menyampaikannya kepada bawahan saya.'],
            ['no' => 4, 'a' => 'Bila saya ditegur oleh atasan, saya akan memanggil semua bawahan dan mengatakan semua teguran tersebut kepada mereka.', 'b' => 'Saya selalu memberikan tugas-tugas yang sangat sulit kepada karyawan yang paling berpengalaman.'],
            ['no' => 5, 'a' => 'Saya selalu melakukan diskusi-diskusi untuk mencapai kata sepakat.', 'b' => 'Saya selalu menganjurkan bawahan memberikan usul, tetapi kadang-kadang saya langsung mengambil tindakan tertentu.'],
            ['no' => 6, 'a' => 'Seringkali saya lebih mementingkan tugas daripada diri saya sendiri.', 'b' => 'Saya mengijinkan bawahan-bawahan saya untuk ikut serta dalam mengambil keputusan.'],
            ['no' => 7, 'a' => 'Bila hasil kerja bagian tidak memuaskan, saya mengatakan kepada bawahan bahwa direktur merasa kecewa. Oleh karena itu mereka harus memperbaiki kerja.', 'b' => 'Saya membuat keputusan sendiri dan kemudian mencoba untuk "menjual" keputusan itu kepada bawahan saya.'],
            ['no' => 8, 'a' => 'Bila saya mengumumkan keputusan kurang menyenangkan, saya akan menjelaskan kepada bawahan bahwa keputusan ini dibuat oleh direktur.', 'b' => 'Saya mengijinkan bawahan ikut serta mengambil keputusan, tapi sayapun menyediakan sesuatu yang jitu sebagai keputusan terakhir.'],
            ['no' => 9, 'a' => 'Saya memberikan tugas sulit kepada bawahan belum berpengalaman, tapi bila mereka kesukaran, saya mengambil alih tanggung jawab mereka.', 'b' => 'Bila hasil kerja tidak memuaskan, saya katakan kepada bawahan bahwa direktur kecewa dan mereka harus memperbaiki kerja.'],
            ['no' => 10, 'a' => 'Saya merasa bahwa dengan bekerja keras untuk bawahan, mereka akan menyukai saya.', 'b' => 'Saya membiarkan orang lain menangani tugas masing-masing, walaupun mereka membuat banyak kesalahan.'],
            ['no' => 11, 'a' => 'Saya menunjukkan minat terhadap kehidupan pribadi bawahan, karena sayapun mengharapkan mereka berbuat seperti itu kepada saya.', 'b' => 'Saya merasa bawahan tidak perlu mengerti mengapa mereka mengerjakan sesuatu hal, sejauh mereka mengerjakan hal tersebut.'],
            ['no' => 12, 'a' => 'Saya percaya bawahan yang tidak disiplin tidak akan memperbaiki jumlah atau mutu kerja dalam jangka panjang.', 'b' => 'Bila menghadapi masalah sulit, saya berusaha mencapai pemecahan yang dapat diterima sebagian besar orang.'],
            ['no' => 13, 'a' => 'Bila beberapa bawahan merasa tidak bahagia, saya akan mencoba melakukan sesuatu untuk mengatasi hal tersebut.', 'b' => 'Saya berusaha bekerja sebaik mungkin dan memberikan ide-ide pengembangan pada pimpinan.'],
            ['no' => 14, 'a' => 'Saya menyetujui kenaikan tunjangan-tunjangan untuk staf dan karyawan.', 'b' => 'Saya mendukung bawahan meningkatkan pengetahuan tentang pekerjaan, walaupun sebenarnya belum diperlukan untuk kedudukan mereka sekarang.'],
            ['no' => 15, 'a' => 'Saya membiarkan orang lain menangani tugas masing-masing, walaupun mereka banyak membuat kesalahan.', 'b' => 'Saya membuat keputusan-keputusan sendiri, tetapi saya akan mempertimbangkan usul-usul dari bawahan saya.'],
            ['no' => 16, 'a' => 'Bila bawahan dikucilkan dari kelompok kerjanya, saya akan mencari cara agar orang lain dapat berteman dengannya.', 'b' => 'Bila seorang karyawan tidak sanggup menyelesaikan tugasnya, saya akan membantu dia menyelesaikan tugas tersebut.'],
            ['no' => 17, 'a' => 'Saya percaya bahwa penerapan disiplin merupakan contoh untuk karyawan-karyawan lain.', 'b' => 'Saya merasa lebih mementingkan tugas daripada diri saya sendiri.'],
            ['no' => 18, 'a' => 'Saya mencela pembicaraan yang tidak perlu diantara bawahan selama mereka bekerja.', 'b' => 'Saya menyetujui kenaikan tunjangan-tunjangan untuk staf dan karyawan.'],
            ['no' => 19, 'a' => 'Saya selalu memperhatikan keterlambatan dan kemangkiran bawahan saya.', 'b' => 'Saya percaya bahwa serikat-serikat buruh akan mencoba meruntuhkan kewibawaan pimpinan perusahaan.'],
            ['no' => 20, 'a' => 'Kadang-kadang saya merasa bahwa apa yang dikeluhkan oleh serikat buruh bukanlah masalah yang mendasar.', 'b' => 'Saya merasa keluhan-keluhan tidak dapat dicegah dan saya berusaha untuk menghilangkan keluhan tersebut.'],
            ['no' => 21, 'a' => 'Penting bagi saya untuk memperoleh penghargaan atas ide-ide saya yang baik.', 'b' => 'Saya mengemukakan pendapat di muka umum hanya bila saya merasa bahwa orang lain akan setuju dengan saya.'],
            ['no' => 22, 'a' => 'Saya percaya bahwa serikat-serikat buruh akan mencoba meruntuhkan kewibawaan pimpinan perusahaan.', 'b' => 'Saya percaya pertemuan pribadi yang sering dengan karyawan membantu pengembangan diri mereka.'],
            ['no' => 23, 'a' => 'Saya merasa bawahan tidak perlu mengerti mengapa mereka mengerjakan sesuatu, sejauh mereka mengerjakan hal tersebut.', 'b' => 'Saya merasa jam pencatat waktu datang dan pulang akan mengurangi keterlambatan.'],
            ['no' => 24, 'a' => 'Saya biasanya membuat keputusan sendiri dan menyampaikannya kepada bawahan saya.', 'b' => 'Saya merasa serikat buruh dan pimpinan perusahaan dapat bekerjasama mencapai tujuan bersama.'],
            ['no' => 25, 'a' => 'Saya menyukai penggunaan skala penggajian karyawan.', 'b' => 'Saya selalu melakukan diskusi-diskusi untuk mencapai kata sepakat.'],
            ['no' => 26, 'a' => 'Saya tidak akan memberikan tugas yang tidak saya senangi kepada orang lain.', 'b' => 'Bila beberapa bawahan tidak berbahagia, saya mencoba melakukan sesuatu untuk mengatasi hal tersebut.'],
            ['no' => 27, 'a' => 'Bila ada tugas mendesak, walaupun peralatan selesai, saya membiarkannya dan meminta bawahan mengerjakan tugas tersebut.', 'b' => 'Adalah penting bagi saya untuk memperoleh penghargaan atas ide-ide saya yang baik.'],
            ['no' => 28, 'a' => 'Tujuan saya adalah berusaha mengerjakan tugas sebaik mungkin tanpa mengeluh.', 'b' => 'Saya memberikan tugas kepada bawahan tanpa banyak mempertimbangkan pengalaman/kemampuan, saya menuntut hasilnya saja.'],
            ['no' => 29, 'a' => 'Saya memberikan tugas kepada bawahan tanpa banyak mempertimbangkan pengalaman/kemampuan, saya menuntut hasilnya saja.', 'b' => 'Saya dengan sabar mendengarkan keluhan bawahan, tetapi sering kali saya meralat apa yang mereka katakan.'],
            ['no' => 30, 'a' => 'Saya merasa keluhan-keluhan tidak dapat dicegah dan saya berusaha menghilangkan keluhan tersebut.', 'b' => 'Saya percaya bawahan saya akan merasakan kepuasan kerja tanpa merasa tertekan oleh saya.'],
            ['no' => 31, 'a' => 'Bila menghadapi masalah sulit, saya berusaha mencapai pemecahan yang dapat diterima sebagian besar orang.', 'b' => 'Saya percaya pengalaman bekerja lebih bermanfaat daripada pendidikan teoritis.'],
            ['no' => 32, 'a' => 'Saya selalu memberikan tugas sangat sulit kepada karyawan paling berpengalaman.', 'b' => 'Saya percaya kenaikan jabatan adalah semata-mata berdasarkan kemampuan yang ada.'],
            ['no' => 33, 'a' => 'Saya merasa masalah diantara karyawan biasanya dapat diselesaikan sendiri tanpa campur tangan saya.', 'b' => 'Bila saya ditegur atasan, saya akan memanggil semua bawahan dan mengatakan semua teguran tersebut kepada mereka.'],
            ['no' => 34, 'a' => 'Saya tidak peduli dengan apa yang dikeluhkan oleh karyawan saya di luar jam kerja kantornya.', 'b' => 'Saya percaya bawahan yang tidak disiplin tidak akan memperbaiki mutu kerja dalam jangka panjang.'],
            ['no' => 35, 'a' => 'Saya memberikan informasi kepada pimpinan perusahaan tidak lebih dari apa yang mereka tanyakan.', 'b' => 'Kadang-kadang saya merasa bahwa apa yang dikeluhkan serikat buruh bukanlah masalah mendasar.'],
            ['no' => 36, 'a' => 'Saya kadang ragu-ragu untuk membuat suatu keputusan yang tidak disukai oleh bawahan-bawahan saya.', 'b' => 'Tujuan saya adalah berusaha mengerjakan tugas sebaik mungkin tanpa mengeluh.'],
            ['no' => 37, 'a' => 'Saya dengan sabar mendengarkan keluhan bawahan, tetapi sering kali saya meralat apa yang mereka katakan.', 'b' => 'Saya kadang ragu-ragu untuk membuat suatu keputusan yang tidak disukai oleh bawahan-bawahan saya.'],
            ['no' => 38, 'a' => 'Saya mengemukakan pendapat di muka umum hanya bila merasa orang lain akan setuju dengan saya.', 'b' => 'Sebagian besar bawahan dapat menyelesaikan tugas mereka tanpa kehadiran saya bila perlu.'],
            ['no' => 39, 'a' => 'Saya berusaha bekerja sebaik mungkin dan memberikan ide pengembangan pada pimpinan perusahaan.', 'b' => 'Bila memberikan tugas kepada bawahan, saya akan menentukan batas waktu penyelesaiannya.'],
            ['no' => 40, 'a' => 'Saya selalu menganjurkan bawahan memberikan usul, tapi kadang saya langsung mengambil tindakan tertentu.', 'b' => 'Saya mencoba membuat bawahan merasa senang apabila mereka berbicara dengan saya.'],
            ['no' => 41, 'a' => 'Dalam diskusi saya memberikan fakta sesuai pemahaman bawahan, dan membiarkan mereka membuat kesimpulan sendiri.', 'b' => 'Bila direktur memberikan perintah kurang menyenangkan, saya pikir bijaksana bila menyebutkan namanya bukan nama saya.'],
            ['no' => 42, 'a' => 'Bila ada tugas mendadak/tidak menyenangkan, sebelumnya saya meminta sukarelawan yang mau mengerjakan tugas tersebut.', 'b' => 'Saya menunjukkan minat terhadap kehidupan pribadi bawahan, karena sayapun mengharapkan hal yang sama.'],
            ['no' => 43, 'a' => 'Saya selalu memperhatikan kebahagiaan karyawan saya saat mereka mengerjakan tugas-tugas mereka.', 'b' => 'Saya selalu memperhatikan keterlambatan dan kemangkiran bawahan saya.'],
            ['no' => 44, 'a' => 'Sebagian besar bawahan dapat menyelesaikan tugas mereka tanpa kehadiran saya bila perlu.', 'b' => 'Bila ada tugas mendesak, walaupun peralatan selesai, saya membiarkannya dan meminta bawahan mengerjakan.'],
            ['no' => 45, 'a' => 'Saya percaya bawahan saya akan merasakan kepuasan kerja tanpa merasa tertekan oleh saya.', 'b' => 'Saya memberikan informasi kepada pimpinan perusahaan tidak lebih dari apa yang mereka tanyakan.'],
            ['no' => 46, 'a' => 'Saya percaya pertemuan pribadi membantu pengembangan diri karyawan.', 'b' => 'Saya selalu memperhatikan kebahagiaan karyawan saya saat mereka mengerjakan tugas-tugas mereka.'],
            ['no' => 47, 'a' => 'Saya mendukung bawahan meningkatkan pengetahuan pekerjaan/perusahaan walaupun belum diperlukan untuk kedudukan sekarang.', 'b' => 'Saya mengawasi benar bawahan yang kurang mahir atau yang hasil kerjanya kurang memuaskan.'],
            ['no' => 48, 'a' => 'Saya mengijinkan bawahan ikut mengambil keputusan dan saya selalu mematuhi keputusan suara terbanyak.', 'b' => 'Saya membuat bawahan bekerja keras, dan meyakinkan mereka biasanya akan mendapat perlakuan adil dari pimpinan.'],
            ['no' => 49, 'a' => 'Saya merasa semua karyawan pada jabatan yang sama seharusnya memperoleh gaji yang sama.', 'b' => 'Bila ada karyawan hasil kerjanya tidak memuaskan, saya menunggu kesempatan memindahkannya bukan memecatnya.'],
            ['no' => 50, 'a' => 'Saya merasa bahwa tujuan serikat buruh dan tujuan perusahaan saling berbeda.', 'b' => 'Saya merasa bahwa dengan bekerja keras bagi bawahan, mereka akan menyenangi saya.'],
            ['no' => 51, 'a' => 'Saya mengawasi benar bawahan yang kurang mahir atau yang hasil kerjanya kurang memuaskan.', 'b' => 'Saya mencela pembicaraan yang tidak perlu diantara bawahan selama mereka bekerja.'],
            ['no' => 52, 'a' => 'Bila memberikan tugas kepada bawahan, saya akan menentukan batas waktu penyelesaiannya.', 'b' => 'Saya tidak akan memberikan tugas yang tidak saya senangi kepada orang lain.'],
            ['no' => 53, 'a' => 'Saya percaya pengalaman bekerja lebih bermanfaat daripada pendidikan teoritis.', 'b' => 'Saya tidak peduli dengan apa yang dikeluhkan oleh karyawan saya di luar jam kantornya.'],
            ['no' => 54, 'a' => 'Saya merasa jam pencatat waktu datang dan pulang pegawai akan mengurangi keterlambatan.', 'b' => 'Saya mengijinkan bawahan ikut mengambil keputusan dan saya selalu mematuhi keputusan suara terbanyak.'],
            ['no' => 55, 'a' => 'Saya membuat keputusan sendiri, tetapi saya dapat mempertimbangkan saran wajar dari bawahan saya.', 'b' => 'Saya merasa bahwa tujuan serikat buruh dan tujuan perusahaan adalah saling berbeda.'],
            ['no' => 56, 'a' => 'Saya membuat keputusan sendiri dan kemudian mencoba "menjual" keputusan itu kepada bawahan saya.', 'b' => 'Apabila mungkin, saya akan membentuk kelompok kerja yang terdiri dari teman-teman baik saya.'],
            ['no' => 57, 'a' => 'Saya tidak ragu mempekerjakan pegawai cacat jasmani bilamana saya pasti mereka dapat menangani pekerjaannya.', 'b' => 'Saya tidak akan menegur pelanggar peraturan bila merasa pasti tidak ada yang mengetahui pelanggaran tersebut.'],
            ['no' => 58, 'a' => 'Apabila mungkin, saya akan membentuk kelompok kerja yang terdiri dari teman-teman baik saya.', 'b' => 'Saya memberikan tugas sulit kepada bawahan belum berpengalaman, tapi bila kesukaran, saya mengambil alih tanggung jawab.'],
            ['no' => 59, 'a' => 'Saya membuat bawahan bekerja keras, dan meyakinkan mereka biasanya akan mendapat perlakuan adil dari pimpinan.', 'b' => 'Saya percaya penerapan disiplin merupakan contoh untuk karyawan-karyawan lain.'],
            ['no' => 60, 'a' => 'Saya mencoba membuat bawahan merasa senang apabila mereka berbicara dengan saya.', 'b' => 'Saya menyukai penggunaan skala penggajian karyawan.'],
            ['no' => 61, 'a' => 'Saya percaya kenaikan jabatan adalah semata-mata berdasarkan kemampuan yang ada.', 'b' => 'Saya merasa masalah diantara karyawan biasanya dapat diselesaikan sendiri tanpa campur tangan saya.'],
            ['no' => 62, 'a' => 'Saya merasa serikat buruh dan pimpinan perusahaan bekerja untuk mencapai tujuan yang sama.', 'b' => 'Dalam diskusi saya memberikan fakta sesuai pemahaman bawahan, dan membiarkan mereka membuat kesimpulan sendiri.'],
            ['no' => 63, 'a' => 'Bila seorang karyawan tidak sanggup menyelesaikan tugas, saya akan membantu dia menyelesaikan tugas tersebut.', 'b' => 'Saya merasa semua karyawan pada jabatan yang sama seharusnya memperoleh gaji yang sama.'],
            ['no' => 64, 'a' => 'Saya mengijinkan bawahan ikut mengambil keputusan, tapi sayapun menyediakan sesuatu yang jitu sebagai keputusan terakhir.', 'b' => 'Saya tidak ragu mempekerjakan pegawai cacat jasmani bilamana pasti mereka dapat menangani pekerjaannya.'],
        ];

        foreach ($msdtQuestions as $q) {
            PsychologicalQuestion::updateOrCreate(
                ['test_type' => 'msdt', 'question_number' => $q['no']],
                [
                    'option_a' => $q['a'],
                    'option_b' => $q['b']
                ]
            );
        }

        // --- DATA SOAL PAPI KOSTICK (90 Soal) ---
        // --- DATA SOAL PAPI KOSTICK (90 Soal Lengkap) ---
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
            ['no' => 13, 'a' => 'Saya suka melakukan apa yang diharapkan dari saya.', 'da' => 'I', 'b' => 'Saya suka mengerjakan satu pekerjaan sampai selesai.', 'db' => 'N'],
            ['no' => 14, 'a' => 'Saya suka memikat perhatian orang.', 'da' => 'X', 'b' => 'Saya ingin menjadi orang yang sangat berhasil.', 'db' => 'A'],
            ['no' => 15, 'a' => 'Saya suka menjadi anggota kelompok.', 'da' => 'B', 'b' => 'Saya suka membantu orang lain membuat keputusan.', 'db' => 'P'],
            ['no' => 16, 'a' => 'Saya cemas jika seseorang tidak menyukai saya.', 'da' => 'O', 'b' => 'Saya senang jika orang lain memperhatikan saya.', 'db' => 'X'],
            ['no' => 17, 'a' => 'Saya suka mencoba hal-hal yang baru.', 'da' => 'Z', 'b' => 'Saya lebih suka bekerja dengan orang lain daripada bekerja sendirian.', 'db' => 'B'],
            ['no' => 18, 'a' => 'Kadang-kadang saya menyalahkan orang lain jika terjadi sesuatu kesalahan.', 'da' => 'K', 'b' => 'Saya merasa terganggu jika saya tidak akrab dengan seseorang.', 'db' => 'O'],
            ['no' => 19, 'a' => 'Saya ingin menyenangkan atasan saya.', 'da' => 'F', 'b' => 'Saya suka mencoba pekerjaan yang baru dan berbeda.', 'db' => 'Z'],
            ['no' => 20, 'a' => 'Saya suka petunjuk yang jelas untuk melakukan suatu pekerjaan.', 'da' => 'W', 'b' => 'Saya suka memberitahu orang lain jika mereka melakukan kesalahan.', 'db' => 'K'],
            ['no' => 21, 'a' => 'Saya selalu berusaha keras.', 'da' => 'G', 'b' => 'Saya suka menjadi pengarah bagi orang lain.', 'db' => 'P'],
            ['no' => 22, 'a' => 'Saya seorang pemimpin yang baik.', 'da' => 'L', 'b' => 'Saya suka mengerjakan hal-hal yang lucu.', 'db' => 'X'],
            ['no' => 23, 'a' => 'Saya seorang yang mudah menyesuaikan diri.', 'da' => 'I', 'b' => 'Saya lebih suka bekerja sendiri.', 'db' => 'B'],
            ['no' => 24, 'a' => 'Saya merasa senang jika saya diperhatikan.', 'da' => 'X', 'b' => 'Saya merasa senang jika saya menjadi anggota kelompok.', 'db' => 'O'],
            ['no' => 25, 'a' => 'Saya suka bergaul dengan orang banyak.', 'da' => 'B', 'b' => 'Saya suka mencoba hal-hal yang baru.', 'db' => 'Z'],
            ['no' => 26, 'a' => 'Saya senang jika orang-orang bersikap ramah kepada saya.', 'da' => 'O', 'b' => 'Saya suka berdebat.', 'db' => 'K'],
            ['no' => 27, 'a' => 'Saya suka mencoba hal-hal yang baru.', 'da' => 'Z', 'b' => 'Saya ingin disukai oleh atasan saya.', 'db' => 'F'],
            ['no' => 28, 'a' => 'Saya suka bertanggung jawab atas orang lain.', 'da' => 'P', 'b' => 'Saya suka mengikuti petunjuk.', 'db' => 'W'],
            ['no' => 29, 'a' => 'Saya sangat tertib.', 'da' => 'C', 'b' => 'Saya berusaha sekuat tenaga.', 'db' => 'G'],
            ['no' => 30, 'a' => 'Saya ingin menjadi pemimpin yang baik.', 'da' => 'L', 'b' => 'Saya sangat tertib.', 'db' => 'C'],
            ['no' => 31, 'a' => 'Saya seorang yang santai.', 'da' => 'E', 'b' => 'Saya membuat keputusan dengan cepat.', 'db' => 'I'],
            ['no' => 32, 'a' => 'Saya suka mengerjakan dua pekerjaan atau lebih sekaligus.', 'da' => 'T', 'b' => 'Saya suka mengerjakan pekerjaan saya sampai tuntas.', 'db' => 'N'],
            ['no' => 33, 'a' => 'Saya ingin menjadi orang yang berhasil.', 'da' => 'A', 'b' => 'Saya suka memberitahu orang lain caranya melakukan sesuatu.', 'db' => 'L'],
            ['no' => 34, 'a' => 'Saya seorang yang suka bersenang-senang.', 'da' => 'X', 'b' => 'Saya ingin berhasil.', 'db' => 'A'],
            ['no' => 35, 'a' => 'Saya suka bergabung dengan orang lain.', 'da' => 'B', 'b' => 'Saya suka menunjukkan caranya melakukan sesuatu.', 'db' => 'L'],
            ['no' => 36, 'a' => 'Saya sangat senang jika saya bersahabat intim dengan seseorang.', 'da' => 'O', 'b' => 'Saya suka tertawa dan bercanda.', 'db' => 'X'],
            ['no' => 37, 'a' => 'Saya suka berubah-ubah.', 'da' => 'Z', 'b' => 'Saya suka mengerjakan sesuatu bersama-sama dengan orang lain.', 'db' => 'B'],
            ['no' => 38, 'a' => 'Saya suka bercanda.', 'da' => 'K', 'b' => 'Saya ingin orang-orang mengenal saya dengan baik.', 'db' => 'O'],
            ['no' => 39, 'a' => 'Saya ingin menyenangkan atasan saya.', 'da' => 'F', 'b' => 'Saya suka melakukan hal-hal dengan cara yang baru dan berbeda.', 'db' => 'Z'],
            ['no' => 40, 'a' => 'Saya suka mengikuti petunjuk.', 'da' => 'W', 'b' => 'Saya suka membela hak-hak saya.', 'db' => 'K'],
            ['no' => 41, 'a' => 'Saya seorang yang sangat giat.', 'da' => 'G', 'b' => 'Saya ingin menyenangkan atasan saya.', 'db' => 'F'],
            ['no' => 42, 'a' => 'Saya suka mempengaruhi orang lain agar melakukan apa yang saya inginkan.', 'da' => 'L', 'b' => 'Saya suka mengikuti petunjuk.', 'db' => 'W'],
            ['no' => 43, 'a' => 'Saya senang jika orang lain meminta pendapat saya.', 'da' => 'P', 'b' => 'Saya seorang yang sangat tertib.', 'db' => 'C'],
            ['no' => 44, 'a' => 'Saya ingin orang-orang memperhatikan saya.', 'da' => 'X', 'b' => 'Saya seorang yang giat bekerja.', 'db' => 'G'],
            ['no' => 45, 'a' => 'Saya suka bergaul dengan orang banyak.', 'da' => 'B', 'b' => 'Saya seorang pemimpin yang baik.', 'db' => 'L'],
            ['no' => 46, 'a' => 'Saya ingin orang-orang menyukai saya.', 'da' => 'O', 'b' => 'Saya membuat keputusan dengan cepat.', 'db' => 'I'],
            ['no' => 47, 'a' => 'Saya suka melakukan hal-hal yang baru dan berbeda.', 'da' => 'Z', 'b' => 'Saya suka mengerjakan pekerjaan saya sampai tuntas.', 'db' => 'N'],
            ['no' => 48, 'a' => 'Kadang-kadang saya membenci orang yang tidak sependapat dengan saya.', 'da' => 'K', 'b' => 'Saya ingin menjadi orang yang berhasil.', 'db' => 'A'],
            ['no' => 49, 'a' => 'Saya ingin orang-orang menyukai saya.', 'da' => 'F', 'b' => 'Saya suka bertanggung jawab atas orang lain.', 'db' => 'P'],
            ['no' => 50, 'a' => 'Saya suka mengikuti petunjuk.', 'da' => 'W', 'b' => 'Saya ingin orang-orang memperhatikan saya.', 'db' => 'X'],
            ['no' => 51, 'a' => 'Saya selalu giat bekerja.', 'da' => 'G', 'b' => 'Saya banyak bergerak.', 'db' => 'V'],
            ['no' => 52, 'a' => 'Saya suka memimpin kelompok.', 'da' => 'L', 'b' => 'Saya seorang yang giat bekerja.', 'db' => 'G'],
            ['no' => 53, 'a' => 'Saya membuat keputusan secara cepat.', 'da' => 'I', 'b' => 'Saya seorang pemimpin yang baik.', 'db' => 'L'],
            ['no' => 54, 'a' => 'Saya suka mengerjakan beberapa pekerjaan sekaligus.', 'da' => 'T', 'b' => 'Saya membuat keputusan secara cepat.', 'db' => 'I'],
            ['no' => 55, 'a' => 'Saya ingin berhasil.', 'da' => 'A', 'b' => 'Saya suka mengerjakan beberapa pekerjaan sekaligus.', 'db' => 'T'],
            ['no' => 56, 'a' => 'Saya suka menarik perhatian orang.', 'da' => 'X', 'b' => 'Saya ingin berhasil.', 'db' => 'A'],
            ['no' => 57, 'a' => 'Saya suka bergabung dengan kelompok.', 'da' => 'B', 'b' => 'Saya suka menarik perhatian orang.', 'db' => 'X'],
            ['no' => 58, 'a' => 'Saya ingin orang-orang menyukai saya.', 'da' => 'O', 'b' => 'Saya suka bergabung dengan kelompok.', 'db' => 'B'],
            ['no' => 59, 'a' => 'Saya suka hal-hal yang baru.', 'da' => 'Z', 'b' => 'Saya ingin orang-orang menyukai saya.', 'db' => 'O'],
            ['no' => 60, 'a' => 'Saya suka berdebat.', 'da' => 'K', 'b' => 'Saya suka hal-hal yang baru.', 'db' => 'Z'],
            ['no' => 61, 'a' => 'Saya suka menyenangkan atasan saya.', 'da' => 'F', 'b' => 'Saya suka berdebat.', 'db' => 'K'],
            ['no' => 62, 'a' => 'Saya suka mengikuti petunjuk.', 'da' => 'W', 'b' => 'Saya suka menyenangkan atasan saya.', 'db' => 'F'],
            ['no' => 63, 'a' => 'Saya seorang yang sangat tertib.', 'da' => 'C', 'b' => 'Saya suka mengikuti petunjuk.', 'db' => 'W'],
            ['no' => 64, 'a' => 'Saya seorang yang tenang.', 'da' => 'E', 'b' => 'Saya seorang yang sangat tertib.', 'db' => 'C'],
            ['no' => 65, 'a' => 'Saya seorang yang giat bekerja.', 'da' => 'G', 'b' => 'Saya seorang yang tenang.', 'db' => 'E'],
            ['no' => 66, 'a' => 'Saya suka memimpin kelompok.', 'da' => 'L', 'b' => 'Saya banyak bergerak.', 'db' => 'V'],
            ['no' => 67, 'a' => 'Saya membuat keputusan dengan cepat.', 'da' => 'I', 'b' => 'Saya bergerak dengan cepat.', 'db' => 'S'],
            ['no' => 68, 'a' => 'Saya suka mengerjakan beberapa pekerjaan sekaligus.', 'da' => 'T', 'b' => 'Saya seorang yang sabar.', 'db' => 'R'],
            ['no' => 69, 'a' => 'Saya ingin berhasil.', 'da' => 'A', 'b' => 'Saya seorang yang teliti.', 'db' => 'D'],
            ['no' => 70, 'a' => 'Saya suka menarik perhatian orang.', 'da' => 'X', 'b' => 'Saya seorang yang tertib.', 'db' => 'C'],
            ['no' => 71, 'a' => 'Saya suka bergabung dengan kelompok.', 'da' => 'B', 'b' => 'Saya seorang yang tenang.', 'db' => 'E'],
            ['no' => 72, 'a' => 'Saya ingin orang menyukai saya.', 'da' => 'O', 'b' => 'Saya seorang yang giat bekerja.', 'db' => 'G'],
            ['no' => 73, 'a' => 'Saya suka hal-hal yang baru.', 'da' => 'Z', 'b' => 'Saya seorang pemimpin yang baik.', 'db' => 'L'],
            ['no' => 74, 'a' => 'Saya suka berdebat.', 'da' => 'K', 'b' => 'Saya membuat keputusan secara cepat.', 'db' => 'I'],
            ['no' => 75, 'a' => 'Saya suka menyenangkan atasan saya.', 'da' => 'F', 'b' => 'Saya suka mengerjakan beberapa pekerjaan sekaligus.', 'db' => 'T'],
            ['no' => 76, 'a' => 'Saya suka mengikuti petunjuk.', 'da' => 'W', 'b' => 'Saya ingin berhasil.', 'db' => 'A'],
            ['no' => 77, 'a' => 'Saya seorang yang sangat tertib.', 'da' => 'C', 'b' => 'Saya suka menarik perhatian orang.', 'db' => 'X'],
            ['no' => 78, 'a' => 'Saya seorang yang tenang.', 'da' => 'E', 'b' => 'Saya suka bergabung dengan kelompok.', 'db' => 'B'],
            ['no' => 79, 'a' => 'Saya seorang yang giat bekerja.', 'da' => 'G', 'b' => 'Saya ingin orang-orang menyukai saya.', 'db' => 'O'],
            ['no' => 80, 'a' => 'Saya seorang pemimpin yang baik.', 'da' => 'L', 'b' => 'Saya suka hal-hal yang baru.', 'db' => 'Z'],
            ['no' => 81, 'a' => 'Saya membuat keputusan dengan cepat.', 'da' => 'I', 'b' => 'Saya suka berdebat.', 'db' => 'K'],
            ['no' => 82, 'a' => 'Saya suka mengerjakan beberapa pekerjaan sekaligus.', 'da' => 'T', 'b' => 'Saya suka menyenangkan atasan saya.', 'db' => 'F'],
            ['no' => 83, 'a' => 'Saya ingin berhasil.', 'da' => 'A', 'b' => 'Saya suka mengikuti petunjuk.', 'db' => 'W'],
            ['no' => 84, 'a' => 'Saya suka menarik perhatian orang.', 'da' => 'X', 'b' => 'Saya sangat tertib.', 'db' => 'C'],
            ['no' => 85, 'a' => 'Saya suka bergabung dengan kelompok.', 'da' => 'B', 'b' => 'Saya seorang yang tenang.', 'db' => 'E'],
            ['no' => 86, 'a' => 'Saya ingin orang menyukai saya.', 'da' => 'O', 'b' => 'Saya seorang yang giat bekerja.', 'db' => 'G'],
            ['no' => 87, 'a' => 'Saya suka hal-hal yang baru.', 'da' => 'Z', 'b' => 'Saya seorang pemimpin yang baik.', 'db' => 'L'],
            ['no' => 88, 'a' => 'Saya suka berdebat.', 'da' => 'K', 'b' => 'Saya membuat keputusan dengan cepat.', 'db' => 'I'],
            ['no' => 89, 'a' => 'Saya suka menyenangkan atasan saya.', 'da' => 'F', 'b' => 'Saya suka mengerjakan beberapa pekerjaan sekaligus.', 'db' => 'T'],
            ['no' => 90, 'a' => 'Saya suka mengikuti petunjuk.', 'da' => 'W', 'b' => 'Saya ingin berhasil.', 'db' => 'A'],
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
