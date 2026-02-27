<!DOCTYPE html>
<html>
<head>
    <title>Laporan Detail Performa Kraepelin - {{ $application->user->name }}</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; color: #1e293b; line-height: 1.5; margin: 0; padding: 0; }
        
        /* Header & Branding */
        .header { border-bottom: 3px solid #4338ca; padding-bottom: 20px; margin-bottom: 25px; text-align: left; }
        .logo-text { font-size: 24px; font-weight: bold; color: #4338ca; margin: 0; }
        .report-title { font-size: 14px; text-transform: uppercase; letter-spacing: 2px; color: #64748b; margin-top: 5px; }
        
        /* Information Table */
        .info-table { width: 100%; background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { font-size: 12px; padding: 4px 0; vertical-align: top; }
        .label-cell { color: #64748b; width: 120px; }
        .value-cell { font-weight: bold; }

        .metrics-wrapper {
            width: 100%;
            margin: 20px 0;
        }
        .metrics-table {
            width: 90%; /* Memberikan margin otomatis di kanan-kiri */
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 10px 0;
        }
        .metric-card {
            width: 25%;
            border: 1px solid #e2e8f0;
            padding: 15px 5px;
            text-align: center;
            border-radius: 10px;
            background: #ffffff;
        }
        .metric-value { font-size: 20px; font-weight: bold; color: #4338ca; margin-bottom: 5px; }
        .metric-label { font-size: 10px; font-weight: bold; color: #64748b; text-transform: uppercase; }
        .metric-desc { font-size: 8px; color: #94a3b8; margin-top: 5px; line-height: 1.2; padding: 0 5px; }

        /* Analysis Sections */
        .section-header { font-size: 13px; font-weight: bold; color: #4338ca; border-left: 4px solid #4338ca; padding-left: 10px; margin: 25px 0 15px 0; text-transform: uppercase; }
        .analysis-content { font-size: 12px; background: #fff; border: 1px solid #f1f5f9; padding: 15px; border-radius: 8px; }
        
        .interpretation-item { margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px dashed #e2e8f0; }
        .interpretation-item:last-child { border-bottom: none; }
        .int-title { font-weight: bold; font-size: 12px; color: #334155; }
        .int-text { font-size: 11px; color: #64748b; margin-top: 3px; text-align: justify; }

        /* Recommendation Box */
        .recommendation { margin-top: 30px; padding: 20px; border-radius: 12px; text-align: center; }
        .rec-fit { background: #dcfce7; border: 1px solid #22c55e; color: #166534; }
        .rec-consider { background: #fef9c3; border: 1px solid #eab308; color: #854d0e; }
        .rec-not { background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; }

        .footer { position: fixed; bottom: 0cm; left: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <p class="logo-text">HerbaTech</p>
        <p class="report-title">Hasil Penilaian Psikologis Kraepelin</p>
    </div>

    <div class="info-table">
        <table width="100%">
            <tr>
                <td class="label-cell">Nama Kandidat</td>
                <td class="value-cell">: {{ $application->user->name }}</td>
                <td class="label-cell">ID Assessment</td>
                <td class="value-cell">: Assessment-{{ str_pad($test->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td class="label-cell">Posisi Dilamar</td>
                <td class="value-cell">: {{ $application->job->title }}</td>
                <td class="label-cell">Metode Tes</td>
                <td class="value-cell">: Digital Kraepelin Test</td>
            </tr>
            <tr>
                <td class="label-cell">Waktu Selesai</td>
                <td class="value-cell">: {{ $test->completed_at->translatedFormat('d F Y, H:i') }} WIB</td>
                <td class="label-cell">Durasi Total</td>
                <td class="value-cell">: 1.500 Detik (50 Kolom)</td>
            </tr>
        </table>
    </div>

    <div class="section-header">Indikator Kinerja Utama</div>
    
    <div class="metrics-wrapper">
        <table class="metrics-table">
            <tr>
                <td class="metric-card">
                    <div class="metric-value">{{ $test->total_answered }}</div>
                    <div class="metric-label">PANKER</div>
                    <div class="metric-desc">Faktor Kecepatan: Menunjukkan energi kerja dan produktivitas.</div>
                </td>

                <td class="metric-card">
                    <div class="metric-value">{{ $test->total_correct }}</div>
                    <div class="metric-label">TIANKER</div>
                    <div class="metric-desc">Faktor Ketelitian: Menunjukkan kemampuan meminimalisir kesalahan.</div>
                </td>

                <td class="metric-card">
                    <div class="metric-value">{{ $accuracy }}%</div>
                    <div class="metric-label">JANKER</div>
                    <div class="metric-desc">Faktor Ketahanan: Kemampuan fokus pada jangka waktu lama.</div>
                </td>

                <td class="metric-card">
                    @php
                        $wrong = $test->total_answered - $test->total_correct;
                        $stability = $test->total_answered > 0 ? round((1 - ($wrong / $test->total_answered)) * 100, 1) : 0;
                    @endphp
                    <div class="metric-value">{{ $stability }}%</div>
                    <div class="metric-label">GANKER</div>
                    <div class="metric-desc">Faktor Stabilitas: Menunjukkan konsistensi emosi dan ritme.</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-header">Interpretasi Mendetail</div>
    <div class="analysis-content">
        <div class="interpretation-item">
            <div class="int-title">1. Aspek Kecepatan Kerja (Work Speed)</div>
            <div class="int-text">
                Kandidat memiliki total input {{ $test->total_answered }} data. Ini mengindikasikan bahwa kandidat memiliki 
                <strong>@if($test->total_answered > 1200) motivasi berprestasi yang sangat tinggi @elseif($test->total_answered > 800) tempo kerja yang dinamis @else tempo kerja yang cenderung berhati-hati @endif</strong> 
                dalam menangani beban tugas yang repetitif.
            </div>
        </div>
        <div class="interpretation-item">
            <div class="int-title">2. Aspek Ketelitian & Konsentrasi (Accuracy)</div>
            <div class="int-text">
                Dengan tingkat akurasi {{ $accuracy }}%, kandidat menunjukkan kemampuan 
                <strong>@if($accuracy > 95) kontrol diri yang sangat prima @elseif($accuracy > 85) kemampuan deteksi kesalahan yang baik @else kerentanan terhadap distraksi @endif</strong> 
                ketika bekerja di bawah tekanan waktu (time pressure).
            </div>
        </div>
        <div class="interpretation-item">
            <div class="int-title">3. Stabilitas Emosi & Ketahanan (Stability)</div>
            <div class="int-text">
                @if($stability > 90)
                    Kandidat mampu menjaga ritme kerja secara konsisten dari awal hingga akhir sesi tanpa menunjukkan tanda-tanda kelelahan mental yang signifikan.
                @else
                    Terdapat fluktuasi pada performa kandidat yang menunjukkan adanya pengaruh tekanan lingkungan terhadap kestabilan konsentrasi.
                @endif
            </div>
        </div>
    </div>

    @php
        $isRecommended = $accuracy > 85 && $test->total_answered > 800;
        $isConsidered = $accuracy > 75 && $test->total_answered > 600;
    @endphp

    <div class="recommendation @if($isRecommended) rec-fit @elseif($isConsidered) rec-consider @else rec-not @endif">
        <div style="font-size: 10px; text-transform: uppercase; margin-bottom: 5px;">Kesimpulan Rekomendasi</div>
        <div style="font-size: 18px; font-weight: bold;">
            @if($isRecommended)
                MEMENUHI SYARAT (RECOMMENDED)
            @elseif($isConsidered)
                DIPERTIMBANGKAN (TO BE CONSIDERED)
            @else
                TIDAK MEMENUHI SYARAT (NOT RECOMMENDED)
            @endif
        </div>
        <div style="font-size: 11px; margin-top: 5px;">
            @if($isRecommended)
                Kandidat sangat potensial untuk mengemban tanggung jawab yang membutuhkan kecepatan dan akurasi tinggi.
            @elseif($isConsidered)
                Kandidat memerlukan supervisi tambahan atau pelatihan fokus untuk mengoptimalkan potensi kerja.
            @else
                Kandidat kurang disarankan untuk posisi yang memiliki tingkat stres tinggi atau detail teknis yang kritis.
            @endif
        </div>
    </div>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh sistem rekrutmen HerbaTech. <br>
        Segala bentuk manipulasi data pada laporan ini merupakan pelanggaran kebijakan perusahaan.
    </div>
</body>
</html>