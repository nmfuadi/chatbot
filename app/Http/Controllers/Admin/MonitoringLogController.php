<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class MonitoringLogController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');
        // storage_path otomatis mengarah ke /var/www/chatbot/storage/
        $path = storage_path("app/bot-logs/bot-{$today}.log");

        $stats = ['total' => 0, 'error' => 0, 'latency_sum' => 0, 'tokens' => 0, 'logs' => []];

        if (File::exists($path)) {
            $lines = file($path);
            foreach ($lines as $line) {
                $data = json_decode($line, true);
                if (!$data) continue;

                $stats['total']++;
                if (isset($data['status']) && $data['status'] === 'error') {
                    $stats['error']++;
                }
                $stats['latency_sum'] += $data['latency'] ?? 0;
                $stats['tokens'] += ($data['p_tokens'] ?? 0) + ($data['c_tokens'] ?? 0);
                
                // Ambil 15 data terbaru saja untuk tabel
                array_unshift($stats['logs'], $data);
            }
            $stats['logs'] = array_slice($stats['logs'], 0, 15);
        }

        $avgLatency = $stats['total'] > 0 ? round(($stats['latency_sum'] / $stats['total']) / 1000, 2) : 0;

        // Ambil daftar file log untuk manajemen hapus
        $allFiles = File::files(storage_path("app/bot-logs"));
        $logFiles = collect($allFiles)->map(fn($f) => $f->getFilename())->sortDesc();

        return view('admin.monitoring-log', compact('stats', 'avgLatency', 'logFiles', 'today'));
    }

    public function destroy($filename)
    {
        $path = storage_path("app/bot-logs/{$filename}");
        if (File::exists($path)) {
            File::delete($path);
            return back()->with('success', "File log $filename telah dihapus.");
        }
        return back()->with('error', "File tidak ditemukan.");
    }
}