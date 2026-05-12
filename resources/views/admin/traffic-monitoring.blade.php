<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📈 Lalu Lintas & Performa Pesan (n8n & AI)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center transition hover:shadow-md">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pesan Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($todayLogs) }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center transition hover:shadow-md">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Rata-rata Respons AI</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $avgResponseSec }} <span class="text-base text-gray-500 font-normal">detik</span></p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center transition hover:shadow-md">
                    <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Gagal Terkirim</p>
                        <p class="text-3xl font-bold {{ $todayErrors > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ number_format($todayErrors) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Rasio Keberhasilan Hari Ini</h3>
                    <div class="relative h-48 w-full">
                        <canvas id="successRateChart"></canvas>
                    </div>
                    <div class="mt-4 text-center text-sm text-gray-500">
                        Membandingkan pesan yang berhasil dibalas AI vs pesan yang mengalami error sistem.
                    </div>
                </div>
                
                <div class="lg:col-span-2 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-sm border border-gray-100 p-6 text-white flex flex-col justify-center relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-2">Pantau Performa Bot Anda!</h3>
                        <p class="text-indigo-100 mb-4 max-w-lg">
                            Kecepatan respons AI yang ideal adalah di bawah <strong>3.0 detik</strong>. Jika rata-rata waktu melebihi angka tersebut, pertimbangkan untuk menyederhanakan <i>prompt</i> di Groq AI atau mengecek koneksi internet server.
                        </p>
                    </div>
                    <svg class="absolute right-0 bottom-0 opacity-10 w-64 h-64 transform translate-x-10 translate-y-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm border border-gray-100 sm:rounded-xl">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">10 Riwayat Pesan Terakhir</h3>
                    <button onclick="window.location.reload()" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Refresh Data
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tujuan (No HP)</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin Bot</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kecepatan (AI)</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($recentLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $log->created_at->format('H:i:s') }}
                                        <span class="text-xs text-gray-400 block">{{ $log->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 font-mono">📞 {{ $log->phone_number ?? 'Unknown' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $log->instance_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($log->processing_time_ms)
                                            <span class="font-medium {{ $log->processing_time_ms > 4000 ? 'text-yellow-600' : 'text-green-600' }}">
                                                {{ number_format($log->processing_time_ms / 1000, 2) }}s
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($log->status === 'success')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                Sukses
                                            </span>
                                        @else
                                            <div class="flex flex-col items-start">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200 mb-1">
                                                    Gagal
                                                </span>
                                                <span class="text-xs text-red-500" style="max-width: 200px; white-space: normal; line-height: 1.2;">
                                                    {{ Str::limit($log->error_message, 50) }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        <span class="italic">Belum ada riwayat pesan yang terekam hari ini.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('successRateChart').getContext('2d');
            
            // Ambil data PHP ke variabel JS
            const successCount = {{ $todayLogs - $todayErrors }};
            const errorCount = {{ $todayErrors }};

            // Jika belum ada data sama sekali, tampilkan grafik kosong (abu-abu)
            const hasData = (successCount > 0 || errorCount > 0);
            
            const data = {
                labels: ['Berhasil', 'Gagal/Error'],
                datasets: [{
                    data: hasData ? [successCount, errorCount] : [1, 0],
                    backgroundColor: hasData ? ['#10B981', '#EF4444'] : ['#E5E7EB', '#E5E7EB'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            };

            const config = {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            enabled: hasData // Matikan tooltip jika tidak ada data
                        }
                    }
                }
            };

            new Chart(ctx, config);
        });
    </script>
</x-app-layout>