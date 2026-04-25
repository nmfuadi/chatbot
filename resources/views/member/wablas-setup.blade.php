<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Setup & Status Perangkat Wablas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex justify-center">
            
            @if(!$token)
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded shadow-sm w-full max-w-md text-center">
                    API Key Wablas Anda belum diatur oleh Admin. Silakan tunggu atau hubungi Admin.
                </div>
            @elseif($deviceInfo)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 w-full max-w-sm" x-data="{ showQr: false }">
                    
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-800 leading-tight">{{ $deviceInfo['name'] ?? 'Unknown' }}</h3>
                                <p class="text-sm text-blue-500 font-medium">#{{ $deviceInfo['serial'] ?? '----' }}</p>
                            </div>
                        </div>
                        
                        @if($deviceInfo['status'] === 'connected')
                            <span class="px-3 py-1 bg-green-50 text-green-500 text-xs font-semibold rounded-full border border-green-100">
                                Connected
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-50 text-red-500 text-xs font-semibold rounded-full border border-red-100">
                                Disconnected
                            </span>
                        @endif
                    </div>

                    <div class="border border-green-200 rounded-lg p-2.5 flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                        <span class="text-green-500 font-medium tracking-wide">{{ $deviceInfo['sender'] ?? 'Belum ada nomor' }}</span>
                    </div>

                   
                        <hr class="border-slate-200">
                        <div class="flex items-center gap-3 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-teal-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            <span class="text-blue-500">{{ $deviceInfo['quota'] === -1 ? 'Unlimited' : $deviceInfo['quota'] }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            <span class="text-green-500 font-medium">{{ \Carbon\Carbon::parse($deviceInfo['expired_date'])->format('d M Y') }}</span>
                        </div>
                    </div>

                    @if($deviceInfo['status'] === 'disconnected')
                        <button @click="showQr = !showQr" class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg shadow-sm hover:bg-blue-600 transition mb-4">
                            Munculkan QR Code
                        </button>
                        
                        <div x-show="showQr" x-transition class="flex flex-col items-center border border-dashed border-gray-300 p-4 rounded-lg mb-4">
                            @if($qrUrl)
                                <img src="{{ $qrUrl }}" alt="QR Code Wablas" class="w-48 h-48 mb-2">
                                <p class="text-xs text-gray-500 text-center">Scan menggunakan fitur Linked Devices di WhatsApp Anda.</p>
                            @else
                                <p class="text-sm text-red-500">Gagal memuat QR. Coba refresh halaman.</p>
                            @endif
                        </div>
                    @endif

                </div>
            @else
                <div class="bg-red-100 text-red-800 p-4 rounded shadow-sm w-full max-w-md text-center">
                    Gagal menghubungi server Wablas. Pastikan API Key valid.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>