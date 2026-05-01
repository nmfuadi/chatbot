<x-app-layout>
    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-6 sm:px-8">
            
            <div class="bg-white shadow-sm border border-gray-200 overflow-hidden rounded-lg">
                
                <div class="bg-[#4e73df] p-10 flex justify-between items-center text-white">
                    <div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-[#4e73df]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"></path></svg>
                            </div>
                            <span class="text-2xl font-bold tracking-tight">Terabot.AI</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <h1 class="text-4xl font-light tracking-[0.2em]">INVOICE</h1>
                    </div>
                </div>

                <div class="p-12">
                    <div class="flex flex-col md:flex-row justify-between mb-16 gap-8">
                        <div class="flex-1">
                            <h2 class="text-gray-900 font-bold text-lg mb-4">Invoice To:</h2>
                            <div class="text-gray-600 space-y-1">
                                <p class="font-bold text-gray-800 text-lg">{{ $invoice->user->name }}</p>
                                <p>{{ $invoice->user->business_name }}</p>
                                <p>{{ $invoice->user->whatsapp_number }}</p>
                            </div>
                        </div>

                        <div class="flex-1 md:text-right">
                            <div class="inline-block text-left md:text-right">
                                <p class="text-gray-900 font-bold mb-1">Invoice Number</p>
                                <p class="text-gray-500 font-mono mb-4">{{ $invoice->invoice_number }}</p>
                                
                                <p class="text-gray-900 font-bold mb-1">Date</p>
                                <p class="text-gray-500 mb-4">{{ $invoice->created_at->format('d F Y') }}</p>

                                <div class="mt-2">
                                    @if($invoice->status == 'unpaid')
                                        <span class="bg-red-50 text-red-600 border border-red-200 px-3 py-1 rounded text-xs font-bold uppercase">Unpaid</span>
                                    @else
                                        <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded text-xs font-bold uppercase">Paid</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-12 overflow-hidden">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-y border-gray-200 text-gray-700 text-xs font-bold uppercase tracking-wider">
                                    <th class="py-4 px-6 text-center w-16">SL.</th>
                                    <th class="py-4 px-6 text-left">Item Description</th>
                                    <th class="py-4 px-6 text-center">Duration</th>
                                    <th class="py-4 px-6 text-right">Price</th>
                                    <th class="py-4 px-6 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="text-gray-700">
                                    <td class="py-6 px-6 text-center font-medium">1</td>
                                    <td class="py-6 px-6">
                                        <p class="font-bold text-gray-900">{{ $invoice->subscription->plan->name }}</p>
                                        <p class="text-sm text-gray-500">Subscription for AI Assistant Services</p>
                                    </td>
                                    <td class="py-6 px-6 text-center">30 Days</td>
                                    <td class="py-6 px-6 text-right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                    <td class="py-6 px-6 text-right font-bold text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end mb-16">
                        <div class="w-full md:w-64 space-y-3">
                            <div class="flex justify-between text-gray-600 text-sm">
                                <span>Sub Total:</span>
                                <span class="font-bold text-gray-900">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600 text-sm">
                                <span>Tax (0%):</span>
                                <span class="font-bold text-gray-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between text-blue-600 text-xl font-black border-t border-gray-200 pt-3">
                                <span>Total:</span>
                                <span>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-12 flex flex-col items-center">
                        <a href="{{ route('payment.index') }}" class="inline-flex items-center px-10 py-4 bg-[#4e73df] hover:bg-[#3e5cb0] text-white font-bold rounded-md shadow-md transition-all uppercase tracking-widest text-sm mb-4">
                            Proceed to Payment
                        </a>
                        <p class="text-xs text-gray-400">© 2026 Terabot.AI - Computer Generated Invoice</p>
                    </div>
                </div>

                <div class="h-2 bg-[#4e73df] w-full"></div>
            </div>
        </div>
    </div>
</x-app-layout>