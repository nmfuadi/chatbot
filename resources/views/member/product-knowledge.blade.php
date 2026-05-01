<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Brain & Knowledge Base') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Latih AI Anda dengan informasi produk dan prosedur operasional bisnis Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                
                <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight">Input Pengetahuan AI</h3>
                        </div>

                        @if(session('success'))
                            <div class="mb-6 bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-2xl flex items-center gap-3 animate-pulse">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="text-sm font-bold">{{ session('success') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('member.pk.save') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Dokumentasi Bisnis / SOP</label>
                                <textarea 
                                    name="content" 
                                    rows="15" 
                                    class="w-full border-gray-100 bg-gray-50 rounded-[1.5rem] shadow-inner focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-gray-700 leading-relaxed placeholder-gray-400 p-6 transition-all" 
                                    placeholder="Contoh: 
- Jam buka: 08:00 - 20:00
- Lokasi: Jl. Merdeka No. 123
- Cara pesan: Kirim format Nama#Alamat
- Harga ongkir: Flat Rp 10.000"
                                >{{ old('content', $pk->content ?? '') }}</textarea>
                                <p class="mt-3 text-[10px] text-gray-400 italic">** Semakin detail informasi yang Anda berikan, semakin cerdas AI dalam menjawab.</p>
                            </div>

                            <button type="submit" class="w-full flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-6 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Pengetahuan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-[2.5rem] shadow-2xl shadow-gray-400/20 overflow-hidden sticky top-10 border border-gray-800">
                    <div class="p-6 bg-gray-800/50 border-b border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-black text-gray-300 uppercase tracking-widest">AI Simulator</span>
                        </div>
                        <span class="text-[10px] text-gray-500 font-bold px-2 py-1 bg-gray-900 rounded-md">GROQ CLOUD</span>
                    </div>

                    <div class="h-[450px] overflow-y-auto p-6 space-y-4 flex flex-col bg-slate-900/50" id="chat-box" style="scrollbar-width: thin; scrollbar-color: #334155 #0f172a;">
                        <div class="self-start max-w-[85%]">
                            <div class="bg-gray-800 text-gray-200 p-4 rounded-2xl rounded-tl-none text-sm shadow-sm leading-relaxed border border-gray-700">
                                Halo! Saya adalah asisten AI bisnis Anda. Coba tanyakan sesuatu setelah Anda menyimpan data pengetahuan di samping.
                            </div>
                            <span class="text-[10px] text-gray-600 mt-1 block">Bot • Baru saja</span>
                        </div>
                    </div>
                    
                    <div class="p-6 bg-gray-800/30 border-t border-gray-700">
                        <div class="relative flex items-center">
                            <input type="text" id="chat-input" 
                                class="w-full bg-gray-900 border-gray-700 rounded-2xl text-sm text-gray-200 placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500 py-4 pl-4 pr-16 shadow-inner" 
                                placeholder="Coba tes pengetahuan AI...">
                            <button type="button" 
                                onclick="alert('Fitur Simulasi Groq akan segera aktif!')" 
                                class="absolute right-2 p-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-500 transition-all shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>