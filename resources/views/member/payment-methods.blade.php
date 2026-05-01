<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('user.invoice.show', $invoice->id) }}" class="text-slate-500 hover:text-slate-700">
                &larr; Kembali ke Invoice
            </a>
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Pilih Metode Pembayaran') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-xl border border-slate-200 overflow-hidden">
                <div class="p-8 sm:p-10">
                    
                    <div class="text-center mb-10">
                        <h1 class="text-2xl font-bold text-slate-900">Selesaikan Pembayaran</h1>
                        <p class="text-slate-500 mt-2">Total Tagihan: <span class="text-indigo-600 font-bold text-xl">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span></p>
                    </div>

                    <form action="{{ route('payment.request', $invoice->id) }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-10">
                            @foreach($paymentMethods as $method)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="payment_method" value="{{ $method['paymentMethod'] }}" class="peer sr-only" required>
                                    
                                    <div class="h-full rounded-xl border-2 border-slate-100 bg-white p-5 hover:bg-slate-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all flex flex-col items-center justify-center gap-3">
                                        <img src="{{ $method['paymentImage'] }}" alt="{{ $method['paymentName'] }}" class="h-10 object-contain">
                                        <div class="text-center">
                                            <p class="text-sm font-bold text-slate-800">{{ $method['paymentName'] }}</p>
                                            @if($method['totalFee'] > 0)
                                                <p class="text-[10px] text-slate-500 mt-1">+ Biaya Rp {{ number_format($method['totalFee'], 0, ',', '.') }}</p>
                                            @else
                                                <p class="text-[10px] text-green-600 font-bold mt-1">Gratis Biaya Admin</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-blue-600 transition-opacity">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="text-center border-t border-slate-100 pt-8">
                            <button type="submit" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-lg font-extrabold py-4 px-12 rounded-xl shadow-lg transition duration-200 transform hover:-translate-y-1">
                                Lanjutkan ke Pembayaran
                            </button>
                            <p class="text-xs text-slate-400 mt-4 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Secured by Duitku Payment Gateway
                            </p>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>