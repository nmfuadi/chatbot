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
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-gray-900/5 overflow-hidden p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2.5 bg-green-50 text-green-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight">Koneksi Google Sheets</h3>
                        <p class="text-xs text-gray-500">Pastikan akses link Google Sheet Anda diatur ke <b>"Anyone with the link / Siapa saja yang memiliki link"</b>.</p>
                    </div>
                </div>

                <form action="{{ route('member.sync_sheet') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">URL Google Sheet Public</label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="url" name="google_sheet_url" 
                                value="{{ old('google_sheet_url', $pk->google_sheet_url ?? '') }}" 
                                class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-inner focus:ring-2 focus:ring-indigo-500 text-sm p-3.5" 
                                placeholder="Contoh: https://docs.google.com/spreadsheets/d/1xxxxxxxxxxxxxxx/edit?usp=sharing" required>
                            
                            <button type="submit" onclick="this.innerHTML='Menarik Data... ⏳'; this.classList.add('opacity-75');" class="flex-shrink-0 flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-6 rounded-xl shadow-md transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Sync Data Sekarang
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
                <div class="p-8 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-900 tracking-tight">Data Katalog Saat Ini</h3>
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full border border-gray-200">
                        Total: {{ $catalogs->count() }} Baris
                    </span>
                </div>

                <div class="overflow-x-auto">
                    @if($catalogs->isEmpty())
                        <div class="p-10 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <h4 class="text-gray-500 font-bold mb-1">Katalog Masih Kosong</h4>
                            <p class="text-sm text-gray-400">Silakan masukkan URL Google Sheet di atas lalu klik Sync Data.</p>
                        </div>
                    @else
                        @php
                            // Mengambil kunci array (Header Kolom) dari baris pertama data
                            $headers = array_keys($catalogs->first()->raw_data ?? []);
                        @endphp

                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="py-4 px-6 bg-gray-50 text-xs font-black text-gray-500 uppercase tracking-wider border-b border-gray-200">No</th>
                                    @foreach($headers as $header)
                                        <th class="py-4 px-6 bg-gray-50 text-xs font-black text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            {{ $header }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($catalogs as $index => $item)
                                    <tr class="hover:bg-indigo-50/30 transition-colors">
                                        <td class="py-4 px-6 text-sm text-gray-500 font-medium">
                                            {{ $index + 1 }}
                                        </td>
                                        @foreach($headers as $header)
                                            <td class="py-4 px-6 text-sm text-gray-700">
                                                {{-- Batasi karakter agar tidak kepanjangan jika ada kolom deskripsi --}}
                                                {{ Str::limit($item->raw_data[$header] ?? '-', 50) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>