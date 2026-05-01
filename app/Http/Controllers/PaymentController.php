<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // Tambahkan ini

class PaymentController extends Controller
{
    /**
     * Menangani Callback dari Duitku
     */
    public function callback(Request $request)
    {
        $apiKey = env('DUITKU_API_KEY');
        
        $merchantCode = $request->input('merchantCode');
        $amount = $request->input('amount');
        $merchantOrderId = $request->input('merchantOrderId'); // Ini adalah Nomor Invoice kita
        $signature = $request->input('signature');
        $resultCode = $request->input('resultCode'); // '00' artinya sukses
        $reference = $request->input('reference');

        // Pastikan parameter penting tidak kosong
        if (!empty($merchantCode) && !empty($amount) && !empty($merchantOrderId) && !empty($signature)) {
            
            // Rumus MD5 dari Duitku
            $params = $merchantCode . $amount . $merchantOrderId . $apiKey;
            $calcSignature = md5($params);

            // Validasi Keaslian Data
            if ($signature == $calcSignature) {
                
                // Jika Duitku menyatakan pembayaran sukses ('00')
                if ($resultCode == '00') {
                    
                    // Cari invoice berdasarkan nomor
                    $invoice = Invoice::where('invoice_number', $merchantOrderId)->first();

                    if ($invoice && $invoice->status === 'unpaid') {
                        
                        // 1. Ubah status Invoice jadi Lunas
                        $invoice->update(['status' => 'paid']);

                        // 2. Aktifkan paket langganan (Subscription)
                        $subscription = $invoice->subscription;
                        $subscription->update([
                            'status' => 'active',
                            'starts_at' => now(),
                            'ends_at' => now()->addDays(30), // Asumsi langganan 30 hari
                            'payment_id' => $reference // Simpan nomor referensi Duitku
                        ]);

                        // 3. Update status user agar bisa masuk dashboard
                        $invoice->user->update(['subscription_status' => 'active']);
                        
                        Log::info("Pembayaran Sukses: Invoice {$merchantOrderId} telah dilunasi.");
                    }
                }

                // Wajib mengembalikan HTTP 200 OK agar Duitku tidak mengirim ulang datanya
                return response()->json(['status' => 'success', 'message' => 'Callback diterima'], 200);

            } else {
                Log::error("Duitku Callback: Bad Signature untuk Order {$merchantOrderId}");
                return response()->json(['status' => 'error', 'message' => 'Bad Signature'], 400);
            }
        } else {
            Log::error("Duitku Callback: Parameter tidak lengkap.");
            return response()->json(['status' => 'error', 'message' => 'Bad Parameter'], 400);
        }
    }

    public function getPaymentMethods(Invoice $invoice)
    {
        // 1. Keamanan: Pastikan hanya pemilik invoice yang belum lunas yang bisa akses
        if ($invoice->user_id !== auth()->id() || $invoice->status !== 'unpaid') {
            abort(403, 'Akses ditolak atau tagihan sudah lunas.');
        }

        // 2. Siapkan parameter untuk Duitku
        $merchantCode = env('DUITKU_MERCHANT_CODE');
        $apiKey = env('DUITKU_API_KEY');
        $datetime = date('Y-m-d H:i:s');
        $paymentAmount = (int) $invoice->amount; // Duitku butuh format integer

        // 3. Buat Signature sesuai rumus Duitku
        $signature = hash('sha256', $merchantCode . $paymentAmount . $datetime . $apiKey);

        $params = [
            'merchantcode' => $merchantCode,
            'amount' => $paymentAmount,
            'datetime' => $datetime,
            'signature' => $signature
        ];

        // 4. Hit API Duitku menggunakan HTTP Laravel
        // Gunakan URL Sandbox untuk testing. Jika nanti live, ubah ke URL Production.
        $url = env('DUITKU_ENV') === 'sandbox' 
            ? 'https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod'
            : 'https://passport.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($url, $params);

        // 5. Cek apakah response dari Duitku sukses
        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['responseCode']) && $data['responseCode'] === '00') {
                $paymentMethods = $data['paymentFee'];
                return view('member.payment-methods', compact('invoice', 'paymentMethods'));
            }
        }

        // Jika gagal hit API, log error dan kembalikan user
        Log::error('Duitku GetPaymentMethod Error: ' . $response->body());
        return back()->with('error', 'Gagal mengambil metode pembayaran. Silakan coba lagi.');
    }

    /**
     * Fungsi kosong untuk tahap Request Transaction (kita buat kerangkanya dulu)
     */
    public function requestTransaction(Request $request, Invoice $invoice)
    {
        // Nanti kita isi ini di tahap selanjutnya
        return "Memproses metode: " . $request->payment_method;
    }
}