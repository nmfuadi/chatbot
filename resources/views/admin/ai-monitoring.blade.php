<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('🧠 Pemantauan Otak AI (Groq LLM)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-black text-white mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Koneksi API Groq</h3>
                        <p class="text-sm text-gray-500">Mendeteksi respons langsung dari server utama Groq.</p>
                    </div>
                </div>
                
                <div class="text-right">
                    @if($apiStatus === 'online')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800 border border-green-200">
                            <span class="w-2.5 h-2.5 mr-2 bg-green-500 rounded-full animate-pulse"></span> ONLINE (Terhubung)
                        </span>
                        <p class="text-xs text-gray-500 mt-1">{{ $models }} Model Tersedia</p>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800 border border-red-200">
                            <span class="w-2.5 h-2.5 mr-2 bg-red-500 rounded-full"></span> OFFLINE / API KEY ERROR
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">Total Token Hari Ini</p>
                        <h4 class="text-4xl font-extrabold text-indigo-600 mb-4">{{ number_format($totalTokensToday) }}</h4>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Prompt (Pertanyaan User)</span>
                                <span class="font-bold">{{ number_format($promptTokensToday) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Completion (Jawaban AI)</span>
                                <span class="font-bold">{{ number_format($completionTokensToday) }}</span>
                            </div>
                        </div>
                    </div>
                    <svg class="absolute right-0 bottom-0 opacity-5 w-48 h-48 transform translate-x-10 translate-y-10 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>

                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-sm border border-gray-700 p-6 text-white flex flex-col justify-center">
                    <p class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-1">Total Token Bulan Ini</p>
                    <h4 class="text-4xl font-extrabold text-white mb-2">{{ number_format($totalTokensMonth) }} <span class="text-lg font-normal text-gray-400">Tokens</span></h4>
                    <p class="text-sm text-gray-400 mt-2">Pantau angka ini agar Anda tidak melebihi batas (Rate Limit) gratis harian/bulanan dari Groq.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>