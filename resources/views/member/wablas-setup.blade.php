<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Koneksi Perangkat') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Pantau status koneksi dan kelola sinkronisasi WhatsApp Anda dengan Wablas.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center">
            
            @if(!$token)
                <div class="w-full max-w-lg bg-white rounded-3xl shadow-sm ring-1 ring-gray-900/5 p-8 text-center">
                    <div class="w-20 h-20 bg-yellow-50 text-yellow-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">API Key Belum Siap</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Wablas API Key Anda belum diatur oleh Admin. Silakan hubungi tim dukungan kami untuk melakukan aktivasi perangkat.</p>
                </div>

            @elseif($deviceInfo)
                <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 ring-1 ring-gray-900/5 overflow-hidden" x-data="{ showQr: false }">
                    
                    <div class="p-8 pb-6 border-b border-gray-50">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-gray-900 tracking-tight">{{ $deviceInfo['name'] ?? 'Device' }}</h3>
                                    <p class="text-xs font-mono font-bold text-blue-600 tracking-widest uppercase">ID: {{ $deviceInfo['serial'] ?? '----' }}</p>
                                </div>
                            </div>

                            @if($deviceInfo['status'] === 'connected')
                                <div class="flex flex-col items-end">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest rounded-full ring-1 ring-green-100">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                        </span>
                                        Connected
                                    </span>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-widest rounded-full ring-1 ring-rose-100">
                                    <span class="w-2 h-2 bg-rose-500 rounded-full"></span>
                                    Offline
                                </span>
                            @endif
                        </div>

                        <div class="bg-gray-50/80 ring-1 ring-gray-100 rounded-2xl p-4 flex items-center justify-center gap-3">
                            <div class="bg-white p-2 rounded-lg shadow-sm">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <span class="text-xl font-black text-gray-900 tracking-tighter">{{ $deviceInfo['sender'] ?? '---' }}</span>
                        </div>
                    </div>

                    <div class="p-8 pt-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-gray-50/50 ring-1 ring-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Kuota Pesan</p>
                                <p class="text-lg font-extrabold text-gray-900">{{ $deviceInfo['quota'] == -1 ? 'Unlimited' : $deviceInfo['quota'] }}</p>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50/50 ring-1 ring-gray-100">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Masa Aktif</p>
                                <p class="text-sm font-extrabold text-gray-900">{{ \Carbon\Carbon::parse($deviceInfo['expired_date'])->format('d M Y') }}</p>
                            </div>
                        </div>

                        @if($deviceInfo['status'] === 'disconnected')
                            <div class="mt-6 pt-2">
                                <button @click="showQr = !showQr" 
                                    class="w-full group flex items-center justify-center gap-3 bg-gray-900 hover:bg-black text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 shadow-lg shadow-gray-900/20">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    <span x-text="showQr ? 'Sembunyikan QR' : 'Hubungkan Perangkat'"></span>
                                </button>
                                
                                <div x-show="showQr" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     class="mt-6 p-6 rounded-3xl border-2 border-dashed border-gray-200 bg-gray-50/30 flex flex-col items-center">
                                    
                                    @if($qrUrl)
                                        <div class="p-3 bg-white rounded-2xl shadow-sm mb-4">
                                            <img src="{{ $qrUrl }}" alt="QR Code Wablas" class="w-48 h-48">
                                        </div>
                                        <div class="text-center">
                                            <p class="text-sm font-bold text-gray-800 mb-1">Scan QR Code</p>
                                            <p class="text-[10px] text-gray-500 leading-relaxed uppercase tracking-tight">Buka WhatsApp > Perangkat Tertaut > Tautkan Perangkat</p>
                                        </div>
                                    @else
                                        <p class="text-sm text-rose-500 font-bold py-10 text-center uppercase tracking-wider">Gagal memuat QR Code.<br>Harap refresh halaman ini.</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100 flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-xs text-blue-700 leading-relaxed font-medium">Perangkat Anda sudah terhubung secara optimal. Bot AI siap merespon pesan pelanggan Anda secara otomatis.</p>
                            </div>
                        @endif
                    </div>
                </div>

            @else
                <div class="w-full max-w-lg bg-white rounded-3xl shadow-sm ring-1 ring-gray-900/5 p-8 text-center">
                    <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Masalah Koneksi Server</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-6">Gagal menghubungi server Wablas. Hal ini biasanya terjadi jika API Key tidak valid atau server mitra sedang dalam pemeliharaan.</p>
                    <button onclick="window.location.reload()" class="bg-gray-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-black transition-colors">Coba Segarkan Halaman</button>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>