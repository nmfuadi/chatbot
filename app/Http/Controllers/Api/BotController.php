<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductKnowledge;
use App\Models\ChatHistory;

class BotController extends Controller {
    
    // 1. API untuk Get SOP Context & History
    public function getContext(Request $request) {
        $request->validate([
            'device_id' => 'required',
            'customer_phone' => 'required'
        ]);

        // Cari member berdasarkan device_id dari Wablas
        $member = User::where('wablas_device_id', $request->device_id)->first();

        if (!$member) {
            return response()->json(['error' => 'Member tidak ditemukan'], 404);
        }

        // Ambil SOP (Product Knowledge)
        $knowledge = ProductKnowledge::where('user_id', $member->id)
                        ->pluck('content')->implode("\n\n");

        if(empty($knowledge)) $knowledge = "Tidak ada SOP khusus.";

        // Ambil History Chat 24 Jam Terakhir untuk customer ini
        $histories = ChatHistory::where('user_id', $member->id)
            ->where('customer_wa', $request->customer_phone)
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'asc')
            ->get();

        $historyText = "";
        foreach ($histories as $h) {
            $historyText .= "Customer: " . $h->user_message . "\n";
            $historyText .= "AI: " . $h->ai_response . "\n";
        }

        return response()->json([
            'knowledge' => $knowledge,
            'history' => $historyText,
            'wablas_api_key' => $member->wablas_api_key,
            'wablas_secret_key' => $member->wablas_secret_key,
            'history' => $histories
        ]);
    }

    // 2. API untuk Save History (Dipanggil setelah AI membalas)
    public function saveHistory(Request $request) {
        $member = User::where('wablas_device_id', $request->device_id)->first();

        if ($member) {
            ChatHistory::create([
                'user_id' => $member->id,
                'customer_wa' => $request->customer_phone,
                'user_message' => $request->user_message,
                'ai_response' => $request->ai_response,
            ]);
        }
        return response()->json(['status' => 'success']);
    }
}