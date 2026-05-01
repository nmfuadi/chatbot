<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            {{ __('Detail Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-14 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-2xl rounded-3xl border border-gray-100 overflow-hidden">
                <div class="p-10">

                    {{-- HEADER --}}
                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-6 border-b pb-8 mb-10">
                        <div>
                            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                                Invoice
                            </h1>
                            <p class="text-sm text-gray-400 mt-2">
                                No:
                                <span class="font-mono text-gray-700 font-semibold">
                                    {{ $invoice->invoice_number }}
                                </span>
                            </p>
                        </div>

                        {{-- STATUS --}}
                        <div>
                            @if($invoice->status == 'unpaid')
                                <span class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Menunggu Pembayaran
                                </span>
                            @else
                                <span class="inline-flex items-center px-5 py-2.5 rounded-full text-sm font-semibold bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Lunas
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- CUSTOMER & SERVICE --}}
                    <div class="grid md:grid-cols-2 gap-6 mb-10">

                        {{-- CUSTOMER --}}
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition">
                            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                                Ditagihkan Kepada
                            </h3>

                            <p class="text-lg font-bold text-gray-900">
                                {{ $invoice->user->name }}
                            </p>

                            <p class="text-gray-600">
                                {{ $invoice->user->business_name }}
                            </p>

                            <p class="text-sm text-gray-500 mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 16.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $invoice->user->whatsapp_number }}
                            </p>
                        </div>

                        {{-- SERVICE --}}
                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 hover:shadow-md transition">
                            <h3 class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-3">
                                Detail Layanan
                            </h3>

                            <p class="text-xl font-extrabold text-blue-900">
                                {{ $invoice->subscription->plan->name }}
                            </p>

                            <p class="text-sm text-blue-700 mt-3">
                                Masa Aktif:
                                <span class="font-semibold">30 Hari</span>
                            </p>

                            <p class="text-sm text-blue-700">
                                Limit:
                                <span class="font-semibold">
                                    {{ $invoice->subscription->plan->is_unlimited_messages 
                                        ? 'Unlimited' 
                                        : number_format($invoice->subscription->plan->max_messages, 0, ',', '.') . ' Pesan' }}
                                </span>
                            </p>
                        </div>

                    </div>

                    {{-- SUMMARY --}}
                    <div class="border-t pt-6 mb-10 space-y-2">

                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold text-gray-900">
                                Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between border-b pb-4 mb-6 text-gray-600">
                            <span>PPN / Biaya Admin Sistem</span>
                            <span class="font-semibold text-green-600">Gratis</span>
                        </div>

                        {{-- TOTAL --}}
                        <div class="flex justify-between items-center bg-gradient-to-r from-gray-900 to-gray-800 text-white p-7 rounded-2xl shadow-inner">
                            <span class="text-lg text-gray-300">
                                Total Pembayaran
                            </span>
                            <span class="text-4xl font-extrabold text-blue-400 tracking-tight">
                                Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                            </span>
                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-6">
                        <a href="{{ route('payment.index') }}"
                           class="w-full flex justify-center items-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-lg font-semibold py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                            
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>

                            Lanjutkan Pembayaran
                        </a>

                        <p class="text-center text-xs text-gray-400 mt-4 flex justify-center items-center">
                            <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Aktivasi otomatis setelah pembayaran berhasil
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>