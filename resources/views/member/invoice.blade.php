<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Tagihan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="p-6 sm:p-10">

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-6 mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">INVOICE</h1>
                            <p class="text-sm text-gray-500 mt-1">Nomor: <span class="font-mono font-bold text-gray-800">{{ $invoice->invoice_number }}</span></p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @if($invoice->status == 'unpaid')
                                <span class="px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-700">
                                    MENUNGGU PEMBAYARAN
                                </span>
                            @else
                                <span class="px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700">
                                    LUNAS
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                            <h3 class="text-xs font-bold text-gray-500 uppercase mb-3">Ditagihkan Kepada:</h3>
                            <p class="text-lg font-bold text-gray-900">{{ $invoice->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $invoice->user->business_name }}</p>
                            <p class="text-sm text-gray-600 mt-1">WA: {{ $invoice->user->whatsapp_number }}</p>
                        </div>

                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-100">
                            <h3 class="text-xs font-bold text-blue-500 uppercase mb-3">Detail Layanan:</h3>
                            <p class="text-lg font-bold text-blue-900">{{ $invoice->subscription->plan->name }}</p>
                            <p class="text-sm text-blue-700 mt-1">Masa Aktif: <span class="font-bold">30 Hari</span></p>
                            <p class="text-sm text-blue-700">Limit: <span class="font-bold">{{ $invoice->subscription->plan->is_unlimited_messages ? 'Unlimited' : number_format($invoice->subscription->plan->max_messages, 0, ',', '.') . ' Pesan' }}</span></p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6 mb-8">
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Subtotal Layanan</span>
                            <span class="text-gray-900 font-bold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-200 mb-6">
                            <span class="text-gray-600">PPN & Biaya Admin</span>
                            <span class="text-green-600 font-bold">Gratis</span>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-center bg-gray-800 text-white p-6 rounded-lg">
                            <span class="text-lg font-bold mb-2 sm:mb-0">Total Pembayaran</span>
                            <span class="text-3xl font-bold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-8 text-center border-t border-gray-100 pt-8">
                        <div class="flex justify-center">
                            <a href="{{ route('payment.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow transition duration-150">
                                Bayar Sekarang
                            </a>
                        </div>
                        <p class="text-xs text-gray-400 mt-4">Invoice ini diterbitkan secara otomatis oleh sistem.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>