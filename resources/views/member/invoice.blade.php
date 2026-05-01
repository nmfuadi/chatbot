<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-200 overflow-hidden">

                {{-- Header Invoice --}}
                <div class="px-8 pt-8 pb-6 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black tracking-widest text-gray-900">INVOICE</h1>
                            <p class="text-sm text-gray-400 mt-1">
                                No. <span class="font-mono font-semibold text-gray-700">{{ $invoice->invoice_number }}</span>
                            </p>
                        </div>

                        @if($invoice->status == 'unpaid')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 w-fit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                MENUNGGU PEMBAYARAN
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 w-fit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                LUNAS
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-8 space-y-6">

                    {{-- Info Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Ditagihkan Kepada --}}
                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-5 space-y-1">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Ditagihkan Kepada</p>
                            <p class="text-base font-bold text-gray-900">{{ $invoice->user->name }}</p>
                            <p class="text-sm font-medium text-gray-600">{{ $invoice->user->business_name }}</p>
                            <p class="text-sm text-gray-400 flex items-center gap-1.5 pt-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $invoice->user->whatsapp_number }}
                            </p>
                        </div>

                        {{-- Detail Layanan --}}
                        <div class="rounded-xl border border-blue-100 bg-blue-50 p-5 space-y-1">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-blue-400 mb-3">Detail Layanan</p>
                            <p class="text-base font-black text-blue-900">{{ $invoice->subscription->plan->name }}</p>
                            <p class="text-sm text-blue-700">
                                Masa Aktif: <span class="font-bold">30 Hari</span>
                            </p>
                            <p class="text-sm text-blue-700">
                                Limit:
                                <span class="font-bold">
                                    {{ $invoice->subscription->plan->is_unlimited_messages
                                        ? 'Unlimited'
                                        : number_format($invoice->subscription->plan->max_messages, 0, ',', '.') . ' Pesan' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Rincian Biaya --}}
                    <div class="rounded-xl border border-gray-100 overflow-hidden">
                        <div class="divide-y divide-gray-100">
                            <div class="flex justify-between items-center px-5 py-4">
                                <span class="text-sm text-gray-500">Subtotal</span>
                                <span class="text-sm font-semibold text-gray-800">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center px-5 py-4">
                                <span class="text-sm text-gray-500">PPN / Biaya Admin Sistem</span>
                                <span class="text-sm font-bold text-emerald-600">Gratis</span>
                            </div>
                        </div>

                        <div class="bg-gray-900 px-5 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <span class="text-sm font-medium text-gray-400">Total Pembayaran</span>
                            <span class="text-3xl font-black text-blue-400 tracking-tight">
                                Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- CTA --}}
                    <div class="space-y-3 pt-2">
                        <a href="{{ route('payment.index') }}"
                           class="w-full flex justify-center items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-base font-bold py-3.5 px-6 rounded-xl transition-colors duration-150">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Lanjutkan Pembayaran Sekarang
                        </a>

                        <p class="text-center text-xs text-gray-400 flex justify-center items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Sistem akan otomatis mengaktifkan AI Anda setelah pembayaran berhasil.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
