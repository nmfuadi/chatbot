<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Detail Tagihan
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">

                    {{-- HEADER --}}
                    <div class="invoice-header">
                        <div>
                            <h1 class="invoice-title">INVOICE</h1>
                            <p class="invoice-number">
                                Nomor:
                                <span>{{ $invoice->invoice_number }}</span>
                            </p>
                        </div>

                        <x-invoice.status :status="$invoice->status" />
                    </div>

                    {{-- CUSTOMER & SERVICE --}}
                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <x-invoice.customer :user="$invoice->user" />
                        <x-invoice.service :subscription="$invoice->subscription" />
                    </div>

                    {{-- PAYMENT --}}
                    <div class="invoice-summary">
                        <x-invoice.row label="Subtotal" :value="$invoice->amount" />
                        <x-invoice.row label="PPN / Biaya Admin Sistem" value="Gratis" isFree />

                        <div class="invoice-total">
                            <span>Total Pembayaran</span>
                            <strong>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    {{-- ACTION --}}
                    <div class="mt-8">
                        <a href="{{ route('payment.index') }}" class="btn-primary">
                            Bayar Sekarang
                        </a>

                        <p class="invoice-note">
                            Sistem akan otomatis mengaktifkan AI Anda setelah pembayaran berhasil.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>