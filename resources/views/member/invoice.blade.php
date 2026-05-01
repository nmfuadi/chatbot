<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200">
                <div class="p-8 sm:p-12">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-8 mb-8 gap-4">
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 tracking-tight"> INVOICE</h1>
                            <p class="text-sm text-gray-500 mt-1"> Nomor: <span class="font-mono font-semibold text-gray-800">{{ $invoice->invoice_number }}</span></p>
                        </div>
                        <div>
                            @if($invoice->status == 'unpaid')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    MENUNGGU PEMBAYARAN
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700 border border-green-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    LUNAS
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Ditagihkan Kepada:</h3>
                            <p class="text-lg font-bold text-gray-900">{{ $invoice->user->name }}</p>
                            <p class="text-md text-gray-600 font-medium">{{ $invoice->user->business_name }}</p>
                            <p class="text-sm text-gray-500 mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ $invoice->user->whatsapp_number }}
                            </p>
                        </div>
                        
                        <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                            <h3 class="text-xs font-bold text-blue-500 uppercase tracking-wider mb-3">Detail Layanan:</h3>
                            <p class="text-xl font-black text-blue-900">{{ $invoice->subscription->plan->name }}</p>
                            <p class="text-sm text-blue-700 font-medium mt-2">Masa Aktif: <span class="font-bold">30 Hari</span></p>
                            <p class="text-sm text-blue-700 font-medium">Limit: <span class="font-bold">{{ $invoice->subscription->plan->is_unlimited_messages ? 'Unlimited' : number_format($invoice->subscription->plan->max_messages, 0, ',', '.') . ' Pesan' }}</span></p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6 mb-8">
                        <div class="flex justify-between items-center py-3">
                            <span class="text-gray-600 font-medium">Subtotal</span>
                            <span class="text-gray-900 font-semibold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-200 mb-6">
                            <span class="text-gray-600 font-medium">PPN / Biaya Admin Sistem</span>
                            <span class="text-green-600 font-bold">Gratis</span>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-between items-center bg-gray-900 text-white p-6 sm:p-8 rounded-xl shadow-inner">
                            <span class="text-lg font-medium text-gray-300 mb-2 sm:mb-0">Total Pembayaran</span>
                            <span class="text-4xl font-black text-blue-400 tracking-tight">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('payment.index') }}" class="w-full flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white text-lg font-bold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Lanjutkan Pembayaran Sekarang
                        </a>
                        <p class="text-center text-sm text-gray-400 mt-4 flex justify-center items-center">
                            <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Sistem akan otomatis mengaktifkan AI Anda setelah pembayaran berhasil.
                        </p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>