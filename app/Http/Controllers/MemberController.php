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
    // A. Halaman Pairing Wablas
    public function wablasPairing()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $token = $user->wablas_api_key;
        $deviceInfo = null;
        $qrUrl = null;

        if ($token) {
            // 1. Cek Status Device Wablas menggunakan cURL
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL,  "https://jkt.wablas.com/api/device/info?token=$token");
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            $result = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($result, true);

            if (isset($response['status']) && $response['status'] == true) {
                $deviceInfo = $response['data'];

                // 2. Jika status disconnected, ambil link QR Code sekalian
                if ($deviceInfo['status'] === 'disconnected') {
                    $qrResponse = \Illuminate\Support\Facades\Http::get("https://jkt.wablas.com/api/device/scan?token={$token}");
                    if ($qrResponse->successful()) {
                        $qrUrl = $qrResponse->json(); 
                    }
                }
            }
        }

        return view('member.wablas-setup', compact('deviceInfo', 'qrUrl', 'token'));
    }

    // Tambahkan method ini di dalam MemberController
public function showProductKnowledge()
{
    // Mengambil data product knowledge milik user yang sedang login
    $pk = ProductKnowledge::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
    
    return view('member.product-knowledge', compact('pk'));
}

    // B. Konfirmasi Pembayaran
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

    // C. Setup Product Knowledge
    public function saveProductKnowledge(Request $request)
    {
        ProductKnowledge::updateOrCreate(
            ['user_id' => Auth::id()],
            ['content' => $request->content]
        );
        return back()->with('success', 'Product Knowledge berhasil disimpan untuk AI.');
    }
}
