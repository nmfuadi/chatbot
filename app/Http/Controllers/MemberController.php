<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\Payment;
use App\Models\ProductKnowledge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\TrackingIntegration;

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
                // Di v2, format JSON respons bisa sedikit berbeda, kita gunakan fallback
                $state = $stateData['instance']['state'] ?? $stateData['state'] ?? 'close';

                if ($state === 'open') {
                    $deviceInfo['status'] = 'connected';
                    
                    // --- FORCE UPDATE JIKA KONEK ---
                    \App\Models\User::where('id', $userId)->update([
                        'wablas_device_id' => $instanceName
                    ]);
                    
                } else {
                    // 2. Minta QR Code baru (Jika instance ada tapi terputus)
                    $getQr = \Illuminate\Support\Facades\Http::withHeaders($headers)
                        ->get("{$evolutionUrl}/instance/connect/{$instanceName}");
                    
                    if ($getQr->successful()) {
                        $qrResponse = $getQr->json();
                        $qrBase64 = $qrResponse['base64'] ?? $qrResponse['qrcode']['base64'] ?? null;
                    }
                }
            } elseif ($checkState->status() == 404) {
                // 3. Buat Instance Baru (SUDAH DIUPDATE KE FORMAT V2.x)
                $createInstance = \Illuminate\Support\Facades\Http::withHeaders($headers)
                    ->post("{$evolutionUrl}/instance/create", [
                        'instanceName' => $instanceName,
                        'token' => 'terabot123_' . $userId,
                        'qrcode' => true,
                        'integration' => 'WHATSAPP-BAILEYS', // Parameter wajib di v2.x
                        'webhook' => [
                            'url' => 'https://chatbotnew.web.id/python-api/webhook/wablas',
                            'byEvents' => false,
                            'base64' => false, // Penting: agar n8n tidak crash menerima file Base64 gambar
                            'headers' => [
                                'Content-Type' => 'application/json'
                            ],
                            'events' => [
                                'APPLICATION_STARTUP',
                                'MESSAGES_UPSERT'
                            ]
                        ]
                    ]);

                if ($createInstance->successful()) {
                    $responseCreate = $createInstance->json();
                    // Menangkap QR Code dari respons v2.x
                    $qrBase64 = $responseCreate['qrcode']['base64'] ?? $responseCreate['base64'] ?? null;

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
        $pk = \App\Models\ProductKnowledge::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        
        return view('member.product-knowledge', compact('pk'));
    }

    public function saveProductKnowledge(\Illuminate\Http\Request $request)
    {
        // 1. Validasi
        $request->validate([
            'content'           => 'nullable|string',
            'ai_name'           => 'nullable|string|max:50',
            'customer_call'     => 'nullable|string|max:50',
            'gaya_bahasa'       => 'nullable|string',
            'gaya_berpikir'     => 'nullable|string',
            'primary_objective' => 'nullable|string',
            'reply_length'      => 'nullable|string',
            'fallback_behavior' => 'nullable|string',
            'use_emoji'         => 'nullable|string',
            'catalog_trigger_words' => 'nullable|string',
        ]);

        // 2. Coba Simpan ke Database (Pakai Try-Catch)
        try {
            \App\Models\ProductKnowledge::updateOrCreate(
                ['user_id' => \Illuminate\Support\Facades\Auth::id()],
                [
                    'content'           => $request->content,
                    'ai_name'           => $request->ai_name,
                    'customer_call'     => $request->customer_call,
                    'gaya_bahasa'       => $request->gaya_bahasa,
                    'gaya_berpikir'     => $request->gaya_berpikir,
                    'primary_objective' => $request->primary_objective,
                    'reply_length'      => $request->reply_length,
                    'fallback_behavior' => $request->fallback_behavior,
                    'use_emoji'         => $request->use_emoji,
                    'catalog_trigger_words' => $request->catalog_trigger_words,
                ]
            );

            return back()->with('success', 'SOP dan Pengaturan Gaya Bot berhasil disimpan.');

        } catch (\Exception $e) {
            // 3. Jika gagal, catat ke log (storage/logs/laravel.log)
            \Illuminate\Support\Facades\Log::error('ERROR SIMPAN PK: ' . $e->getMessage());
            
            // 4. Langsung muntahkan error aslinya ke layar agar kita tahu penyebab pastinya!
            dd('GAGAL MENYIMPAN KE DATABASE! Pesan Error: ', $e->getMessage());
        }
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

    // 2. Menyimpan halaman SOP saja
    

        // ====================================================================
    // --- MENU INTEGRASI & TRACKING (CAPI, GA4, TIKTOK) ---
    // ====================================================================

    public function showIntegrations()
        {
            // Ambil data integrasi dan kelompokkan berdasarkan nama provider
            $integrations = \App\Models\TrackingIntegration::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->get()
                ->keyBy('provider');

            return view('member.integrations', compact('integrations'));
        }

    public function saveIntegrations(\Illuminate\Http\Request $request)
        {
            $userId = \Illuminate\Support\Facades\Auth::id();

            // Looping data provider yang dikirim dari form
            if ($request->has('providers')) {
                foreach ($request->providers as $providerName => $data) {
                    
                    // Cek apakah user mengisi ID Pixel atau Webhook URL
                    if (!empty($data['pixel_id']) || !empty($data['access_token'])) {
                        \App\Models\TrackingIntegration::updateOrCreate(
                            [
                                'user_id' => $userId, 
                                'provider' => $providerName
                            ],
                            [
                                'pixel_id' => $data['pixel_id'] ?? null,
                                'access_token' => $data['access_token'] ?? null,
                                'is_active' => isset($data['is_active']) ? true : false,
                            ]
                        );
                    } else {
                        // Jika dikosongkan saat update, hapus dari database agar bersih
                        \App\Models\TrackingIntegration::where('user_id', $userId)
                            ->where('provider', $providerName)
                            ->delete();
                    }
                }
            }

            return back()->with('success', 'Konfigurasi Tracking & API berhasil disimpan. Event akan dikirimkan secara realtime!');
        }

    public function showAiRules()
    {
        $pk = ProductKnowledge::where('user_id', Auth::id())->first();
        return view('member.ai-rules', compact('pk'));
    }

    // 4. Menyimpan data Aturan AI
    public function saveAiRules(Request $request)
    {
        $request->validate([
            'objection_reasons' => 'nullable|string',
            'lead_rule_baru' => 'nullable|string',
            'lead_rule_prospect' => 'nullable|string',
            'lead_rule_hot_prospek' => 'nullable|string',
            'lead_rule_deal' => 'nullable|string',
            'lead_rule_closing' => 'nullable|string',
            'lead_rule_gagal' => 'nullable|string',
        ]);

        ProductKnowledge::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'objection_reasons' => $request->objection_reasons,
                'lead_rule_baru' => $request->lead_rule_baru,
                'lead_rule_prospect' => $request->lead_rule_prospect,
                'lead_rule_hot_prospek' => $request->lead_rule_hot_prospek,
                'lead_rule_deal' => $request->lead_rule_deal,
                'lead_rule_closing' => $request->lead_rule_closing,
                'lead_rule_gagal' => $request->lead_rule_gagal,
            ]
        );

        return back()->with('success', 'Aturan Indikator Pipeline AI berhasil diperbarui.');
    }


    public function storeBlacklist(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'phone_number' => 'required'
        ]);

        $phone = $request->phone_number;

        // 1. Bersihkan semua karakter selain angka (spasi, strip, tanda + hilang)
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // 2. Normalisasi: Jika berawalan 08, ganti 0 dengan 62
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }

        // Simpan ke database
        \App\Models\Blacklist::updateOrCreate([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'phone_number' => $phone
        ]);

        return back()->with('success', "Nomor $phone berhasil dimasukkan ke daftar Blacklist!");
    }
}