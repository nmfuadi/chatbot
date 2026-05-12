<?php

namespace App\Http\Controllers;

use App\Models\BotLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class AiMonitoringController extends Controller
{
    public function index()
    {
        // 1. Cek Status Server Groq
        $groqKey = env('GROQ_API_KEY');
        try {
            $response = Http::withToken($groqKey)
                            ->timeout(5)
                            ->get('https://api.groq.com/openai/v1/models');
            
            $apiStatus = $response->successful() ? 'online' : 'error';
            $models = $response->successful() ? count($response->json()['data'] ?? []) : 0;
        } catch (\Exception $e) {
            $apiStatus = 'offline';
            $models = 0;
        }

        // 2. Hitung Penggunaan Token Hari Ini
        $todayLogs = BotLog::whereDate('created_at', Carbon::today())->get();
        $promptTokensToday = $todayLogs->sum('prompt_tokens');
        $completionTokensToday = $todayLogs->sum('completion_tokens');
        $totalTokensToday = $promptTokensToday + $completionTokensToday;

        // 3. Hitung Penggunaan Token Bulan Ini
        $monthlyLogs = BotLog::whereMonth('created_at', Carbon::now()->month)
                             ->whereYear('created_at', Carbon::now()->year)->get();
        $totalTokensMonth = $monthlyLogs->sum('prompt_tokens') + $monthlyLogs->sum('completion_tokens');

        return view('admin.ai-monitoring', compact(
            'apiStatus', 'models', 'promptTokensToday', 'completionTokensToday', 'totalTokensToday', 'totalTokensMonth'
        ));
    }
}