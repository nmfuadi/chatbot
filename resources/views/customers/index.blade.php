<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Data Customer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                <div class="p-6 text-slate-900 overflow-x-auto">
                    
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="p-4 font-bold text-slate-600 rounded-tl-xl">Nama Customer</th>
                                <th class="p-4 font-bold text-slate-600">Nomor WA</th>
                                <th class="p-4 font-bold text-slate-600">Status AI</th>
                                <th class="p-4 font-bold text-slate-600 rounded-tr-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($customers as $customer)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4 font-medium text-slate-800">
                                        {{ $customer->customer_name ?? 'Customer Baru' }}
                                    </td>
                                    <td class="p-4 text-slate-600">
                                        {{ $customer->customer_phone }}
                                    </td>
                                    <td class="p-4">
                                        @if($customer->is_ai_active)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-bold uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-100 text-red-700 text-xs rounded-full font-bold uppercase tracking-wide">
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                Dihentikan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 flex items-center gap-3">
                                        <a href="{{ route('customers.history', $customer->customer_phone) }}" 
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 text-sm font-bold rounded-lg hover:bg-indigo-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            History Chat
                                        </a>

                                        <form action="{{ route('customers.toggle', $customer->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" 
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-lg transition
                                                {{ $customer->is_ai_active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                                
                                                @if($customer->is_ai_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Matikan AI
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Aktifkan AI
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="font-medium text-slate-500">Belum ada data interaksi customer.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>