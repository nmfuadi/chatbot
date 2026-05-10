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
        $userId = $user->id;
        $instanceName = 'member_' . $userId; 

        $evolutionUrl = env('EVOLUTION_URL', 'http://103.150.196.172:8080'); 
        $globalApiKey = env('EVOLUTION_API_KEY', 'terabot123');

        $headers = [
            'apikey' => $globalApiKey,
            'Content-Type' => 'application/json'
        ];

        $deviceInfo = ['status' => 'disconnected'];
        $qrBase64 = null;

        // 1. Cek status koneksi
        $checkState = \Illuminate\Support\Facades\Http::withHeaders($headers)
            ->get("{$evolutionUrl}/instance/connectionState/{$instanceName}");

        if ($checkState->successful()) {
            $stateData = $checkState->json();
            $state = $stateData['instance']['state'] ?? 'close';

            if ($state === 'open') {
                $deviceInfo['status'] = 'connected';
                
                // --- FORCE UPDATE JIKA KONEK ---
                \App\Models\User::where('id', $userId)->update([
                    'wablas_device_id' => $instanceName
                ]);
                
            } else {
                // 2. Minta QR Code baru
                $getQr = \Illuminate\Support\Facades\Http::withHeaders($headers)
                    ->get("{$evolutionUrl}/instance/connect/{$instanceName}");
                
                if ($getQr->successful()) {
                    $qrResponse = $getQr->json();
                    $qrBase64 = $qrResponse['base64'] ?? $qrResponse['qrcode']['base64'] ?? null;
                }
            }
        } elseif ($checkState->status() == 404) {
            // 3. Buat Instance Baru
            $createInstance = \Illuminate\Support\Facades\Http::withHeaders($headers)
                ->post("{$evolutionUrl}/instance/create", [
                    'instanceName' => $instanceName,
                    'token' => 'terabot123_' . $userId,
                    'qrcode' => true,
                    'webhook' => 'https://n8n.chatbotnew.web.id/webhook/terabot',
                    'webhook_by_events' => false,
                    'events' => [
                        'QRCODE_UPDATED', 'MESSAGES_UPSERT', 'MESSAGES_UPDATE', 
                        'MESSAGES_DELETE', 'SEND_MESSAGE', 'CONNECTION_UPDATE', 'CALL'
                    ]
                ]);

            if ($createInstance->successful()) {
                $responseCreate = $createInstance->json();
                $qrBase64 = $responseCreate['hash']['base64'] ?? $responseCreate['qrcode']['base64'] ?? $responseCreate['base64'] ?? null;

                // --- FORCE UPDATE SAAT BERHASIL CREATE ---
                \App\Models\User::where('id', $userId)->update([
                    'wablas_device_id' => $instanceName
                ]);
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