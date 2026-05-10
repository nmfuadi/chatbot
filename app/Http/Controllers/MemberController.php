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
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Kita jadikan ID user sebagai nama instance agar otomatis multi-tenant
        $instanceName = 'member_' . $user->id; 

        // URL dan Global API Key Server Evolution Anda
        $evolutionUrl = env('EVOLUTION_URL', 'http://103.150.196.172:8080'); 
        $globalApiKey = env('EVOLUTION_API_KEY', 'terabot123');

        $headers = [
            'apikey' => $globalApiKey,
            'Content-Type' => 'application/json'
        ];

        $deviceInfo = ['status' => 'disconnected'];
        $qrBase64 = null;

        // 1. Cek apakah instance (sesi) sudah pernah dibuat sebelumnya
        $checkState = \Illuminate\Support\Facades\Http::withHeaders($headers)
            ->get("{$evolutionUrl}/instance/connectionState/{$instanceName}");

        if ($checkState->successful()) {
            $stateData = $checkState->json();
            $state = $stateData['instance']['state'] ?? 'close';

            if ($state === 'open') {
                $deviceInfo['status'] = 'connected';
                
                // Pastikan DB juga terupdate jika instance sudah open tapi DB kosong
                if (empty($user->wablas_device_id)) {
                    $user->update(['wablas_device_id' => $instanceName]);
                }
                
            } else {
                // 2. Jika instance ada tapi terputus (disconnected), minta QR Code baru
                $getQr = \Illuminate\Support\Facades\Http::withHeaders($headers)
                    ->get("{$evolutionUrl}/instance/connect/{$instanceName}");
                
                if ($getQr->successful()) {
                    // Coba ambil dari root response atau dari dalam object
                    $qrResponse = $getQr->json();
                    $qrBase64 = $qrResponse['base64'] ?? $qrResponse['qrcode']['base64'] ?? null;
                    
                    // --- DETEKTIF 1: Jika sukses tapi QR tetap kosong ---
                    if (empty($qrBase64)) {
                        dd('SUKSES HIT API CONNECT, TAPI QR KOSONG. Balasan Server:', $qrResponse);
                    }
                } else {
                    // --- DETEKTIF 2: Jika Evolution API menolak memberikan QR ---
                    dd('GAGAL MINTA QR. Error dari Server:', $getQr->status(), $getQr->body());
                }
            }
        } elseif ($checkState->status() == 404) {
            // 3. Jika instance belum ada sama sekali (Member Baru), buat otomatis SEKALIGUS SETTING WEBHOOK!
            $createInstance = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->post("{$evolutionUrl}/instance/create", [
                    'instanceName' => $instanceName,
                    'token' => 'terabot'.$instanceName,
                    'qrcode' => true,
                    'webhook' => 'https://n8n.chatbotnew.web.id/webhook/terabot',
                    'webhook_by_events' => false,
                    'events' => [
                        'QRCODE_UPDATED',
                        'MESSAGES_UPSERT',
                        'MESSAGES_UPDATE',
                        'MESSAGES_DELETE',
                        'SEND_MESSAGE',
                        'CONNECTION_UPDATE',
                        'CALL'
                    ]
                ]);

            if ($createInstance->successful()) {
                $responseCreate = $createInstance->json();
                
                // Mengambil Base64 (Mendukung Evolution API v1 dan v2)
                $qrBase64 = $responseCreate['hash']['base64'] ?? $responseCreate['qrcode']['base64'] ?? $responseCreate['base64'] ?? null;

                // Simpan nama instance ke database
                $user->update([
                    'wablas_device_id' => $instanceName
                ]);

                // --- DETEKTIF 3: Jika Create Instance sukses tapi QR kosong ---
                if (empty($qrBase64)) {
                    dd('INSTANCE DIBUAT, TAPI QR KOSONG. Balasan Server:', $responseCreate);
                }
            } else {
                // --- DETEKTIF 4: Jika gagal membuat instance ---
                dd('GAGAL BUAT INSTANCE. Error dari Server:', $createInstance->status(), $createInstance->body());
            }
        }

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