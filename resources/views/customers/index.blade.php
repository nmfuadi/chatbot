<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Database Pelanggan') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data interaksi dan kontrol asisten AI untuk setiap pelanggan Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('success'))
                <div class="bg-green-50/80 backdrop-blur-sm border border-green-200 text-green-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm ring-1 ring-green-900/5">
                    <div class="p-2 bg-green-100 rounded-xl">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm ring-1 ring-gray-900/5 sm:rounded-3xl">
                <div class="overflow-x-auto">
                    
                    <table class="min-w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-sm">
                                <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider w-16 text-center">No</th>
                                <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider">Nama Customer</th>
                                <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider">Nomor WA</th>
                                <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider">Status AI</th>
                                <th class="px-6 py-4 font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($customers as $index => $customer)
                                <tr class="hover:bg-blue-50/30 transition-colors duration-200 group">
                                    
                                    <td class="px-6 py-4 text-center text-sm font-semibold text-gray-400">
                                        {{ method_exists($customers, 'firstItem') ? $customers->firstItem() + $index : $loop->iteration }}
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-extrabold text-sm shadow-inner">
                                                {{ strtoupper(substr($customer->customer_name ?? 'C', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $customer->customer_name ?? 'Customer Baru' }}</p>
                                                <p class="text-xs text-gray-400 font-medium mt-0.5">Disimpan secara otomatis</p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-600 rounded-lg text-sm font-mono font-semibold border border-gray-100">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $customer->customer_phone }}
                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        @if($customer->is_ai_active)
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 text-xs rounded-full font-bold uppercase tracking-wide border border-green-100">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                                </span>
                                                Bot Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-700 text-xs rounded-full font-bold uppercase tracking-wide border border-red-100">
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                Bot Berhenti
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 flex items-center justify-end gap-3">
                                        <a href="{{ route('customers.history', $customer->customer_phone) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white ring-1 ring-gray-200 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-50 hover:text-blue-600 hover:ring-blue-200 transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Histori
                                        </a>

                                        <form action="{{ route('customers.toggle', $customer->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" 
                                                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl transition-all shadow-sm
                                                {{ $customer->is_ai_active ? 'bg-white text-red-600 ring-1 ring-red-200 hover:bg-red-50' : 'bg-gray-900 text-white hover:bg-gray-800' }}">
                                                
                                                @if($customer->is_ai_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Matikan AI
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                    Aktifkan AI
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            </div>
                                            <p class="font-bold text-gray-500 text-base">Belum Ada Pelanggan</p>
                                            <p class="text-sm text-gray-400 mt-1 max-w-sm">Data interaksi dan pelanggan akan muncul secara otomatis saat ada seseorang yang mengirim pesan ke nomor WhatsApp Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
                
                @if(method_exists($customers, 'links') && $customers->hasPages())
                    <div class="bg-white px-6 py-4 border-t border-gray-100">
                        {{ $customers->links() }}
                    </div>
                @endif

            </div>
            
        </div>
    </div>
</x-app-layout>