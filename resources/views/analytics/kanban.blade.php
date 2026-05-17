<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('✨Sales Pipeline') }}
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
        .custom-scrollbar::-webkit-scrollbar { height: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; border: 3px solid #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>

    <div class="py-8">
        <div class="w-full mx-auto sm:px-4 lg:px-8">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-center">
                    <span class="text-sm font-semibold text-gray-500">Total Leads</span>
                    <span class="text-3xl font-black text-gray-900">{{ $totalLeads ?? 0 }}</span>
                </div>
                <div class="bg-blue-50 p-5 rounded-xl border border-blue-200 shadow-sm flex flex-col justify-center">
                    <span class="text-sm font-semibold text-blue-700">Prospect</span>
                    <span class="text-3xl font-black text-blue-600">{{ isset($leads['prospect']) ? count($leads['prospect']) : 0 }}</span>
                </div>
                <div class="bg-amber-50 p-5 rounded-xl border border-amber-200 shadow-sm flex flex-col justify-center">
                    <span class="text-sm font-semibold text-amber-700">Hot Prospek</span>
                    <span class="text-3xl font-black text-amber-600">{{ isset($leads['hot_prospek']) ? count($leads['hot_prospek']) : 0 }}</span>
                </div>
                <div class="bg-purple-50 p-5 rounded-xl border border-purple-200 shadow-sm flex flex-col justify-center">
                    <span class="text-sm font-semibold text-purple-700">Deal</span>
                    <span class="text-3xl font-black text-purple-600">{{ isset($leads['deal']) ? count($leads['deal']) : 0 }}</span>
                </div>
                <div class="bg-emerald-50 p-5 rounded-xl border border-emerald-200 shadow-sm flex flex-col justify-center">
                    <span class="text-sm font-semibold text-emerald-700">Closing</span>
                    <span class="text-3xl font-black text-emerald-600">{{ isset($leads['closing']) ? count($leads['closing']) : 0 }}</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                
                @php
                    // Struktur 6 Kolom Utama Sesuai Aturan AI Baru
                    $columns = [
                        'baru'        => ['title' => 'Baru Masuk', 'color' => 'slate', 'icon' => '📥'],
                        'prospect'    => ['title' => 'Prospect', 'color' => 'blue', 'icon' => '💬'],
                        'hot_prospek' => ['title' => 'Hot Prospek', 'color' => 'amber', 'icon' => '🔥'],
                        'deal'        => ['title' => 'Deal', 'color' => 'purple', 'icon' => '🤝'],
                        'closing'     => ['title' => 'Closing', 'color' => 'emerald', 'icon' => '💰'],
                        'gagal'       => ['title' => 'Gagal / Batal', 'color' => 'rose', 'icon' => '❌'],
                    ];
                @endphp

                <div class="overflow-x-auto custom-scrollbar p-6 bg-gray-50/50">
                    <div class="flex gap-6 items-start w-max pr-6 pb-4 min-h-[60vh]">
                        
                        @foreach($columns as $statusKey => $col)
                        <div class="flex-shrink-0 w-80 bg-{{ $col['color'] }}-50/60 rounded-xl border border-{{ $col['color'] }}-200 flex flex-col shadow-sm">
                            
                            <div class="p-3.5 border-b border-{{ $col['color'] }}-200 flex justify-between items-center bg-white/70 rounded-t-xl">
                                <h3 class="font-bold text-{{ $col['color'] }}-700 flex items-center gap-2">
                                    <span>{{ $col['icon'] }}</span> {{ $col['title'] }}
                                </h3>
                                <span class="bg-white border border-{{ $col['color'] }}-200 text-{{ $col['color'] }}-700 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm column-counter">
                                    {{ isset($leads[$statusKey]) ? count($leads[$statusKey]) : 0 }}
                                </span>
                            </div>
                            
                            <div id="col-{{ $statusKey }}" data-status="{{ $statusKey }}" class="kanban-column p-3 flex-1 overflow-y-auto space-y-3 min-h-[150px]">
                                @if(isset($leads[$statusKey]))
                                    @foreach($leads[$statusKey] as $lead)
                                    
                                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 cursor-grab hover:border-blue-400 hover:shadow-md transition relative group" 
                                         data-id="{{ $lead->id }}" 
                                         data-phone="{{ $lead->phone }}"
                                         data-summary="{{ $lead->chat_summary }}">
                                        
                                        <div class="flex justify-between items-start mb-2.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200">
                                                🏷️ {{ $lead->sumber_iklan }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-medium bg-gray-50 px-1.5 py-0.5 rounded">
                                                {{ $lead->created_at->diffForHumans(null, true, true) }}
                                            </span>
                                        </div>
                                        
                                        <h4 class="font-bold text-gray-900 tracking-tight text-lg">{{ str_replace('@s.whatsapp.net', '', $lead->phone) }}</h4>
                                        <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1 font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            {{ $lead->instance }}
                                        </p>

                                        <div class="mt-3.5 p-2.5 bg-gray-50/80 rounded-lg border border-gray-100 relative">
                                            <span class="absolute -top-2 -left-1 text-lg">🤖</span>
                                            <p class="text-[11px] text-gray-600 leading-relaxed italic pl-3 summary-text">
                                                "{{ $lead->chat_summary ?? 'Belum ada kesimpulan...' }}"
                                            </p>
                                        </div>

                                        <div class="mt-3.5">
                                            <div class="flex justify-between items-center mb-1.5">
                                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Skor AI</span>
                                                <span class="text-[10px] font-black {{ $lead->lead_score > 70 ? 'text-emerald-600' : ($lead->lead_score > 40 ? 'text-amber-600' : 'text-gray-400') }}">
                                                    {{ $lead->lead_score ?? 0 }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                                <div class="h-1.5 rounded-full transition-all duration-500 {{ $lead->lead_score > 70 ? 'bg-emerald-500' : ($lead->lead_score > 40 ? 'bg-amber-400' : 'bg-gray-300') }}" style="width: {{ $lead->lead_score ?? 0 }}%"></div>
                                            </div>
                                        </div>

                                        <div class="alasan-container mt-3.5 text-[11px] bg-rose-50 border border-rose-100 text-rose-700 px-2.5 py-2 rounded-lg flex items-start gap-1.5 font-medium {{ $lead->alasan_batal && $statusKey == 'gagal' ? '' : 'hidden' }}">
                                            <span class="text-rose-500">⚠️</span> <span class="alasan-text">{{ $lead->alasan_batal }}</span>
                                        </div>

                                        <div class="mt-3.5 grid grid-cols-2 gap-2 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all">
                                            <button onclick="openHistory('{{ $lead->phone }}')" class="flex items-center justify-center gap-1 w-full bg-white border-2 border-gray-200 text-gray-700 hover:bg-gray-50 hover:border-gray-300 text-[11px] py-1.5 rounded-lg font-bold transition-colors">
                                                📄 History
                                            </button>
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" class="flex items-center justify-center gap-1 w-full bg-blue-50 border-2 border-blue-100 text-blue-700 hover:bg-blue-100 hover:border-blue-200 text-[11px] py-1.5 rounded-lg font-bold transition-colors">
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
    </div>

    <div id="updateStatusModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
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

    <div id="historyModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
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
                alasanInput.value = ''; // Reset nilai
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