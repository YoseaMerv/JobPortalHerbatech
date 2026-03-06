<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function jobs(Request $request)
    {
        $stats = [
            'total' => Job::count(),
            'active' => Job::where('status', 'published')->where('deadline', '>=', now())->count(),
            'expired' => Job::where('deadline', '<', now())->count(),
            'by_category' => DB::table('jobs')
                ->join('job_categories', 'jobs.category_id', '=', 'job_categories.id')
                ->select('job_categories.name', DB::raw('count(*) as total'))
                ->groupBy('job_categories.name')
                ->get(),
        ];
        
        // Buat PDF Langsung dari HTML String
        if ($request->query('export') === 'pdf') {
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
                    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>Laporan Statistik Lowongan Pekerjaan</h2>
                    <p>PT HerbaTech Innopharma Industry | Dicetak: ' . date('d M Y H:i') . '</p>
                </div>
                <p><strong>Total Lowongan:</strong> ' . $stats['total'] . '</p>
                <p><strong>Lowongan Aktif:</strong> ' . $stats['active'] . '</p>
                <p><strong>Lowongan Kedaluwarsa:</strong> ' . $stats['expired'] . '</p>
                
                <h4>Distribusi per Kategori:</h4>
                <table>
                    <tr><th style="width: 50px;">No</th><th>Kategori</th><th>Jumlah Lowongan</th></tr>';
            
            foreach ($stats['by_category'] as $index => $cat) {
                $html .= '<tr><td style="text-align:center;">' . ($index + 1) . '</td><td>' . $cat->name . '</td><td style="text-align:center;">' . $cat->total . '</td></tr>';
            }

            $html .= '</table></body></html>';

            return Pdf::loadHTML($html)->download('Laporan_Lowongan_Pekerjaan_HerbaTech.pdf');
        }

        return view('admin.reports.jobs', compact('stats'));
    }

    public function applications(Request $request)
    {
        $stats = [
            'total' => JobApplication::count(),
            'pending' => JobApplication::where('status', 'pending')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
            'accepted' => JobApplication::where('status', 'accepted')->count(),
            'daily_applications' => JobApplication::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get(),
        ];

        if ($request->query('export') === 'pdf') {
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
                    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>Laporan Statistik Lamaran Masuk</h2>
                    <p>PT HerbaTech Innopharma Industry | Dicetak: ' . date('d M Y H:i') . '</p>
                </div>
                <p><strong>Total Lamaran Keseluruhan:</strong> ' . $stats['total'] . '</p>
                <ul>
                    <li>Menunggu (Pending): ' . $stats['pending'] . '</li>
                    <li>Terpilih (Shortlisted): ' . $stats['shortlisted'] . '</li>
                    <li>Diterima (Accepted): ' . $stats['accepted'] . '</li>
                    <li>Ditolak (Rejected): ' . $stats['rejected'] . '</li>
                </ul>
                
                <h4>Tren Lamaran Harian (30 Hari Terakhir):</h4>
                <table>
                    <tr><th style="width: 50px;">No</th><th>Tanggal</th><th>Jumlah Lamaran</th></tr>';
            
            foreach ($stats['daily_applications'] as $index => $day) {
                $html .= '<tr><td style="text-align:center;">' . ($index + 1) . '</td><td>' . \Carbon\Carbon::parse($day->date)->format('d F Y') . '</td><td style="text-align:center;">' . $day->total . '</td></tr>';
            }

            $html .= '</table></body></html>';

            return Pdf::loadHTML($html)->download('Laporan_Lamaran_HerbaTech.pdf');
        }

        return view('admin.reports.applications', compact('stats'));
    }

    public function users(Request $request)
    {
        $stats = [
            'total' => User::count(),
            'seekers' => User::where('role', 'seeker')->count(),
            'companies' => User::where('role', 'company')->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        if ($request->query('export') === 'pdf') {
            $html = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
                    .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
                    .box { border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; background-color: #f9f9f9; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>Laporan Pengguna Sistem (Job Portal)</h2>
                    <p>PT HerbaTech Innopharma Industry | Dicetak: ' . date('d M Y H:i') . '</p>
                </div>
                
                <div class="box">
                    <h3>Total Pengguna Terdaftar: ' . $stats['total'] . '</h3>
                </div>
                <div class="box">
                    <p><strong>Rincian Peran (Role):</strong></p>
                    <ul>
                        <li>Pencari Kerja (Seeker): ' . $stats['seekers'] . ' Pengguna</li>
                        <li>Perusahaan (Company): ' . $stats['companies'] . ' Pengguna</li>
                    </ul>
                </div>
                <div class="box">
                    <p><strong>Pertumbuhan Bulan Ini:</strong> ' . $stats['new_this_month'] . ' Pengguna Baru</p>
                </div>
            </body></html>';

            return Pdf::loadHTML($html)->download('Laporan_Pengguna_Sistem_HerbaTech.pdf');
        }

        return view('admin.reports.users', compact('stats'));
    }
}