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
     * Memproses request transaksi pembayaran ke Duitku (Inquiry)
     */
    public function requestTransaction(Request $request, Invoice $invoice)
    {
        // 1. Validasi pilihan metode pembayaran
        $request->validate([
            'payment_method' => 'required|string'
        ]);

        // 2. Keamanan: Pastikan invoice milik user yang login dan belum lunas
        if ($invoice->user_id !== auth()->id() || $invoice->status !== 'unpaid') {
            abort(403, 'Akses ditolak atau tagihan sudah lunas.');
        }

        $user = $invoice->user;

        // 3. Persiapkan Kredensial & Detail Transaksi
        $merchantCode = env('DUITKU_MERCHANT_CODE');
        $apiKey = env('DUITKU_API_KEY');
        
        $paymentAmount = (int) $invoice->amount;
        $paymentMethod = $request->payment_method;
        $merchantOrderId = $invoice->invoice_number; 
        $productDetails = 'Pembayaran Paket: ' . $invoice->subscription->plan->name;
        
        // Data Pelanggan (Otomatis ditarik dari database)
        $email = $user->email ?? 'no-email@terabot.ai'; // Default jika email kosong
        $phoneNumber = $user->whatsapp_number;
        $customerVaName = $user->name;
        
        // URL Konfigurasi
        $callbackUrl = url('/api/duitku/callback'); 
        $returnUrl = route('user.invoice.show', $invoice->id); 
        $expiryPeriod = 60; // Waktu kadaluarsa dalam hitungan menit

        // 4. Buat Signature sesuai rumus Duitku
        $signature = md5($merchantCode . $merchantOrderId . $paymentAmount . $apiKey);

        // 5. Susun Array Data Alamat Pelanggan
        $address = [
            'firstName' => $user->name,
            'lastName' => '', 
            'address' => $user->business_address ?? 'Alamat tidak diisi', 
            'city' => 'Jakarta', // Duitku mewajibkan field ini terisi
            'postalCode' => '10000',
            'phone' => $phoneNumber,
            'countryCode' => 'ID'
        ];

        $customerDetail = [
            'firstName' => $user->name,
            'lastName' => '',
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'billingAddress' => $address,
            'shippingAddress' => $address
        ];

        // 6. Susun Array Item Pembelian
        $itemDetails = [
            [
                'name' => $invoice->subscription->plan->name,
                'price' => $paymentAmount,
                'quantity' => 1
            ]
        ];

        // 7. Bungkus Semua Payload Sesuai Format cURL Duitku
        $params = [
            'merchantCode' => $merchantCode,
            'paymentAmount' => $paymentAmount,
            'paymentMethod' => $paymentMethod,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $productDetails,
            'additionalParam' => '',
            'merchantUserInfo' => '',
            'customerVaName' => $customerVaName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'itemDetails' => $itemDetails,
            'customerDetail' => $customerDetail,
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'signature' => $signature,
            'expiryPeriod' => $expiryPeriod
        ];

        // 8. Tentukan URL (Sandbox atau Production)
        $url = env('DUITKU_ENV') === 'sandbox' 
            ? 'https://sandbox.duitku.com/webapi/api/merchant/v2/inquiry'
            : 'https://passport.duitku.com/webapi/api/merchant/v2/inquiry';

        try {
            // 9. Kirim POST Request menggunakan Http Laravel
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $params);

            $result = $response->json();

            // 10. Evaluasi Response dari Duitku
            if (isset($result['statusCode']) && $result['statusCode'] === '00') {
                
                // BERHASIL! Langsung arahkan user ke URL Pembayaran Duitku
                return redirect()->away($result['paymentUrl']);
                
            } else {
                // Tangani jika terjadi penolakan dari Duitku (misal: nominal kurang, signature salah)
                \Illuminate\Support\Facades\Log::error('Duitku Inquiry Failed:', $result ?? []);
                $errorMessage = $result['statusMessage'] ?? 'Unknown Error';
                
                return back()->with('error', 'Gagal memproses pembayaran: ' . $errorMessage);
            }

        } catch (\Exception $e) {
            // Tangani jika koneksi ke server Duitku terputus
            \Illuminate\Support\Facades\Log::error('Duitku Exception: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menghubungi payment gateway.');
        }
    }
}