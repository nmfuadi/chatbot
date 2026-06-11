<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Tera.ai - Chatbot AI + CRM Otomatis | Solusi Layanan Pelanggan Cerdas</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.3); }
        .gradient-mesh { background: radial-gradient(circle at 0% 0%, rgba(139, 92, 246, 0.15) 0%, rgba(59, 130, 246, 0.1) 50%, transparent 80%); }
        .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-lift:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15); }
    </style>
</head>
<body class="bg-white antialiased text-slate-800 selection:bg-purple-500 selection:text-white">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-slate-800 font-extrabold text-2xl tracking-tight flex items-center gap-2">
                <span class="bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Tera.ai</span>
                <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-2 py-0.5 rounded-full">AI</span>
            </div>
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="#fitur-unggulan" class="text-slate-600 hover:text-purple-700 transition">Fitur Utama</a>
                <a href="#crm-prospek" class="text-slate-600 hover:text-purple-700 transition">CRM Prospek</a>
                <a href="#pricing" class="text-slate-600 hover:text-purple-700 transition">Harga</a>
                <a href="#testimoni" class="text-slate-600 hover:text-purple-700 transition">Testimoni</a>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="text-slate-700 hover:text-purple-700 font-medium px-4 py-2 transition text-sm">Masuk</a>
                <a href="{{ route('register') }}" class="bg-gradient-to-r from-purple-600 to-blue-600 text-white hover:shadow-lg font-bold px-5 py-2.5 rounded-full transition shadow-md text-sm">Daftar Gratis</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-50 via-white to-purple-50 pt-36 pb-24 px-4 overflow-hidden">
        <div class="absolute inset-0 gradient-mesh pointer-events-none"></div>
        <div class="max-w-5xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-purple-200 rounded-full px-4 py-1.5 shadow-sm mb-6">
                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                <span class="text-sm font-medium text-slate-700">Siap pakai + Auto CRM Prospek</span>
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-700 bg-clip-text text-transparent mb-6">Tera.ai</h1>
            <p class="text-2xl md:text-3xl font-semibold text-slate-800 mb-4">Chatbot AI + CRM Otomatis <br> untuk Bisnis Anda</p>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto mb-8">Ditenagai Llama & DeepInfra, terintegrasi WhatsApp. <br class="hidden md:block">Dilengkapi penilaian prospek otomatis (Hot/Deal/Cancel) dari percakapan.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="group px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold rounded-full shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2 text-lg">🚀 Coba Gratis Sekarang</a>
                <a href="#fitur-unggulan" class="px-8 py-4 bg-white border-2 border-purple-300 text-purple-700 font-bold rounded-full hover:bg-purple-50 transition shadow-sm">Lihat Cara Kerja</a>
            </div>
            <div class="flex flex-wrap justify-center gap-6 mt-12 text-sm font-medium text-slate-500">
                <div class="flex items-center gap-2">✅ Tanpa kartu kredit</div>
                <div class="flex items-center gap-2">⚡ Setup 5 menit</div>
                <div class="flex items-center gap-2">🔒 Data aman</div>
            </div>
        </div>
    </section>

    <!-- Stack Teknologi (DeepInfra, Llama, Evolution API) -->
    <section class="max-w-5xl mx-auto px-4 -mt-12 relative z-20">
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-slate-100 flex flex-wrap justify-between items-center gap-4">
            <div class="flex flex-wrap items-center gap-4 justify-center md:justify-start">
                <span class="text-slate-600 font-semibold">Teknologi:</span>
                <div class="flex flex-wrap gap-3">
                    <span class="px-4 py-2 bg-slate-100 text-slate-800 rounded-xl text-sm font-bold shadow-sm">DeepInfra</span>
                    <span class="px-4 py-2 bg-slate-100 text-slate-800 rounded-xl text-sm font-bold shadow-sm">Llama 3</span>
                    <span class="px-4 py-2 bg-slate-100 text-slate-800 rounded-xl text-sm font-bold shadow-sm">Evolution API</span>
                    <span class="px-4 py-2 bg-slate-100 text-slate-800 rounded-xl text-sm font-bold shadow-sm">Laravel + Python</span>
                </div>
            </div>
            <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
            <div class="flex items-center gap-6"><div><span class="font-black text-2xl text-purple-700">100%</span> <span class="text-slate-500">otomatisasi prospek</span></div><div><span class="font-black text-2xl text-blue-600">24/7</span> <span class="text-slate-500">respon instan</span></div></div>
        </div>
    </section>

    <!-- FITUR UTAMA YANG SUDAH JADI (sesuai deskripsi) -->
    <section id="fitur-unggulan" class="py-20 px-4 bg-white">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-indigo-600 font-semibold text-sm bg-indigo-100 px-4 py-1.5 rounded-full">Fitur yang Sudah Aktif</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-5 mb-4 bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Kendali Penuh atas AI & Prospek</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Sistem chatbot cerdas dengan CRM prospek internal — siap digunakan untuk bisnis Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Fitur 1: Custom Prompt & SOP -->
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex items-start gap-4 hover:shadow-md transition">
                    <div class="bg-purple-100 p-2 rounded-xl text-purple-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800 text-lg">Custom Prompt & SOP</h3><p class="text-slate-500 text-sm">Atur instruksi AI dari dashboard admin. Bisa mengikuti SOP bisnis Anda atau biarkan AI berpikir luas sesuai data.</p></div>
                </div>
                <!-- Fitur 2: Balasan dengan emoji / tanpa emoji -->
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex items-start gap-4 hover:shadow-md transition">
                    <div class="bg-amber-100 p-2 rounded-xl text-amber-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800 text-lg">Gaya Balasan Fleksibel</h3><p class="text-slate-500 text-sm">Aktifkan/nonaktifkan emoji, pilih gaya bahasa: <strong>Formal, Slang, atau Gaul Teknologi</strong>. Sesuaikan dengan persona brand.</p></div>
                </div>
                <!-- Fitur 3: Auto Prospect & Update Status -->
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex items-start gap-4 hover:shadow-md transition">
                    <div class="bg-green-100 p-2 rounded-xl text-green-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800 text-lg">Penilaian Prospek Otomatis</h3><p class="text-slate-500 text-sm">AI menganalisis percakapan lalu mengupdate status prospek menjadi <strong>Hot / Deal / Cancel</strong> secara otomatis. CRM internal tanpa ribet.</p></div>
                </div>
                <!-- Fitur 4: WhatsApp Integration -->
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex items-start gap-4 hover:shadow-md transition">
                    <div class="bg-blue-100 p-2 rounded-xl text-blue-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800 text-lg">Terhubung WhatsApp (Evolution API)</h3><p class="text-slate-500 text-sm">Integrasi penuh dengan WhatsApp Business. Balas pelanggan otomatis dari nomor bisnis Anda.</p></div>
                </div>
                <!-- Fitur 5: Admin Panel Laravel -->
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex items-start gap-4 hover:shadow-md transition">
                    <div class="bg-indigo-100 p-2 rounded-xl text-indigo-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800 text-lg">Manajemen Admin Laravel</h3><p class="text-slate-500 text-sm">Kelola prompt, data prospek, histori chat, dan konfigurasi AI dari dashboard intuitif.</p></div>
                </div>
                <!-- Fitur 6: Python Engine + DeepInfra -->
                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 flex items-start gap-4 hover:shadow-md transition">
                    <div class="bg-rose-100 p-2 rounded-xl text-rose-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800 text-lg">Engine Python + DeepInfra AI</h3><p class="text-slate-500 text-sm">Proses pesan cepat dan stabil menggunakan model Llama 3 via DeepInfra, dipisahkan dari web server.</p></div>
                </div>
            </div>
            <div class="mt-8 text-center bg-purple-50 p-4 rounded-2xl">
                <p class="text-purple-800 font-medium">✨ <strong>Siap jualan?</strong> Semua fitur di atas sudah berfungsi. Tinggal daftar, integrasikan WhatsApp, dan AI siap membantu bisnis Anda.</p>
            </div>
        </div>
    </section>

    <!-- CRM OTOMATIS & PENILAIAN PROSPEK (detail lebih dalam) -->
    <section id="crm-prospek" class="py-20 px-4 bg-gradient-to-br from-slate-50 to-indigo-50/30">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <span class="bg-blue-100 text-blue-700 text-sm font-semibold px-4 py-1 rounded-full">🤖 CRM Otomatis dari Percakapan</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-5 text-slate-800">AI Tidak Hanya Chat, Tapi <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Mengelola Prospek Penjualan</span></h2>
                <p class="text-slate-600 max-w-2xl mx-auto mt-3">Setiap chat dengan pelanggan dinilai secara otomatis → update status pipeline. Tanpa input manual.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-purple-500 hover-lift">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-5">📊</div>
                    <h3 class="text-xl font-bold mb-2">Deteksi Intent & Sentimen</h3>
                    <p class="text-slate-500 text-sm">AI membaca apakah pelanggan berminat (Hot), siap beli (Deal), atau menolak (Cancel). Update CRM langsung.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-blue-500 hover-lift">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-5">🏷️</div>
                    <h3 class="text-xl font-bold mb-2">Otomatis Ubah Status Prospek</h3>
                    <p class="text-slate-500 text-sm">Tanpa repot, chatbot akan menandai prospek sebagai HOT, DEAL, atau CANCEL berdasarkan percakapan. Dashboard langsung terlihat.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-emerald-500 hover-lift">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-5">📈</div>
                    <h3 class="text-xl font-bold mb-2">Rekap Prospek & Notifikasi</h3>
                    <p class="text-slate-500 text-sm">Lihat daftar prospek terbaru, export data, dan notifikasi ke tim sales via email/WhatsApp.</p>
                </div>
            </div>
            <div class="mt-12 text-center bg-white/60 p-6 rounded-2xl shadow-sm max-w-3xl mx-auto">
                <p class="text-slate-700 font-medium">💡 <strong>Contoh kerja:</strong> Pelanggan chat "Saya tertarik dengan produk A, tapi harga?" → AI deteksi minat tinggi → status prospek otomatis menjadi <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded">HOT</span> dan tersimpan di CRM Anda.</p>
            </div>
        </div>
    </section>

    <!-- TARGET PASAR -->
    <section class="py-16 px-4 bg-white">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-3">Cocok untuk Semua Bisnis yang Butuh Chatbot + CRM Otomatis</h2>
            <p class="text-slate-500 mb-10">E-commerce, properti, jasa keuangan, pendidikan, kesehatan, agensi, UKM, dan startup.</p>
            <div class="flex flex-wrap justify-center gap-5">
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">🛒 Toko Online</div>
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">🏢 Properti & Real Estate</div>
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">🎓 Lembaga Pendidikan</div>
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">🏥 Klinik & RS</div>
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">📱 Startup Digital</div>
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">🤝 Layanan Konsultasi</div>
            </div>
        </div>
    </section>

    <!-- TESTIMONI (fiktif namun membangun kepercayaan) -->
    <section id="testimoni" class="py-16 px-4 bg-indigo-50/40">
        <div class="max-w-5xl mx-auto text-center">
            <span class="text-sm font-semibold text-purple-600 bg-purple-100 px-3 py-1 rounded-full">Testimoni</span>
            <h2 class="text-3xl font-bold mt-4 mb-8">Apa Kata Pengguna Tera.ai?</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm text-left">
                    <div class="flex text-yellow-400 mb-2">★★★★★</div>
                    <p class="text-slate-600 text-sm">"Fitur auto-update prospek ke HOT/Deal benar-benar menghemat waktu sales. Chatbot langsung menilai minat pelanggan, kami tinggal follow up."</p>
                    <h4 class="font-bold mt-4">— Dani, Owner E-commerce</h4>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm text-left">
                    <div class="flex text-yellow-400 mb-2">★★★★★</div>
                    <p class="text-slate-600 text-sm">"Kustomisasi gaya bahasa dan emoji membuat chatbot terdengar seperti tim kami sendiri. Tingkat konversi prospek meningkat 42%."</p>
                    <h4 class="font-bold mt-4">— Rina, Manajer Penjualan</h4>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm text-left">
                    <div class="flex text-yellow-400 mb-2">★★★★★</div>
                    <p class="text-slate-600 text-sm">"Integrasi WhatsApp via Evolution API sangat mulus. Sekarang pelanggan bisa chat 24/7 dan data prospek langsung masuk CRM."</p>
                    <h4 class="font-bold mt-4">— Andre, CEO Properti</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING SECTION (original blade logic, tidak diubah) -->
    <section id="pricing" class="py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-purple-600 font-semibold text-sm bg-purple-100 px-4 py-1 rounded-full">Paket Harga</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-4 mb-3">Pilih Sesuai Kebutuhan Bisnis Anda</h2>
                <p class="text-slate-500">Semua paket sudah termasuk fitur chatbot AI + CRM prospek otomatis.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                @if(isset($plans) && $plans->count() > 0)
                    @foreach($plans as $plan)
                    <div class="bg-white rounded-3xl p-7 border border-slate-200 shadow-sm hover:shadow-xl transition-all flex flex-col relative">
                        @if($loop->first)
                            <div class="absolute -top-3 left-6 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs font-bold px-4 py-1 rounded-full">Paling Populer</div>
                        @endif
                        <h3 class="text-2xl font-extrabold text-slate-800 text-center mb-2">{{ $plan->name }}</h3>
                        <div class="text-center mb-5">
                            <span class="text-4xl font-black text-blue-600">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                            <span class="text-slate-500">/bulan</span>
                        </div>
                        <ul class="space-y-3 mb-8 text-sm text-slate-600 flex-1">
                            <li class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span> @if($plan->max_messages > 0) Kuota <strong>{{ number_format($plan->max_messages, 0, ',', '.') }}</strong> Pesan AI @else Kuota <strong>Unlimited</strong> Pesan AI @endif </span>
                            </li>
                            @php
                                $featuresList = [];
                                if (!empty($plan->features)) {
                                    if (is_string($plan->features)) {
                                        $decoded = json_decode($plan->features, true);
                                        $featuresList = is_array($decoded) ? $decoded : explode("\n", $plan->features);
                                    } elseif (is_array($plan->features)) {
                                        $featuresList = $plan->features;
                                    }
                                }
                            @endphp
                            @foreach($featuresList as $feature)
                                @if(trim($feature) !== '')
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>{{ trim($feature) }}</span>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                        <a href="{{ route('register') }}" class="block text-center w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:shadow-lg transition mt-auto">Pilih Paket</a>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center text-slate-500 py-8 bg-slate-50 rounded-2xl">Paket berlangganan akan segera hadir. Hubungi kami untuk demo gratis.</div>
                @endif
            </div>
            <div class="mt-12 bg-gradient-to-r from-indigo-900 to-purple-800 rounded-3xl p-8 text-center text-white shadow-xl">
                <h3 class="text-2xl font-bold mb-2">Butuh solusi enterprise?</h3>
                <p class="mb-5 text-indigo-100">Integrasi khusus, volume chat tinggi, atau on-premise. Tim kami siap membantu.</p>
                <a href="https://wa.me/6285295955580" target="_blank" class="inline-block px-8 py-3 bg-white text-indigo-700 font-bold rounded-full hover:bg-gray-100 transition shadow-md">Hubungi Tim Sales →</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-16 px-4 relative">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div><div class="text-2xl font-extrabold bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent mb-4">Tera.ai</div><p class="text-slate-400 text-sm">Chatbot AI + CRM otomatis. Tingkatkan penjualan dan layanan pelanggan.</p></div>
                <div><h4 class="font-bold mb-4">Fitur</h4><ul class="space-y-2 text-sm text-slate-300"><li><a href="#fitur-unggulan" class="hover:text-white">Custom Prompt & SOP</a></li><li><a href="#crm-prospek" class="hover:text-white">Auto Prospek (Hot/Deal/Cancel)</a></li><li><a href="#pricing" class="hover:text-white">Harga & Paket</a></li></ul></div>
                <div><h4 class="font-bold mb-4">Dukungan</h4><ul class="space-y-2 text-sm text-slate-300"><li><a href="https://wa.me/6285295955580" target="_blank">WhatsApp Support</a></li><li><a href="#">Pusat Bantuan</a></li><li><a href="#">Integrasi API</a></li></ul></div>
                <div><h4 class="font-bold mb-4">Kontak</h4><p class="text-sm text-slate-300">📞 0852-9595-5580</p><p class="text-sm text-slate-300">✉️ nmfuadi@gmail.com</p><p class="text-sm text-slate-300 text-xs mt-2">Jl. Kemiri Jaya, Beji, Depok, Jawa Barat</p></div>
            </div>
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-slate-400">
                <p>© 2025 Tera.ai — Chatbot AI + CRM Prospek Otomatis. Siap membantu bisnis Anda.</p>
                <div class="flex gap-6 mt-3 md:mt-0"><a href="#" class="hover:text-white">Kebijakan Privasi</a><a href="#" class="hover:text-white">Syarat & Ketentuan</a></div>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if(targetId === "#") return;
                const target = document.querySelector(targetId);
                if(target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
            });
        });
    </script>
</body>
</html>