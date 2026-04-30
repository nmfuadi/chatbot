<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg border border-gray-200">
                <div class="p-8">
                    <div class="flex justify-between items-start border-b pb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">INVOICE</h1>
                            <p class="text-sm text-gray-500">Nomor: {{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-4 py-1 rounded-full text-sm font-bold bg-amber-100 text-amber-700">MENUNGGU PEMBAYARAN</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 py-8 border-b">
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase">Tagihan Untuk:</h3>
                            <p class="font-bold text-gray-800">{{ $invoice->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $invoice->user->business_name }}</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-xs font-bold text-gray-400 uppercase">Detail Paket:</h3>
                            <p class="font-bold text-gray-800">{{ $invoice->subscription->plan->name }}</p>
                            <p class="text-sm text-gray-600">Durasi: 30 Hari</p>
                        </div>
                    </div>

                    <div class="py-8 text-center bg-gray-50 rounded-xl my-6">
                        <p class="text-sm text-gray-500">Total Pembayaran</p>
                        <h2 class="text-4xl font-extrabold text-blue-600">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</h2>
                    </div>

                    <div class="space-y-4">
                        <a href="{{ route('payment.index') }}" class="block w-full text-center bg-blue-600 text-white font-bold py-4 rounded-lg hover:bg-blue-700 transition">
                            Bayar Sekarang (Konfirmasi Otomatis)
                        </a>
                        <p class="text-center text-xs text-gray-400 italic">Invoice ini diterbitkan secara otomatis oleh sistem Smart AI Assistant.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>