<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Database Katalog (Google Sheets)') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Sinkronisasi data produk, harga, dan stok langsung dari Google Sheets Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-2xl flex items-center gap-3 animate-pulse">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-gray-900/5 overflow-hidden p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-green-50 text-green-600 rounded-xl">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight">Pengaturan Google Sheets</h3>
                            <p class="text-xs text-gray-500">Hubungkan spreadsheet untuk menyuplai data pengetahuan produk ke AI secara otomatis.</p>
                        </div>
                    </div>
                    
                    @if(!empty($pk->google_sheet_url))
                        <span class="self-start sm:self-auto px-3 py-1 bg-green-100 text-green-700 text-xs font-black rounded-full border border-green-200 flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
                            Terkoneksi ke Mesin Auto-Sync
                        </span>
                    @else
                        <span class="self-start sm:self-auto px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-black rounded-full border border-yellow-200">
                            ⚠️ Belum Terhubung
                        </span>
                    @endif
                </div>

                <form action="{{ route('member.sync_sheet') }}" method="POST" id="syncForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">URL Google Sheet Public</label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="url" name="google_sheet_url" 
                                value="{{ old('google_sheet_url', $pk->google_sheet_url ?? '') }}" 
                                class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-inner focus:ring-2 focus:ring-indigo-500 text-sm p-3.5" 
                                placeholder="Contoh: https://docs.google.com/spreadsheets/d/1xxxxxxxxxxxxxxx/edit?usp=sharing" required>
                            
                            <button type="submit" id="btnSubmitSync" class="flex-shrink-0 flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-6 rounded-xl shadow-md transition-all active:scale-[0.98]">
                                <svg class="