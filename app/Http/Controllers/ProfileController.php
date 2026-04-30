<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Logika penentuan status layanan
        $serviceStatus = [
            'label' => $user->subscription_status == 'active' ? 'Aktif' : 'Non-Aktif',
            'color' => $user->subscription_status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
            'icon' => $user->subscription_status == 'active' ? 'check-circle' : 'x-circle'
        ];

        // Memanggil file view yang kita buat di langkah sebelumnya
        return view('member.profile', compact('user', 'serviceStatus'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function sendOtp(Request $request) {
        $otp = rand(1000, 9999);
        $user = auth()->user();
        $user->update(['otp_code' => $otp]);
    
        // Kirim via Wablas
        $client = new \GuzzleHttp\Client();
        $client->post("https://jkt.wablas.com/api/send-message", [
            'headers' => ['Authorization' => $user->wablas_api_key], // Atau API Key Global Admin
            'form_params' => [
                'phone' => $request->whatsapp_number,
                'message' => "Kode OTP Verifikasi Smart AI Anda adalah: $otp. Jangan berikan kode ini kepada siapapun.",
            ]
        ]);
        
        return redirect()->route('verification.page');
    }
}
