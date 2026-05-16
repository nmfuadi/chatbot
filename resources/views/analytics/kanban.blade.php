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
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 h-screen flex flex-col overflow-hidden">

    <header class="bg-white border-b border-slate-200 px-6 py-5 flex-shrink-0 z-10 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">✨ AI Sales Pipeline</h1>
                <p class="text-sm text-slate-500 mt-1">Pantau analitik prospek, evaluasi kinerja iklan, dan ambil alih chat kapan saja.</p>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium hover:bg-slate-50 transition shadow-sm">
                    Export Data
                </button>
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
        // Konfigurasi Kolom Kanban
        $columns = [
            'baru' => ['title' => 'Baru Masuk', 'color' => 'slate', 'icon' => '📥'],
            'tanya_harga' => ['title' => 'Tanya Harga', 'color' => 'blue', 'icon' => '💬'],
            'hot_prospek' => ['title' => 'Hot Prospek', 'color' => 'amber', 'icon' => '🔥'],
            'closing' => ['title' => 'Closing', 'color' => 'emerald', 'icon' => '💰'],
            'gagal' => ['title' => 'Gagal', 'color' => 'rose', 'icon' => '❌'],
        ];
    @endphp

    <main class="flex-1 overflow-x-auto no-scrollbar p-6 bg-slate-100/50">
        <div class="flex gap-6 h-full items-start">
            
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
                        
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 cursor-grab hover:border-blue-400 hover:shadow-md transition relative group" data-id="{{ $lead->id }}">
                            
                            <div class="flex justify-between items-start mb-2.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                    🏷️ {{ $lead->sumber_iklan }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium bg-slate-50 px-1.5 py-0.5 rounded">
                                    {{ $lead->created_at->diffForHumans(null, true, true) }}
                                </span>
                            </div>
                            
                            <h4 class="font-bold text-slate-900 tracking-tight text-lg">{{ $lead->phone }}</h4>
                            <p class="text-[10px] text-slate-400 mt-1 flex items-center gap-1 font-medium">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                {{ $lead->instance }}
                            </p>

                            <div class="mt-3.5 p-2.5 bg-slate-50/80 rounded-lg border border-slate-100 relative">
                                <span class="absolute -top-2 -left-1 text-lg">🤖</span>
                                <p class="text-[11px] text-slate-600 leading-relaxed italic pl-3">
                                    "{{ $lead->chat_summary ?? 'Menunggu kesimpulan AI...' }}"
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

                            @if($lead->alasan_batal && $statusKey == 'gagal')
                                <div class="mt-3.5 text-[11px] bg-rose-50 border border-rose-100 text-rose-700 px-2.5 py-2 rounded-lg flex items-start gap-1.5 font-medium">
                                    <span class="text-rose-500">⚠️</span> {{ $lead->alasan_batal }}
                                </div>
                            @endif

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const columns = document.querySelectorAll('.kanban-column');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            columns.forEach(col => {
                new Sortable(col, {
                    group: 'shared',
                    animation: 200, // Transisi lebih halus
                    ghostClass: 'sortable-ghost',
                    dragClass: 'sortable-drag',
                    delay: 50, // Mencegah drag tidak sengaja saat scroll
                    delayOnTouchOnly: true,

                    onEnd: function (evt) {
                        // Jika posisi kartu tidak pindah kolom, tidak usah proses
                        if(evt.from === evt.to) return; 

                        const itemEl = evt.item;
                        const prospekId = itemEl.getAttribute('data-id');
                        const statusBaru = evt.to.getAttribute('data-status');
                        
                        // Update angka badge counter di UI secara otomatis
                        const fromBadge = evt.from.previousElementSibling.querySelector('.column-counter');
                        const toBadge = evt.to.previousElementSibling.querySelector('.column-counter');
                        if (fromBadge && toBadge) {
                            fromBadge.innerText = parseInt(fromBadge.innerText) - 1;
                            toBadge.innerText = parseInt(toBadge.innerText) + 1;
                        }

                        // Kirim data update ke Laravel via Fetch API
                        fetch('{{ route('sales.update-status') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                id: prospekId,
                                status_prospek: statusBaru
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(!data.success) {
                                alert('Oops! Gagal menyimpan pembaruan status ke server.');
                                // Kembalikan kartu jika gagal
                                evt.from.appendChild(itemEl);
                            } else {
                                console.log('Sukses: ' + data.message);
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            alert('Terjadi kesalahan jaringan.');
                            // Kembalikan kartu jika gagal koneksi
                            evt.from.appendChild(itemEl);
                        });
                    },
                });
            });
        });
    </script>

<div id="historyModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeHistory()"></div>
        
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl z-10 flex flex-col max-h-[80vh] overflow-hidden transform transition-all relative">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-lg">Riwayat Analitik AI</h3>
                <button onclick="closeHistory()" class="text-slate-400 hover:text-rose-500 font-bold text-xl">&times;</button>
            </div>
            
            <div id="historyContent" class="p-6 overflow-y-auto flex-1 space-y-0">
                </div>
        </div>
    </div>

    <script>
        function openHistory(phone) {
            // Tampilkan modal
            const modal = document.getElementById('historyModal');
            const content = document.getElementById('historyContent');
            modal.classList.remove('hidden');
            content.innerHTML = '<div class="text-center text-sm text-slate-500 py-4 font-medium animate-pulse">Menarik data riwayat...</div>';
            
            // Ambil data dari Laravel
            fetch(`/sales-intelligence/history/${encodeURIComponent(phone)}`)
                .then(res => res.json())
                .then(data => {
                    if(data.data.length === 0) {
                        content.innerHTML = '<div class="text-center text-sm text-slate-500 py-4">Belum ada riwayat.</div>';
                        return;
                    }

                    let html = '';
                    data.data.forEach((item, index) => {
                        // Formatting tanggal
                        const dateObj = new Date(item.created_at);
                        const timeString = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                        const dateString = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                        
                        // Menentukan warna titik timeline berdasarkan status terbaru
                        const isLatest = index === 0;
                        const dotColor = isLatest ? 'bg-blue-500 ring-4 ring-blue-100' : 'bg-slate-300';
                        const textColor = isLatest ? 'text-slate-900' : 'text-slate-500';

                        html += `
                        <div class="relative pl-6 pb-6 border-l-2 ${isLatest ? 'border-blue-200' : 'border-slate-100'} last:border-0 last:pb-0">
                            <div class="absolute w-3 h-3 rounded-full -left-[7px] top-1 ${dotColor}"></div>
                            
                            <div class="flex justify-between items-baseline mb-1">
                                <h4 class="font-bold text-sm uppercase tracking-wide ${textColor}">${item.status_prospek.replace('_', ' ')}</h4>
                                <span class="text-[10px] font-medium text-slate-400">${dateString}, ${timeString}</span>
                            </div>
                            
                            <p class="text-xs ${isLatest ? 'text-slate-600' : 'text-slate-400'} italic bg-slate-50 p-2.5 rounded-lg border border-slate-100 mt-1.5 leading-relaxed">
                                "${item.chat_summary}"
                            </p>
                            
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md ${item.lead_score > 70 ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600'}">
                                    Suhu Prospek: ${item.lead_score}%
                                </span>
                            </div>
                        </div>
                        `;
                    });
                    content.innerHTML = html;
                })
                .catch(err => {
                    content.innerHTML = '<div class="text-center text-sm text-rose-500 py-4">Gagal memuat riwayat jaringan.</div>';
                });
        }

        function closeHistory() {
            document.getElementById('historyModal').classList.add('hidden');
        }
    </script>
</body>
</html>