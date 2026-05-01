<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
                
                <div class="bg-[#4e73df] p-8 flex justify-between items-center">
                    <div class="text-white">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#4e73df]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"></path></svg>
                            </div>
                            <span class="text-xl font-bold uppercase tracking-wider">Terabot.AI</span>
                        </div>
                    </div>
                    <div class="text-white text-right">
                        <h1 class="text-3xl font-light tracking-widest">INVOICE</h1>
                    </div>
                </div>

                <div class="p-10">
                    <div class="flex flex-col md:flex-row justify-between mb-12 gap-8">
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-400 uppercase mb-2">Ditagihkan Kepada:</p>
                            <p class="text-lg font-bold text-gray-900">{{ $invoice->user->name }}</p>
                            <p class="text-gray-600">{{ $invoice->user->business_name }}</p>
                            <p class="text-gray-600">{{ $invoice->user->whatsapp_number }}</p>
                        </div>

                        <div class="flex-1 md:text-right">
                            <div class="space-y-1 text-sm">
                                <p><span class="font-bold text-gray-900">Nomor Invoice:</span> <span class="text-gray-600">{{ $invoice->invoice_number }}</span></p>
                                <p><span class="font-bold text-gray-900">Tanggal:</span> <span class="text-gray-600">{{ $invoice->created_at->format('d M Y') }}</span></p>
                                <p>
                                    <span class="font-bold text-gray-900">Status:</span> 
                                    <span class="ml-2 px-2 py-1 bg-red-100 text-red-600 text-[10px] font-bold rounded uppercase">
                                        {{ $invoice->status }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-10 border border-gray-100 rounded-lg overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr class="text-gray-700 text-xs font-bold uppercase tracking-wider">
                                    <th class="py-4 px-6 text-center w-16">SL.</th>
                                    <th class="py-4 px-6">Deskripsi Layanan</th>
                                    <th class="py-4 px-6 text-center">Durasi</th>
                                    <th class="py-4 px-6 text-right">Harga</th>
                                    <th class="py-4 px-6 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="text-gray-700 text-sm">
                                    <td class="py-6 px-6 text-center">1</td>
                                    <td class="py-6 px-6">
                                        <p class="font-bold text-gray-900">{{ $invoice->subscription->plan->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1 italic">Layanan Chatbot AI Assistant</p>
                                    </td>
                                    <td class="py-6 px-6 text-center italic">30 Hari</td>
                                    <td class="py-6 px-6 text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                    <td class="py-6 px-6 text-right font-bold text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mb-16">
                        <div class="w-full md:w-72 space-y-3">
                            <div class="flex justify-between text-sm text-gray-600 px-2">
                                <span>Subtotal:</span>
                                <span class="font-semibold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 px-2 border-b border-gray-100 pb-3">
                                <span>Biaya Admin / Pajak:</span>
                                <span class="font-semibold text-green-600">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                                <span class="text-sm font-bold text-gray-800 uppercase">Total:</span>
                                <span class="text-2xl font-black text-[#4e73df]">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center pt-8 border-t border-gray-100">
                        <a href="{{ route('payment.index') }}" 
                           class="inline-block bg-[#4e73df] hover:bg-[#3751a3] text-white font-extrabold py-4 px-12 rounded-lg shadow-md transition-all uppercase tracking-widest text-sm text-center">
                            Lanjutkan Pembayaran
                        </a>
                        <p class="mt-4 text-[10px] text-gray-400 font-medium">Terima kasih atas kepercayaan Anda menggunakan layanan Terabot.AI</p>
                    </div>

                </div>
                
                <div class="bg-[#4e73df] h-2"></div>
            </div>
        </div>
    </div>
</x-app-layout>