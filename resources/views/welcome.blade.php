<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tera.ai - CS WhatsApp yang ngerti SOP kamu</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
       .glass { backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased selection:bg-violet-600 selection:text-white">

    <!-- NAV -->
    <header class="sticky top-0 z-50 border-b border-slate-100 bg-white/80 glass">
        <nav class="max-w-7xl mx-auto px-6 lg:px-8 h- flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-700 to-indigo-600 flex items-center justify-center shadow-md shadow-violet-200">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M8 12h8M12 8v8M21 12c0 4.97-4.03 9-9 9-1.52 0-2.95-.38-4.2-1.05L3 21l1.05-4.8A8.96 8.96 0 013 12c0-4.97 4.03-9 9-9s9 4.03 9 9z" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                </div>
                <span class="text- font-extrabold tracking-tight">tera<span class="bg-gradient-to-r from-violet-700 to-indigo-600 bg-clip-text text-transparent">.ai</span></span>
            </a>
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 rounded-xl transition">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2.5 text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-sm transition">Daftar Baru</a>
            </div>
        </nav>
    </header>

    <!-- HERO -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -top-40 -right-40 w- h- bg-violet-200 rounded-full blur- opacity-40"></div>
            <div class="absolute top-20 -left-40 w- h- bg-indigo-200 rounded-full blur- opacity-30"></div>
        </div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-14 pb-20 lg:pt-20 lg:pb-28">
            <div class="grid lg:grid-cols-2 gap-14 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-violet-50 border border-violet-200 text-violet-700 text-xs font-semibold mb-6">
                        <span class="w-2 h-2 rounded-full bg-violet-600 animate-pulse"></span>
                        CS AI Generasi Terbaru untuk WhatsApp
                    </div>
                    <h1 class="text- leading-[1.1] sm:text-5xl lg:text- font-black tracking-tight text-slate-900">
                        Balas chat 24/7, <span class="bg-gradient-to-r from-violet-700 to-indigo-600 bg-clip-text text-transparent">tag prospek otomatis</span> jadi HOT
                    </h1>
                    <p class="mt-6 text-lg leading-relaxed text-slate-600 max-w-xl">
                        tera.ai adalah CS WhatsApp AI yang ngerti SOP kamu. Dilatih dari data bisnismu, ngomong kayak manusia, kerja nonstop. Atur prompt dari dashboard, pilih gaya bahasa formal, slang, atau gaul teknologi, dengan atau tanpa emoji.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-2xl font-semibold text-white bg-gradient-to-r from-violet-700 to-indigo-600 shadow-lg shadow-violet-200 hover:shadow-xl transition hover:-translate-y-0.5">
                            Coba Gratis 7 Hari
                        </a>
                        <a href="#fitur" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition">
                            Lihat Fitur
                        </a>
                    </div>
                    <div class="mt-6 flex items-center gap-4 text-sm text-slate-500">
                        <div class="flex -space-x-2">
                            <img class="w-7 h-7 rounded-full border-2 border-white" src="https://i.pravatar.cc/28?img=1" alt="">
                            <img class="w-7 h-7 rounded-full border-2 border-white" src="https://i.pravatar.cc/28?img=2" alt="">
                            <img class="w-7 h-7 rounded-full border-2 border-white" src="https://i.pravatar.cc/28?img=3" alt="">
                        </div>
                        <span><strong class="text-slate-900">1,247+</strong> bisnis sudah pakai</span>
                    </div>
                </div>

                <!-- WhatsApp Mockup -->
                <div class="relative lg:pl-10">
                    <div class="relative mx-auto max-w-">
                        <div class="absolute -inset-6 bg-gradient-to-b from-violet-600/20 to-indigo-600/20 rounded- blur-2xl"></div>
                        <div class="relative rounded-[2.8rem] border- border-slate-900 bg-slate-900 shadow-2xl">
                            <div class="bg-[#0b141a] rounded- overflow-hidden">
                                <div class="bg-[#202c33] px-4 py-3 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold">t</div>
                                    <div class="flex-1">
                                        <p class="text-white font-medium text- leading-tight">CS tera.ai</p>
                                        <p class="text-emerald-400 text-">online</p>
                                    </div>
                                </div>
                                <div class="h- bg-[#111b21] p-3 space-y-3 overflow-hidden">
                                    <div class="flex justify-start">
                                        <div class="bg-[#202c33] text-[#e9edef] rounded-xl rounded-tl-sm px-3 py-2 max-w-[75%] text-">Kak, harga facial acne brp ya? <span class="block text- text-[#8696a0] text-right mt-1">08:12</span></div>
                                    </div>
                                    <div class="flex justify-end">
                                        <div class="bg-[#005c4b] text-[#e9edef] rounded-xl rounded-tr-sm px-3 py-2 max-w-[80%] text-">Halo kak 😊 Facial Acne Rp 299rb, 60 menit sudah termasuk konsultasi. Mau booking hari ini? <span class="block text- text-[#8faca1] text-right mt-1">08:12 ✓✓</span></div>
                                    </div>
                                    <div class="flex justify-center">
                                        <div class="bg-[#182229] border border-[#2a3942] text- text-[#8696a0] px-3 py-1.5 rounded-full">AUTO-TAG: <strong class="text-amber-400">HOT 🔥</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TRUST -->
    <div class="border-y border-slate-100 bg-slate-50/70">
        <div class="max-w-6xl mx-auto px-6 py-7 flex flex-col md:flex-row items-center justify-center gap-5">
            <span class="text-sm font-medium text-slate-500">Didukung model terbaik</span>
            <div class="flex flex-wrap items-center justify-center gap-2.5">
                <span class="px-3.5 py-1.5 bg-white border border-slate-200 rounded-full text-sm font-semibold text-slate-700 shadow-sm">Llama 3</span>
                <span class="px-3.5 py-1.5 bg-white border border-slate-200 rounded-full text-sm font-semibold text-slate-700 shadow-sm">GPT-4o</span>
                <span class="px-3.5 py-1.5 bg-white border border-slate-200 rounded-full text-sm font-semibold text-slate-700 shadow-sm">Claude 3.5</span>
                <span class="px-3.5 py-1.5 bg-white border border-slate-200 rounded-full text-sm font-semibold text-slate-700 shadow-sm">Gemini 1.5</span>
            </div>
        </div>
    </div>

    <!-- FITUR UTAMA -->
    <section id="fitur" class="bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
            <div class="max-w-2xl">
                <span class="text-violet-600 font-semibold text-sm bg-violet-100 px-3 py-1 rounded-full">Fitur Inti</span>
                <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-slate-900 mt-4">Semua yang kamu butuhkan, dikontrol dari dashboard</h2>
                <p class="mt-3 text-slate-600">Tanpa coding. Ubah perilaku AI sesuai SOP bisnismu.</p>
            </div>
            <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- 1 -->
                <div class="rounded-3xl bg-white border border-slate-200 p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="w-11 h-11 rounded-2xl bg-violet-100 text-violet-700 flex items-center justify-center mb-4">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 0 008 19.4a1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 0 005 15.4a1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 0 009 4.6a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 0 0019.4 9c.36.22.6.6.6 1.02H21a2 2 0 010 4h-.09a1.65 1.65 0 00-.51 1.38z"/></svg>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Atur Prompt dari Web</h3>
                    <p class="mt-2 text-slate-600 text-">Setting prompt langsung dari dashboard Laravel kamu. Tidak perlu deploy ulang.</p>
                </div>
                <!-- 2 -->
                <div class="rounded-3xl bg-white border border-slate-200 p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-4">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Balasan Saklek SOP atau AI Berpikir Luas</h3>
                    <p class="mt-2 text-slate-600 text-">Pilih mode: ikuti SOP 100% verbatim, atau biarkan AI improvisasi closing seperti sales terbaik.</p>
                </div>
                <!-- 3 -->
                <div class="rounded-3xl bg-white border border-slate-200 p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="w-11 h-11 rounded-2xl bg-fuchsia-100 text-fuchsia-700 flex items-center justify-center mb-4">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a4 4 0 01-4 4H7l-4 4V7a4 4 0 014-4h10a4 4 0 014 4v8z"/></svg>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Gaya Bahasa & Emoji Fleksibel</h3>
                    <p class="mt-2 text-slate-600 text-">Formal untuk korporat, Slang untuk anak muda, Gaul Teknologi untuk tech-savvy. Emoji on/off sekali klik.</p>
                </div>
                <!-- 4 -->
                <div class="rounded-3xl bg-white border border-slate-200 p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-4">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">CRM Otomatis: HOT / DEAL / CANCEL</h3>
                    <p class="mt-2 text-slate-600 text-">AI membaca chat, ambil data prospek, dan update status otomatis berdasarkan penilaian. Tim tinggal follow-up yang HOT.</p>
                </div>
                <!-- 5 -->
                <div class="rounded-3xl bg-white border border-slate-200 p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="w-11 h-11 rounded-2xl bg-green-100 text-green-700 flex items-center justify-center mb-4">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21 5.46 0 9.91-4.45 9.91-9.91S17.5 2 12.04 2z"/></svg>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">WhatsApp Bisnis Native</h3>
                    <p class="mt-2 text-slate-600 text-">Terhubung via Evolution API. Bisa balas teks & voice note. Engine Python kamu handle pengiriman cepat.</p>
                </div>
                <!-- 6 -->
                <div class="rounded-3xl bg-white border border-slate-200 p-7 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center mb-4">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="M18 17V9M13 17V5M8 17v-3"/></svg>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900">Dashboard Analitik</h3>
                    <p class="mt-2 text-slate-600 text-">Pantau jumlah chat, response time, konversi HOT ke DEAL. Efisiensi CS hingga 80%.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section id="pricing" class="py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-purple-600 font-semibold text-sm bg-purple-100 px-3 py-1 rounded-full">Pricing</span>
                <h2 class="text-3xl md:text-4xl font-black mt-4 mb-4 tracking-tight">Pilih Paket yang Sesuai Kebutuhan</h2>
                <p class="text-slate-500">Tingkatkan performa tim support Anda hari ini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                @if(isset($plans) && $plans->count() > 0)
                    @foreach($plans as $plan)
                        <div class="{{ $loop->iteration == 2? 'bg-white rounded- p-8 border-2 border-violet-600 shadow-[0_20px_60px_-20px_rgba(124,58,237,0.4)] relative flex flex-col' : 'bg-white rounded- p-8 border border-slate-200 shadow-sm hover:shadow-xl transition flex flex-col' }}">
                            @if($loop->iteration == 2)
                                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-violet-600 text-white text-xs font-bold">PALING POPULER</div>
                            @endif
                            <h3 class="text-xl font-bold text-slate-800 text-center mb-2">{{ $plan->name }}</h3>
                            <div class="text-center mb-6">
                                <span class="text-4xl font-extrabold {{ $loop->iteration == 2? 'text-violet-600' : 'text-slate-900' }}">
                                    Rp {{ number_format($plan->price, 0, ',', '.') }}
                                </span>
                                <span class="text-slate-500">/bulan</span>
                            </div>

                            <ul class="space-y-4 mb-8 text-sm text-slate-600 flex-1">
                                <li class="flex items-start text-sm text-slate-700">
                                    <svg class="w-5 h-5 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>
                                        @if($plan->max_messages > 0)
                                            Kuota <strong>{{ number_format($plan->max_messages, 0, ',', '.') }}</strong> Pesan AI
                                        @else
                                            Kuota <strong>Unlimited</strong> Pesan AI
                                        @endif
                                    </span>
                                </li>

                                @php
                                    $featuresList = [];
                                    if (!empty($plan->features)) {
                                        if (is_string($plan->features)) {
                                            $decoded = json_decode($plan->features, true);
                                            $featuresList = is_array($decoded)? $decoded : explode("\n", $plan->features);
                                        } elseif (is_array($plan->features)) {
                                            $featuresList = $plan->features;
                                        }
                                    }
                                @endphp

                                @foreach($featuresList as $feature)
                                    @if(trim($feature)!== '')
                                        <li class="flex items-start text-sm text-slate-700">
                                            <svg class="w-5 h-5 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span>{{ trim($feature) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>

                            <a href="{{ route('register') }}" class="block text-center w-full py-3 {{ $loop->iteration == 2? 'bg-gradient-to-r from-violet-700 to-indigo-600 text-white' : 'bg-slate-900 text-white' }} font-bold rounded-xl hover:opacity-90 transition shadow-lg mt-auto">
                                Daftar & Pilih Paket
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center text-slate-500">
                        Paket berlangganan belum tersedia saat ini.
                    </div>
                @endif
            </div>

            <div class="mt-12 bg-gradient-to-r from-purple-600 to-blue-600 rounded-3xl p-8 text-center text-white shadow-xl">
                <h3 class="text-2xl font-bold mb-2">CS AI Custom Enterprise</h3>
                <p class="mb-6 text-purple-100">Butuh fitur untuk skala enterprise dengan keamanan tingkat tinggi?</p>
                <a href="https://wa.me/6285295955580" target="_blank" class="inline-block px-8 py-2.5 bg-white text-blue-600 font-bold rounded-full hover:bg-gray-50 transition">Hubungi Kami</a>
            </div>
        </div>
    </section>

    <footer class="bg-gradient-to-br from-indigo-700 to-purple-800 py-16 px-4 text-center text-white relative">
        <div class="max-w-4xl mx-auto relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap punya CS yang ngerti SOP kamu?</h2>
            <p class="mb-10 text-purple-200">Aktifkan tera.ai sekarang. Gratis 7 hari.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
                <div class="bg-white/10 p-6 rounded-2xl border border-white/20 backdrop-blur-sm">
                    <div class="text-2xl mb-2">📱</div>
                    <h4 class="font-bold">WhatsApp</h4>
                    <p class="text-sm text-purple-200 mt-2">085295955580</p>
                </div>
                <div class="bg-white/10 p-6 rounded-2xl border border-white/20 backdrop-blur-sm">
                    <div class="text-2xl mb-2">✉</div>
                    <h4 class="font-bold">Email</h4>
                    <p class="text-sm text-purple-200 mt-2">nmfuadi@gmail.com</p>
                </div>
                <div class="bg-white/10 p-6 rounded-2xl border border-white/20 backdrop-blur-sm">
                    <div class="text-2xl mb-2">📍</div>
                    <h4 class="font-bold">Kantor</h4>
                    <p class="text-sm text-purple-200 mt-2">Jl. Kemiri Jaya, Beji, Depok, Jawa Barat 16421</p>
                </div>
            </div>

            <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-white text-purple-700 font-bold rounded-full hover:bg-gray-100 transition shadow-lg">
                Mulai Gratis Sekarang
            </a>
        </div>
    </footer>

</body>
</html>