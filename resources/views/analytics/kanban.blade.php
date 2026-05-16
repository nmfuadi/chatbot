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

                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}" target="_blank" class="mt-3.5 flex items-center justify-center gap-1.5 w-full bg-white border-2 border-blue-100 text-blue-600 hover:bg-blue-50 hover:border-blue-200 text-xs py-2 rounded-lg transition-all font-bold opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                Takeover Chat
                            </a>
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
</body>
</html>