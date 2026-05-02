<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Riwayat Tagihan Anda') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm font-bold text-rose-700">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-white">
                    <h3 class="text-lg font-bold text-slate-900">Daftar Tagihan</h3>
                    <p class="text-sm text-slate-500">Pantau semua histori transaksi dan status perpanjangan layanan Anda.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-[11px] font-bold uppercase tracking-widest border-b border-slate-200">
                                <th class="py-4 px-6">Nomor Invoice</th>
                                <th class="py-4 px-6">Paket Layanan</th>
                                <th class="py-4 px-6">Tanggal Terbit</th>
                                <th class="py-4 px-6">Total Tagihan</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($invoices as $invoice)
                                <tr class="hover:bg-slate-50 transition-colors {{ $invoice->status == 'expired' ? 'opacity-50 grayscale' : '' }}">
                                    
                                    <td class="py-4 px-6 font-mono text-sm font-bold text-slate-700">
                                        #{{ $invoice->invoice_number }}
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <div class="text-sm font-bold text-slate-900">{{ $invoice->subscription->plan->name ?? 'Paket Premium' }}</div>
                                        <div class="text-[10px] uppercase font-bold tracking-wider text-slate-400 mt-0.5">Langganan 30 Hari</div>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-sm text-slate-600 font-medium">
                                        {{ $invoice->created_at->format('d M Y') }}
                                    </td>
                                    
                                    <td class="py-4 px-6 text-sm font-black text-slate-900">
                                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center">
                                        @if($invoice->status == 'unpaid' || $invoice->status == 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-600 ring-1 ring-amber-200 uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                                Belum Dibayar
                                            </span>
                                        @elseif($invoice->status == 'expired')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-500 ring-1 ring-slate-200 uppercase tracking-wider">
                                                Dibatalkan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-green-50 text-green-600 ring-1 ring-green-200 uppercase tracking-wider">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                Lunas
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('user.invoice.show', $invoice->id) }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                                                Detail
                                            </a>
                                            
                                            @if($invoice->status == 'unpaid' || $invoice->status == 'pending')
                                                <a href="{{ route('user.invoice.show', $invoice->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-lg hover:bg-black transition-all shadow-sm">
                                                    Bayar 
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="text-slate-500 font-bold">Belum ada riwayat tagihan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 p-5 bg-blue-50/50 ring-1 ring-blue-100 rounded-2xl flex items-start gap-4">
                <div class="p-2 bg-white rounded-xl shadow-sm shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-blue-900 mb-1">Informasi Tagihan</h4>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        Jika Anda melakukan pembayaran untuk tagihan terbaru, maka tagihan <strong>"Belum Dibayar"</strong> lainnya (jika ada) akan otomatis dibatalkan oleh sistem untuk mencegah pembayaran ganda. Jika status belum berubah setelah Anda membayar, mohon tunggu 1-2 menit.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>