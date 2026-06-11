<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tera.AI - Asisten AI & CRM Otomatis WhatsApp</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-nav { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .gradient-text { background: linear-gradient(to right, #a855f7, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-slate-50 antialiased text-slate-800 selection:bg-purple-500 selection:text-white">

    <nav class="fixed top-0 w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-white font-extrabold text-2xl tracking-tight flex items-center gap-2 drop-shadow-md">
                <div class="w-8 h-8 bg-gradient-to-tr from-purple-500 to-blue-500 rounded-lg flex items-center justify-center text-white text-sm">🤖</div>
                Tera.AI
            </div>
            
            <div class="flex items-center gap-3 sm:gap-5">
                <a href="{{ route('login') }}" class="text-white hover:text-purple-200 font-medium px-2 py-2 transition text-sm sm:text-base drop-shadow-md">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="bg-white text-purple-700 hover:bg-gray-50 hover:scale-105 font-bold px-6 py-2.5 rounded-full transition shadow-lg text-sm sm:text-base">
                    Daftar Gratis
                </a>
            </div>
        </div>
    </nav>

    <section class="relative bg-[#0f172a] text-white pt-40 pb-32 px-4 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] bg-purple-600/30 rounded-full blur-[120px]"></div>
            <div class="absolute top-[20%] -right-[10%] w-[40%] h-[40%] bg-blue-600/30 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 mb-6 rounded-full bg-white/10 text-sm font-medium border border-white/20">
                    <span class="flex h-2 w-2 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    AI Chatbot & CRM Terintegrasi
                </div>
                <h1 class="text-5xl md:text-6xl font-extrabold mb-6 tracking-tight leading-tight">
                    Balas Chat Cepat,<br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">Filter Prospek Otomatis.</span>
                </h1>
                <p class="text-lg md:text-xl mb-8 text-slate-300 max-w-xl">
                    Tinggalkan cara lama. Tera.AI membalas WhatsApp dengan gaya bahasa bisnis Anda, menilai chat prospek, dan mengubahnya menjadi <span class="text-green-400 font-bold">Hot, Deal, atau Cancel</span> secara otomatis!
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold rounded-full hover:shadow-[0_0_20px_rgba(168,85,247,0.4)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 text-lg">
                        Mulai Otomatisasi Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>

            <div class="relative hidden lg:block">
                <div class="bg-white/10 p-4 rounded-3xl border border-white/20 backdrop-blur-md shadow-2xl relative">
                    <div class="flex justify-end mb-4">
                        <div class="bg-purple-600 text-white p-3 rounded-2xl rounded-tr-none text-sm max-w-[80%] shadow-md">
                            "Halo kak, mau tanya pricelist paket hemat dong?"
                        </div>
                    </div>
                    <div class="flex justify-start mb-4 relative">
                        <div class="bg-slate-100 text-slate-800 p-3 rounded-2xl rounded-tl-none text-sm max-w-[80%] shadow-md">
                            "Halo kak! 🙌 Tentu, untuk paket hemat harganya Rp 150rb aja kak. Kakak minat untuk pengiriman ke daerah mana nih? 😊"
                        </div>
                    </div>
                    <div class="flex justify-center mt-6">
                        <div class="bg-green-500/20 border border-green-500/50 text-green-300 text-xs px-4 py-2 rounded-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Tera.AI mengubah status prospek ke: <strong>HOT LEAD 🔥</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 -mt-10 relative z-20">
        <div class="bg-white rounded-2xl shadow-xl p-6 text-center border border-slate-100">
            <p class="text-slate-500 mb-4 text-sm font-semibold uppercase tracking-wider">Diotaki Oleh Teknologi AI Terkemuka</p>
            <div class="flex flex-wrap justify-center gap-6 items-center grayscale opacity-70 hover:grayscale-0 transition-all duration-500">
                <span class="text-xl font-bold text-slate-700">🦙 Llama</span>
                <span class="text-xl font-bold text-slate-700">Meta AI</span>
                <span class="text-xl font-bold text-slate-700">DeepInfra Engine</span>
            </div>
        </div>
    </section>

    <section class="py-24 px-4 bg-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-extrabold mb-4 text-slate-800">Kenapa Memilih <span class="gradient-text">Tera.AI?</span></h2>
                <p class="text-slate-600 text-lg max-w-2xl mx-auto">Kami tidak hanya membalas chat. Kami merancang asisten yang mengerti cara berjualan dan mendata pelanggan Anda.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Gaya Bahasa Fleksibel</h3>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        Atur persona bot sesuka Anda! Mau balasan saklek ala korporat formal? Gaya anak gaul Jaksel? Atau penuh emoji? Semua bisa diatur melalui *Prompt* di Dashboard.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl z-10">FITUR UNGGULAN</div>
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Penilaian Prospek Cerdas</h3>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        AI akan membaca konteks chat. Jika pelanggan terlihat tertarik, AI otomatis mencatatnya sebagai prospek <strong>HOT</strong>, <strong>DEAL</strong>, atau <strong>CANCEL</strong> ke dalam sistem CRM. Sales Anda tinggal mem-follow up yang "Hot" saja!
                    </p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center text-green-600 mb-6 group-hover:scale-110 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Integrasi WhatsApp Lancar</h3>
                    <p class="text-slate-600 leading-relaxed mb-4">
                        Sistem kami terhubung langsung dengan WhatsApp menggunakan Evolution API yang stabil. Respon dalam hitungan detik, online 24 jam nonstop tanpa perlu menggaji admin lembur.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="py-24 px-4 bg-white relative">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-blue-600 font-bold text-sm tracking-wider uppercase bg-blue-100 px-4 py-2 rounded-full inline-block mb-4">Investasi Bisnis Anda</span>
                <h2 class="text-3xl md:text-5xl font-extrabold mb-4 text-slate-800">Pilih Paket <span class="gradient-text">Tera.AI</span></h2>
                <p class="text-slate-500 text-lg">Gaji admin mahal? Tera.AI bekerja 24/7 dengan biaya jauh lebih hemat.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                @if(isset($plans) && $plans->count() > 0)
                    @foreach($plans as $plan)
                        <div class="bg-white rounded-[2rem] p-8 border-2 {{ $loop->iteration == 2 ? 'border-purple-500 shadow-2xl relative transform md:-translate-y-4' : 'border-slate-100 shadow-lg' }} flex flex-col hover:border-purple-300 transition-all">
                            
                            @if($loop->iteration == 2)
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-purple-600 to-blue-600 text-white px-4 py-1 rounded-full text-sm font-bold shadow-md">
                                Paling Diminati
                            </div>
                            @endif

                            <h3 class="text-2xl font-bold text-slate-800 text-center mb-2">{{ $plan->name }}</h3>
                            <div class="text-center mb-6 py-4 border-b border-slate-100">
                                <span class="text-4xl font-extrabold text-slate-800">
                                    Rp{{ number_format($plan->price, 0, ',', '.') }}
                                </span>
                                <span class="text-slate-500 block mt-1">/bulan</span>
                            </div>
                            
                            <ul class="space-y-4 mb-8 flex-1">
                                <li class="flex items-start">
                                    <div class="bg-green-100 p-1 rounded-full mr-3 shrink-0">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-slate-700 font-medium">
                                        @if($plan->max_messages > 0)
                                            <strong>{{ number_format($plan->max_messages, 0, ',', '.') }}</strong> Pesan AI / Bulan
                                        @else
                                            Pesan AI <strong>Unlimited</strong>
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
                                        <li class="flex items-start">
                                            <div class="bg-green-100 p-1 rounded-full mr-3 shrink-0">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                            <span class="text-slate-600">{{ trim($feature) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            
                            <a href="{{ route('register') }}" class="block text-center w-full py-4 {{ $loop->iteration == 2 ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white' : 'bg-slate-100 text-slate-800 hover:bg-slate-200' }} font-bold rounded-2xl transition shadow-md mt-auto">
                                Pilih Paket {{ $plan->name }}
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center text-slate-500 bg-slate-50 p-8 rounded-2xl border border-dashed border-slate-300">
                        Memuat paket langganan... Silakan hubungi admin.
                    </div>
                @endif
            </div>

            <div class="mt-16 bg-[#0f172a] rounded-3xl p-10 text-center text-white shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-purple-600/20 rounded-full blur-[80px]"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl md:text-3xl font-bold mb-4">Butuh Kapasitas Enterprise atau Fitur Khusus?</h3>
                    <p class="mb-8 text-slate-300 max-w-2xl mx-auto">Untuk perusahaan dengan volume chat massal, integrasi API sistem pihak ketiga, atau instalasi model AI *on-premise*.</p>
                    <a href="https://wa.me/6285295955580" target="_blank" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-slate-900 font-bold rounded-full hover:bg-gray-100 transition shadow-lg">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM11 19.93C7.05 19.43 4 16.05 4 12C4 7.95 7.05 4.57 11 4.07V19.93ZM13 4.07C16.95 4.57 20 7.95 20 12C20 16.05 16.95 19.43 13 19.93V4.07Z"></path></svg>
                        Konsultasi dengan Tim
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 pt-20 pb-10 px-4 text-white relative border-t border-slate-800">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Jangan Biarkan Prospek Anda Menunggu Lama.</h2>
                    <p class="text-slate-400 text-lg mb-8">Otomatisasi pelayanan, tingkatkan konversi penjualan Anda dengan Tera.AI.</p>
                    <a href="https://wa.me/6285295955580" target="_blank" class="inline-flex items-center gap-2 px-8 py-4 bg-green-500 text-white font-bold rounded-full hover:bg-green-600 transition shadow-lg shadow-green-500/30">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.487-1.761-1.663-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                        Hubungi via WhatsApp
                    </a>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700">
                        <div class="text-xl mb-2">✉</div>
                        <h4 class="font-bold text-slate-200">Email Kami</h4>
                        <p class="text-sm text-slate-400 mt-1">nmfuadi@gmail.com</p>
                    </div>
                    <div class="bg-slate-800/50 p-6 rounded-2xl border border-slate-700">
                        <div class="text-xl mb-2">📍</div>
                        <h4 class="font-bold text-slate-200">Alamat Kantor</h4>
                        <p class="text-sm text-slate-400 mt-1">Jl. Kemiri Jaya, Kecamatan Beji,<br>Kota Depok, Jawa Barat 16421</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-slate-500 text-sm">© {{ date('Y') }} Tera.AI. All rights reserved.</p>
                <div class="flex gap-4 mt-4 md:mt-0">
                    <a href="#" class="text-slate-500 hover:text-white transition">Syarat & Ketentuan</a>
                    <a href="#" class="text-slate-500 hover:text-white transition">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Simple script to handle navbar background on scroll
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('bg-slate-900/90', 'shadow-lg');
                nav.classList.remove('glass-nav');
            } else {
                nav.classList.add('glass-nav');
                nav.classList.remove('bg-slate-900/90', 'shadow-lg');
            }
        });
    </script>
</body>
</html>