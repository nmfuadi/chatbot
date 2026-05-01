<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-2xl rounded-xl overflow-hidden relative">
                
                <div class="bg-blue-500 px-8 py-8 sm:px-12 flex justify-between items-center">
                    <div class="text-white font-bold text-xl sm:text-2xl flex items-center gap-2">
                        <svg class="w-8 h-8 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="tracking-wide">Terabot.AI</span>
                    </div>
                    <div class="text-white text-2xl sm:text-4xl font-black tracking-widest uppercase">
                        INVOICE
                    </div>
                </div>

                <div class="px-8 py-10 sm:px-12">
                    <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                        
                        <div>
                            <h3 class="text-gray-400 font-bold text-xs uppercase tracking-wider mb-2">Ditagihkan Kepada:</h3>
                            <h2 class="text-xl font-bold text-gray-900">{{ $invoice->user->name }}</h2>
                            <p class="text-gray-600 mt-1 font-medium">{{ $invoice->user->business_name }}</p>
                            <p class="text-gray-500 mt-1 text-sm flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ $invoice->user->whatsapp_number }}
                            </p>
                        </div>

                        <div class="md:text-right w-full md:w-auto">
                            <div class="grid grid-cols-2 md:flex md:flex-col gap-x-4 gap-y-2 text-sm">
                                <div class="text-gray-500 font-semibold md:hidden">Nomor Invoice:</div>
                                <div class="text-gray-900 font-mono font-bold md:mb-1"><span class="hidden md:inline text-gray-500 font-semibold font-sans mr-2">Invoice #:</span> {{ $invoice->invoice_number }}</div>

                                <div class="text-gray-500 font-semibold md:hidden">Tanggal:</div>
                                <div class="text-gray-900 font-medium md:mb-3"><span class="hidden md:inline text-gray-500 font-semibold font-sans mr-2">Tanggal:</span> {{ $invoice->created_at->format('d / m / Y') }}</div>

                                <div class="text-gray-500 font-semibold md:hidden">Status:</div>
                                <div>
                                    @if($invoice->status == 'unpaid')
                                        <span class="inline-block px-4 py-1.5 bg-amber-100 text-amber-700 rounded-md text-xs font-bold uppercase tracking-wider border border-amber-200">
                                            Menunggu Pembayaran
                                        </span>
                                    @else
                                        <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 rounded-md text-xs font-bold uppercase tracking-wider border border-green-200">
                                            Lunas
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-100 text-gray-500 text-xs font-bold tracking-wider uppercase">
                                    <th class="py-4 px-6 rounded-l-lg">Deskripsi Layanan</th>
                                    <th class="py-4 px-6">Harga</th>
                                    <th class="py-4 px-6 text-center">Durasi</th>
                                    <th class="py-4 px-6 text-right rounded-r-lg">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-100">
                                    <td class="py-6 px-6">
                                        <p class="font-bold text-gray-900 text-base">{{ $invoice->subscription->plan->name }}</p>
                                        <p class="text-sm text-gray-500 mt-1">Limit: {{ $invoice->subscription->plan->is_unlimited_messages ? 'Unlimited' : number_format($invoice->subscription->plan->max_messages, 0, ',', '.') . ' Pesan' }}</p>
                                    </td>
                                    <td class="py-6 px-6 text-gray-700 font-medium">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                    <td class="py-6 px-6 text-gray-700 font-medium text-center">30 Hari</td>
                                    <td class="py-6 px-6 text-gray-900 font-bold text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <div class="w-full sm:w-1/2 lg:w-1/3">
                            <div class="flex justify-between py-2 text-gray-600 text-sm font-medium">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-2 text-gray-600 text-sm font-medium">
                                <span>PPN / Biaya Admin</span>
                                <span class="text-green-600 font-bold">Gratis</span>
                            </div>
                            <div class="flex justify-between py-4 mt-3 border-t-2 border-gray-100 items-center">
                                <span class="text-lg font-bold text-gray-900">Total Tagihan</span>
                                <span class="text-2xl font-black text-blue-600">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-8 py-8 sm:px-12 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-sm text-gray-500 text-center md:text-left">
                        <p class="font-semibold text-gray-800 mb-1">Terima kasih atas kepercayaan Anda.</p>
                        <p>Sistem akan otomatis mengaktifkan layanan setelah pembayaran berhasil.</p>
                    </div>
                    <div class="w-full md:w-auto shrink-0">
                        <a href="{{ route('payment.index') }}" class="block w-full md:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow hover:shadow-lg transition-all duration-200 text-center uppercase tracking-wide text-sm">
                            Bayar Sekarang
                        </a>
                    </div>
                </div>

                <div class="h-3 bg-blue-500 w-full"></div>
                
            </div>
        </div>
    </div>
</x-app-layout>