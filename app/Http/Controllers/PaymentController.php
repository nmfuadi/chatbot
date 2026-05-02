<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // Tambahkan ini
use Illuminate\Support\Str;

class PaymentController extends Controller
{

    public function index()
    {
        // Mengambil semua invoice milik user, urutkan dari yang terbaru
        $invoices = \App\Models\Invoice::where('user_id', auth()->id())
                    ->with('subscription.plan')
                    ->latest()
                    ->get();
    
        return view('member.invoices.index', compact('invoices'));
    }


  /**
     * Menangani Callback dari Duitku
     */
    public function callback(Request $request)
    {
        // 1. LOG SEMUA DATA MASUK (Untuk cek apakah Duitku benar-benar kirim)
        \Log::info('Duitku Callback Masuk:', $request->all());

        $apiKey = env('DUITKU_API_KEY');
        $merchantCode = $request->input('merchantCode');
        $amount = $request->input('amount');
        $merchantOrderId = $request->input('merchantOrderId');
        $signature = $request->input('signature');
        $resultCode = $request->input('resultCode');
        $reference = $request->input('reference');

        if (!empty($merchantCode) && !empty($amount) && !empty($merchantOrderId) && !empty($signature)) {
            
            // 2. HITUNG SIGNATURE (Penting: nominal harus persis sama dengan saat request)
            $params = $merchantCode . $amount . $merchantOrderId . $apiKey;
            $calcSignature = md5($params);

            if ($signature == $calcSignature) {
                if ($resultCode == '00') {
                    $invoice = \App\Models\Invoice::where('invoice_number', $merchantOrderId)->first();

                    if ($invoice) {
                        // Cek jika status memang belum lunas
                        if ($invoice->status !== 'paid') {
                            $invoice->update(['status' => 'paid']);

                            $subscription = $invoice->subscription;
                            $subscription->update([
                                'status' => 'active',
                                'starts_at' => now(),
                                'ends_at' => now()->addDays(30),
                                'payment_id' => $reference
                            ]);

                            $invoice->user->update(['subscription_status' => 'active']);
                            
                            // --- TAMBAHAN BARU: BATALKAN INVOICE PENDING LAINNYA ---
                            \App\Models\Invoice::where('user_id', $invoice->user_id)
                                ->where('id', '!=', $invoice->id)
                                ->where('status', 'pending') // Atau 'unpaid' jika di DB Anda memakai kata itu
                                ->update(['status' => 'expired']);
                            // ---------------------------------------------------------
                            
                            \Log::info("Invoice {$merchantOrderId} BERHASIL DIUPDATE via Callback. Invoice pending lain telah dibatalkan.");
                        }
                    } else {
                        \Log::warning("Invoice {$merchantOrderId} TIDAK DITEMUKAN di database.");
                    }
                }
                
                // Duitku butuh respon 200 OK
                return response()->json(['status' => 'success'], 200);

            } else {
                \Log::error("SIGNATURE SALAH. Diterima: $signature, Dihitung: $calcSignature");
                return response()->json(['status' => 'error', 'message' => 'Bad Signature'], 400);
            }
        }
        
        return response()->json(['status' => 'error', 'message' => 'Bad Parameter'], 400);
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
        $returnUrl = route('payment.return'); // <-- Sudah mengarah ke Return URL yang benar
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

    /**
     * Menangani Return/Redirect URL dari Duitku setelah user berinteraksi dengan halaman pembayaran
     */
    public function paymentReturn(Request $request)
    {
        // Tangkap data yang dikirim oleh Duitku via URL parameter (GET)
        $merchantOrderId = $request->query('merchantOrderId');
        $resultCode = $request->query('resultCode');
        $reference = $request->query('reference');

        // Cari invoice berdasarkan nomor order
        $invoice = \App\Models\Invoice::where('invoice_number', $merchantOrderId)->first();

        // Jika invoice tidak ditemukan, lempar ke dashboard
        if (!$invoice) {
            return redirect()->route('dashboard')->with('error', 'Tagihan tidak ditemukan.');
        }

        // Evaluasi hasil kembalian dari Duitku
        if ($resultCode == '00') {
            // Sukses (Sudah Dibayar)
            // Kita arahkan ke Dashboard karena status DB-nya akan di-update oleh fungsi Callback di background
            return redirect()->route('dashboard')->with('success', 'Hore! Pembayaran berhasil. Sistem sedang mengaktifkan layanan AI Anda.');
            
        } elseif ($resultCode == '01') {
            // Gagal / Dibatalkan
            return redirect()->route('user.invoice.show', $invoice->id)->with('error', 'Pembayaran gagal atau dibatalkan. Silakan coba metode lain.');
            
        } else {
            // Kode '02' atau lainnya (Pending / Menunggu Pembayaran via ATM/Minimarket)
            return redirect()->route('user.invoice.show', $invoice->id)->with('success', 'Instruksi pembayaran berhasil dibuat! Silakan selesaikan pembayaran Anda.');
        }
    }

    /**
     * FUNGSI OTOMATIS: Menerbitkan invoice perpanjangan saat paket user habis
     */
    public static function generateRenewalInvoice(User $user)
    {
        // 1. Cari riwayat langganan (subscription) terakhir yang pernah dimiliki user
        $lastSub = Subscription::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

        // Jika user belum pernah langganan sama sekali, hentikan proses (jangan buat tagihan)
        if (!$lastSub) return null;

        // 2. Cek apakah sistem sudah pernah membuatkan invoice 'unpaid' untuk paket ini
        // Ini MENCEGAH sistem membuat invoice dobel jika user me-refresh halaman berkali-kali
        $existingInvoice = Invoice::where('user_id', $user->id)
                            ->where('status', 'unpaid') // Sesuaikan dengan status default di database Anda ('unpaid' atau 'pending')
                            ->where('plan_id', $lastSub->plan_id)
                            ->first();

        // 3. Jika belum ada tagihan yang menggantung, buat tagihan baru!
        if (!$existingInvoice) {
            return Invoice::create([
                'user_id' => $user->id,
                'plan_id' => $lastSub->plan_id,
                'amount'  => $lastSub->plan->price ?? 0, // Ambil harga dari relasi tabel plan
                'status'  => 'unpaid', // Status awal tagihan
                'invoice_number' => 'INV-' . strtoupper(Str::random(10)), // Hasil: INV-AB12CD34EF
            ]);
        }

        // Jika tagihan sudah ada, kembalikan data tagihan tersebut
        return $existingInvoice;
    }
}