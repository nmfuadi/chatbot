<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Tera.ai - Chatbot & CRM Otomatis untuk Bisnis Modern</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .gradient-mesh {
            background: radial-gradient(circle at 0% 0%, rgba(139, 92, 246, 0.15) 0%, rgba(59, 130, 246, 0.1) 50%, transparent 80%);
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-white antialiased text-slate-800 selection:bg-purple-500 selection:text-white">

    <!-- Modern Navigation with additional links (preserving login/register) -->
    <nav class="fixed top-0 left-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm transition-all">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-slate-800 font-extrabold text-2xl tracking-tight flex items-center gap-2">
                <span class="bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">Tera.ai</span>
                <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-2 py-0.5 rounded-full">AI</span>
            </div>
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="#features-utama" class="text-slate-600 hover:text-purple-700 transition">Fitur Unggulan</a>
                <a href="#crm-automation" class="text-slate-600 hover:text-purple-700 transition">CRM Otomatis</a>
                <a href="#pricing" class="text-slate-600 hover:text-purple-700 transition">Harga</a>
                <a href="#testimoni" class="text-slate-600 hover:text-purple-700 transition">Testimoni</a>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('login') }}" class="text-slate-700 hover:text-purple-700 font-medium px-4 py-2 transition text-sm">Masuk</a>
                <a href="{{ route('register') }}" class="bg-gradient-to-r from-purple-600 to-blue-600 text-white hover:shadow-lg font-bold px-5 py-2.5 rounded-full transition shadow-md text-sm">Daftar Gratis</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section – Brand: Tera.ai + CRM & Chatbot focus -->
    <section class="relative bg-gradient-to-br from-indigo-50 via-white to-purple-50 pt-36 pb-24 px-4 overflow-hidden">
        <div class="absolute inset-0 gradient-mesh pointer-events-none"></div>
        <div class="max-w-5xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur-sm border border-purple-200 rounded-full px-4 py-1.5 shadow-sm mb-6">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-sm font-medium text-slate-700">AI generasi baru + CRM otomatis</span>
            </div>
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight bg-gradient-to-r from-purple-700 via-indigo-700 to-blue-700 bg-clip-text text-transparent mb-6">
                Tera.ai
            </h1>
            <p class="text-2xl md:text-3xl font-semibold text-slate-800 mb-4">Chatbot cerdas + CRM otomatis <br> dalam satu platform</p>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto mb-8">Tingkatkan layanan pelanggan 24/7, ubah prospek jadi pelanggan, dan kelola relasi secara otomatis. <br class="hidden md:block">Dirancang untuk semua bisnis: e-commerce, jasa, pendidikan, dan lainnya.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="group px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold rounded-full shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2 text-lg">
                    🚀 Coba Gratis 14 Hari
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
                <a href="#crm-automation" class="px-8 py-4 bg-white border-2 border-purple-300 text-purple-700 font-bold rounded-full hover:bg-purple-50 transition shadow-sm">Lihat Fitur CRM</a>
            </div>
            <div class="flex flex-wrap justify-center gap-6 mt-12 text-sm font-medium text-slate-500">
                <div class="flex items-center gap-2">✅ Tanpa kartu kredit</div>
                <div class="flex items-center gap-2">⚡ Setup 5 menit</div>
                <div class="flex items-center gap-2">🔒 Keamanan enterprise</div>
            </div>
        </div>
        <!-- Abstract floating shape -->
        <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
    </section>

    <!-- AI Model Badges & Stat (preserve original block but restyled) -->
    <section class="max-w-5xl mx-auto px-4 -mt-12 relative z-20">
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border border-slate-100 flex flex-wrap justify-between items-center gap-4">
            <div class="flex flex-wrap items-center gap-4 justify-center md:justify-start">
                <span class="text-slate-600 font-semibold">Didukung oleh:</span>
                <div class="flex flex-wrap gap-3">
                    <span class="px-4 py-2 bg-slate-100 text-slate-800 rounded-xl text-sm font-bold shadow-sm">Llama 3</span>
                    <span class="px-4 py-2 bg-slate-100 text-slate-800 rounded-xl text-sm font-bold shadow-sm">GPT-4o</span>
                    <span class="px-4 py-2 bg-slate-100 text-slate-800 rounded-xl text-sm font-bold shadow-sm">Claude 3.5</span>
                    <span class="px-4 py-2 bg-slate-100 text-slate-800 rounded-xl text-sm font-bold shadow-sm">Gemini 1.5</span>
                </div>
            </div>
            <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
            <div class="flex items-center gap-6">
                <div><span class="font-black text-2xl text-purple-700">500+</span> <span class="text-slate-500">bisnis aktif</span></div>
                <div><span class="font-black text-2xl text-blue-600">80%</span> <span class="text-slate-500">efisiensi CS</span></div>
            </div>
        </div>
    </section>

    <!-- MAIN FEATURES SECTION (keeping original "Semua yang Anda Butuhkan" plus upgrade style) -->
    <section id="features-utama" class="py-20 px-4 bg-white">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-indigo-600 font-semibold text-sm bg-indigo-100 px-4 py-1.5 rounded-full">Keunggulan Inti</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-5 mb-4 bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Semua yang Anda Butuhkan dalam Satu Platform</h2>
                <p class="text-slate-500 max-w-2xl mx-auto">Chatbot, CRM, dan automasi — bekerja seamless untuk bisnis Anda</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Preserving original 6 feature items but redesigning as modern cards -->
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex items-start gap-4 transition hover:shadow-md">
                    <div class="bg-purple-100 p-2 rounded-xl text-purple-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800">Kustomisasi SOP CS</h3><p class="text-sm text-slate-500">Sesuaikan alur percakapan sesuai standar operasional bisnis Anda.</p></div>
                </div>
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex items-start gap-4 transition hover:shadow-md">
                    <div class="bg-green-100 p-2 rounded-xl text-green-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800">WhatsApp Bisnis Terintegrasi</h3><p class="text-sm text-slate-500">Balas pelanggan langsung dari WhatsApp + AI.</p></div>
                </div>
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex items-start gap-4 transition hover:shadow-md">
                    <div class="bg-amber-100 p-2 rounded-xl text-amber-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800">Pesan Teks & Suara</h3><p class="text-sm text-slate-500">Mendukung input suara dan teks untuk pengalaman lebih natural.</p></div>
                </div>
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex items-start gap-4 transition hover:shadow-md">
                    <div class="bg-blue-100 p-2 rounded-xl text-blue-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800">Integrasi CRM Penuh</h3><p class="text-sm text-slate-500">Sinkron data customer, histori percakapan, dan otomatisasi follow-up.</p></div>
                </div>
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex items-start gap-4 transition hover:shadow-md">
                    <div class="bg-rose-100 p-2 rounded-xl text-rose-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800">Efisiensi CS 80%</h3><p class="text-sm text-slate-500">Respon instan, kurangi beban tim support.</p></div>
                </div>
                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex items-start gap-4 transition hover:shadow-md">
                    <div class="bg-indigo-100 p-2 rounded-xl text-indigo-600 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                    <div><h3 class="font-bold text-slate-800">Dashboard Analitik Interaksi</h3><p class="text-sm text-slate-500">Pantau sentimen, topik pembicaraan, dan performa AI secara realtime.</p></div>
                </div>
            </div>
        </div>
    </section>

    <!-- NEW SECTION: CRM AUTOMATION DEEP DIVE (Fitur CRM Otomatis yang powerful) -->
    <section id="crm-automation" class="py-20 px-4 bg-gradient-to-br from-slate-50 to-indigo-50/30">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <span class="bg-blue-100 text-blue-700 text-sm font-semibold px-4 py-1 rounded-full">🤖 CRM Otomatis Terintegrasi</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-5 text-slate-800">Tingkatkan Penjualan & Layanan dengan <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Automasi CRM Cerdas</span></h2>
                <p class="text-slate-600 max-w-2xl mx-auto mt-3">Chatbot Tera.ai tidak hanya menjawab pertanyaan — tapi secara otomatis mengelola seluruh siklus hidup pelanggan.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-purple-500 hover-lift">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-5">📥</div>
                    <h3 class="text-xl font-bold mb-2">Sinkronisasi Data Otomatis</h3>
                    <p class="text-slate-500 text-sm">Setiap percakapan dengan pelanggan secara otomatis tercatat di CRM: nama, kontak, riwayat, dan preferensi.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-blue-500 hover-lift">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-5">🎯</div>
                    <h3 class="text-xl font-bold mb-2">Lead Scoring & Tracking</h3>
                    <p class="text-slate-500 text-sm">AI memberikan skor prospek berdasarkan interaksi & niat beli, lalu alihkan ke tim sales secara real-time.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-emerald-500 hover-lift">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-5">⏱️</div>
                    <h3 class="text-xl font-bold mb-2">Follow-up Otomatis</h3>
                    <p class="text-slate-500 text-sm">Jadwalkan reminder, broadcast WhatsApp, email marketing berdasarkan data dari chatbot CRM.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-amber-500 hover-lift">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-5">📊</div>
                    <h3 class="text-xl font-bold mb-2">Laporan Performa 360°</h3>
                    <p class="text-slate-500 text-sm">Dashboard interaktif: retensi pelanggan, waktu respons, resolusi otomatis, & konversi penjualan.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-rose-500 hover-lift">
                    <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center mb-5">🔗</div>
                    <h3 class="text-xl font-bold mb-2">Integrasi API Terbuka</h3>
                    <p class="text-slate-500 text-sm">Hubungkan dengan HubSpot, Salesforce, Pipedrive, atau sistem internal Anda.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-indigo-500 hover-lift">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-5">⚡</div>
                    <h3 class="text-xl font-bold mb-2">Otomasi Tiket & Notifikasi</h3>
                    <p class="text-slate-500 text-sm">Buat tiket otomatis untuk pertanyaan kompleks, kirim notifikasi ke tim via Slack/Email.</p>
                </div>
            </div>
            <div class="mt-12 text-center">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-8 py-3 rounded-full font-semibold shadow-lg hover:bg-slate-800 transition">✨ Aktifkan CRM Otomatis Sekarang →</a>
            </div>
        </div>
    </section>

    <!-- TARGET PASAR / USE CASES SECTION -->
    <section class="py-16 px-4 bg-white">
        <div class="max-w-5xl mx-auto text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-3">Cocok untuk Semua Industri & Skala Bisnis</h2>
            <p class="text-slate-500 mb-10">E-commerce, jasa keuangan, pendidikan, kesehatan, properti, startup, hingga UKM.</p>
            <div class="flex flex-wrap justify-center gap-5">
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">🛒 Toko Online</div>
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">🏥 Klinik & RS</div>
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">🎓 Lembaga Pendidikan</div>
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">🏢 Properti & Real Estate</div>
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">📱 Startup Digital</div>
                <div class="bg-slate-100 rounded-full px-5 py-2 text-slate-700 font-medium">🤝 Agen & Layanan</div>
            </div>
        </div>
    </section>

    <!-- TESTIMONI AREA (static untuk membangun kepercayaan, tanpa mengubah logic) -->
    <section id="testimoni" class="py-16 px-4 bg-indigo-50/40">
        <div class="max-w-5xl mx-auto text-center">
            <span class="text-sm font-semibold text-purple-600 bg-purple-100 px-3 py-1 rounded-full">Testimoni</span>
            <h2 class="text-3xl font-bold mt-4 mb-8">Apa Kata Pengguna Tera.ai?</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-6 shadow-sm text-left">
                    <div class="flex text-yellow-400 mb-2">★★★★★</div>
                    <p class="text-slate-600 text-sm">"CRM otomatisnya luar biasa! Pelanggan yang chat di WhatsApp langsung masuk ke sistem dan tim sales bisa follow-up lebih cepat. Revenue meningkat 35%."</p>
                    <h4 class="font-bold mt-4">— Fina, Owner Brand Kosmetik</h4>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm text-left">
                    <div class="flex text-yellow-400 mb-2">★★★★★</div>
                    <p class="text-slate-600 text-sm">"Setup chatbot hanya 10 menit, sekarang CS kami bisa fokus ke masalah kompleks. Integrasi CRM memudahkan kami melacak prospek."</p>
                    <h4 class="font-bold mt-4">— Rizki, Manajer Operasional</h4>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm text-left">
                    <div class="flex text-yellow-400 mb-2">★★★★★</div>
                    <p class="text-slate-600 text-sm">"Fitur prediktif dan analitik membantu kami memahami kebutuhan customer. Tera.ai adalah game changer untuk bisnis jasa."</p>
                    <h4 class="font-bold mt-4">— Dina, CEO Agensi Digital</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING SECTION (original blade logic untouched, restyled visually) -->
    <section id="pricing" class="py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-purple-600 font-semibold text-sm bg-purple-100 px-4 py-1 rounded-full">Paket harga</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-4 mb-3">Pilih Langganan Sesuai Kebutuhan</h2>
                <p class="text-slate-500">Mudah upgrade/downgrade kapan saja. Mulai dari paket gratis 14 hari.</p>
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
                        <a href="{{ route('register') }}" class="block text-center w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:shadow-lg transition mt-auto">Daftar & Pilih Paket</a>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center text-slate-500 py-8 bg-slate-50 rounded-2xl">Paket berlangganan segera hadir. Hubungi kami untuk info early access.</div>
                @endif
            </div>
            <!-- Enterprise block (preserved but redesigned) -->
            <div class="mt-12 bg-gradient-to-r from-indigo-900 to-purple-800 rounded-3xl p-8 text-center text-white shadow-xl">
                <h3 class="text-2xl font-bold mb-2">Butuh solusi enterprise khusus?</h3>
                <p class="mb-5 text-indigo-100">Chatbot volume tinggi, on-premise, atau integrasi kustom CRM. Tim kami siap membantu.</p>
                <a href="https://wa.me/6285295955580" target="_blank" class="inline-block px-8 py-3 bg-white text-indigo-700 font-bold rounded-full hover:bg-gray-100 transition shadow-md">Hubungi Tim Sales →</a>
            </div>
        </div>
    </section>

    <!-- Footer with CTAs & contact original details but modern -->
    <footer class="bg-slate-900 text-white py-16 px-4 relative">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="text-2xl font-extrabold bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent mb-4">Tera.ai</div>
                    <p class="text-slate-400 text-sm">Chatbot + CRM otomatis untuk bisnis modern. Didukung AI tercanggih.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Produk</h4>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li><a href="#features-utama" class="hover:text-white">Fitur Chatbot</a></li>
                        <li><a href="#crm-automation" class="hover:text-white">CRM Otomatis</a></li>
                        <li><a href="#pricing" class="hover:text-white">Harga & Paket</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li><a href="https://wa.me/6285295955580" target="_blank">WhatsApp Support</a></li>
                        <li><a href="#">Pusat Bantuan</a></li>
                        <li><a href="#">Integrasi API</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Kontak</h4>
                    <p class="text-sm text-slate-300">📞 0852-9595-5580</p>
                    <p class="text-sm text-slate-300">✉️ nmfuadi@gmail.com</p>
                    <p class="text-sm text-slate-300 text-xs mt-2">Jl. Kemiri Jaya, Beji, Depok, Jawa Barat</p>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-slate-400">
                <p>© 2025 Tera.ai — Revolusi Layanan Pelanggan & CRM otomatis.</p>
                <div class="flex gap-6 mt-3 md:mt-0">
                    <a href="#" class="hover:text-white">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Smooth scroll behavior for anchor links -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if(targetId === "#") return;
                const target = document.querySelector(targetId);
                if(target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>