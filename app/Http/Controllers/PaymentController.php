<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}