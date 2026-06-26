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

        .glow-violet { box-shadow: 0 0 60px rgba(124, 58, 237, 0.15); }
        .glow-cyan   { box-shadow: 0 0 40px rgba(6, 182, 212, 0.15); }

        .gradient-text {
            background: linear-gradient(135deg, #7c3aed 0%, #0284c7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-grid {
            background-image:
                linear-gradient(rgba(124,58,237,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,58,237,0.05) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
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

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .float-anim { animation: float 3s ease-in-out infinite; }

        .swatch-selected { box-shadow: 0 0 0 3px rgba(124,58,237,0.3); }

        @media (prefers-reduced-motion: reduce) {
            .chat-bubble-in, .chat-bubble-out, .float-anim { animation: none; opacity: 1; }
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased selection:bg-violet-200 selection:text-violet-900">

    <nav class="fixed top-0 left-0 w-full z-50 px-6 py-4 flex justify-between items-center backdrop-blur-md bg-white/80 border-b border-slate-200 shadow-sm">
        <div class="max-w-6xl w-full mx-auto flex justify-between items-center">
            <div class="font-black text-xl tracking-tight">
                <span class="text-slate-900">tera</span><span class="gradient-text">.ai</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-slate-600 hover:text-violet-600 font-medium px-4 py-2 transition text-sm">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="bg-violet-600 hover:bg-violet-700 text-white font-bold px-5 py-2 rounded-lg transition text-sm shadow-md shadow-violet-200">
                    Coba Gratis
                </a>
            </div>
        </div>
    </nav>

    <section class="relative min-h-screen flex items-center pt-24 pb-20 px-4 hero-grid overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-violet-200 rounded-full blur-3xl pointer-events-none opacity-60"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-cyan-100 rounded-full blur-3xl pointer-events-none opacity-60"></div>

        <div class="max-w-6xl mx-auto w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 mb-6 rounded-full bg-violet-50 border border-violet-200 text-violet-700 text-xs font-semibold shadow-sm">
                        <span class="relative inline-flex h-2 w-2">
                            <span class="pulse-dot relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        Live 24/7 — AI sedang aktif menjawab pelanggan
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-[1.08] tracking-tight mb-6 text-slate-900">
                        CS Otomatis.<br>
                        Prospek Tersaring.<br>
                        <span class="gradient-text">Bisnis Jalan Sendiri.</span>
                    </h1>

                    <p class="text-slate-600 text-lg leading-relaxed mb-8 max-w-lg">
                        Tera.AI menjawab chat WhatsApp pelanggan Anda 24 jam, mengenali siapa yang serius beli, dan mengupdate CRM — tanpa perlu tambah tim CS.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-3 mb-10">
                        <a href="{{ route('register') }}" class="px-7 py-3.5 bg-violet-600 hover:bg-violet-700 text-white font-bold rounded-lg transition shadow-lg shadow-violet-200 text-center">
                            Mulai Gratis Sekarang
                        </a>
                        <a href="#cara-kerja" class="px-7 py-3.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-medium rounded-lg transition text-center shadow-sm">
                            Lihat Cara Kerja →
                        </a>
                    </div>

                    <div class="flex items-center gap-4 text-sm text-slate-500 font-medium">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-300 to-violet-400 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-300 to-blue-400 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-300 to-rose-400 border-2 border-white"></div>
                        </div>
                        <span>Sudah dipakai ratusan bisnis di Indonesia</span>
                    </div>
                </div>

                <div class="relative">
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xl glow-violet">
                        <div class="bg-slate-50 px-4 py-3 flex items-center gap-3 border-b border-slate-200">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-cyan-500 flex items-center justify-center text-xs font-bold text-white shadow-sm">T</div>
                            <div>
                                <div class="text-sm font-bold text-slate-900">Tera.AI</div>
                                <div class="text-xs text-green-600 flex items-center gap-1 font-medium">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full inline-block"></span>
                                    Online — Membalas otomatis
                                </div>
                            </div>
                        </div>

                        <div class="p-4 space-y-3 min-h-[280px] bg-slate-50/50">
                            <div class="chat-bubble-in flex justify-start">
                                <div class="bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-tl-sm px-4 py-2.5 text-sm max-w-[75%] shadow-sm">
                                    Halo, saya mau tanya soal harga paket bulanannya 🙏
                                </div>
                            </div>
                            <div class="chat-bubble-out flex justify-end">
                                <div class="bg-violet-600 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 text-sm max-w-[80%] shadow-sm">
                                    Halo kak! 👋 Kami punya 3 pilihan paket, mulai dari Rp 299rb/bulan. Boleh tahu bisnisnya bergerak di bidang apa ya, biar saya rekomendasiin yang paling pas?
                                </div>
                            </div>
                            <div class="chat-bubble-in flex justify-start">
                                <div class="bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-tl-sm px-4 py-2.5 text-sm max-w-[75%] shadow-sm">
                                    Saya jualan properti, tim CS saya kewalahan balas chat 😅
                                </div>
                            </div>
                            <div class="chat-bubble-out flex justify-end">
                                <div class="bg-violet-600 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 text-sm max-w-[80%] shadow-sm">
                                    Wah pas banget! Paket Bisnis kami cocok untuk properti — AI bisa follow-up prospek, bedain yang serius vs yang cuma tanya-tanya, dan update data secara otomatis 🏠
                                </div>
                            </div>
                            <div class="chat-bubble-in flex justify-start">
                                <div class="bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-tl-sm px-4 py-2.5 text-sm max-w-[75%] shadow-sm">
                                    Menarik! Bisa coba dulu?
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50/80 border-t border-green-100 px-4 py-3 flex items-center gap-3">
                            <div class="w-6 h-6 rounded bg-green-100 flex items-center justify-center border border-green-200">
                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="text-xs">
                                <span class="text-green-700 font-bold">CRM diupdate otomatis</span>
                                <span class="text-slate-500 ml-1 font-medium">· Prospek ini ditandai </span>
                                <span class="badge-hot text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm">🔥 HOT</span>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -bottom-4 -left-4 bg-white border border-slate-200 rounded-xl px-4 py-3 shadow-xl">
                        <div class="text-2xl font-black text-violet-600">80%</div>
                        <div class="text-xs text-slate-600 font-medium">lebih sedikit chat<br>yang harus dibalas manual</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 px-4 border-y border-slate-200 bg-slate-50">
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-slate-500 text-xs uppercase tracking-widest font-bold mb-6">Didukung model AI terbaik dunia</p>
            <div class="flex flex-wrap justify-center gap-3">
                <span class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-bold shadow-sm">Llama 3</span>
                <span class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-bold shadow-sm">GPT-4o</span>
                <span class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-bold shadow-sm">Claude 3.5</span>
                <span class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-bold shadow-sm">Gemini 1.5</span>
            </div>
        </div>
    </section>

    <section class="py-24 px-4 bg-white">
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-violet-600 text-sm font-bold uppercase tracking-widest mb-4">Masalah yang kami selesaikan</p>
            <h2 class="text-3xl md:text-4xl font-black mb-16 text-slate-900 leading-tight">
                Tim CS Anda kelelahan.<br>
                <span class="text-slate-400">Prospek kabur sebelum sempat difollow-up.</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 text-left shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-3xl mb-3">😤</div>
                    <h3 class="font-bold text-slate-900 mb-2">Chat masuk ratusan per hari</h3>
                    <p class="text-slate-600 text-sm font-medium">CS kewalahan, respon lambat, pelanggan kabur ke kompetitor.</p>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 text-left shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-3xl mb-3">🤷</div>
                    <h3 class="font-bold text-slate-900 mb-2">Tidak tahu mana prospek serius</h3>
                    <p class="text-slate-600 text-sm font-medium">Semua chat diperlakukan sama. Prospek panas malah didiamkan.</p>
                </div>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 text-left shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-3xl mb-3">📋</div>
                    <h3 class="font-bold text-slate-900 mb-2">Data prospek berserakan</h3>
                    <p class="text-slate-600 text-sm font-medium">Update CRM manual, data sering kelewat, follow-up berantakan.</p>
                </div>
            </div>

            <div class="flex items-center justify-center gap-4 text-violet-600 text-sm font-bold mb-16 uppercase tracking-wider">
                <div class="h-px flex-1 bg-violet-100"></div>
                Tera.AI mengatasinya sekaligus
                <div class="h-px flex-1 bg-violet-100"></div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-4 pb-24 px-4 bg-slate-50 border-t border-slate-200">
        <div class="max-w-6xl mx-auto pt-20">
            <div class="text-center mb-16">
                <p class="text-violet-600 text-sm font-bold uppercase tracking-widest mb-4">Fitur</p>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">Satu platform, semua yang Anda butuhkan</h2>
            </div>

            <div class="mb-20">
                <div class="text-center mb-10">
                    <p class="text-slate-500 text-sm font-medium mb-3">Tera.AI hadir dalam dua mode — pilih sesuai kebutuhan bisnis Anda</p>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900">Untuk CS atau Marketing?<br><span class="gradient-text">Tera.AI punya keduanya.</span></h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="relative bg-white border-2 border-violet-100 rounded-2xl p-7 overflow-hidden group hover:border-violet-300 transition-colors shadow-sm hover:shadow-md">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-violet-50 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="flex items-center gap-3 mb-4 relative z-10">
                            <div class="w-11 h-11 rounded-xl bg-violet-100 border border-violet-200 flex items-center justify-center text-xl shadow-inner">🎧</div>
                            <div>
                                <div class="text-xs text-violet-600 font-bold uppercase tracking-wider">Mode CS</div>
                                <div class="text-lg font-black text-slate-900">Customer Service AI</div>
                            </div>
                        </div>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed mb-5 relative z-10">AI yang dilatih menjalankan SOP berbasis <strong class="text-violet-700">Golden Rules Customer Experience</strong> dari para pakar CX dunia — menjawab, menyelesaikan masalah, dan membangun loyalitas pelanggan.</p>
                        <div class="space-y-2 relative z-10">
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-700"><span class="text-violet-500">✦</span> Empati, kecepatan, & resolusi di setiap percakapan</div>
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-700"><span class="text-violet-500">✦</span> SOP Golden Rules CX built-in & bisa dikustomisasi</div>
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-700"><span class="text-violet-500">✦</span> Eskalasi otomatis ke human agent saat dibutuhkan</div>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-100 relative z-10">
                            <span class="text-xs text-violet-600 font-bold">Cocok untuk: E-commerce, properti, layanan pelanggan, helpdesk →</span>
                        </div>
                    </div>
                    <div class="relative bg-white border-2 border-rose-100 rounded-2xl p-7 overflow-hidden group hover:border-rose-300 transition-colors shadow-sm hover:shadow-md">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-rose-50 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="flex items-center gap-3 mb-4 relative z-10">
                            <div class="w-11 h-11 rounded-xl bg-rose-100 border border-rose-200 flex items-center justify-center text-xl shadow-inner">🚀</div>
                            <div>
                                <div class="text-xs text-rose-600 font-bold uppercase tracking-wider">Mode Marketing</div>
                                <div class="text-lg font-black text-slate-900">Sales & Growth AI</div>
                            </div>
                        </div>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed mb-5 relative z-10">AI yang aktif mengkualifikasi prospek, menilai lead score, dan menggerakkan pipeline penjualan — dilengkapi <strong class="text-rose-600">Kanban Board & AI Scoring</strong> untuk tim sales yang ingin bergerak lebih cepat.</p>
                        <div class="space-y-2 relative z-10">
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-700"><span class="text-rose-500">✦</span> Pipeline visual Kanban: New → Qualified → Negotiation → Won</div>
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-700"><span class="text-rose-500">✦</span> Lead scoring otomatis 0–100 berdasarkan analisis AI</div>
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-700"><span class="text-rose-500">✦</span> Tools follow-up, broadcast, & nurturing prospek terintegrasi</div>
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-100 relative z-10">
                            <span class="text-xs text-rose-600 font-bold">Cocok untuk: Bisnis properti, asuransi, edukasi, B2B sales →</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-20">
                <div class="flex items-center gap-4 mb-12">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-violet-100 border border-violet-200 flex items-center justify-center text-base shadow-inner">🎧</div>
                        <div class="text-xs text-violet-700 font-bold uppercase tracking-widest">Mode CS · Customer Service AI</div>
                    </div>
                    <div class="h-px flex-1 bg-violet-200"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                    <div>
                        <div class="inline-block px-3 py-1 bg-violet-100 border border-violet-200 text-violet-700 text-xs font-black rounded-full mb-4 uppercase tracking-wider shadow-sm">Golden Rules CX</div>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 mb-4">AI CS yang dilatih para pakar<br>Customer Experience dunia</h3>
                        <p class="text-slate-600 font-medium leading-relaxed mb-6">
                            Bukan sekadar bot yang menjawab pertanyaan. Tera.AI Mode CS menjalankan <span class="text-violet-600 font-bold">SOP Golden Rules Customer Experience</span> — prinsip yang dikembangkan dari metodologi CX terbaik dunia seperti Shep Hyken, Tony Hsieh, dan Blake Morgan — untuk memastikan setiap pelanggan merasa didengar, dihargai, dan puas.
                        </p>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3 bg-white border border-slate-200 shadow-sm rounded-xl p-3.5">
                                <div class="w-7 h-7 rounded-lg bg-violet-100 border border-violet-200 flex items-center justify-center text-sm shrink-0">⚡</div>
                                <div>
                                    <div class="text-sm font-black text-slate-900 mb-0.5">Respon Cepat, Empati Pertama</div>
                                    <div class="text-xs text-slate-600 font-medium">Setiap pesan dibalas &lt;3 detik dengan nada empatik — bukan jawaban robotik — sesuai prinsip "First Contact Resolution".</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 bg-white border border-slate-200 shadow-sm rounded-xl p-3.5">
                                <div class="w-7 h-7 rounded-lg bg-violet-100 border border-violet-200 flex items-center justify-center text-sm shrink-0">🎯</div>
                                <div>
                                    <div class="text-sm font-black text-slate-900 mb-0.5">Selesaikan di Percakapan Pertama</div>
                                    <div class="text-xs text-slate-600 font-medium">AI berusaha menyelesaikan masalah dalam satu sesi, bukan memindahkan pelanggan ke sana-sini (prinsip "Zero Handoff" dari CX framework global).</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 bg-white border border-slate-200 shadow-sm rounded-xl p-3.5">
                                <div class="w-7 h-7 rounded-lg bg-violet-100 border border-violet-200 flex items-center justify-center text-sm shrink-0">🤝</div>
                                <div>
                                    <div class="text-sm font-black text-slate-900 mb-0.5">Personalisasi Berbasis Riwayat</div>
                                    <div class="text-xs text-slate-600 font-medium">AI mengenali pelanggan yang kembali dan menyesuaikan respons — karena pelanggan yang merasa dikenal, tidak akan pergi ke kompetitor.</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 bg-white border border-slate-200 shadow-sm rounded-xl p-3.5">
                                <div class="w-7 h-7 rounded-lg bg-violet-100 border border-violet-200 flex items-center justify-center text-sm shrink-0">🔄</div>
                                <div>
                                    <div class="text-sm font-black text-slate-900 mb-0.5">Eskalasi Cerdas ke Human Agent</div>
                                    <div class="text-xs text-slate-600 font-medium">Ketika situasi memerlukan sentuhan manusia, AI langsung mengoper ke admin dengan konteks percakapan lengkap — tanpa pelanggan harus mengulang cerita.</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-violet-50 border border-violet-200 rounded-xl p-4 shadow-sm">
                            <div class="text-xs text-violet-700 font-black uppercase tracking-wider mb-2">Diinspirasi dari metodologi:</div>
                            <div class="flex flex-wrap gap-2">
                                <span class="text-xs bg-white border border-violet-200 text-violet-700 font-bold px-2.5 py-1 rounded-lg shadow-sm">Shep Hyken · CX Loyalty</span>
                                <span class="text-xs bg-white border border-violet-200 text-violet-700 font-bold px-2.5 py-1 rounded-lg shadow-sm">Blake Morgan · Digital CX</span>
                                <span class="text-xs bg-white border border-violet-200 text-violet-700 font-bold px-2.5 py-1 rounded-lg shadow-sm">Jeanne Bliss · Chief Customer</span>
                                <span class="text-xs bg-white border border-violet-200 text-violet-700 font-bold px-2.5 py-1 rounded-lg shadow-sm">NPS & CSAT Framework</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
                        <div class="bg-slate-50 px-5 py-3 border-b border-slate-200 flex items-center justify-between">
                            <div class="text-xs text-slate-600 uppercase font-bold tracking-wider">CS AI · Golden Rules Engine</div>
                            <div class="flex items-center gap-1.5 text-xs text-violet-600 font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-violet-500 inline-block shadow-[0_0_5px_rgba(139,92,246,0.8)]"></span>
                                Aktif
                            </div>
                        </div>

                        <div class="grid grid-cols-3 divide-x divide-slate-100 border-b border-slate-100 bg-white">
                            <div class="p-3 text-center">
                                <div class="text-xl font-black text-violet-600">98%</div>
                                <div class="text-[10px] font-bold text-slate-500 mt-0.5 uppercase tracking-wide">FCR Rate</div>
                            </div>
                            <div class="p-3 text-center">
                                <div class="text-xl font-black text-green-500">4.9</div>
                                <div class="text-[10px] font-bold text-slate-500 mt-0.5 uppercase tracking-wide">CSAT Score</div>
                            </div>
                            <div class="p-3 text-center">
                                <div class="text-xl font-black text-cyan-500">&lt;3s</div>
                                <div class="text-[10px] font-bold text-slate-500 mt-0.5 uppercase tracking-wide">Avg. Response</div>
                            </div>
                        </div>

                        <div class="p-4 space-y-3 bg-slate-50/50">
                            <div class="flex justify-start">
                                <div class="bg-white border border-slate-200 text-slate-700 shadow-sm rounded-2xl rounded-tl-sm px-3.5 py-2.5 text-xs max-w-[75%] font-medium">
                                    Pesanan saya sudah 3 hari belum sampai, ini gimana? 😤
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] font-bold text-violet-500 px-1 uppercase tracking-wide">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Golden Rule #1: Empati Pertama
                            </div>
                            <div class="flex justify-end">
                                <div class="bg-violet-600 text-white shadow-sm rounded-2xl rounded-tr-sm px-3.5 py-2.5 text-xs max-w-[80%] font-medium">
                                    Halo Kak! Saya turut memahami rasa khawatir kakak ya 🙏 Izin saya cek langsung status pengirimannya sekarang — boleh share nomor order-nya?
                                </div>
                            </div>
                            <div class="flex justify-start">
                                <div class="bg-white border border-slate-200 text-slate-700 shadow-sm rounded-2xl rounded-tl-sm px-3.5 py-2.5 text-xs max-w-[75%] font-medium">
                                    #ORD-2847, sudah bayar full lho
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] font-bold text-cyan-600 px-1 uppercase tracking-wide">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Golden Rule #2: Selesaikan di Percakapan Ini
                            </div>
                            <div class="flex justify-end">
                                <div class="bg-violet-600 text-white shadow-sm rounded-2xl rounded-tr-sm px-3.5 py-2.5 text-xs max-w-[80%] font-medium">
                                    Sudah ketemu Kak! Paketnya tertahan di hub Cakung sejak kemarin. Saya sudah eskalasi ke kurir prioritas — estimasi tiba besok pagi. Saya kirimkan bukti eskalasi sekarang ya 📦
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 bg-green-50 border-t border-green-100 flex items-center gap-2 text-xs font-bold text-green-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Masalah diselesaikan dalam 1 percakapan · Zero Handoff ✓
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-20">
                <div class="flex items-center gap-4 mb-12">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-100 border border-rose-200 flex items-center justify-center text-base shadow-inner">🚀</div>
                        <div class="text-xs text-rose-600 font-bold uppercase tracking-widest">Mode Marketing · Sales & Growth AI</div>
                    </div>
                    <div class="h-px flex-1 bg-rose-200"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
                        <div class="bg-slate-50 px-5 py-3 border-b border-slate-200 flex items-center justify-between">
                            <div class="text-xs text-slate-600 uppercase font-bold tracking-wider">Marketing Pipeline · Kanban Board</div>
                            <div class="text-[10px] bg-rose-100 border border-rose-200 text-rose-700 px-2 py-1 rounded font-black shadow-sm">🤖 AI Scoring Aktif</div>
                        </div>

                        <div class="p-4 overflow-x-auto bg-slate-50/50">
                            <div class="flex gap-3 min-w-[520px]">
                                <div class="flex-1 min-w-[110px]">
                                    <div class="text-[10px] font-black text-slate-500 uppercase mb-2 flex items-center gap-1 tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 inline-block"></span> New
                                        <span class="ml-auto bg-slate-200 text-slate-600 text-[9px] px-1.5 py-0.5 rounded-full border border-slate-300">3</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="bg-white rounded-lg p-2.5 border border-slate-200 shadow-sm">
                                            <div class="text-[11px] font-bold text-slate-900 mb-1">Andi S.</div>
                                            <div class="text-[9px] font-medium text-slate-500 mb-1.5">Tanya produk via WA</div>
                                            <div class="flex items-center justify-between">
                                                <div class="text-[9px] font-bold text-slate-400 uppercase">Score:</div>
                                                <div class="text-[10px] font-black text-yellow-500">42</div>
                                            </div>
                                            <div class="mt-1 h-1.5 bg-slate-100 rounded-full overflow-hidden"><div class="h-full bg-yellow-400 rounded-full" style="width:42%"></div></div>
                                        </div>
                                        <div class="bg-white rounded-lg p-2.5 border border-slate-200 shadow-sm">
                                            <div class="text-[11px] font-bold text-slate-900 mb-1">Putri L.</div>
                                            <div class="text-[9px] font-medium text-slate-500 mb-1.5">Klik iklan, tanya harga</div>
                                            <div class="flex items-center justify-between">
                                                <div class="text-[9px] font-bold text-slate-400 uppercase">Score:</div>
                                                <div class="text-[10px] font-black text-orange-500">61</div>
                                            </div>
                                            <div class="mt-1 h-1.5 bg-slate-100 rounded-full overflow-hidden"><div class="h-full bg-orange-500 rounded-full" style="width:61%"></div></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-[110px]">
                                    <div class="text-[10px] font-black text-blue-500 uppercase mb-2 flex items-center gap-1 tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span> Qualified
                                        <span class="ml-auto bg-blue-100 text-blue-700 text-[9px] px-1.5 py-0.5 rounded-full border border-blue-200">2</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="bg-white rounded-lg p-2.5 border border-blue-200 shadow-sm ring-1 ring-blue-50">
                                            <div class="text-[11px] font-bold text-slate-900 mb-1">Budi S.</div>
                                            <div class="text-[9px] font-medium text-slate-500 mb-1.5">Minta jadwal demo</div>
                                            <div class="flex items-center justify-between">
                                                <div class="text-[9px] font-bold text-slate-400 uppercase">Score:</div>
                                                <div class="text-[10px] font-black text-orange-600">78</div>
                                            </div>
                                            <div class="mt-1 h-1.5 bg-slate-100 rounded-full overflow-hidden"><div class="h-full bg-orange-500 rounded-full" style="width:78%"></div></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-[110px]">
                                    <div class="text-[10px] font-black text-amber-500 uppercase mb-2 flex items-center gap-1 tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span> Nego
                                        <span class="ml-auto bg-amber-100 text-amber-700 text-[9px] px-1.5 py-0.5 rounded-full border border-amber-200">1</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="bg-white rounded-lg p-2.5 border border-amber-200 shadow-sm ring-1 ring-amber-50">
                                            <div class="text-[11px] font-bold text-slate-900 mb-1">Sari D.</div>
                                            <div class="text-[9px] font-medium text-slate-500 mb-1.5">Minta diskon, siap DP</div>
                                            <div class="flex items-center justify-between">
                                                <div class="text-[9px] font-bold text-slate-400 uppercase">Score:</div>
                                                <div class="text-[10px] font-black text-rose-600">91</div>
                                            </div>
                                            <div class="mt-1 h-1.5 bg-slate-100 rounded-full overflow-hidden"><div class="h-full bg-rose-500 rounded-full" style="width:91%"></div></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-[110px]">
                                    <div class="text-[10px] font-black text-green-600 uppercase mb-2 flex items-center gap-1 tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span> Won
                                        <span class="ml-auto bg-green-100 text-green-700 text-[9px] px-1.5 py-0.5 rounded-full border border-green-200">4</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="bg-white rounded-lg p-2.5 border border-green-200 shadow-sm ring-1 ring-green-50">
                                            <div class="text-[11px] font-bold text-slate-900 mb-1">Reza F.</div>
                                            <div class="text-[9px] font-bold text-green-600 mb-1.5">✅ Deal · 2 unit</div>
                                            <div class="flex items-center justify-between">
                                                <div class="text-[9px] font-bold text-slate-400 uppercase">Score:</div>
                                                <div class="text-[10px] font-black text-green-600">100</div>
                                            </div>
                                            <div class="mt-1 h-1.5 bg-slate-100 rounded-full overflow-hidden"><div class="h-full bg-green-500 rounded-full" style="width:100%"></div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 bg-rose-50 border-t border-rose-100 flex items-start gap-3">
                            <div class="w-6 h-6 rounded-lg bg-rose-200 flex items-center justify-center shrink-0 mt-0.5 border border-rose-300">
                                <svg class="w-3.5 h-3.5 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            </div>
                            <div class="text-xs text-slate-600 font-medium leading-relaxed">
                                <span class="text-rose-700 font-bold">AI Scoring 0–100</span> dihitung otomatis dari: intensitas pertanyaan, budget signal, urgensi waktu, dan kedalaman minat beli — diperbarui setiap kali ada pesan baru.
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="inline-block px-3 py-1 bg-rose-100 border border-rose-200 text-rose-700 text-xs font-black rounded-full mb-4 uppercase tracking-wider shadow-sm">Tools Marketing Lengkap</div>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 mb-4">Pipeline visual + AI Scoring yang memandu tim sales ke deal berikutnya</h3>
                        <p class="text-slate-600 font-medium leading-relaxed mb-6">
                            Tera.AI Mode Marketing bukan sekadar CRM pasif. AI secara aktif mengkualifikasi setiap lead, memindahkan kartu di Kanban, dan memberi skor real-time — sehingga tim sales Anda tahu persis harus fokus ke siapa hari ini.
                        </p>

                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3 text-sm text-slate-700 font-medium">
                                <div class="w-5 h-5 rounded-full bg-rose-100 border border-rose-200 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div><span class="text-slate-900 font-bold">Kanban Board visual</span> — drag & drop pipeline dari New Lead sampai Won, digerakkan otomatis oleh AI</div>
                            </div>
                            <div class="flex items-start gap-3 text-sm text-slate-700 font-medium">
                                <div class="w-5 h-5 rounded-full bg-rose-100 border border-rose-200 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div><span class="text-slate-900 font-bold">AI Lead Scoring 0–100</span> — skor dinamis berdasarkan sinyal minat beli dari setiap percakapan</div>
                            </div>
                            <div class="flex items-start gap-3 text-sm text-slate-700 font-medium">
                                <div class="w-5 h-5 rounded-full bg-rose-100 border border-rose-200 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div><span class="text-slate-900 font-bold">Auto follow-up & nurturing</span> — pesan otomatis dikirim berdasarkan posisi prospek di pipeline</div>
                            </div>
                            <div class="flex items-start gap-3 text-sm text-slate-700 font-medium">
                                <div class="w-5 h-5 rounded-full bg-rose-100 border border-rose-200 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div><span class="text-slate-900 font-bold">Broadcast & campaign tools</span> — kirim pesan ke segmen prospek berdasarkan skor atau status pipeline</div>
                            </div>
                            <div class="flex items-start gap-3 text-sm text-slate-700 font-medium">
                                <div class="w-5 h-5 rounded-full bg-rose-100 border border-rose-200 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div><span class="text-slate-900 font-bold">Sales analytics dashboard</span> — konversi per stage, deal velocity, dan performa tim dalam satu tampilan</div>
                            </div>
                        </div>

                        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex gap-3 shadow-sm">
                            <div class="text-xl shrink-0">🎯</div>
                            <div>
                                <div class="text-sm font-bold text-rose-700 mb-1">Fokus ke prospek skor tertinggi hari ini</div>
                                <div class="text-xs text-slate-600 font-medium leading-relaxed">Setiap pagi, AI merekomendasikan daftar prioritas follow-up berdasarkan skor dan posisi pipeline — tim sales Anda tidak perlu menebak-nebak lagi.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center mb-20">
                <div>
                    <div class="inline-block px-3 py-1 bg-violet-100 border border-violet-200 text-violet-700 text-xs font-black rounded-full mb-4 uppercase tracking-wider shadow-sm">CS Otomatis</div>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 mb-4">AI yang memahami bisnis Anda, bukan sekadar bot kaku</h3>
                    <p class="text-slate-600 font-medium leading-relaxed mb-6">
                        Setting prompt langsung dari dashboard — masukkan SOP, info produk, dan cara bicara yang Anda mau. AI akan menjawab persis seperti tim CS terbaik Anda, 24 jam tanpa henti.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Custom SOP & persona sesuai brand Anda
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Pilih gaya bahasa: formal, santai, atau gaul
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Balas pesan teks dan pesan suara (voice note)
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-violet-100 border border-violet-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Toggle emoji on/off sesuai selera brand
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xl">
                    <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-4">Dashboard Pengaturan AI</div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs text-slate-500 font-bold mb-1.5 block uppercase tracking-wide">Prompt / SOP CS Anda</label>
                            <div class="bg-slate-50 rounded-lg p-3 text-sm text-slate-700 font-medium border border-slate-200 shadow-inner">
                                Anda adalah CS dari Toko Baju Maju. Selalu sapa dengan "Halo Kak!" dan tawarkan promo hari ini...
                                <span class="text-violet-500 font-bold">|</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs text-slate-500 font-bold mb-1.5 block uppercase tracking-wide">Gaya Bahasa</label>
                                <div class="bg-violet-600 rounded-lg px-3 py-2 text-sm text-white font-bold text-center shadow-md">Santai ✓</div>
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 font-bold mb-1.5 block uppercase tracking-wide">Gunakan Emoji</label>
                                <div class="bg-slate-50 rounded-lg px-3 py-2 flex items-center justify-between border border-slate-200 shadow-sm">
                                    <span class="text-sm text-slate-700 font-bold">Aktif</span>
                                    <div class="w-8 h-4 bg-violet-600 rounded-full relative shadow-inner">
                                        <div class="w-3 h-3 bg-white rounded-full absolute right-0.5 top-0.5 shadow-sm"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center mb-20">
                <div class="order-2 lg:order-1 bg-white rounded-2xl border border-slate-200 p-6 shadow-xl">
                    <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-4">CRM Otomatis — Update Real-time</div>
                    <div class="space-y-3">
                        <div class="bg-slate-50 rounded-xl p-4 flex items-center gap-4 border border-slate-200 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-rose-500 flex items-center justify-center text-sm font-black text-white shrink-0 shadow-sm">B</div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900 text-sm">Budi Santoso</div>
                                <div class="text-xs text-slate-500 font-medium truncate">"Kapan bisa survey lokasinya?"</div>
                            </div>
                            <span class="badge-hot text-white text-[11px] font-black px-2 py-1 rounded-full shrink-0 shadow-sm">🔥 Hot</span>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 flex items-center gap-4 border border-slate-200 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-sm font-black text-white shrink-0 shadow-sm">S</div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900 text-sm">Sari Dewi</div>
                                <div class="text-xs text-slate-500 font-medium truncate">"Oke, saya mau ambil 2 unit"</div>
                            </div>
                            <span class="text-white text-[11px] font-black px-2 py-1 rounded-full bg-green-500 shrink-0 shadow-sm">✅ Deal</span>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 flex items-center gap-4 border border-slate-200 shadow-sm">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center text-sm font-black text-white shrink-0 shadow-sm">R</div>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-slate-900 text-sm">Reza Firmansyah</div>
                                <div class="text-xs text-slate-500 font-medium truncate">"Cuma tanya-tanya aja kak"</div>
                            </div>
                            <span class="badge-cold text-white text-[11px] font-black px-2 py-1 rounded-full shrink-0 shadow-sm">❄️ Cold</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100 flex items-center gap-2 text-xs font-bold text-slate-500">
                        <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Diperbarui otomatis berdasarkan analisis percakapan AI
                    </div>
                </div>
                <div class="order-1 lg:order-2">
                    <div class="inline-block px-3 py-1 bg-cyan-100 border border-cyan-200 text-cyan-700 text-xs font-black rounded-full mb-4 uppercase tracking-wider shadow-sm">CRM Cerdas</div>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 mb-4">AI yang tahu siapa yang layak dikejar tim sales Anda</h3>
                    <p class="text-slate-600 font-medium leading-relaxed mb-6">
                        Dari setiap percakapan, Tera.AI otomatis menilai tingkat ketertarikan calon pembeli — dan langsung mengupdate status mereka di CRM. Tim sales Anda tinggal fokus ke yang sudah panas.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-cyan-100 border border-cyan-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Klasifikasi otomatis: 🔥 Hot, ⚡ Warm, ❄️ Cold, ✅ Deal, ❌ Cancel
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-cyan-100 border border-cyan-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Data nama, kontak, & kebutuhan prospek tersimpan otomatis
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-cyan-100 border border-cyan-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Tim sales Anda tahu harus follow-up siapa duluan
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center mb-20">
                <div>
                    <div class="inline-block px-3 py-1 bg-emerald-100 border border-emerald-200 text-emerald-700 text-xs font-black rounded-full mb-4 uppercase tracking-wider shadow-sm">Widget Chat Website</div>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-900 mb-4">Pasang chatbot AI di website mana pun dalam hitungan menit</h3>
                    <p class="text-slate-600 font-medium leading-relaxed mb-6">
                        Salin satu baris kode, tempel ke website Anda — dan widget chat Tera.AI langsung muncul. Tampilannya bisa disesuaikan penuh: warna, posisi ikon, hingga pesan sapaan awal sebelum pengunjung mulai chat.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Custom warna widget sesuai identitas brand
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Posisi ikon bebas: kanan bawah, kiri bawah, atau custom
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Pesan greeting otomatis saat pengunjung membuka widget
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            Kompatibel dengan semua platform: WordPress, Shopify, landing page, dll.
                        </div>
                    </div>
                    <div class="mt-6 bg-slate-800 border border-slate-700 rounded-xl p-4 font-mono text-xs shadow-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-400 shadow-sm"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-400 shadow-sm"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-green-400 shadow-sm"></div>
                            <span class="text-slate-400 ml-2 font-semibold">index.html</span>
                        </div>
                        <div class="text-slate-300">&lt;<span class="text-cyan-300 font-bold">script</span></div>
                        <div class="text-slate-300 pl-4">src=<span class="text-emerald-300">"https://chatbotnew.web.id/tera-widget.js"</span></div>
                        <div class="text-slate-300 pl-4">data-color=<span class="text-emerald-300">"#7C3AED"</span></div>
                        <div class="text-slate-300 pl-4">data-position=<span class="text-emerald-300">"bottom-right"</span></div>
                        <div class="text-slate-300 pl-4">data-greeting=<span class="text-emerald-300">"Halo! Ada yang bisa kami bantu? 👋"</span></div>
                        <div class="text-slate-300">&gt;&lt;/<span class="text-cyan-300 font-bold">script</span>&gt;</div>
                    </div>
                </div>

                <div class="relative bg-white rounded-2xl border border-slate-200 p-6 overflow-hidden min-h-[420px] shadow-xl">
                    <div class="text-xs text-slate-500 uppercase font-bold tracking-wider mb-4">Preview Widget — Live Customizer</div>

                    <div class="space-y-4 mb-6 relative z-10">
                        <div>
                            <label class="text-xs text-slate-500 font-bold uppercase tracking-wide mb-2 block">Warna Widget</label>
                            <div class="flex gap-2">
                                <div class="w-7 h-7 rounded-full bg-violet-600 cursor-pointer swatch-selected border-2 border-white shadow-md"></div>
                                <div class="w-7 h-7 rounded-full bg-emerald-500 cursor-pointer border-2 border-white shadow-sm hover:shadow-md transition"></div>
                                <div class="w-7 h-7 rounded-full bg-rose-500 cursor-pointer border-2 border-white shadow-sm hover:shadow-md transition"></div>
                                <div class="w-7 h-7 rounded-full bg-cyan-500 cursor-pointer border-2 border-white shadow-sm hover:shadow-md transition"></div>
                                <div class="w-7 h-7 rounded-full bg-orange-500 cursor-pointer border-2 border-white shadow-sm hover:shadow-md transition"></div>
                                <div class="w-7 h-7 rounded-full bg-slate-100 cursor-pointer border-2 border-dashed border-slate-300 flex items-center justify-center hover:border-slate-400 transition">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 font-bold uppercase tracking-wide mb-2 block">Posisi Ikon</label>
                            <div class="flex gap-2">
                                <div class="px-3 py-1.5 bg-violet-600 text-white text-xs rounded-lg font-bold shadow-md">Kanan Bawah ✓</div>
                                <div class="px-3 py-1.5 bg-slate-50 text-slate-600 text-xs rounded-lg font-bold border border-slate-200 shadow-sm hover:bg-slate-100 cursor-pointer transition">Kiri Bawah</div>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 font-bold uppercase tracking-wide mb-1.5 block">Pesan Greeting</label>
                            <div class="bg-slate-50 rounded-lg px-3 py-2 text-xs text-slate-700 font-medium border border-slate-200 shadow-inner">
                                Halo! Ada yang bisa kami bantu? 👋<span class="text-violet-500 font-bold">|</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative h-20 bg-slate-50/50 rounded-xl border border-