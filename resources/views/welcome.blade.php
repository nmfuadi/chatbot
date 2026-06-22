<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tera.AI – CS Otomatis 24 Jam + CRM Cerdas via WhatsApp</title>
    <meta name="description" content="Tera.AI: Chatbot AI WhatsApp yang menjawab pelanggan 24 jam, memfilter prospek panas, dan update CRM secara otomatis. Tanpa tambah karyawan.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }

        .glow-violet { box-shadow: 0 0 60px rgba(124, 58, 237, 0.35); }
        .glow-cyan   { box-shadow: 0 0 40px rgba(6, 182, 212, 0.25); }

        .gradient-text {
            background: linear-gradient(135deg, #a78bfa 0%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-grid {
            background-image:
                linear-gradient(rgba(124,58,237,0.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,58,237,0.08) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .chat-bubble-in {
            animation: slideInLeft 0.5s ease forwards;
            opacity: 0;
        }
        .chat-bubble-out {
            animation: slideInRight 0.5s ease forwards;
            opacity: 0;
        }
        .chat-bubble-in:nth-child(1) { animation-delay: 0.1s; }
        .chat-bubble-in:nth-child(3) { animation-delay: 0.6s; }
        .chat-bubble-in:nth-child(5) { animation-delay: 1.1s; }
        .chat-bubble-out:nth-child(2) { animation-delay: 0.35s; }
        .chat-bubble-out:nth-child(4) { animation-delay: 0.85s; }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-16px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(16px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .pulse-dot::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            background: #22c55e;
            animation: ping 1.5s cubic-bezier(0,0,0.2,1) infinite;
        }
        @keyframes ping {
            75%, 100% { transform: scale(2); opacity: 0; }
        }

        .badge-hot {
            background: linear-gradient(135deg, #f97316, #ef4444);
        }
        .badge-warm {
            background: linear-gradient(135deg, #eab308, #f97316);
        }
        .badge-cold {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
        }

        @media (prefers-reduced-motion: reduce) {
            .chat-bubble-in, .chat-bubble-out { animation: none; opacity: 1; }
        }
    </style>
</head>
<body class="bg-[#0A0F1E] text-slate-100 antialiased selection:bg-violet-600 selection:text-white">

    <!-- ═══════════════════════════════════════════════════
         NAVBAR
    ═══════════════════════════════════════════════════ -->
    <nav class="fixed top-0 left-0 w-full z-50 px-6 py-4 flex justify-between items-center backdrop-blur-md bg-[#0A0F1E]/80 border-b border-white/5">
        <div class="max-w-6xl w-full mx-auto flex justify-between items-center">
            <div class="font-black text-xl tracking-tight">
                <span class="text-white">tera</span><span class="gradient-text">.ai</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-slate-300 hover:text-white font-medium px-4 py-2 transition text-sm">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="bg-violet-600 hover:bg-violet-500 text-white font-bold px-5 py-2 rounded-lg transition text-sm shadow-lg shadow-violet-900/50">
                    Coba Gratis
                </a>
            </div>
        </div>
    </nav>

    <!-- ═══════════════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════════════ -->
    <section class="relative min-h-screen flex items-center pt-24 pb-20 px-4 hero-grid overflow-hidden">
        <!-- Background blobs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-cyan-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-6xl mx-auto w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <!-- Left: Copy -->
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-6 rounded-full bg-violet-500/10 border border-violet-500/30 text-violet-300 text-xs font-semibold">
                        <span class="relative inline-flex h-2 w-2">
                            <span class="pulse-dot relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        Live 24/7 — AI sedang aktif menjawab pelanggan
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-[1.08] tracking-tight mb-6">
                        CS Otomatis.<br>
                        Prospek Tersaring.<br>
                        <span class="gradient-text">Bisnis Jalan Sendiri.</span>
                    </h1>

                    <p class="text-slate-400 text-lg leading-relaxed mb-8 max-w-lg">
                        Tera.AI menjawab chat WhatsApp pelanggan Anda 24 jam, mengenali siapa yang serius beli, dan mengupdate CRM — tanpa perlu tambah tim CS.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 mb-10">
                        <a href="{{ route('register') }}" class="px-7 py-3.5 bg-violet-600 hover:bg-violet-500 text-white font-bold rounded-lg transition shadow-xl shadow-violet-900/50 text-center">
                            Mulai Gratis Sekarang
                        </a>
                        <a href="#cara-kerja" class="px-7 py-3.5 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-medium rounded-lg transition text-center">
                            Lihat Cara Kerja →
                        </a>
                    </div>

                    <!-- Social proof mini -->
                    <div class="flex items-center gap-4 text-sm text-slate-500">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-400 to-violet-500 border-2 border-[#0A0F1E]"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 border-2 border-[#0A0F1E]"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-rose-500 border-2 border-[#0A0F1E]"></div>
                        </div>
                        <span>Sudah dipakai ratusan bisnis di Indonesia</span>
                    </div>
                </div>

                <!-- Right: Chat Demo Visual -->
                <div class="relative">
                    <div class="bg-[#111827] rounded-2xl border border-white/10 overflow-hidden glow-violet">
                        <!-- Phone status bar -->
                        <div class="bg-[#1a2332] px-4 py-3 flex items-center gap-3 border-b border-white/5">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-cyan-500 flex items-center justify-center text-xs font-bold">T</div>
                            <div>
                                <div class="text-sm font-semibold text-white">Tera.AI</div>
                                <div class="text-xs text-green-400 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full inline-block"></span>
                                    Online — Membalas otomatis
                                </div>
                            </div>
                        </div>

                        <!-- Chat messages -->
                        <div class="p-4 space-y-3 min-h-[280px] bg-[#0d1520]">
                            <div class="chat-bubble-in flex justify-start">
                                <div class="bg-[#1e293b] text-slate-200 rounded-2xl rounded-tl-sm px-4 py-2.5 text-sm max-w-[75%]">
                                    Halo, saya mau tanya soal harga paket bulanannya 🙏
                                </div>
                            </div>
                            <div class="chat-bubble-out flex justify-end">
                                <div class="bg-violet-600 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 text-sm max-w-[80%]">
                                    Halo kak! 👋 Kami punya 3 pilihan paket, mulai dari Rp 299rb/bulan. Boleh tahu bisnisnya bergerak di bidang apa ya, biar saya rekomendasiin yang paling pas?
                                </div>
                            </div>
                            <div class="chat-bubble-in flex justify-start">
                                <div class="bg-[#1e293b] text-slate-200 rounded-2xl rounded-tl-sm px-4 py-2.5 text-sm max-w-[75%]">
                                    Saya jualan properti, tim CS saya kewalahan balas chat 😅
                                </div>
                            </div>
                            <div class="chat-bubble-out flex justify-end">
                                <div class="bg-violet-600 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 text-sm max-w-[80%]">
                                    Wah pas banget! Paket Bisnis kami cocok untuk properti — AI bisa follow-up prospek, bedain yang serius vs yang cuma tanya-tanya, dan update data secara otomatis 🏠
                                </div>
                            </div>
                            <div class="chat-bubble-in flex justify-start">
                                <div class="bg-[#1e293b] text-slate-200 rounded-2xl rounded-tl-sm px-4 py-2.5 text-sm max-w-[75%]">
                                    Menarik! Bisa coba dulu?
                                </div>
                            </div>
                        </div>

                        <!-- CRM auto-update badge -->
                        <div class="bg-[#0f2010] border-t border-green-900/50 px-4 py-3 flex items-center gap-3">
                            <div class="w-6 h-6 rounded bg-green-500/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="text-xs">
                                <span class="text-green-400 font-semibold">CRM diupdate otomatis</span>
                                <span class="text-slate-500 ml-1">· Prospek ini ditandai </span>
                                <span class="badge-hot text-white text-[10px] font-bold px-1.5 py-0.5 rounded">🔥 HOT</span>
                            </div>
                        </div>
                    </div>

                    <!-- Floating stat -->
                    <div class="absolute -bottom-4 -left-4 bg-[#111827] border border-white/10 rounded-xl px-4 py-3 shadow-2xl">
                        <div class="text-2xl font-black text-white">80%</div>
                        <div class="text-xs text-slate-400">lebih sedikit chat<br>yang harus dibalas manual</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         LOGO BAR (AI Models)
    ═══════════════════════════════════════════════════ -->
    <section class="py-10 px-4 border-y border-white/5 bg-[#080c17]">
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-slate-600 text-xs uppercase tracking-widest font-semibold mb-6">Didukung model AI terbaik dunia</p>
            <div class="flex flex-wrap justify-center gap-3">
                <span class="px-4 py-2 bg-[#111827] border border-white/8 text-slate-300 rounded-lg text-sm font-semibold">Llama 3</span>
                <span class="px-4 py-2 bg-[#111827] border border-white/8 text-slate-300 rounded-lg text-sm font-semibold">GPT-4o</span>
                <span class="px-4 py-2 bg-[#111827] border border-white/8 text-slate-300 rounded-lg text-sm font-semibold">Claude 3.5</span>
                <span class="px-4 py-2 bg-[#111827] border border-white/8 text-slate-300 rounded-lg text-sm font-semibold">Gemini 1.5</span>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         PROBLEM → SOLUTION
    ═══════════════════════════════════════════════════ -->
    <section class="py-24 px-4 bg-[#0A0F1E]">
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-violet-400 text-sm font-semibold uppercase tracking-widest mb-4">Masalah yang kami selesaikan</p>
            <h2 class="text-3xl md:text-4xl font-black mb-16 text-white leading-tight">
                Tim CS Anda kelelahan.<br>
                <span class="text-slate-500">Prospek kabur sebelum sempat difollow-up.</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                <div class="bg-[#111827] border border-red-900/30 rounded-xl p-6 text-left">
                    <div class="text-3xl mb-3">😤</div>
                    <h3 class="font-bold text-white mb-2">Chat masuk ratusan per hari</h3>
                    <p class="text-slate-500 text-sm">CS kewalahan, respon lambat, pelanggan kabur ke kompetitor.</p>
                </div>
                <div class="bg-[#111827] border border-yellow-900/30 rounded-xl p-6 text-left">
                    <div class="text-3xl mb-3">🤷</div>
                    <h3 class="font-bold text-white mb-2">Tidak tahu mana prospek serius</h3>
                    <p class="text-slate-500 text-sm">Semua chat diperlakukan sama. Prospek panas malah didiamkan.</p>
                </div>
                <div class="bg-[#111827] border border-blue-900/30 rounded-xl p-6 text-left">
                    <div class="text-3xl mb-3">📋</div>
                    <h3 class="font-bold text-white mb-2">Data prospek berserakan</h3>
                    <p class="text-slate-500 text-sm">Update CRM manual, data sering kelewat, follow-up berantakan.</p>
                </div>
            </div>

            <div class="flex items-center justify-center gap-4 text-slate-500 text-sm font-medium mb-16">
                <div class="h-px flex-1 bg-white/5"></div>
                Tera.AI mengatasinya sekaligus
                <div class="h-px flex-1 bg-white/5"></div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         FITUR UTAMA
    ═══════════════════════════════════════════════════ -->
    <section id="fitur" class="py-4 pb-24 px-4 bg-[#0A0F1E]">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <p class="text-violet-400 text-sm font-semibold uppercase tracking-widest mb-4">Fitur</p>
                <h2 class="text-3xl md:text-4xl font-black text-white">Satu platform, semua yang Anda butuhkan</h2>
            </div>

            <!-- Feature 1: Chatbot AI -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center mb-20">
                <div>
                    <div class="inline-block px-3 py-1 bg-violet-500/10 border border-violet-500/20 text-violet-300 text-xs font-bold rounded-full mb-4 uppercase tracking-wider">CS Otomatis</div>
                    <h3 class="text-2xl md:text-3xl font-black text-white mb-4">AI yang memahami bisnis Anda, bukan sekadar bot kaku</h3>
                    <p class="text-slate-400 leading-relaxed mb-6">
                        Setting prompt langsung dari dashboard — masukkan SOP, info produk, dan cara bicara yang Anda mau. AI akan menjawab persis seperti tim CS terbaik Anda, 24 jam tanpa henti.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <div class="w-5 h-5 rounded-full bg-violet-500/20 border border-violet-500/30 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Custom SOP & persona sesuai brand Anda
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <div class="w-5 h-5 rounded-full bg-violet-500/20 border border-violet-500/30 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Pilih gaya bahasa: formal, santai, atau gaul
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <div class="w-5 h-5 rounded-full bg-violet-500/20 border border-violet-500/30 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Balas pesan teks dan pesan suara (voice note)
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <div class="w-5 h-5 rounded-full bg-violet-500/20 border border-violet-500/30 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Toggle emoji on/off sesuai selera brand
                        </div>
                    </div>
                </div>
                <div class="bg-[#111827] rounded-2xl border border-white/8 p-6">
                    <div class="text-xs text-slate-500 uppercase font-semibold tracking-wider mb-4">Dashboard Pengaturan AI</div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs text-slate-400 mb-1.5 block">Prompt / SOP CS Anda</label>
                            <div class="bg-[#1e293b] rounded-lg p-3 text-sm text-slate-300 border border-white/5">
                                Anda adalah CS dari Toko Baju Maju. Selalu sapa dengan "Halo Kak!" dan tawarkan promo hari ini...
                                <span class="text-violet-400">|</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs text-slate-400 mb-1.5 block">Gaya Bahasa</label>
                                <div class="bg-violet-600 rounded-lg px-3 py-2 text-sm text-white font-medium text-center">Santai ✓</div>
                            </div>
                            <div>
                                <label class="text-xs text-slate-400 mb-1.5 block">Gunakan Emoji</label>
                                <div class="bg-[#1e293b] rounded-lg px-3 py-2 flex items-center justify-between border border-white/5">
                                    <span class="text-sm text-slate-300">Aktif</span>
                                    <div class="w-8 h-4 bg-violet-600 rounded-full relative">
                                        <div class="w-3 h-3 bg-white rounded-full absolute right-0.5 top-0.5"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 2: Auto CRM -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="order-2 lg:order-1 bg-[#111827] rounded-2xl border border-white/8 p-6">
                    <div class="text-xs text-slate-500 uppercase font-semibold tracking-wider mb-4">CRM Otomatis — Update Real-time</div>
                    <div class="space-y-3">
                        <div class="bg-[#1e293b] rounded-xl p-4 flex items-center gap-4 border border-white/5">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-rose-500 flex items-center justify-center text-sm font-bold shrink-0">B</div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-white text-sm">Budi Santoso</div>
                                <div class="text-xs text-slate-400 truncate">"Kapan bisa survey lokasinya?"</div>
                            </div>
                            <span class="badge-hot text-white text-[11px] font-bold px-2 py-1 rounded-full shrink-0">🔥 Hot</span>
                        </div>
                        <div class="bg-[#1e293b] rounded-xl p-4 flex items-center gap-4 border border-white/5">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-sm font-bold shrink-0">S</div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-white text-sm">Sari Dewi</div>
                                <div class="text-xs text-slate-400 truncate">"Oke, saya mau ambil 2 unit"</div>
                            </div>
                            <span class="text-white text-[11px] font-bold px-2 py-1 rounded-full bg-green-600 shrink-0">✅ Deal</span>
                        </div>
                        <div class="bg-[#1e293b] rounded-xl p-4 flex items-center gap-4 border border-white/5">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-sm font-bold shrink-0">R</div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-white text-sm">Reza Firmansyah</div>
                                <div class="text-xs text-slate-400 truncate">"Cuma tanya-tanya aja kak"</div>
                            </div>
                            <span class="badge-cold text-white text-[11px] font-bold px-2 py-1 rounded-full shrink-0">❄️ Cold</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/5 flex items-center gap-2 text-xs text-slate-500">
                        <svg class="w-3.5 h-3.5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Diperbarui otomatis berdasarkan analisis percakapan AI
                    </div>
                </div>
                <div class="order-1 lg:order-2">
                    <div class="inline-block px-3 py-1 bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-xs font-bold rounded-full mb-4 uppercase tracking-wider">CRM Cerdas</div>
                    <h3 class="text-2xl md:text-3xl font-black text-white mb-4">AI yang tahu siapa yang layak dikejar tim sales Anda</h3>
                    <p class="text-slate-400 leading-relaxed mb-6">
                        Dari setiap percakapan, Tera.AI otomatis menilai tingkat ketertarikan calon pembeli — dan langsung mengupdate status mereka di CRM. Tim sales Anda tinggal fokus ke yang sudah panas.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <div class="w-5 h-5 rounded-full bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Klasifikasi otomatis: 🔥 Hot, ⚡ Warm, ❄️ Cold, ✅ Deal, ❌ Cancel
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <div class="w-5 h-5 rounded-full bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Data nama, kontak, & kebutuhan prospek tersimpan otomatis
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-300">
                            <div class="w-5 h-5 rounded-full bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Tim sales Anda tahu harus follow-up siapa duluan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         CARA KERJA
    ═══════════════════════════════════════════════════ -->
    <section id="cara-kerja" class="py-24 px-4 bg-[#080c17]">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <p class="text-violet-400 text-sm font-semibold uppercase tracking-widest mb-4">Cara Kerja</p>
                <h2 class="text-3xl md:text-4xl font-black text-white">Aktif dalam 3 langkah mudah</h2>
            </div>
            <div class="relative">
                <div class="absolute left-6 top-8 bottom-8 w-px bg-gradient-to-b from-violet-600 via-cyan-500 to-transparent hidden md:block"></div>
                <div class="space-y-10 md:pl-20">
                    <div class="relative flex gap-6 items-start">
                        <div class="absolute -left-14 w-12 h-12 rounded-full bg-violet-600 flex items-center justify-center text-white font-black text-lg shrink-0 hidden md:flex">1</div>
                        <div class="md:hidden w-10 h-10 rounded-full bg-violet-600 flex items-center justify-center text-white font-black shrink-0">1</div>
                        <div>
                            <h3 class="text-lg font-bold text-white mb-2">Sambungkan WhatsApp bisnis Anda</h3>
                            <p class="text-slate-400">Hubungkan nomor WhatsApp Anda lewat QR Code — proses kurang dari 5 menit. Tidak perlu pindah nomor atau platform.</p>
                        </div>
                    </div>
                    <div class="relative flex gap-6 items-start">
                        <div class="absolute -left-14 w-12 h-12 rounded-full bg-violet-500 flex items-center justify-center text-white font-black text-lg shrink-0 hidden md:flex">2</div>
                        <div class="md:hidden w-10 h-10 rounded-full bg-violet-500 flex items-center justify-center text-white font-black shrink-0">2</div>
                        <div>
                            <h3 class="text-lg font-bold text-white mb-2">Setting prompt & kepribadian AI</h3>
                            <p class="text-slate-400">Masukkan SOP, info produk, dan gaya bahasa yang Anda inginkan. AI akan belajar dan menjawab sesuai standar bisnis Anda.</p>
                        </div>
                    </div>
                    <div class="relative flex gap-6 items-start">
                        <div class="absolute -left-14 w-12 h-12 rounded-full bg-cyan-500 flex items-center justify-center text-white font-black text-lg shrink-0 hidden md:flex">3</div>
                        <div class="md:hidden w-10 h-10 rounded-full bg-cyan-500 flex items-center justify-center text-white font-black shrink-0">3</div>
                        <div>
                            <h3 class="text-lg font-bold text-white mb-2">AI bekerja, CRM terupdate sendiri</h3>
                            <p class="text-slate-400">Setiap chat masuk dijawab otomatis. Prospek dinilai, data tersimpan, dan tim Anda tinggal fokus ke yang paling potensial.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         STATS
    ═══════════════════════════════════════════════════ -->
    <section class="py-16 px-4 bg-[#0A0F1E] border-y border-white/5">
        <div class="max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl md:text-4xl font-black gradient-text mb-1">24/7</div>
                <div class="text-slate-500 text-sm">AI aktif tanpa henti</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-black gradient-text mb-1">80%</div>
                <div class="text-slate-500 text-sm">penghematan waktu CS</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-black gradient-text mb-1">&lt;3s</div>
                <div class="text-slate-500 text-sm">rata-rata waktu respon</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-black gradient-text mb-1">100%</div>
                <div class="text-slate-500 text-sm">chat terjawab & tercatat</div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         PRICING
    ═══════════════════════════════════════════════════ -->
    <section id="pricing" class="py-24 px-4 bg-[#0A0F1E]">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <p class="text-violet-400 text-sm font-semibold uppercase tracking-widest mb-4">Harga</p>
                <h2 class="text-3xl md:text-4xl font-black text-white mb-3">Mulai sesuai skala bisnis Anda</h2>
                <p class="text-slate-500">Upgrade atau downgrade kapan saja. Tanpa kontrak jangka panjang.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch mb-10">
                @if(isset($plans) && $plans->count() > 0)
                    @foreach($plans as $index => $plan)
                        <div class="relative rounded-2xl p-px {{ $index === 1 ? 'bg-gradient-to-b from-violet-500 to-cyan-500' : 'bg-white/8' }} card-hover flex flex-col">
                            @if($index === 1)
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-gradient-to-r from-violet-600 to-cyan-600 rounded-full text-white text-xs font-bold whitespace-nowrap">
                                    ⭐ Paling Populer
                                </div>
                            @endif
                            <div class="bg-[#111827] rounded-2xl p-7 flex flex-col flex-1">
                                <h3 class="text-base font-bold text-slate-400 mb-1">{{ $plan->name }}</h3>
                                <div class="mb-6">
                                    <span class="text-4xl font-black text-white">Rp {{ number_format($plan->price, 0, ',', '.') }}</span>
                                    <span class="text-slate-500 text-sm">/bulan</span>
                                </div>

                                <ul class="space-y-3 mb-8 flex-1">
                                    <li class="flex items-start gap-3 text-sm text-slate-300">
                                        <svg class="w-4 h-4 text-violet-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @if($plan->max_messages > 0)
                                            <span><strong class="text-white">{{ number_format($plan->max_messages, 0, ',', '.') }}</strong> pesan AI/bulan</span>
                                        @else
                                            <span><strong class="text-white">Unlimited</strong> pesan AI</span>
                                        @endif
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
                                            <li class="flex items-start gap-3 text-sm text-slate-300">
                                                <svg class="w-4 h-4 text-violet-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                {{ trim($feature) }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>

                                <a href="{{ route('register') }}" class="block text-center w-full py-3 font-bold rounded-xl transition mt-auto
                                    {{ $index === 1
                                        ? 'bg-gradient-to-r from-violet-600 to-cyan-600 text-white hover:opacity-90 shadow-lg shadow-violet-900/30'
                                        : 'bg-white/5 hover:bg-white/10 border border-white/10 text-white' }}">
                                    Pilih Paket Ini
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-full text-center text-slate-500 py-10">
                        Paket berlangganan belum tersedia saat ini.
                    </div>
                @endif
            </div>

            <!-- Enterprise -->
            <div class="bg-gradient-to-r from-violet-900/40 to-cyan-900/30 border border-violet-500/20 rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <div class="text-xs text-violet-400 font-bold uppercase tracking-wider mb-2">Enterprise / Custom</div>
                    <h3 class="text-xl font-black text-white mb-1">Butuh solusi skala besar?</h3>
                    <p class="text-slate-400 text-sm">Multi-nomor, integrasi custom, SLA tinggi, dan dedicated support.</p>
                </div>
                <a href="https://wa.me/6289528285349?text=Saya%20mengetahui%20Tera%20AI%20dari%20web,%20saya%20ingin%20tahu%20lebih%20lanjut%20produk%20chatbot%20dan%20CRM." target="_blank" class="shrink-0 px-7 py-3 bg-white text-slate-900 font-bold rounded-xl hover:bg-slate-100 transition whitespace-nowrap">
                    Diskusi Kebutuhan Anda
                </a>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         CTA FINAL
    ═══════════════════════════════════════════════════ -->
    <section class="py-24 px-4 bg-[#080c17]">
        <div class="max-w-3xl mx-auto text-center">
            <div class="text-6xl mb-6">🚀</div>
            <h2 class="text-3xl md:text-5xl font-black text-white mb-5 leading-tight">
                Bisnis Anda butuh CS<br>
                <span class="gradient-text">yang tidak pernah tidur.</span>
            </h2>
            <p class="text-slate-400 text-lg mb-8 max-w-xl mx-auto">
                Daftar sekarang dan rasakan perbedaannya. Setup kurang dari 10 menit, langsung aktif di WhatsApp Anda.
            </p>
            <a href="{{ route('register') }}" class="inline-block px-10 py-4 bg-violet-600 hover:bg-violet-500 text-white font-black rounded-xl transition shadow-2xl shadow-violet-900/60 text-lg">
                Mulai Gratis — Tanpa Kartu Kredit
            </a>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════ -->
    <footer class="bg-[#060910] border-t border-white/5 py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <div class="font-black text-2xl mb-2">
                    <span class="text-white">tera</span><span class="gradient-text">.ai</span>
                </div>
                <p class="text-slate-600 text-sm">CS Otomatis & CRM Cerdas via WhatsApp</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-12">
                <a href="https://wa.me/6289528285349?text=Saya%20mengetahui%20Tera%20AI%20dari%20web,%20saya%20ingin%20tahu%20lebih%20lanjut%20produk%20chatbot%20dan%20CRM." target="_blank" class="bg-[#111827] hover:bg-[#1e293b] border border-white/5 p-5 rounded-xl flex items-center gap-4 transition">
                    <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center text-xl shrink-0">📱</div>
                    <div>
                        <div class="font-semibold text-white text-sm">WhatsApp</div>
                        <div class="text-slate-500 text-xs mt-0.5">085295955580</div>
                    </div>
                </a>
                <a href="mailto:nmfuadi@gmail.com" class="bg-[#111827] hover:bg-[#1e293b] border border-white/5 p-5 rounded-xl flex items-center gap-4 transition">
                    <div class="w-10 h-10 rounded-lg bg-violet-500/10 flex items-center justify-center text-xl shrink-0">✉️</div>
                    <div>
                        <div class="font-semibold text-white text-sm">Email</div>
                        <div class="text-slate-500 text-xs mt-0.5">nmfuadi@gmail.com</div>
                    </div>
                </a>
                <div class="bg-[#111827] border border-white/5 p-5 rounded-xl flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-cyan-500/10 flex items-center justify-center text-xl shrink-0">📍</div>
                    <div>
                        <div class="font-semibold text-white text-sm">Kantor</div>
                        <div class="text-slate-500 text-xs mt-0.5">Jl. Kemiri Jaya, Beji,<br>Depok, Jawa Barat 16421</div>
                    </div>
                </div>
            </div>

            <div class="text-center text-slate-700 text-xs">
                © {{ date('Y') }} Tera.AI · Hak cipta dilindungi
            </div>
        </div>
    </footer>

</body>

<script src="https://chatbotnew.web.id/tera-widget.js" data-base-url="https://chatbotnew.web.id" data-user-id="10"></script>
</html>