@php
    $user = auth()->user();
    // Mengambil data langganan yang sedang aktif beserta relasi nama paketnya
    $activeSub = \App\Models\Subscription::with('plan')->where('user_id', $user->id)->where('status', 'active')->first();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-8 sm:p-10 text-white flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold mb-2">Halo, {{ $user->name }}! 👋</h1>
                    <p class="text-blue-100 text-sm md:text-base">Selamat datang di pusat kendali AI untuk <span class="font-bold text-white">{{ $user->business_name ?? 'Bisnis Anda' }}</span>.</p>
                </div>
                <div class="shrink-0 bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/20 text-center w-full md:w-auto">
                    <p class="text-xs text-blue-200 uppercase tracking-widest font-bold mb-1">Nomor Terdaftar</p>
                    <p class="text-lg font-mono font-bold">{{ $user->whatsapp_number }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-4">Status Langganan</h3>
                        
                        @if($user->subscription_status === 'active' && $activeSub)
                            <div class="flex items-center gap-3 mb-4">
                                <span class="relative flex h-4 w-4">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                                </span>
                                <span class="font-bold text-emerald-600 uppercase tracking-wider text-sm">Aktif</span>
                            </div>
                            
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 mb-6">
                                <p class="text-xs text-slate-500 font-bold uppercase mb-1">Paket Saat Ini:</p>
                                <p class="text-xl font-black text-blue-900">{{ $activeSub->plan->name }}</p>
                                <p class="text-xs text-slate-500 mt-2">Berlaku s/d: <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($activeSub->ends_at)->format('d M Y') }}</span></p>
                            </div>

                            <form action="{{ route('user.plans.cancel') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin mengakhiri paket ini dan beralih ke paket lain? Sisa masa aktif akan hangus.')" 
                                        class="w-full bg-white text-red-500 border-2 border-red-100 hover:border-red-500 hover:bg-red-50 px-4 py-3 rounded-xl text-sm font-bold transition duration-200">
                                    Berhenti & Ganti Paket
                                </button>
                            </form>

                        @else
                            <div class="flex items-center gap-3 mb-4">
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500"></span>
                                <span class="font-bold text-amber-600 uppercase tracking-wider text-sm">Belum Aktif</span>
                            </div>
                            
                            <p class="text-sm text-slate-600 mb-6">Anda belum memiliki paket aktif. Fitur AI Chatbot tidak dapat digunakan saat ini.</p>
                            
                            <a href="{{ route('user.plans.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-3 rounded-xl text-sm font-bold shadow transition duration-200">
                                Pilih Paket Sekarang
                            </a>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-4 border-b border-slate-100 pb-4">Pintasan</h3>
                        <div class="space-y-3">
                            <a href="{{ route('user.invoice.index') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 transition border border-transparent hover:border-slate-200 group">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600">Riwayat Tagihan</span>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <a href="{{ route('member.pk') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 transition border border-transparent hover:border-slate-200 group">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600">SOP & Knowledge Base</span>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 transition border border-transparent hover:border-slate-200 group">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-blue-600">Pengaturan Akun</span>
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 px-1">Menu Fitur AI</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        
                        <a href="{{ route('member.wablas') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:border-blue-300 transition group relative overflow-hidden">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-slate-900 mb-1">Setup WhatsApp Bot</h4>
                            <p class="text-sm text-slate-500">Hubungkan nomor Anda dan scan QR Code untuk mengaktifkan AI.</p>
                        </a>

                        <a href="{{ route('customers.index') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:blue-300 transition group relative overflow-hidden">
                            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2