<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Sales Pipeline - Intelligent CRM</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sortable-ghost { opacity: 0.4; border: 2px dashed #94a3b8; background-color: #f8fafc; }
        .sortable-drag { cursor: grabbing !important; }
        
        /* Scrollbar Horizontal Custom */
        .custom-scrollbar::-webkit-scrollbar {
            height: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1; 
            border-radius: 20px; 
            border: 3px solid #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8; 
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 h-screen flex flex-col overflow-hidden">

    <header class="bg-white border-b border-slate-200 px-6 py-5 flex-shrink-0 z-10 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">✨ AI Sales Pipeline</h1>
                <p class="text-sm text-slate-500 mt-1">Pantau analitik prospek, evaluasi kinerja iklan, dan ambil alih chat kapan saja.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 flex justify-between items-center shadow-sm">
                <span class="text-sm font-semibold text-slate-500">Total Leads</span>
                <span class="text-2xl font-black text-slate-900">{{ number_format($totalLeads) }}</span>
            </div>
            <div class="bg-amber-50 p-4 rounded-xl border border-amber-200 flex justify-between items-center shadow-sm">
                <span class="text-sm font-semibold text-amber-700">Hot Prospek</span>
                <span class="text-2xl font-black text-amber-600">{{ number_format($hotLeads) }}</span>
            </div>
            <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200 flex justify-between items-center shadow-sm">
                <span class="text-sm font-semibold text-emerald-700">Closing</span>
                <span class="text-2xl font-black text-emerald-600">{{ number_format($closingLeads) }}</span>
            </div>
            <div class="bg-rose-50 p-4 rounded-xl border border-rose-200 flex justify-between items-center shadow-sm">
                <span class="text-sm font-semibold text-rose-700">Gagal / Lost</span>
                <span class="text-2xl font-black text-rose-600">{{ number_format($gagalLeads) }}</span>
            </div>
        </div>
    </header>

    @php
        $columns = [
            'baru' => ['title' => 'Baru Masuk', 'color' => 'slate', 'icon' => '📥'],
            'tanya_harga' => ['title' => 'Tanya Harga', 'color' => 'blue', 'icon' => '💬'],
            'hot_prospek' => ['title' => 'Hot Prospek', 'color' => 'amber', 'icon' => '🔥'],
            'closing' => ['title' => 'Closing', 'color' => 'emerald', 'icon' => '💰'],
            'gagal' => ['title' => 'Gagal / Batal', 'color' => 'rose', 'icon' => '❌'],
        ];
    @endphp

    <main class="flex-1 overflow-x-auto custom-scrollbar p-6 bg-slate-100/50">
        <div class="flex gap-6 h-full items-start w-max pr-6 pb-4">
            
            @foreach($columns as $statusKey => $col)
            <div class="flex-shrink-0 w-80 bg-{{ $col['color'] }}-50/60 rounded-xl border border-{{ $col['color'] }}-200 flex flex-col max-h-full shadow-sm">
                
                <div class="p-3.5 border-b border-{{ $col['color'] }}-200 flex justify-between items-center bg-white/50 rounded-t-xl sticky top-0 z-10">
                    <h3 class="font-bold text-{{ $col['color'] }}-700 flex items-center gap-2">
                        <span>{{ $col['icon'] }}</span> {{ $col['title'] }}
                    </h3>
                    <span class="bg-white border border-{{ $col['color'] }}-200 text-{{ $col['color'] }}-700 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm column-counter">
                        {{ isset($leads[$statusKey]) ? count($leads[$statusKey]) : 0 }}
                    </span>
                </div>
                
                <div id="col-{{ $statusKey }}" data-status="{{ $statusKey }}" class="kanban-column p-3 flex-1 overflow-y-auto space-y-3 min-h-[250px]">
                    @if(isset($leads[$statusKey]))
                        @foreach($leads[$statusKey] as $lead)
                        
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 cursor-grab hover:border-blue-400 hover:shadow-md transition relative group" 
                             data-id="{{ $lead->id }}" 
                             data-phone="{{ $lead->phone }}"
                             data-summary="{{ $lead->chat_summary }}">
                            
                            <div class="flex justify-between items-start mb-2.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                    🏷️ {{ $lead->sumber_iklan }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium bg-slate-50 px-1.5 py-0.5 rounded">
                                    {{ $lead->created_at->diffForHumans(null, true, true) }}
                                </span >
                            </div>
                            
                            <h4 class="font-bold text-slate-900 tracking-tight text-lg">{{ str_replace('@s.whatsapp.net', '', $lead->phone) }}</h4>
                            <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1 font-medium">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                {{ $lead->instance }}
                            </p>

                            <div class="mt-3.5 p-2.5 bg-slate-50/80 rounded-lg border border-slate-100 relative">
                                <span class="absolute -top-2 -left-1 text-lg">🤖</span>
                                <p class="text-[11px] text-slate-600 leading-relaxed italic pl-3 summary-text">
                                    "{{ $lead->chat_summary ?? 'Belum ada kesimpulan...' }}"
                                </p>
                            </div>

                            <div class="mt-3.5">
                                <div class="flex justify-between items-center mb-1.5">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Skor AI</span>
                                    <span class="text-[10px] font-black {{ $lead->lead_score > 70 ? 'text-emerald-600' : ($lead->lead_score > 40 ? 'text-amber-600' : 'text-slate-400') }}">
                                        {{ $lead->lead_score ?? 0 }}%
                                    </span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full transition-all duration-500 {{ $lead->lead_score > 70 ? 'bg-emerald-500' : ($lead->lead_score > 40 ? 'bg-amber-400' : 'bg-slate-300') }}" style="width: {{ $lead->lead_score ?? 0 }}%"></div>
                                </div>
                            </div>

                            <div class="alasan-container mt-3.5 text-[11px] bg-rose-50 border border-rose-100 text-rose-700 px-2.5 py-2 rounded-lg flex items-start gap-1.5 font-medium {{ $lead->alasan_batal && $statusKey == 'gagal' ? '' : 'hidden' }}">
                                <span class="text-rose-500">⚠️</span> <span class="alasan-text">{{ $lead->alasan_batal }}</span>
                            </div>

                            <div class="mt-3.5 grid grid-cols-2 gap-2 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all">
                                <button onclick="openHistory('{{ $lead->phone }}')" class="flex items-center justify-center gap-1 w-full bg-white border-2 border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 text-[11px] py-1.5 rounded-lg font-bold transition-colors">
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
    </main>

    <div id="updateStatusModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl z-10 p-6 relative transform transition-all">
            <h3 class="font-bold text-slate-900 text-lg mb-2">📝 Perbarui Informasi Prospek</h3>
            <p class="text-xs text-slate-400 mb-4">Lengkapi data di bawah ini untuk disimpan langsung ke database analitik.</p>
            
            <form id="updateStatusForm" onsubmit="submitStatusUpdate(event)">
                <input type="hidden" id="modal-lead-id">
                <input type="hidden" id="modal-status-target">

                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Chat Summary / Catatan Terakhir</label>
                    <textarea id="modal-chat-summary" required rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50" placeholder="Tulis ringkasan obrolan atau progres terakhir pelanggan..."></textarea>
                </div>

                <div id="modal-alasan-container" class="mb-5 hidden">
                    <label class="block text-xs font-bold text-rose-700 uppercase tracking-wider mb-2">⚠️ Alasan Batal / Kendala Utama</label>
                    <select id="modal-alasan-batal" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 bg-white">
                        <option value="budget_kurang">Budget Kurang / Kemahalan</option>
                        <option value="ongkir_mahal">Ongkos Kirim Mahal</option>
                        <option value="kompetitor">Pindah ke Kompetitor</option>
                        <option value="slow_respon">Respon Pelanggan Hilang (Ghosting)</option>
                        <option value="lainnya">Alasan Lainnya</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                    <button type="button" onclick="cancelStatusUpdate()" class="px-4 py-2 text-sm font-semibold text-slate-500 bg-slate-100 hover:bg-slate-200 rounded-lg transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">Simpan & Pindahkan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="historyModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeHistory()"></div>
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl z-10 flex flex-col max-h-[80vh] overflow-hidden transform transition-all relative">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-lg">Riwayat Analitik AI</h3>
                <button onclick="closeHistory()" class="text-slate-400 hover:text-rose-500 font-bold text-xl">&times;</button>
            </div>
            <div id="historyContent" class="p-6 overflow-y-auto flex-1 space-y-0"></div>
        </div>
    </div>

    <script>
        let currentDragEvent = null;

        document.addEventListener('DOMContentLoaded', function () {
            const columns = document.querySelectorAll('.kanban-column');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

                        // Simpan event seret ke variabel global agar bisa dibatalkan jika pengguna membatalkan form
                        currentDragEvent = evt;

                        const itemEl = evt.item;
                        const leadId = itemEl.getAttribute('data-id');
                        const statusTarget = evt.to.getAttribute('data-status');
                        const existingSummary = itemEl.getAttribute('data-summary');

                        // Buka jendela popup modal untuk mengisi data summary & alasan
                        openUpdateModal(leadId, statusTarget, existingSummary);
                    },
                });
            });
        });

        function openUpdateModal(leadId, statusTarget, existingSummary) {
            document.getElementById('modal-lead-id').value = leadId;
            document.getElementById('modal-status-target').value = statusTarget;
            document.getElementById('modal-chat-summary').value = existingSummary || '';
            
            // Jika digeser ke kolom GAGAL, munculkan input alasan batal
            const alasanContainer = document.getElementById('modal-alasan-container');
            const alasanSelect = document.getElementById('modal-alasan-batal');
            if(statusTarget === 'gagal') {
                alasanContainer.classList.remove('hidden');
                alasanSelect.setAttribute('required', 'required');
            } else {
                alasanContainer.classList.add('hidden');
                alasanSelect.removeAttribute('required');
            }

            document.getElementById('updateStatusModal').classList.remove('hidden');
        }

        function cancelStatusUpdate() {
            // Kembalikan posisi kartu ke kolom semula di UI jika batal mengisi form
            if (currentDragEvent) {
                currentDragEvent.from.appendChild(currentDragEvent.item);
            }
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

            // Kirim data lengkap ke Laravel
            fetch('{{ route('sales.update-status') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: id,
                    status_prospek: status_prospek,
                    chat_summary: chat_summary,
                    alasan_batal: alasan_batal
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Update tampilan teks di dalam kartu secara real-time tanpa refresh halaman
                    cardEl.setAttribute('data-summary', chat_summary);
                    cardEl.querySelector('.summary-text').innerText = `"${chat_summary}"`;
                    
                    const alasanBox = cardEl.querySelector('.alasan-container');
                    if(status_prospek === 'gagal' && alasan_batal) {
                        alasanBox.classList.remove('hidden');
                        cardEl.querySelector('.alasan-text').innerText = alasan_batal.replace('_', ' ');
                    } else {
                        alasanBox.classList.add('hidden');
                    }

                    // Update Badge Counter Kolom Atas
                    const fromBadge = currentDragEvent.from.previousElementSibling.querySelector('.column-counter');
                    const toBadge = currentDragEvent.to.previousElementSibling.querySelector('.column-counter');
                    fromBadge.innerText = Math.max(0, parseInt(fromBadge.innerText) - 1);
                    toBadge.innerText = parseInt(toBadge.innerText) + 1;

                    document.getElementById('updateStatusModal').classList.add('hidden');
                } else {
                    alert('Gagal memperbarui status data.');
                    cancelStatusUpdate();
                }
            })
            .catch(err => {
                alert('Terjadi kendala jaringan.');
                cancelStatusUpdate();
            });
        }

        // FUNGSI MODAL HISTORY
        function openHistory(phone) {
            const modal = document.getElementById('historyModal');
            const content = document.getElementById('historyContent');
            modal.classList.remove('hidden');
            content.innerHTML = '<div class="text-center text-sm text-slate-500 py-4 animate-pulse">Menarik data riwayat...</div>';
            
            fetch(`/sales-intelligence/history/${encodeURIComponent(phone)}`)
                .then(res => res.json())
                .then(data => {
                    if(data.data.length === 0) {
                        content.innerHTML = '<div class="text-center text-sm text-slate-500 py-4">Belum ada riwayat.</div>';
                        return;
                    }
                    let html = '';
                    data.data.forEach((item, index) => {
                        const dateObj = new Date(item.created_at);
                        const timeString = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                        const dateString = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                        const isLatest = index === 0;
                        
                        html += `
                        <div class="relative pl-6 pb-6 border-l-2 ${isLatest ? 'border-blue-200' : 'border-slate-100'} last:border-0 last:pb-0">
                            <div class="absolute w-3 h-3 rounded-full -left-[7px] top-1 ${isLatest ? 'bg-blue-500 ring-4 ring-blue-100' : 'bg-slate-300'}"></div>
                            <div class="flex justify-between items-baseline mb-1">
                                <h4 class="font-bold text-sm uppercase tracking-wide ${isLatest ? 'text-slate-900' : 'text-slate-500'}">${item.status_prospek.replace('_', ' ')}</h4>
                                <span class="text-[10px] font-medium text-slate-400">${dateString}, ${timeString}</span>
                            </div>
                            <p class="text-xs ${isLatest ? 'text-slate-600' : 'text-slate-400'} italic bg-slate-50 p-2.5 rounded-lg border border-slate-100 mt-1.5 leading-relaxed">
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
</body>
</html>