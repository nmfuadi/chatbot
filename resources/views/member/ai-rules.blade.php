<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                {{ __('⚙️ Kustomisasi Indikator Pipeline (AI Rules)') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">Dikte kecerdasan AI untuk mengklasifikasikan data prospek sesuai dengan kriteria unik bisnis Anda.</p>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-100 text-green-700 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('member.ai-rules.save') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="bg-white p-6 rounded-[2rem] border border-gray-200 shadow-sm">
                    <label class="block text-sm font-black text-rose-800 uppercase tracking-wider mb-1">⚠️ Daftar Alasan Gagal / Batal Khusus Bisnis Anda</label>
                    <p class="text-xs text-gray-400 mb-3">Pisahkan setiap alasan dengan tanda koma ( , ). Contoh: kemahalan, ongkir_mahal, tidak_respon</p>
                    <input type="text" name="objection_reasons" value="{{ old('objection_reasons', $pk->objection_reasons ?? '') }}" class="w-full border-gray-200 bg-gray-50 rounded-xl focus:ring-2 focus:ring-rose-500 text-sm p-3.5 shadow-inner transition-all" placeholder="ongkir_mahal, budget_kurang, kompetitor, slow_respon">
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-200 shadow-sm">
                    <h3 class="text-md font-black text-gray-900 mb-6 border-b pb-3">📌 Syarat & Indikator Pemicu Status</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-slate-50 p-5 rounded-2xl border border-gray-200">
                            <label class="block text-xs font-black text-gray-600 uppercase tracking-wider mb-2.5">📥 1. Kondisi "Baru Masuk"</label>
                            <textarea name="lead_rule_baru" rows="3" class="w-full border-gray-200 bg-white rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 text-xs p-3.5 leading-relaxed">{{ old('lead_rule_baru', $pk->lead_rule_baru ?? 'Baru masuk ke sistem WhatsApp, baru melakukan chat pertama kali, atau sekadar menyapa.') }}</textarea>
                        </div>

                        <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100">
                            <label class="block text-xs font-black text-blue-800 uppercase tracking-wider mb-2.5">💬 2. Kondisi "Prospect"</label>
                            <textarea name="lead_rule_prospect" rows="3" class="w-full border-blue-200 bg-white rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 text-xs p-3.5 leading-relaxed">{{ old('lead_rule_prospect', $pk->lead_rule_prospect ?? 'Pelanggan mulai aktif mengobrol, tanya produk, meminta katalog, atau bertanya harga.') }}</textarea>
                        </div>

                        <div class="bg-amber-50/50 p-5 rounded-2xl border border-amber-100">
                            <label class="block text-xs font-black text-amber-800 uppercase tracking-wider mb-2.5">🔥 3. Kondisi "Hot Prospek"</label>
                            <textarea name="lead_rule_hot_prospek" rows="3" class="w-full border-amber-200 bg-white rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 text-xs p-3.5 leading-relaxed">{{ old('lead_rule_hot_prospek', $pk->lead_rule_hot_prospek ?? 'Pelanggan cocok dengan budget, cocok dengan kebutuhan, dan menunjukkan indikasi sangat serius ingin membeli.') }}</textarea>
                        </div>

                        <div class="bg-purple-50/50 p-5 rounded-2xl border border-purple-100">
                            <label class="block text-xs font-black text-purple-800 uppercase tracking-wider mb-2.5">🤝 4. Kondisi "Deal"</label>
                            <textarea name="lead_rule_deal" rows="3" class="w-full border-purple-200 bg-white rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 text-xs p-3.5 leading-relaxed">{{ old('lead_rule_deal', $pk->lead_rule_deal ?? 'Pelanggan sudah setuju membeli, meminta nomor rekening, atau membayar booking fee.') }}</textarea>
                        </div>

                        <div class="bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100">
                            <label class="block text-xs font-black text-emerald-800 uppercase tracking-wider mb-2.5">💰 5. Kondisi "Closing"</label>
                            <textarea name="lead_rule_closing" rows="3" class="w-full border-emerald-200 bg-white rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 text-xs p-3.5 leading-relaxed">{{ old('lead_rule_closing', $pk->lead_rule_closing ?? 'Pelanggan sudah mengirimkan bukti transfer lunas dan pembayaran sudah dikonfirmasi.') }}</textarea>
                        </div>

                        <div class="bg-rose-50/50 p-5 rounded-2xl border border-rose-100">
                            <label class="block text-xs font-black text-rose-800 uppercase tracking-wider mb-2.5">❌ 6. Kondisi "Gagal"</label>
                            <textarea name="lead_rule_gagal" rows="3" class="w-full border-rose-200 bg-white rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 text-xs p-3.5 leading-relaxed">{{ old('lead_rule_gagal', $pk->lead_rule_gagal ?? 'Pelanggan menolak membeli secara tegas, membatalkan pesanan, atau ghosting.') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="w-full md:w-auto flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-10 rounded-2xl shadow-lg shadow-indigo-100 transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Aturan Pipeline AI
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>