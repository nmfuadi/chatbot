<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('user.invoice.show', $invoice->id) }}" class="text-slate-500 hover:text-slate-700">
                &larr; Kembali
            </a>
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Pilih Metode Pembayaran') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <form action="{{ route('payment.request', $invoice->id) }}" method="POST" id="payment-form">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2">
                        <div class="bg-white shadow-sm border border-slate-200 rounded-xl p-6 sm:p-8">
                            <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center">
                                <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm mr-3">1</span>
                                Pilih Metode Pembayaran
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($paymentMethods as $method)
                                    <label class="group relative flex cursor-pointer rounded-xl border-2 border-slate-100 bg-white p-4 shadow-sm focus:outline-none hover:border-blue-200 transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                        <input type="radio" name="payment_method" value="{{ $method['paymentMethod'] }}" class="sr-only" required>
                                        
                                        <div class="flex w-full items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <div class="shrink-0">
                                                    <img src="{{ $method['paymentImage'] }}" alt="{{ $method['paymentName'] }}" class="h-10 w-12 object-contain">
                                                </div>
                                                <div class="text-sm">
                                                    <p class="font-bold text-slate-900">{{ $method['paymentName'] }}</p>
                                                    @if($method['totalFee'] > 0)
                                                        <p class="text-slate-500">+ Biaya Rp {{ number_format($method['totalFee'], 0, ',', '.') }}</p>
                                                    @else
                                                        <p class="text-green-600 font-medium">Gratis Biaya Admin</p>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="h-5 w-5 rounded-full border-2 border-slate-200 flex items-center justify-center group-hover:border-blue-400 peer-checked:border-blue-600">
                                                <div class="h-2.5 w-2.5 rounded-full bg-blue-600 scale-0 transition-transform duration-200 peer-checked:scale-100"></div>
                                            </div>
                                        </div>

                                        <style>
                                            input:checked ~ div .h-5 { border-color: #2563eb; }
                                            input:checked ~ div .bg-blue-600 { transform: scale(1); }
                                        </style>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white shadow-sm border border-slate-200 rounded-xl p-6 sticky top-8">
                            <h3 class="text-lg font-bold text-slate-900 mb-6">Ringkasan Pesanan</h3>
                            
                            <div class="space-y-4 mb-8">
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Nomor Invoice</span>
                                    <span class="font-mono font-bold text-slate-800">{{ $invoice->invoice_number }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Paket Layanan</span>
                                    <span class="font-bold text-slate-800 text-right">{{ $invoice->subscription->plan->name }}</span>
                                </div>
                                <div class="border-t border-slate-100 pt-4 flex justify-between items-center">
                                    <span class="text-base font-bold text-slate-900">Total Tagihan</span>
                                    <span class="text-xl font-black text-blue-600">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-4 px-6 rounded-xl shadow-lg transition-all transform hover:-translate-y-1 flex items-center justify-center">
                                <span>Bayar Sekarang</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                            
                            <p class="text-[10px] text-slate-400 mt-4 text-center">
                                Dengan mengklik tombol di atas, Anda setuju dengan Syarat & Ketentuan layanan kami.
                            </p>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
</x-app-layout>