<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChatSession;
use App\Models\ProductKnowledge;

class WebhookController extends Controller
{
    // API untuk mengambil konteks SOP dan Kredensial Wablas
    public function getContext(Request $request)
    {
        $device_id = $request->device_id;
        $customer_phone = $request->customer_phone;

        // 1. Cari Member berdasarkan Device ID
        $member = User::where('wablas_device_id', $device_id)->first();
        if (!$member) return response()->json(['error' => 'Member not found'], 404);

        // 2. Ambil atau buat sesi chat
        $session = ChatSession::firstOrCreate(
            ['user_id' => $member->id, 'customer_phone' => $customer_phone],
            ['is_ai_active' => true]
        );

        // 3. Ambil data SOP
        $sop = ProductKnowledge::where('user_id', $member->id)->value('content');

        return response()->json([
            'is_ai_active' => $session->is_ai_active,
            'sop' => $sop,
            'wablas_api_key' => $member->wablas_api_key,
            'wablas_secret_key' => $member->wablas_secret_key // Tambahan Secret Key
        ]);
    }

    // API untuk mematikan/menyalakan AI (Pause/Resume)
    public function toggleAi(Request $request)
    {
        $device_id = $request->device_id;
        $customer_phone = $request->customer_phone;
        $status = $request->status; // 'true' atau 'false' (string dari n8n)

        $member = User::where('wablas_device_id', $device_id)->first();
        if (!$member) return response()->json(['error' => 'Member not found'], 404);

        // Update status AI di database
        ChatSession::where('user_id', $member->id)
            ->where('customer_phone', $customer_phone)
            ->update(['is_ai_active' => filter_var($status, FILTER_VALIDATE_BOOLEAN)]);

        return response()->json([
            'success' => true,
            'new_status' => $status,
            'wablas_api_key' => $member->wablas_api_key,    // Mengembalikan key agar n8n bisa langsung pakai
            'wablas_secret_key' => $member->wablas_secret_key // Mengembalikan secret agar n8n bisa langsung pakai
        ]);
    }
}