<div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-gray-900/5 overflow-hidden p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-green-50 text-green-600 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight">Pengaturan Google Sheets</h3>
                            <p class="text-xs text-gray-500">Hubungkan spreadsheet untuk menyuplai data pengetahuan produk ke AI secara otomatis.</p>
                        </div>
                    </div>
                    
                    @if(!empty($pk->google_sheet_url))
                        <span class="self-start sm:self-auto px-3 py-1 bg-green-100 text-green-700 text-xs font-black rounded-full border border-green-200 flex items-center gap-1">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
                            Link Terhubung ke Scheduler
                        </span>
                    @else
                        <span class="self-start sm:self-auto px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-black rounded-full border border-yellow-200">
                            ⚠️ Belum Terhubung ke Scheduler
                        </span>
                    @endif
                </div>

                <form action="{{ route('member.sync_sheet') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">URL Google Sheet Public</label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="url" name="google_sheet_url" 
                                value="{{ old('google_sheet_url', $pk->google_sheet_url ?? '') }}" 
                                class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-inner focus:ring-2 focus:ring-indigo-500 text-sm p-3.5" 
                                placeholder="Contoh: https://docs.google.com/spreadsheets/d/1xxxxxxxxxxxxxxx/edit?usp=sharing" required>
                            
                            <button type="submit" id="btnSubmitSync" class="flex-shrink-0 flex justify-center items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-6 rounded-xl shadow-md transition-all active:scale-[0.98]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                <span id="btnText">Simpan & Sync Data</span>
                            </button>
                        </div>
                        <p class="mt-2.5 text-[11px] text-gray-400">
                            * Ubah hak akses Google Sheets Anda menjadi <b>"Anyone with the link"</b> sebelum melakukan penyimpanan.<br>
                            * Setelah disimpan, mesin *background* akan otomatis melakukan penyegaran (*refresh*) data setiap 1 jam sekali.
                        </p>
                    </div>
                </form>
            </div>

            <script>
                document.querySelector('form').addEventListener('submit', function() {
                    let btn = document.getElementById('btnSubmitSync');
                    let txt = document.getElementById('btnText');
                    btn.disabled = true;
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                    txt.innerText = 'Menyimpan & Menarik Data... ⏳';
                });
            </script>