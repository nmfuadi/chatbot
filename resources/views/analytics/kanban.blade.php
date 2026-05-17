<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('✨ AI Sales Pipeline') }}
            </h2>
            <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                Export Data
            </button>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <style>
        .sortable-ghost { opacity: 0.4; border: 2px dashed #94a3b8; background-color: #f8fafc; }
        .sortable-drag { cursor: grabbing !important; }
        /* Scrollbar styling tetap dipertahankan untuk jaga-jaga di layar kecil */
        .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
        
        /* Memastikan container utama benar-benar full width */
        .full-width-container {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 1rem;
            padding-right: 1rem;
        }
    </style>

    <div class="py-6">
        <div class="full-width-container mx-auto">
            
            <div class="grid grid-cols-2 md:grid-cols-6 gap-3 mb-6">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-center">
                    <span class="text-xs font-semibold text-gray-500 uppercase">Total Leads</span>
                    <span class="text-2xl font-black text-gray-900">{{ $totalLeads ?? 0 }}</span>
                </div>
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-200 shadow-sm flex flex-col justify-center">
                    <span class="text-xs font-semibold text-blue-700 uppercase">Prospect</span>
                    <span class="text-2xl font-black text-blue-600">{{ isset($leads['prospect']) ? count($leads['prospect']) : 0 }}</span>
                </div>
                <div class="bg-amber-50 p-4 rounded-xl border border-amber-200 shadow-sm flex flex-col justify-center">
                    <span class="text-xs font-semibold text-amber-700 uppercase">Hot Prospek</span>
                    <span class="text-2xl font-black text-amber-600">{{ isset($leads['hot_prospek']) ? count($leads['hot_prospek']) : 0 }}</span>
                </div>
                <div class="bg-purple-50 p-4 rounded-xl border border-purple-200 shadow-sm flex flex-col justify-center">
                    <span class="text-xs font-semibold text-purple-700 uppercase">Deal</span>
                    <span class="text-2xl font-black text-purple-600">{{ isset($leads['deal']) ? count($leads['deal']) : 0 }}</span>
                </div>
                <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200 shadow-sm flex flex-col justify-center">
                    <span class="text-xs font-semibold text-emerald-700 uppercase">Closing</span>
                    <span class="text-2xl font-black text-emerald-600">{{ isset($leads['closing']) ? count($leads['closing']) : 0 }}</span>
                </div>
                <div class="bg-rose-50 p-4 rounded-xl border border-rose-200 shadow-sm flex flex-col justify-center">
                    <span class="text-xs font-semibold text-rose-700 uppercase">Gagal</span>
                    <span class="text-2xl font-black text-rose-600">{{ isset($leads['gagal']) ? count($leads['gagal']) : 0 }}</span>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-xl border border-gray-200 h-[calc(100vh-220px)] flex flex-col">
                
                @php
                    // Struktur 6 Kolom Utama dengan penyesuaian kelas warna latar untuk kartu
                    $columns = [
                        'baru'        => ['title' => 'Baru Masuk', 'color' => 'slate', 'icon' => '📥', 'card_bg' => 'bg-white', 'card_border' => 'border-slate-200'],
                        'prospect'    => ['title' => 'Prospect', 'color' => 'blue', 'icon' => '💬', 'card_bg' => 'bg-blue-50/50', 'card_border' => 'border-blue-200'],
                        'hot_prospek' => ['title' => 'Hot Prospek', 'color' => 'amber', 'icon' => '🔥', 'card_bg' => 'bg-amber-50/50', 'card_border' => 'border-amber-200'],
                        'deal'        => ['title' => 'Deal', 'color' => 'purple', 'icon' => '🤝', 'card_bg' => 'bg-purple-50/50', 'card_border' => 'border-purple-200'],
                        'closing'     => ['title' => 'Closing', 'color' => 'emerald', 'icon' => '💰', 'card_bg' => 'bg-emerald-50/50', 'card_border' => 'border-emerald-200'],
                        'gagal'       => ['title' => 'Gagal / Batal', 'color' => 'rose', 'icon' => '❌', 'card_bg' => 'bg-rose-50/50', 'card_border' => 'border-rose-200'],
                    ];
                @endphp

                <div class="flex-1 flex overflow-x-auto custom-scrollbar p-4 bg-gray-50/50 gap-4">
                    
                    @foreach($columns as $statusKey => $col)
                    <div class="flex-1 min-w-[280px] bg-{{ $col['color'] }}-50/60 rounded-xl border border-{{ $col['color'] }}-200 flex flex-col shadow-sm h-full">
                        
                        <div class="p-3 border-b border-{{ $col['color'] }}-200 flex justify-between items-center bg-white/80 rounded-t-xl shrink-0">
                            <h3 class="font-bold text-{{ $col['color'] }}-700 flex items-center gap-2 text-sm">
                                <span>{{ $col['icon'] }}</span> {{ $col['title'] }}
                            </h3>
                            <span class="bg-white border border-{{ $col['color'] }}-200 text-{{ $col['color'] }}-700 text-xs font-bold px-2 py-0.5 rounded-full shadow-sm column-counter">
                                {{ isset($leads[$statusKey]) ? count($leads[$statusKey]) : 0 }}
                            </span>
                        </div>
                        
                        <div id="col-{{ $statusKey }}" data-status="{{ $statusKey }}" class="kanban-column p-2.5 flex-1 overflow-y-auto space-y-3">
                            @if(isset($leads[$statusKey]))
                                @foreach($leads[$statusKey] as $lead)
                                
                                <div class="{{ $col['card_bg'] }} p-3.5 rounded-xl shadow-sm border {{ $col['card_border'] }} cursor-grab hover:shadow-md transition relative group" 
                                     data-id="{{ $lead->id }}" 
                                     data-phone="{{ $lead->phone }}"
                                     data-summary="{{ $lead->chat_summary }}">
                                    
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-white text-gray-600 border border-gray-200">
                                            🏷️ {{ $lead->sumber_iklan }}
                                        </span>
                                        <span class="text-[9px] text-gray-500 font-medium bg-white/60 px-1.5 py-0.5 rounded">
                                            {{ $lead->created_at->diffForHumans(null, true, true) }}
                                        </span>
                                    </div>
                                    
                                    <h4 class="font-bold text-gray-900 tracking-tight text-md">{{ str_replace('@s.whatsapp.net', '', $lead->phone) }}</h4>
                                    
                                    @if($lead->buyer_character)
                                        @php
                                            $charColors = [
                                                'To The Point' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                                                'Banyak Tanya' => 'bg-amber-100 text-amber-800 border-amber-300',
                                                'Ragu-Ragu'    => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                                'Skeptis'      => 'bg-rose-100 text-rose-800 border-rose-300',
                                                'Ramah'        => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                            ];
                                            $colorClass = $charColors[$lead->buyer_character] ?? 'bg-white text-gray-700 border-gray-300';
                                        @endphp
                                        <div class="mt-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-bold border {{ $colorClass }}">
                                                👤 Tipe: {{ $lead->buyer_character }}
                                            </span>
                                        </div>
                                    @endif

                                    <p class="text-[10px] text-gray-500 mt-1.5 flex items-center gap-1 font-medium">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        {{ $lead->instance }}
                                    </p>

                                    <div class="mt-2.5 p-2 bg-white/70 rounded-lg border border-white/50 relative backdrop-blur-sm">
                                        <span class="absolute -top-1.5 -left-1 text-sm">🤖</span>
                                        <p class="text-[10px] text-gray-700 leading-relaxed italic pl-3 summary-text">
                                            "{{ $lead->chat_summary ?? 'Belum ada kesimpulan...' }}"
                                        </p>
                                    </div>

                                    <div class="mt-2.5">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Skor AI</span>
                                            <span class="text-[10px] font-black {{ $lead->lead_score > 70 ? 'text-emerald-600' : ($lead->lead_score > 40 ? 'text-amber-600' : 'text-gray-500') }}">
                                                {{ $lead->lead_score ?? 0 }}%
                                            </span>
                                        </div>
                                        <div class="w-full bg-white rounded-full h-1.5 overflow-hidden border border-gray-100">
                                            <div class="h-1.5 rounded-full transition-all duration-500 {{ $lead->lead_score > 70 ? 'bg-emerald-500' : ($lead->lead_score > 40 ? 'bg-amber-400' : 'bg-gray-400') }}" style="width: {{ $lead->lead_score ?? 0 }}%"></div>
                                        </div>
                                    </div>

                                    <div class="alasan-container mt-2.5 text-[10px] bg-rose-100 border border-rose-200 text-rose-800 px-2 py-1.5 rounded-md flex items-start gap-1 font-medium {{ $lead->alasan_batal && $statusKey == 'gagal' ? '' : 'hidden' }}">
                                        <span class="text-rose-600">⚠️</span> <span class="alasan-text">{{ $lead->alasan_batal }}</span>
                                    </div>

                                    <div class="mt-3 grid grid-cols-2 gap-1.5 opacity-0 group-hover:opacity-100 transform translate-y-1 group-hover:translate-y-0 transition-all">
                                        <button onclick="openHistory('{{ $lead->phone }}')" class="flex items-center justify-center gap-1 w-full bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 hover:border-gray-400 text-[10px] py-1 rounded-md font-bold transition-colors">
                                            📄 History
                                        </button>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" class="flex items-center justify-center gap-1 w-full bg-blue-600 border border-blue-700 text-white hover:bg-blue-700 text-[10px] py-1 rounded-md font-bold transition-colors shadow-sm">
                                            💬 Takeover
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    <div id="updateStatusModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl z-10 p-6 relative transform transition-all">
            <h3 class="font-bold text-gray-900 text-lg mb-2">📝 Perbarui Informasi Prospek</h3>
            <p class="text-xs text-gray-500 mb-4">Lengkapi data di bawah ini untuk disimpan ke database analitik.</p>
            
            <form id="updateStatusForm" onsubmit="submitStatusUpdate(event)">
                <input type="hidden" id="modal-lead-id">
                <input type="hidden" id="modal-status-target">

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Chat Summary / Catatan</label>
                    <textarea id="modal-chat-summary" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-50"></textarea>
                </div>

                <div id="modal-alasan-container" class="mb-5 hidden">
                    <label class="block text-xs font-bold text-rose-700 uppercase tracking-wider mb-2">⚠️ Alasan Batal</label>
                    <input type="text" id="modal-alasan-batal" class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 bg-white" placeholder="Contoh: budget_kurang, kompetitor, dll">
                    <p class="text-[10px] text-gray-400 mt-1">Ketik alasan pembatalan sesuai aturan bisnis Anda.</p>
                </div>

                <div class="flex justify-end gap-3 border-t border-gray-100 pt-4 mt-2">
                    <button type="button" onclick="cancelStatusUpdate()" class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition">Simpan & Pindah</button>
                </div>
            </form>
        </div>
    </div>

    <div id="historyModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeHistory()"></div>
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl z-10 flex flex-col max-h-[80vh] overflow-hidden transform transition-all relative">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-800 text-lg">Riwayat Analitik AI</h3>
                <button onclick="closeHistory()" class="text-gray-400 hover:text-rose-500 font-bold text-xl">&times;</button>
            </div>
            <div id="historyContent" class="p-6 overflow-y-auto flex-1 space-y-0"></div>
        </div>
    </div>

    <script>
        let currentDragEvent = null;

        document.addEventListener('DOMContentLoaded', function () {
            const columns = document.querySelectorAll('.kanban-column');

            columns.forEach(col => {
                new Sortable(col, {
                    group: 'shared',
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    dragClass: 'sortable-drag',
                    delay: 50,
                    delayOnTouchOnly: true,

                    onEnd: function (evt) {
                        if(evt.from === evt.to) return; 

                        currentDragEvent = evt;
                        const itemEl = evt.item;
                        openUpdateModal(
                            itemEl.getAttribute('data-id'), 
                            evt.to.getAttribute('data-status'), 
                            itemEl.getAttribute('data-summary')
                        );
                    },
                });
            });
        });

        function openUpdateModal(leadId, statusTarget, existingSummary) {
            document.getElementById('modal-lead-id').value = leadId;
            document.getElementById('modal-status-target').value = statusTarget;
            document.getElementById('modal-chat-summary').value = existingSummary || '';
            
            const alasanContainer = document.getElementById('modal-alasan-container');
            const alasanInput = document.getElementById('modal-alasan-batal');
            if(statusTarget === 'gagal') {
                alasanContainer.classList.remove('hidden');
                alasanInput.setAttribute('required', 'required');
            } else {
                alasanContainer.classList.add('hidden');
                alasanInput.removeAttribute('required');
                alasanInput.value = ''; 
            }

            document.getElementById('updateStatusModal').classList.remove('hidden');
        }

        function cancelStatusUpdate() {
            if (currentDragEvent) currentDragEvent.from.appendChild(currentDragEvent.item);
            document.getElementById('updateStatusModal').classList.add('hidden');
        }

        function submitStatusUpdate(event) {
            event.preventDefault();
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const id = document.getElementById('modal-lead-id').value;
            const status_prospek = document.getElementById('modal-status-target').value;
            const chat_summary = document.getElementById('modal-chat-summary').value;
            const alasan_batal = status_prospek === 'gagal' ? document.getElementById('modal-alasan-batal').value : null;

            const cardEl = currentDragEvent.item;

            fetch('{{ route('sales.update-status') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id, status_prospek, chat_summary, alasan_batal })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    cardEl.setAttribute('data-summary', chat_summary);
                    cardEl.querySelector('.summary-text').innerText = `"${chat_summary}"`;
                    
                    const alasanBox = cardEl.querySelector('.alasan-container');
                    if(status_prospek === 'gagal' && alasan_batal) {
                        alasanBox.classList.remove('hidden');
                        cardEl.querySelector('.alasan-text').innerText = alasan_batal.replace('_', ' ');
                    } else {
                        alasanBox.classList.add('hidden');
                    }

                    // Update UI Card Class Colors based on new status (Optional fine-tuning)
                    // You might want to refresh the page or dynamically change classes if full UI consistency is needed immediately on drag

                    const fromBadge = currentDragEvent.from.previousElementSibling.querySelector('.column-counter');
                    const toBadge = currentDragEvent.to.previousElementSibling.querySelector('.column-counter');
                    fromBadge.innerText = Math.max(0, parseInt(fromBadge.innerText) - 1);
                    toBadge.innerText = parseInt(toBadge.innerText) + 1;

                    document.getElementById('updateStatusModal').classList.add('hidden');
                } else {
                    alert('Gagal memperbarui status.');
                    cancelStatusUpdate();
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan jaringan.');
                cancelStatusUpdate();
            });
        }

        function openHistory(phone) {
            const modal = document.getElementById('historyModal');
            const content = document.getElementById('historyContent');
            modal.classList.remove('hidden');
            content.innerHTML = '<div class="text-center text-sm text-gray-500 py-4 animate-pulse">Menarik data riwayat...</div>';
            
            fetch(`/sales-intelligence/history/${encodeURIComponent(phone)}`)
                .then(res => res.json())
                .then(data => {
                    if(data.data.length === 0) {
                        content.innerHTML = '<div class="text-center text-sm text-gray-500 py-4">Belum ada riwayat.</div>';
                        return;
                    }
                    let html = '';
                    data.data.forEach((item, index) => {
                        const dateObj = new Date(item.created_at);
                        const timeString = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                        const dateString = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                        const isLatest = index === 0;
                        
                        html += `
                        <div class="relative pl-6 pb-6 border-l-2 ${isLatest ? 'border-blue-200' : 'border-gray-200'} last:border-0 last:pb-0">
                            <div class="absolute w-3 h-3 rounded-full -left-[7px] top-1 ${isLatest ? 'bg-blue-500 ring-4 ring-blue-100' : 'bg-gray-300'}"></div>
                            <div class="flex justify-between items-baseline mb-1">
                                <h4 class="font-bold text-sm uppercase tracking-wide ${isLatest ? 'text-gray-900' : 'text-gray-500'}">${item.status_prospek.replace('_', ' ')}</h4>
                                <span class="text-[10px] font-medium text-gray-400">${dateString}, ${timeString}</span>
                            </div>
                            <p class="text-xs ${isLatest ? 'text-gray-600' : 'text-gray-400'} italic bg-gray-50 p-2.5 rounded-lg border border-gray-200 mt-1.5 leading-relaxed">
                                "${item.chat_summary}"
                            </p>
                            ${item.alasan_batal ? `<div class="mt-1 text-[10px] font-bold text-rose-600">Kendala: ${item.alasan_batal.replace('_', ' ')}</div>` : ''}
                        </div>`;
                    });
                    content.innerHTML = html;
                });
        }
        function closeHistory() { document.getElementById('historyModal').classList.add('hidden'); }
    </script>
</x-app-layout>