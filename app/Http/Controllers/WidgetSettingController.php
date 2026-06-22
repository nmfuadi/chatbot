<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WidgetSetting;
use Illuminate\Support\Facades\Auth;

class WidgetSettingController extends Controller
{
    public function index()
    {
        // Ambil data setting widget milik user ini, kalau belum ada otomatis dibuatkan default
        $setting = WidgetSetting::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'is_active' => false,
                'primary_color' => '#4F46E5', // Warna Indigo default
                'greeting_text' => 'Halo! Silakan isi form di bawah untuk memulai obrolan dengan kami.'
            ]
        );

        return view('member.widget-settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'primary_color' => 'required',
            'greeting_text' => 'required|string|max:255',
            'logo' => 'nullable|image|max:1024', // Maksimal 1MB
        ]);

        $setting = WidgetSetting::where('user_id', Auth::id())->first();

        // Handle Upload Logo
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('widget-logos', 'public');
            $setting->logo_path = $path;
        }

        $setting->update([
            'is_active' => $request->has('is_active'), // Ceklist toggle
            'primary_color' => $request->primary_color,
            'greeting_text' => $request->greeting_text,
        ]);

        return back()->with('success', 'Pengaturan Widget berhasil diperbarui!');
    }
}