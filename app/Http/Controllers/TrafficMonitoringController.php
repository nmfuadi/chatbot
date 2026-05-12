<?php

namespace App\Http\Controllers;

use App\Models\BotLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrafficMonitoringController extends Controller
{
    public function index()
    {
        // 1. Hitung Total Pesan Hari Ini
        $todayLogs = BotLog::whereDate('created_at', Carbon::today())->count();
        
        // 2. Hitung Total Error Hari Ini
        $todayErrors = BotLog::whereDate('created_at', Carbon::today())
                             ->where('status', 'error')->count();

        // 3. Hitung Rata-rata Waktu Respons AI (Latency) dalam satuan detik
        $avgProcessingTimeMs = BotLog::where('status', 'success')
                                     ->whereDate('created_at', Carbon::today())
                                     ->avg('processing_time_ms');
        $avgResponseSec = $avgProcessingTimeMs ? round($avgProcessingTimeMs / 1000, 2) : 0;

        // 4. Ambil 10 Log Terakhir untuk ditampilkan di tabel
        $recentLogs = BotLog::orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.traffic-monitoring', compact('todayLogs', 'todayErrors', 'avgResponseSec', 'recentLogs'));
    }
}