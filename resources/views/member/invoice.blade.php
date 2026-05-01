<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen font-sans">
        
        <div class="max-w-5xl mx-auto px-4 sm:px-8 lg:px-12">
            
            <div class="bg-white shadow-xl overflow-hidden rounded-lg">
                
                <div class="bg-indigo-500 px-8 py-8 sm:px-16 flex justify-between items-center">
                    <div class="text-white font-bold text-2xl flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white text-indigo-500 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"></path></svg>
                        </div>
                        <span>Terabot.AI</span>
                    </div>
                    <div class="text-white text-2xl sm:text-4xl font-light tracking-widest uppercase">
                        INVOICE
                    </div>
                </div>

                <div class="w-full flex justify-center mt-[-1px]">
                    <div class="w-0 h-0 border-l-[16px] border-l-transparent border-r-[16px] border-r-transparent border-t-[16px] border-t-indigo-500"></div>
                </div>

                <div class="px-8 py-12 sm:px-16">
                    
                    <div class="flex flex-col md:flex-row justify-between gap-10 mb-16">
                        
                        <div>
                            <h3 class="text-gray-900 font-bold text-lg mb-4">Invoice to: <span class="text-gray-700 ml-1">{{ $invoice->user->name }}</span></h3>
                            <div class="text-gray-500 text-sm leading-relaxed ml-0 md:ml-24">
                                <p>{{ $invoice->user->business_name }}</p>
                                <p>WhatsApp: {{ $invoice->user->whatsapp_number }}</p>
                            </div>
                        </div>

                        <div class="text-left md:text-right text-sm">
                            <div class="mb-2">
                                <span class="text-gray-900 font-bold mr-4">Invoice #:</span> 
                                <span class="text-gray-500">{{ $invoice->invoice_number }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-gray-900 font-bold mr-4">Date:</span> 
                                <span class="text-gray-500">{{ $invoice->created_at->format('d / m / Y') }}</span>
                            </div>
                            <div class="mt-4">
                                @if($invoice->status == 'unpaid')
                                    <span class="text-red-500 font-bold uppercase tracking-wider text-xs border border-red-200 bg-red-50 px-3 py-1 rounded">Menunggu Pembayaran</span>
                                @else
                                    <span class="text-green-500 font-bold uppercase tracking-wider text-xs border border-green-200 bg-green-50 px-3 py-1 rounded">Lunas</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-16 overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-100 text-gray-800 text-xs font-bold tracking-widest uppercase">
                                    <th class="py-4 px-6 w-16 text-center">SL.</th>
                                    <th class="py-4 px-6">ITEM DESCRIPTION</th>
                                    <th class="py-4 px-6 text-right">PRICE</th>
                                    <th class="py-4 px-6 text-center">DURASI / QTY</th>
                                    <th class="py-4 px-6 text-right">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-200">
                                    <td class="py-6 px-6 text-center text-gray-800 font-medium">1</td>
                                    <td class="py-6 px-6">
                                        <p class="font-bold text-gray-800">{{ $invoice->subscription->plan->name }}</p>
                                        <p class="text-sm text-gray-500 mt-1">Limit: {{ $invoice->subscription->plan->is_unlimited_messages ? 'Unlimited' : number_format($invoice->subscription->plan->max_messages, 0, ',', '.') . ' Pesan' }}</p>
                                    </td>
                                    <td class="py-6 px-6 text-gray-600 font-medium text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                    <td class="py-6 px-6 text-gray-600 font-medium text-center">30 Hari</td>
                                    <td class="py-6 px-6 text-gray-800 font-bold text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="bg-gray-200/50">
                                    <td colspan="5" class="py-4"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between gap-10 border-b border-gray-200 pb-12 mb-12">
                        
                        <div class="md:w-1/2">
                            <h3 class="text-lg font-bold text-gray-900 mb-6">Thank you for your business</h3>
                            <div class="text-sm text-gray-600 leading-relaxed">
                                <p class="text-blue-500 font-semibold mb-2">Payment Info:</p>
                                <p>Status: Pembayaran Terintegrasi (Midtrans)</p>
                                <p>Note: Layanan AI Anda akan aktif secara instan sesaat setelah pembayaran berhasil.</p>
                            </div>
                        </div>
                        
                        <div class="w-full md:w-1/3 text-sm">
                            <div class="flex justify-between py-2 text-gray-800">
                                <span>Sub total:</span>
                                <span class="font-bold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-2 text-gray-800">
                                <span>Tax / Admin:</span>
                                <span class="font-bold text-gray-500">Rp 0</span>
                            </div>
                            <div class="flex justify-between py-4 mt-2 text-indigo-500 border-t border-gray-200 font-bold text-base">
                                <span>Total:</span>
                                <span>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center pb-6">
                        <a href="{{ route('payment.index') }}" class="inline-flex items-center justify-center px-12 py-4 bg-indigo-500 hover:bg-indigo-600 text-white text-lg font-bold rounded-md shadow transition-transform hover:-translate-y-0.5 uppercase tracking-wide">
                            Lanjutkan Pembayaran
                        </a>
                        <p class="mt-4 text-xs text-gray-400 font-semibold">Authorised System Generated Invoice</p>
                    </div>

                </div>
                
                <div class="bg-indigo-500 h-10 w-full relative">
                    <div class="absolute top-[-15px] left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-[16px] border-l-transparent border-r-[16px] border-r-transparent border-b-[16px] border-b-indigo-500"></div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>