<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama Dashboard
     */
    public function index()
{
    $user = auth()->user();

    // Ambil paket langganan PALING TERAKHIR (meskipun sudah expired)
    $latestSub = \App\Models\Subscription::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->with('plan')
                    ->first();

    $usageCount = 0;
    $maxMessages = 0;

    if ($latestSub) {
        $maxMessages = $latestSub->plan->max_messages;
        
        // Hitung chat sejak paket terakhir tersebut dimulai
        $usageCount = \App\Models\ChatHistory::where('user_id', $user->id)
                        ->where('created_at', '>=', $latestSub->starts_at)
                        ->count();
    }

    return view('dashboard', compact('latestSub', 'usageCount', 'maxMessages'));
}
    
}