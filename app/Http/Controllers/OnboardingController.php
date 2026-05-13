<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class OnboardingController extends Controller
{
    // 1. Tampilkan Halaman Form Profil Usaha
    public function profileForm()
    {
        return view('onboarding.profile'); // Nanti kita buat file blade-nya
    }

    // 2. Proses Simpan Profil & Kirim OTP
    public function submitProfile(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_category' => 'required|string',
            'business_address' => 'required|string',
            'business_description' => 'required|string',
            'whatsapp_number' => 'required|numeric|unique:users,whatsapp_number,' . Auth::id(),
        ]);

        $user = Auth::user();
        $otp = rand(100000, 999999); // Generate 6 digit OTP

        // Update data user
        $user->update([
            'business_name' => $request->business_name,
            'business_category' => $request->business_category,
            'business_address' => $request->business_address,
            'business_description' => $request->business_description,
            'whatsapp_number' => $request->whatsapp_number,
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5), // Kedaluwarsa dalam 5 menit
        ]);

       // Kredensial Evolution API
       $evolutionUrl = env('EVOLUTION_URL', 'http://103.150.196.172:8080');
       $globalApiKey = env('EVOLUTION_API_KEY', 'terabot123');
       
       // Nama instance pusat yang Anda gunakan khusus untuk mengirim OTP (pastikan sudah di-scan WA-nya)
       $adminInstance = env('EVOLUTION_ADMIN_INSTANCE', 'terabot_admin'); 

       // Pesan OTP
       $messageText = "*terabot.AI*\n\nKode OTP Verifikasi Anda adalah: *$otp*.\nKode ini akan kedaluwarsa dalam 5 menit. JANGAN berikan kode ini kepada siapapun.";

       // Kirim OTP via Evolution API
       Http::withHeaders([
        'apikey' => $globalApiKey,
        'Content-Type' => 'application/json'
    ])->post("{$evolutionUrl}/message/sendText/{$adminInstance}", [
        'number' => $request->whatsapp_number."@s.whatsapp.net", // Tambahkan .'@s.whatsapp.net' di sini jika API-nya butuh
        'text' => $messageText,
        'delay' => 5000,
        'linkPreview' => true
    ]);

       return redirect()->route('onboarding.otp.form')->with('success', 'OTP telah dikirim ke WhatsApp Anda!');
    }

    // 3. Tampilkan Halaman Input OTP
    public function otpForm()
    {
        return view('onboarding.otp'); // Nanti kita buat file blade-nya
    }

    // 4. Proses Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp_code' => 'required|numeric']);
        $user = Auth::user();

        // Cek apakah OTP cocok dan belum kedaluwarsa
        if ($user->otp_code == $request->otp_code && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {
            
            // Sukses! Kosongkan OTP dan tandai terverifikasi
            $user->update([
                'is_wa_verified' => true,
                'otp_code' => null,
                'otp_expires_at' => null
            ]);

            return redirect()->route('dashboard')->with('success', 'WhatsApp berhasil diverifikasi!');
        }

        return back()->with('error', 'Kode OTP salah atau sudah kedaluwarsa. Silakan minta ulang.');
    }
}