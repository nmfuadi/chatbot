
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Riwayat Tagihan Anda') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-sm border border-slate-200 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-white">
                    <h3 class="text-lg font-bold text-slate-900">Daftar Tagihan</h3>
                    <p class="text-sm text-slate-500">Pantau semua histori transaksi dan status langganan Anda.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-[11px] font-bold uppercase tracking-widest border-b border-slate-200">
                                <th class="py-4 px-6">Nomor Invoice</th>
                                <th class="py-4 px-6">Paket Layanan</th>
                                <th class="py-4 px-6">Tanggal</th>
                                <th class="py-4 px-6">Total</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($invoices as $invoice)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-4 px-6 font-mono text-sm font-bold text-slate-700">
                                        #{{ $invoice->invoice_number }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="text-sm font-bold text-slate-900">{{ $invoice->subscription->plan->name }}</div>
                                        <div class="text-xs text-slate-500">Langganan 30 Hari</div>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-slate-600">
                                        {{ $invoice->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-black text-slate-900">
                                        Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($invoice->status == 'unpaid')
                                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200 uppercase">
                                                Unpaid
                                            </span>
                                        @else
                                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black bg-green-100 text-green-700 border border-green-200 uppercase">
                                                Paid
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('user.invoice.show', $invoice->id) }}" class="inline-flex items-center text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">
                                            Lihat Detail
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <p class="text-slate-500 font-medium">Belum ada riwayat tagihan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs text-blue-700 leading-relaxed">
                    <strong>Catatan:</strong> Jika Anda baru saja melakukan pembayaran namun status belum berubah, silakan tunggu beberapa saat atau hubungi tim dukungan kami melalui WhatsApp.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>