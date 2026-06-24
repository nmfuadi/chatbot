<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WidgetSetting;
use Illuminate\Support\Facades\Auth;

class WidgetSettingController extends Controller
{
    public function index()
    {
        $setting = WidgetSetting::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'is_active' => false,
                'primary_color' => '#4F46E5',
                'greeting_text' => 'Halo! Silakan isi form di bawah untuk memulai obrolan dengan kami.',
                'widget_position' => 'bottom-right',
                'widget_shape' => 'circle',
                'widget_icon' => 'chat'
            ]
        );

        return view('member.widget-settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'primary_color' => 'required',
            'greeting_text' => 'required|string|max:255',
            'widget_position' => 'required|string|in:bottom-right,bottom-left,top-right,top-left,center-right,center-left',
            'widget_shape' => 'required|string|in:circle,square,pill',
            'widget_icon' => 'required|string|in:chat,support,whatsapp',
            'widget_text' => 'nullable|string|max:20', // Max ~2 kata (20 karakter agar UI tidak rusak)
            'logo' => 'nullable|image|max:1024', 
        ]);

        $setting = WidgetSetting::where('user_id', Auth::id())->first();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('widget-logos', 'public');
            $setting->logo_path = $path;
        }

        $setting->update([
            'is_active' => $request->has('is_active'),
            'primary_color' => $request->primary_color,
            'greeting_text' => $request->greeting_text,
            'widget_position' => $request->widget_position,
            'widget_shape' => $request->widget_shape,
            'widget_icon' => $request->widget_icon,
            'widget_text' => $request->widget_text,
        ]);

        return back()->with('success', 'Pengaturan Widget berhasil diperbarui!');
    }
}