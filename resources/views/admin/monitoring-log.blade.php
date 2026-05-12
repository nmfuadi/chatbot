<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">📊 Log Monitoring Harian</h2>
    </x-slot>

    <div class="py-12 px-4 max-w-7xl mx-auto space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                <p class="text-xs font-bold text-gray-400 uppercase">Total Chat (Harian)</p>
                <p class="text-3xl font-black text-indigo-600">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                <p class="text-xs font-bold text-gray-400 uppercase">Avg Response AI</p>
                <p class="text-3xl font-black text-green-600">{{ $avgLatency }}s</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                <p class="text-xs font-bold text-gray-400 uppercase">Tokens Used</p>
                <p class="text-3xl font-black text-orange-500">{{ number_format($stats['tokens']) }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                <p class="text-xs font-bold text-gray-400 uppercase">Total Error</p>
                <p class="text-3xl font-black text-red-600">{{ $stats['error'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                    <span class="font-bold text-gray-700">15 Aktivitas Terakhir</span>
                    <button onclick="window.location.reload()" class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-md font-bold uppercase hover:bg-indigo-200 transition">
                        🔄 Refresh Log
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 text-xs">
                            <tr>
                                <th class="px-4 py-3 text-left">Waktu</th>
                                <th class="px-4 py-3 text-left">Instance</th>
                                <th class="px-4 py-3 text-left">Phone</th>
                                <th class="px-4 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($stats['logs'] as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-gray-500">{{ \Carbon\Carbon::parse($log['time'])->format('H:i:s') }}</td>
                                <td class="px-4 py-3 font-bold text-gray-700">{{ $log['instance'] ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ isset($log['phone']) ? explode('@', $log['phone'])[0] : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if(isset($log['status']) && $log['status'] == 'success')
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-green-100 text-green-700">
                                            Sukses ({{ round(($log['latency'] ?? 0)/1000, 1) }}s)
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-red-100 text-red-700" title="{{ $log['error'] ?? 'Unknown Error' }}">
                                            Error
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400 italic">Belum ada chat hari ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b bg-gray-50 font-bold text-gray-700">Manajemen Arsip Log</div>
                <div class="p-2 max-h-[500px] overflow-y-auto">
                    @forelse($logFiles as $file)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                        <div class="flex flex-col">
                            <span class="text-sm font-mono text-gray-600">{{ $file }}</span>
                            @if($file === "bot-{$today}.log")
                                <span class="text-[10px] text-indigo-500 font-bold uppercase tracking-widest mt-1">File Aktif Hari Ini</span>
                            @endif
                        </div>
                        
                        @if($file !== "bot-{$today}.log")
                        <form action="{{ route('admin.monitor.delete', $file) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus file log {{ $file }} secara permanen?')" class="p-2 text-gray-400 hover:text-red-600 transition" title="Hapus Log">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <div class="p-4 text-center text-sm text-gray-400">Tidak ada arsip log.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>