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

                            <div class="mb-6">
                                <label for="payment_method" class="block text-sm font-medium text-slate-700 mb-2">Metode Pembayaran Tersedia</label>
                                <select id="payment_method" name="payment_method" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base py-3" required onchange="showPaymentPreview()">
                                    <option value="" disabled selected>-- Klik untuk memilih bank / dompet digital --</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method['paymentMethod'] }}" 
                                                data-image="{{ $method['paymentImage'] }}" 
                                                data-fee="{{ $method['totalFee'] }}"
                                                data-name="{{ $method['paymentName'] }}">
                                            {{ $method['paymentName'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="payment-preview" class="hidden mt-6 p-6 border-2 border-blue-500 bg-blue-50 rounded-xl flex items-center justify-between transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <div class="bg-white p-2 rounded-lg shadow-sm border border-slate-100">
                                        <img id="preview-image" src="" alt="Payment Logo" class="h-12 w-16 object-contain">
                                    </div>
                                    <div>
                                        <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Metode Terpilih:</p>
                                        <p id="preview-name" class="font-bold text-slate-900 text-lg"></p>
                                        <p id="preview-fee" class="text-sm mt-1"></p>
                                    </div>
                                </div>
                                <div class="text-blue-600 hidden sm:block">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
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

                            <button type="submit" id="btn-submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-4 px-6 rounded-xl shadow-lg transition-all transform hover:-translate-y-1 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                                <span>Lanjutkan Pembayaran</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                            
                            <p class="text-[10px] text-slate-400 mt-4 text-center">
                                Secured by Duitku Payment Gateway
                            </p>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <script>
        function showPaymentPreview() {
            const select = document.getElementById('payment_method');
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption.value) {
                // Ambil data dari tag option
                const imageSrc = selectedOption.getAttribute('data-image');
                const fee = selectedOption.getAttribute('data-fee');
                const name = selectedOption.getAttribute('data-name');

                // Update gambar dan teks preview
                document.getElementById('preview-image').src = imageSrc;
                document.getElementById('preview-name').textContent = name;

                const feeElement = document.getElementById('preview-fee');
                if (parseInt(fee) > 0) {
                    feeElement.textContent = '+ Biaya Admin Rp ' + parseInt(fee).toLocaleString('id-ID');
                    feeElement.className = 'text-sm mt-1 text-slate-600';
                } else {
                    feeElement.textContent = 'Gratis Biaya Admin';
                    feeElement.className = 'text-sm mt-1 text-green-600 font-bold';
                }

                // Tampilkan kotak preview yang sebelumnya disembunyikan
                document.getElementById('payment-preview').classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>