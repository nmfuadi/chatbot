<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terabot.AI - Layanan Pelanggan Cerdas</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased text-slate-800 selection:bg-purple-500 selection:text-white">

    <section class="relative bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-600 text-white pt-32 pb-32 px-4 rounded-b-[3rem] overflow-hidden">
        
        <nav class="absolute top-0 left-0 w-full z-50 px-6 py-6 flex justify-between items-center max-w-6xl mx-auto right-0">
            <div class="text-white font-extrabold text-2xl tracking-tight">
                Terabot.AI
            </div>
            
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ route('login') }}" class="text-white hover:text-purple-200 font-medium px-4 py-2 transition text-sm sm:text-base">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="bg-white text-purple-700 hover:bg-gray-100 font-bold px-5 py-2.5 rounded-full transition shadow-sm text-sm sm:text-base">
                    Daftar Baru
                </a>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto text-center relative z-10">
            <div class="inline-block px-3 py-1 mb-6 rounded-full bg-white/20 text-sm font-medium backdrop-blur-sm border border-white/30 mt-4">
                ✨ CS AI Generasi Terbaru
            </div>
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6 tracking-tight">Terabot.AI</h1>
            <p class="text-xl md:text-2xl font-medium mb-4 text-purple-100">Tingkatkan Layanan Pelanggan Anda dengan Chatbot AI Pintar</p>
            <p class="text-base md:text-lg mb-10 text-purple-200 max-w-2xl mx-auto">
                Asisten virtual berbasis AI yang dilatih spesifik dari data bisnis Anda. Berinteraksi dengan pelanggan layaknya manusia sesungguhnya 24/7.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3 bg-white text-purple-700 font-bold rounded-full hover:bg-gray-100 transition shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Coba Gratis Sekarang
                </a>
                <a href="#pricing" class="w-full sm:w-auto px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-full hover:bg-white/10 transition flex items-center justify-center gap-2">
                    Lihat Harga
                </a>
            </div>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 -mt-16 relative z-20">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center border border-slate-100">
            <h2 class="text-2xl font-bold mb-2">Customer Service yang Tak Pernah Lelah</h2>
            <p class="text-slate-500 mb-6 text-sm">Didukung oleh Model AI Terbaik di Dunia</p>
            <div class="flex flex-wrap justify-center gap-4">
                <span class="px-4 py-2 bg-green-50 text-green-700 rounded-lg text-sm font-semibold border border-green-200">Llama 3</span>
                <span class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold border border-blue-200">GPT-4o</span>
                <span class="px-4 py-2 bg-orange-50 text-orange-700 rounded-lg text-sm font-semibold border border-orange-200">Claude 3.5</span>
                <span class="px-4 py-2 bg-purple-50 text-purple-700 rounded-lg text-sm font-semibold border border-purple-200">Gemini 1.5</span>
            </div>
        </div>
    </section>

    <section class="py-20 px-4 bg-slate-50">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-green-600 font-semibold text-sm bg-green-100 px-3 py-1 rounded-full">Keunggulan</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-4 mb-4">Semua yang Anda Butuhkan dalam Satu Platform</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium text-slate-700">Bisa kustomisasi SOP CS</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium text-slate-700">Tersambung ke WhatsApp Bisnis</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium text-slate-700">Mampu membalas pesan teks & suara</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium text-slate-700">Terintegrasi dengan sistem CRM</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium text-slate-700">Efisiensi waktu CS hingga 80%</span>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium text-slate-700">Dashboard analitik interaksi</span>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="py-20 px-4 bg-white">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-purple-600 font-semibold text-sm bg-purple-100 px-3 py-1 rounded-full">Pricing</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-4 mb-4">Pilih Paket yang Sesuai Kebutuhan</h2>
                <p class="text-slate-500">Tingkatkan performa tim support Anda hari ini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                @if(isset($plans) && $plans->count() > 0)
                    @foreach($plans as $plan)
                        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-xl transition flex flex-col">
                            <h3 class="text-xl font-bold text-slate-800 text-center mb-2">{{ $plan->name }}</h3>
                            <div class="text-center mb-6">
                                <span class="text-4xl font-extrabold text-blue-600">
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
                                            $featuresList = is_array($decoded) ? $decoded : explode("\n", $plan->features);
                                        } elseif (is_array($plan->features)) {
                                            $featuresList = $plan->features;
                                        }
                                    }
                                @endphp

                                @foreach($featuresList as $feature)
                                    @if(trim($feature) !== '')
                                        <li class="flex items-start text-sm text-slate-700">
                                            <svg class="w-5 h-5 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            <span>{{ trim($feature) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            
                            <a href="{{ route('register') }}" class="block text-center w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg mt-auto">
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
                <h3 class="text-2xl font-bold mb-2">CS AI Custom</h3>
                <p class="mb-6 text-purple-100">Butuh fitur untuk skala enterprise dengan keamanan tingkat tinggi?</p>
                <a href="https://wa.me/6285295955580" target="_blank" class="inline-block px-8 py-2 bg-white text-blue-600 font-bold rounded-full hover:bg-gray-50 transition">Hubungi Kami</a>
            </div>
        </div>
    </section>

    <footer class="bg-gradient-to-br from-indigo-700 to-purple-800 py-16 px-4 text-center text-white relative">
        <div class="max-w-4xl mx-auto relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Menghadirkan Layanan Pelanggan yang Cepat dan Cerdas?</h2>
            <p class="mb-10 text-purple-200">Hubungi kami sekarang dan revolusi bisnis Anda.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
                <div class="bg-white/10 p-6 rounded-2xl border border-white/20 backdrop-blur-sm">
                    <div class="text-2xl mb-2">📱</div>
                    <h4 class="font-bold">WhatsApp</h4>
                    <p class="text-sm text-purple-200 mt-2">085295955580</p>
                </div>
                <div class="bg-white/10 p-6 rounded-2xl border border-white/20 backdrop-blur-sm">
                    <div class="text-2xl mb-2">✉️</div>
                    <h4 class="font-bold">Email</h4>
                    <p class="text-sm text-purple-200 mt-2">nmfuadi@gmail.com</p>
                </div>
                <div class="bg-white/10 p-6 rounded-2xl border border-white/20 backdrop-blur-sm">
                    <div class="text-2xl mb-2">📍</div>
                    <h4 class="font-bold">Kantor</h4>
                    <p class="text-sm text-purple-200 mt-2">Jl. Kemiri Jaya, Beji,<br>Kecamatan Beji, Kota Depok,<br>Jawa Barat 16421, Indonesia</p>
                </div>
            </div>

            <a href="https://wa.me/6285295955580" target="_blank" class="inline-block px-8 py-3 bg-green-500 text-white font-bold rounded-full hover:bg-green-600 transition shadow-lg">
                Chat WhatsApp Sekarang
            </a>
        </div>
    </footer>

</body>
</html>