<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200">
                
                <div class="p-8 sm:p-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-100 pb-8 mb-10 gap-4">
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 tracking-tighter">INVOICE</h1>
                            <p class="text-sm text-gray-500 mt-1 uppercase tracking-widest"> 
                                No: <span class="font-mono font-bold text-gray-700">{{ $invoice->invoice_number }}</span>
                            </p>
                        </div>
                        <div>
                            @if($invoice->status == 'unpaid')
                                <span class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-black bg-red-50 text-red-600 border border-red-100 uppercase">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Menunggu Pembayaran
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-black bg-green-50 text-green-600 border border-green-100 uppercase">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Lunas
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                        <div class="bg-gray-50 p-7 rounded-2xl border border-gray-100">
                            <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-4">Ditagihkan Kepada:</h3>
                            <div class="space-y-1">
                                <p class="text-lg font-extrabold text-gray-900">{{ $invoice->user->name }}</p>
                                <p class="text-sm text-gray-600 font-medium">{{ $invoice->user->business_name }}</p>
                                <p class="text-xs text-gray-500 flex items-center pt-2 font-mono">
                                    <svg class="w-3.5 h-3.5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $invoice->user->whatsapp_number }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 p-7 rounded-2xl border border-blue-100">
                            <h3 class="text-[10px] font-bold text-blue-400 uppercase tracking-[0.2em] mb-4">Detail Layanan:</h3>
                            <div class="space-y-1">
                                <p class="text-lg font-extrabold text-blue-900">{{ $invoice->subscription->plan->name }}</p>
                                <p class="text-sm text-blue-700 font-medium">Masa Aktif: <span class="font-bold">30 Hari</span></p>
                                <p class="text-xs text-blue-600 font-medium">Limit: <span class="font-bold">{{ $invoice->subscription->plan->is_unlimited_messages ? 'Unlimited' : number_format($invoice->subscription->plan->max_messages, 0, ',', '.') . ' Pesan' }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 mb-10 px-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-medium">Subtotal Layanan</span>
                            <span class="text-gray-900 font-bold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm pb-4 border-b border-gray-100">
                            <span class="text-gray-500 font-medium">PPN & Biaya Admin</span>
                            <span class="text-green-600 font-extrabold italic">Rp 0</span>
                        </div>
                        
                        <div class="pt-4 flex flex-col sm:flex-row justify-between items-center bg-gray-900 text-white p-6 rounded-2xl shadow-inner">
                            <span class="text-sm font-bold uppercase tracking-widest text-gray-400 mb-2 sm:mb-0">Total Pembayaran</span>
                            <span class="text-3xl font-black text-blue-400 tracking-tighter">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col items-center">
                        <a href="{{ route('payment.index') }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-4 px-10 rounded-xl shadow-lg hover:shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Bayar Sekarang
                        </a>
                        <p class="mt-6 text-[10px] text-gray-400 flex items-center font-bold uppercase tracking-widest">
                            <svg class="w-3 h-3 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Secure Automated Payment System
                        </p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>