<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\Payment;
use App\Models\ProductKnowledge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MemberController extends Controller
{
    // A. Halaman Pairing WhatsApp (Evolution API)
    public function whatsappPairing()
    {
        $user = Auth::user();
        
        // Kita jadikan ID user sebagai nama instance agar otomatis multi-tenant
        // Contoh: member_1, member_2
        $instanceName = 'member_' . $user->id; 

        // URL dan Global API Key Server Evolution Anda
        // Idealnya ini ditaruh di file .env: EVOLUTION_URL=http://103.150.196.172:8080
        $evolutionUrl = env('EVOLUTION_URL', 'http://103.150.196.172:8080'); 
        $globalApiKey = env('EVOLUTION_API_KEY', 'terabot123');

        $headers = [
            'apikey' => $globalApiKey,
            'Content-Type' => 'application/json'
        ];

        $deviceInfo = ['status' => 'disconnected'];
        $qrBase64 = null;

        // 1. Cek apakah instance (sesi) sudah pernah dibuat sebelumnya
        $checkState = Http::withHeaders($headers)
            ->get("{$evolutionUrl}/instance/connectionState/{$instanceName}");

        if ($checkState->successful()) {
            $stateData = $checkState->json();
            $state = $stateData['instance']['state'] ?? 'close';

            if ($state === 'open') {
                $deviceInfo['status'] = 'connected';
            } else {
                // 2. Jika instance ada tapi terputus (disconnected), minta QR Code baru
                $getQr = Http::withHeaders($headers)
                    ->get("{$evolutionUrl}/instance/connect/{$instanceName}");
                
                if ($getQr->successful()) {
                    $qrBase64 = $getQr->json('base64');
                }
            }
        } elseif ($checkState->status() == 404) {
            // 3. Jika instance belum ada sama sekali (Member Baru), buat otomatis!
            $createInstance = Http::withHeaders($headers)
                ->post("{$evolutionUrl}/instance/create", [
                    'instanceName' => $instanceName,
                    'qrcode' => true
                ]);

            if ($createInstance->successful()) {
                $responseCreate = $createInstance->json();
                // Mengambil Base64 dari response Evolution API v1.8.2
                $qrBase64 = $responseCreate['hash']['base64'] ?? $responseCreate['qrcode']['base64'] ?? null;
            }
        }

        // Saya mengganti nama view menjadi whatsapp-setup agar lebih relevan
        return view('member.whatsapp-setup', compact('deviceInfo', 'qrBase64', 'instanceName'));
    }

    // =====================================================================
    // KODE DI BAWAH INI TETAP SAMA, TIDAK ADA YANG DIUBAH
    // =====================================================================

    public function showProductKnowledge()
    {
        $pk = ProductKnowledge::where('user_id', Auth::id())->first();
        return view('member.product-knowledge', compact('pk'));
    }

    public function showPayment()
    {
        $banks = BankAccount::all();
        return view('member.payment', compact('banks'));
    }

    public function submitPayment(Request $request)
    {
        $request->validate([
            'bank_account_id' => 'required',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('proof_image')->store('payments', 'public');

        Payment::create([
            'user_id' => Auth::id(),
            'bank_account_id' => $request->bank_account_id,
            'proof_image' => $path,
        ]);

        return back()->with('success', 'Konfirmasi berhasil dikirim. Menunggu approval Admin.');
    }

    public function saveProductKnowledge(Request $request)
    {
        ProductKnowledge::updateOrCreate(
            ['user_id' => Auth::id()],
            ['content' => $request->content]
        );
        return back()->with('success', 'Product Knowledge berhasil disimpan untuk AI.');
    }
}