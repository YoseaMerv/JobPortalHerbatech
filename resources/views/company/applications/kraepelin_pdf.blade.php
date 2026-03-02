<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Psikometri Kraepelin - {{ $application->user->name }}</title>
    <style>
        @page { margin: 1cm 1.5cm; }
        body { 
            font-family: 'Helvetica', Arial, sans-serif; 
            color: #1e293b; 
            line-height: 1.4; /* Dirapatkan sedikit */
            margin: 0; 
            padding: 0; 
            background-color: #ffffff;
        }
        
        /* Watermark Confidential */
        .watermark {
            position: fixed;
            top: 35%;
            left: 10%;
            transform: rotate(-45deg);
            font-size: 70px;
            color: rgba(226, 232, 240, 0.4);
            z-index: -1000;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Header */
        .header { border-bottom: 3px solid #4338ca; padding-bottom: 8px; margin-bottom: 15px; }
        .company-name { font-size: 22px; font-weight: 800; color: #4338ca; margin: 0; letter-spacing: -1px; }
        .report-type { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #64748b; margin-top: 3px; }
        .confidential { float: right; font-size: 9px; background: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-weight: bold; margin-top: -30px; }

        /* General Tables */
        table { width: 100%; border-collapse: collapse; }
        .info-table { margin-bottom: 15px; background: #f8fafc; border: 1px solid #e2e8f0; }
        .info-table td { padding: 6px 12px; font-size: 11px; border-bottom: 1px solid #f1f5f9; }
        .label { color: #64748b; font-weight: bold; width: 20%; }
        .value { color: #1e293b; font-weight: bold; width: 30%; }

        /* Section Titles */
        .section-title { 
            font-size: 11px; 
            font-weight: bold; 
            color: #ffffff; 
            background: #1e293b; 
            padding: 5px 10px; 
            border-radius: 4px; 
            margin-bottom: 10px; 
            text-transform: uppercase; 
        }
        .sub-title { font-size: 11px; font-weight: bold; color: #4338ca; margin-bottom: 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px;}

        /* Metrics Grid (P-T-J-G) */
        .metrics-table { margin-bottom: 15px; }
        .metrics-table td { width: 25%; text-align: center; padding: 8px; border: 1px solid #e2e8f0; background: #fff; }
        .metric-value { font-size: 20px; font-weight: 900; color: #4338ca; line-height: 1; }
        .metric-label { font-size: 9px; font-weight: bold; color: #334155; text-transform: uppercase; margin-top: 4px; }
        .metric-desc { font-size: 8px; color: #64748b; margin-top: 2px; }
        
        .text-success { color: #166534; }
        .text-danger { color: #991b1b; }
        .text-warning { color: #b45309; }

        /* Progress Bars */
        .progress-wrapper { margin-bottom: 8px; }
        .progress-label { font-size: 9px; font-weight: bold; margin-bottom: 2px; display: block; }
        .progress-label span { float: right; }
        .progress-bg { background-color: #e2e8f0; border-radius: 4px; width: 100%; height: 7px; }
        .progress-bar { height: 7px; border-radius: 4px; }
        .bg-success { background-color: #22c55e; }
        .bg-primary { background-color: #4338ca; }
        .bg-warning { background-color: #f59e0b; }

        /* Bar Chart (CSS Only for PDF) - DIPERPENDEK & DIRAMPINGKAN */
        .chart-table { 
            width: 85%; /* Tidak full layar agar lebih rapi */
            height: 80px; /* Tinggi dikurangi dari 120px menjadi 80px */
            margin: 5px auto 10px auto; /* Margin tengah */
            border-bottom: 1px solid #94a3b8; 
        }
        .chart-table td { vertical-align: bottom; text-align: center; width: 20%; padding: 0; border: none; height: 80px; }
        .bar { background-color: #4338ca; width: 22px; margin: 0 auto; border-radius: 3px 3px 0 0; }
        .bar-label { font-size: 9px; font-weight: bold; margin-top: 4px; color: #64748b;}

        /* Analysis Interpretation */
        .analysis-box { background: #ffffff; border: 1px solid #e2e8f0; padding: 12px 15px; margin-bottom: 15px; }
        .analysis-item { margin-bottom: 10px; }
        .analysis-item:last-child { margin-bottom: 0; }
        .analysis-text { font-size: 10px; color: #475569; text-align: justify; }

        /* Final Verdict Banner */
        .verdict-banner { padding: 12px; text-align: center; margin-top: 15px; border: 2px solid; }
        .fit { background: #f0fdf4; border-color: #22c55e; color: #166534; }
        .marginal { background: #fffbeb; border-color: #f59e0b; color: #92400e; }
        .unfit { background: #fef2f2; border-color: #ef4444; color: #991b1b; }
        .verdict-title { font-size: 9px; text-transform: uppercase; font-weight: bold; margin-bottom: 3px; }
        .verdict-value { font-size: 14px; font-weight: 900; }

        .footer { position: fixed; bottom: -0.5cm; left: 0; width: 100%; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 10px; }
    </style>
</head>
<body>

    @php
        // 1. Persiapan Data
        $chartData = is_string($test->results_chart) ? json_decode($test->results_chart, true) : $test->results_chart;
        if (!is_array($chartData)) $chartData = [];

        // 2. Data Distribusi
        $correct = $test->total_correct;
        $error = $test->total_answered - $test->total_correct;
        $skipped = max(0, $test->tianker - $error);

        // 3. Kalkulasi Persentase
        $accuracy = $test->total_answered > 0 ? round(($test->total_correct / $test->total_answered) * 100, 1) : 0;
        $pankerPerc = min(($test->panker / 25) * 100, 100);
        $jankerPerc = max(100 - ($test->janker * 6), 0);

        // 4. Kalkulasi Kuartal (Bar Chart Data)
        $quarters = [];
        for ($i = 0; $i < 50; $i += 10) {
            $slice = array_slice($chartData, $i, 10);
            $quarters[] = count($slice) > 0 ? round(array_sum($slice) / count($slice), 1) : 0;
        }
        $maxQuarter = count($quarters) > 0 ? max($quarters) : 1;
        if ($maxQuarter == 0) $maxQuarter = 1; // Mencegah division by zero error
    @endphp

    <div class="watermark">CONFIDENTIAL</div>

    <div class="header">
        <p class="company-name">HerbaTech <span style="font-size: 10px; font-weight: normal; color: #94a3b8;">| Human Resources Dept.</span></p>
        <p class="report-type">Executive Summary - Kraepelin Assessment</p>
        <div class="confidential">DOKUMEN RAHASIA</div>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td><td class="value">: {{ $application->user->name }}</td>
            <td class="label">ID / Tgl Tes</td><td class="value">: KR-{{ $test->id }} / {{ $test->completed_at->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">Posisi Tujuan</td><td class="value">: {{ $application->job->title }}</td>
            <td class="label">Durasi Tes</td><td class="value">: 1.500 Detik (50 Kolom)</td>
        </tr>
    </table>

    <div class="section-title">A. Analisis Faktor Kraepelin (Performa Tiap Bagian)</div>
    
    <table class="metrics-table">
        <tr>
            <td>
                <div class="metric-value">{{ round($test->panker, 1) }}</div>
                <div class="metric-label">PANKER (Kecepatan)</div>
                <div class="metric-desc">Kapasitas energi kerja</div>
            </td>
            <td>
                <div class="metric-value text-danger">{{ $test->tianker }}</div>
                <div class="metric-label">TIANKER (Ketelitian)</div>
                <div class="metric-desc">Kesalahan & kelalaian</div>
            </td>
            <td>
                <div class="metric-value text-warning">{{ $test->janker }}</div>
                <div class="metric-label">JANKER (Irama)</div>
                <div class="metric-desc">Stabilitas emosi</div>
            </td>
            <td>
                <div class="metric-value text-success">{{ ($test->ganker > 0 ? '+' : '') . round($test->ganker, 1) }}</div>
                <div class="metric-label">GANKER (Ketahanan)</div>
                <div class="metric-desc">Performa vs kelelahan</div>
            </td>
        </tr>
    </table>

    <table style="margin-bottom: 15px;">
        <tr>
            <td style="width: 45%; vertical-align: top; padding-right: 15px;">
                <div class="sub-title">Distribusi Jawaban Keseluruhan</div>
                <table class="info-table" style="margin-top: 8px; margin-bottom: 0; background: transparent; border: 1px solid #e2e8f0;">
                    <tr>
                        <td style="color: #64748b; padding: 4px 8px;">Total Input:</td>
                        <td style="text-align: right; font-weight: bold; padding: 4px 8px;">{{ $test->total_answered }}</td>
                    </tr>
                    <tr>
                        <td style="color: #166534; padding: 4px 8px;">Jawaban Benar:</td>
                        <td style="text-align: right; font-weight: bold; color: #166534; padding: 4px 8px;">{{ $correct }}</td>
                    </tr>
                    <tr>
                        <td style="color: #991b1b; padding: 4px 8px;">Salah Hitung:</td>
                        <td style="text-align: right; font-weight: bold; color: #991b1b; padding: 4px 8px;">{{ $error }}</td>
                    </tr>
                    <tr>
                        <td style="color: #b45309; padding: 4px 8px;">Hole (Lompatan):</td>
                        <td style="text-align: right; font-weight: bold; color: #b45309; padding: 4px 8px;">{{ $skipped }}</td>
                    </tr>
                </table>
            </td>
            
            <td style="width: 55%; vertical-align: top; padding-left: 10px;">
                <div class="sub-title">Metrik Kinerja Detail</div>
                <div style="margin-top: 8px;">
                    <div class="progress-wrapper">
                        <div class="progress-label">Accuracy Rate (Ketelitian) <span>{{ $accuracy }}%</span></div>
                        <div class="progress-bg"><div class="progress-bar bg-success" style="width: {{ $accuracy }}%;"></div></div>
                    </div>
                    <div class="progress-wrapper">
                        <div class="progress-label">Speed Capacity (Kapasitas Kerja) <span>{{ round($pankerPerc) }}%</span></div>
                        <div class="progress-bg"><div class="progress-bar bg-primary" style="width: {{ $pankerPerc }}%;"></div></div>
                    </div>
                    <div class="progress-wrapper" style="margin-bottom: 0;">
                        <div class="progress-label">Stability Index (Konsistensi) <span>{{ round($jankerPerc) }}%</span></div>
                        <div class="progress-bg"><div class="progress-bar bg-warning" style="width: {{ $jankerPerc }}%;"></div></div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">B. Analisis Produktivitas Per Kuartal (Endurance Chart)</div>
    <div style="margin-bottom: 20px;">
        <table class="chart-table">
            <tr>
                @foreach($quarters as $index => $q)
                    @php 
                        $barHeight = ($q / $maxQuarter) * 100; 
                    @endphp
                    <td>
                        <div style="font-size: 9px; font-weight: bold; color: #4338ca; margin-bottom: 2px;">{{ $q }}</div>
                        <div class="bar" style="height: {{ $barHeight }}%;"></div>
                    </td>
                @endforeach
            </tr>
            <tr>
                <td class="bar-label">Fase 1<br>(1-10)</td>
                <td class="bar-label">Fase 2<br>(11-20)</td>
                <td class="bar-label">Fase 3<br>(21-30)</td>
                <td class="bar-label">Fase 4<br>(31-40)</td>
                <td class="bar-label">Fase 5<br>(41-50)</td>
            </tr>
        </table>
    </div>

    <div class="section-title">C. Interpretasi Klinis & Kesimpulan</div>
    <div class="analysis-box">
        <div class="analysis-item">
            <div class="sub-title" style="border:none; margin-bottom:2px;">1. Kapasitas & Energi (Speed)</div>
            <div class="analysis-text">
                Kandidat menunjukkan level energi rata-rata {{ round($test->panker, 1) }} per unit waktu. Hal ini merefleksikan {{ $test->panker > 12 ? 'kapasitas mental yang luas dalam merespons tekanan tugas yang tinggi dan repetitif.' : 'tempo kerja yang cenderung normal dan metodis dalam beradaptasi dengan ritme tugas.' }}
            </div>
        </div>

        <div class="analysis-item">
            <div class="sub-title" style="border:none; margin-bottom:2px;">2. Pengendalian Diri (Accuracy)</div>
            <div class="analysis-text">
                Dengan akurasi {{ $accuracy }}% ({{ $test->tianker }} kesalahan/hole), kandidat {{ $test->tianker <= 5 ? 'memiliki mekanisme kontrol impuls yang prima, sangat cocok untuk pekerjaan dengan presisi krusial.' : 'menunjukkan kerentanan terhadap distraksi, membutuhkan supervisi pada penyelesaian tugas detail.' }}
            </div>
        </div>

        <div class="analysis-item">
            <div class="sub-title" style="border:none; margin-bottom:2px;">3. Daya Tahan Stres (Endurance)</div>
            <div class="analysis-text">
                Berdasarkan grafik fase dan nilai GANKER ({{ round($test->ganker, 1) }}), tren produktivitas bersifat {{ $test->ganker >= 0 ? 'Meningkat/Stabil' : 'Menurun' }}. Ini menunjukkan kandidat {{ $test->ganker >= 0 ? 'mampu mempertahankan fokus tanpa kelelahan mental (Fatigue) berarti.' : 'cenderung mengalami penurunan motivasi/fokus saat dihadapkan pada rutinitas panjang.' }}
            </div>
        </div>
    </div>

    @php
        // Penentuan Kesimpulan Akhir
        if($accuracy > 90 && $test->panker > 12) {
            $verdictClass = 'fit';
            $verdictText = 'SANGAT DISARANKAN (HIGH ACHIEVER)';
            $verdictDesc = 'Memiliki kombinasi sempurna antara kecepatan tinggi dan ketelitian absolut.';
        } elseif($accuracy > 80 && $test->ganker >= 0) {
            $verdictClass = 'marginal';
            $verdictText = 'DAPAT DIPERTIMBANGKAN (STEADY WORKER)';
            $verdictDesc = 'Memiliki etos kerja yang stabil dan konsisten untuk pekerjaan operasional rutin.';
        } else {
            $verdictClass = 'unfit';
            $verdictText = 'PERLU EVALUASI LANJUT (RISK OF FATIGUE)';
            $verdictDesc = 'Menunjukkan gejala impulsif atau cepat lelah di bawah tekanan berdurasi panjang.';
        }
    @endphp

    <div class="verdict-banner {{ $verdictClass }}">
        <div class="verdict-title">Rekomendasi Final Penempatan</div>
        <div class="verdict-value">{{ $verdictText }}</div>
        <div style="font-size: 9px; margin-top: 3px;">{{ $verdictDesc }}</div>
    </div>

    <div class="footer">
        <strong>Laporan Rahasia HRD HerbaTech Indonesia</strong><br>
        Dokumen dicetak secara sistemis pada {{ date('d/m/Y H:i:s') }}. Dokumen ini sah dan tidak memerlukan tanda tangan.
    </div>
</body>
</html>