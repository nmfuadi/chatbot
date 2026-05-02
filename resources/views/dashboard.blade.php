HTML
@php
    $user = auth()->user();
    
    // 1. Ambil paket langganan PALING TERAKHIR (meskipun sudah expired)
    $latestSub = \App\Models\Subscription::with('plan')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();

    // 2. Tentukan apakah langganan terakhir tersebut MASIH AKTIF
    $activeSub = null;
    if ($latestSub && $latestSub->status === 'active' && $latestSub->ends_at > now()) {
        $activeSub = $latestSub;
    }

    // 3. AMBIL DATA DARI KOLOM usage_count (Bukan menghitung ChatHistory lagi)
    $usageCount = 0;
    $maxMessages = 0;

    if ($latestSub) {
        $maxMessages = $latestSub->plan->max_messages ?? 0;
        
        // --- INI PERUBAHANNYA: Ambil langsung dari kolom database ---
        $usageCount = $latestSub->usage_count; 
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-3xl shadow-lg p-8 sm:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 border border-blue-500/30">
                <div class="absolute top-[-50px] right-[-50px] w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10">
                    <p class="text-blue-200 text-sm font-medium mb-1 tracking-wide">Selamat datang kembali,</p>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight mb-2">{{ $user->name }}</h1>
                    <p class="text-blue-100 text-sm md:text-base max-w-xl leading-relaxed">
                        Pusat kendali AI untuk bisnis <span class="font-semibold text-white">{{ $user->business_name ?? 'Anda' }}</span>.
                    </p>
                </div>
                
                <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-2xl text-center w-full md:w-auto shadow-inner">
                    <p class="text-[10px] text-blue-200 uppercase tracking-widest font-semibold mb-1">WhatsApp Terdaftar</p>
                    <p class="text-xl font-mono font-bold text-white tracking-wider">{{ $user->whatsapp_number }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-8">
                    
                    <div class="bg-white rounded-3xl p-7 shadow-sm ring-1 ring-gray-900/5">
                        <h3 class="text-base font-bold text-gray-900 mb-5 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Informasi Paket
                        </h3>
                        
                        @if($user->subscription_status === 'active' && $activeSub)
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 border border-green-100 mb-5">
                                <span class="relative flex h-2.5 w-2.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                                </span>
                                <span class="text-xs font-bold text-green-700 uppercase tracking-wide">Status Aktif</span>
                            </div>
                            
                            <div class="mb-6">
                                <p class="text-xs text-gray-500 font-medium mb-1">Layanan Saat Ini</p>
                                <p class="text-2xl font-black text-gray-900 tracking-tight">{{ $activeSub->plan->name ?? 'Paket Premium' }}</p>
                                <p class="text-sm text-gray-500 mt-2">
                                    Berlaku s/d: <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($activeSub->ends_at)->format('d M Y') }}</span>
                                </p>
                            </div>

                            <form action="{{ route('user.plans.cancel') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Yakin ingin berhenti dari paket ini? Sisa masa aktif akan hangus dan Anda akan diminta memilih paket baru.')" 
                                        class="w-full flex justify-center items-center gap-2 bg-white text-red-500 border border-red-200 hover:border-red-500 hover:bg-red-50 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all duration-200">
                                    Berhenti & Ganti Paket
                                </button>
                            </form>
                        @else
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-50 border border-yellow-100 mb-5">
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-yellow-500"></span>
                                <span class="text-xs font-bold text-yellow-700 uppercase tracking-wide">Belum Aktif / Expired</span>
                            </div>
                            
                            <p class="text-sm text-gray-500 leading-relaxed mb-6">Masa aktif layanan Anda telah habis atau Anda belum memiliki paket. Aktifkan sekarang untuk menggunakan AI.</p>
                            
                            <a href="{{ route('user.invoice.index') }}" class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-3.5 rounded-2xl text-sm font-bold shadow-md shadow-blue-500/20 transition-all duration-200">
                                Selesaikan Pembayaran
                            </a>
                        @endif
                    </div>

                    @if($latestSub)
                        <div class="bg-white p-7 rounded-3xl shadow-sm ring-1 ring-gray-900/5">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest">Pemakaian Kuota</h3>
                                </div>
                                <div class="text-right">
                                    @if($maxMessages > 0)
                                        <span class="text-xl font-black text-blue-600">{{ number_format($usageCount) }}</span>
                                        <span class="text-xs font-bold text-gray-400">/ {{ number_format($maxMessages) }}</span>
                                    @else
                                        <span class="text-[10px] font-black text-green-600 uppercase tracking-widest bg-green-50 px-3 py-1 rounded-full ring-1 ring-green-100">Unlimited</span>
                                    @endif
                                </div>
                            </div>

                            @if($maxMessages > 0)
                                <div class="w-full bg-gray-100 rounded-full h-2.5 mb-2 overflow-hidden shadow-inner">
                                    @php 
                                        $percentage = ($maxMessages > 0) ? ($usageCount / $maxMessages) * 100 : 0;
                                        $colorClass = $percentage >= 95 ? 'bg-rose-500' : ($percentage >= 75 ? 'bg-amber-400' : 'bg-blue-500');
                                    @endphp
                                    <div class="{{ $colorClass }} h-2.5 rounded-full transition-all duration-1000" style="width: {{ min($percentage, 100) }}%"></div>
                                </div>

                                @if($latestSub->status === 'expired' || $percentage >= 100)
                                    <div class="mt-4 p-3 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <p class="text-[11px] font-bold text-rose-700 leading-snug">Kuota telah habis atau paket kedaluwarsa. AI berhenti membalas pesan.</p>
                                    </div>
                                @elseif($percentage >= 80)
                                    <p class="text-[10px] font-bold text-amber-500 text-right mt-1">Hampir mencapai batas kuota.</p>
                                @endif
                            @endif
                        </div>
                    @endif

                    <div class="bg-white rounded-3xl p-7 shadow-sm ring-1 ring-gray-900/5">
                        <h3 class="text-base font-bold text-gray-900 mb-5">Pintasan Cepat</h3>
                        <div class="space-y-2">
                            <a href="{{ route('user.invoice.index') }}" class="group flex items-center justify-between p-3.5 rounded-2xl hover:bg-gray-50 transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-100 text-gray-500 rounded-lg group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Riwayat Tagihan</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>

                            <a href="{{ route('member.pk') }}" class="group flex items-center justify-between p-3.5 rounded-2xl hover:bg-gray-50 transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-100 text-gray-500 rounded-lg group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">SOP & Edukasi</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>

                            <a href="{{ route('profile.edit') }}" class="group flex items-center justify-between p-3.5 rounded-2xl hover:bg-gray-50 transition-all duration-200">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-100 text-gray-500 rounded-lg group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Pengaturan Akun</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 px-1">Menu Utama AI</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 md:gap-6">
                        
                        <a href="{{ route('member.wablas') }}" class="group block bg-white p-7 rounded-3xl shadow-sm ring-1 ring-gray-900/5 hover:ring-blue-500/30 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Setup WhatsApp</h4>
                            <p class="text-sm text-gray-500 leading-relaxed">Hubungkan perangkat Anda dengan melakukan scan QR Code agar bot dapat merespon.</p>
                        </a>

                        <a href="{{ route('customers.index') }}" class="group block bg-white p-7 rounded-3xl shadow-sm ring-1 ring-gray-900/5 hover:ring-indigo-500/30 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Database Pelanggan</h4>
                            <p class="text-sm text-gray-500 leading-relaxed">Kelola data pelanggan, pantau histori chat, dan atur status AI per kontak.</p>
                        </a>

                        <a href="{{ route('catalogs.index') }}" class="group block bg-white p-7 rounded-3xl shadow-sm ring-1 ring-gray-900/5 hover:ring-green-500/30 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center mb-5 group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Katalog Produk</h4>
                            <p class="text-sm text-gray-500 leading-relaxed">Tambah dan atur detail produk atau jasa yang akan ditawarkan oleh AI Anda.</p>
                        </a>

                        <div class="block bg-gray-50/50 p-7 rounded-3xl border-2 border-dashed border-gray-200 relative overflow-hidden">
                            <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mb-5">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-400 mb-2">Statistik Pintar</h4>
                            <p class="text-sm text-gray-400 leading-relaxed">Laporan analitik performa asisten AI sedang dalam tahap pengembangan.</p>
                            
                            <div class="absolute top-6 right-6 bg-gray-200 text-gray-500 text-[10px] font-bold uppercase tracking-wider py-1 px-3 rounded-full">
                                Segera Hadir
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>