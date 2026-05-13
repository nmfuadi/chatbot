<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
            👋 {{ __('Selamat Datang, Admin!') }}
        </h2>
    </x-slot>

    <div class="py-10 px-4 max-w-7xl mx-auto space-y-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center space-x-4 transition hover:shadow-md">
                <div class="p-4 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Total Member</p>
                    <p class="text-3xl font-black text-gray-800">{{ $totalMembers }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center space-x-4 transition hover:shadow-md">
                <div class="p-4 bg-green-50 text-green-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Member Baru (Bulan Ini)</p>
                    <p class="text-3xl font-black text-gray-800">+{{ $newMembersThisMonth }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center space-x-4 transition hover:shadow-md">
                <div class="p-4 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Chat Bot Hari Ini</p>
                    <p class="text-3xl font-black text-gray-800">{{ number_format($totalMessagesToday) }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center space-x-4 transition hover:shadow-md">
                <div class="p-4 bg-red-50 text-red-600 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Bot Error Hari Ini</p>
                    <p class="text-3xl font-black {{ $totalErrorsToday > 0 ? 'text-red-600' : 'text-gray-800' }}">{{ $totalErrorsToday }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-6 text-white shadow-md">
                    <h3 class="font-bold text-lg mb-4">🚀 Pusat Pemantauan Sistem</h3>
                    <p class="text-sm text-gray-400 mb-6">Akses cepat ke seluruh infrastruktur platform Anda.</p>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.monitor.logs') }}" class="flex items-center justify-between bg-gray-800 hover:bg-gray-700 p-3 rounded-xl border border-gray-700 transition">
                            <span class="flex items-center text-sm font-medium"><span class="text-xl mr-3">📊</span> Lalu Lintas Chat (Log)</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ route('ai.monitor') }}" class="flex items-center justify-between bg-gray-800 hover:bg-gray-700 p-3 rounded-xl border border-gray-700 transition">
                            <span class="flex items-center text-sm font-medium"><span class="text-xl mr-3">🧠</span> Pemantauan Otak AI (Groq)</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ route('wa.monitor') }}" class="flex items-center justify-between bg-gray-800 hover:bg-gray-700 p-3 rounded-xl border border-gray-700 transition">
                            <span class="flex items-center text-sm font-medium"><span class="text-xl mr-3">🟢</span> Status WhatsApp Member</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ route('server.monitor') }}" class="flex items-center justify-between bg-gray-800 hover:bg-gray-700 p-3 rounded-xl border border-gray-700 transition">
                            <span class="flex items-center text-sm font-medium"><span class="text-xl mr-3">🖥️</span> Kesehatan Server (VPS)</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-gray-800">Daftar Pendaftar Terbaru</h3>
                    <a href="{{ route('admin.members') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                            <tr>
                                <th class="px-6 py-4 font-bold">Nama / Email</th>
                                <th class="px-6 py-4 font-bold">Tanggal Daftar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentMembers as $member)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $member->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $member->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-semibold">
                                        {{ $member->created_at->diffForHumans() }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="px-6 py-10 text-center text-gray-400 italic">Belum ada member yang mendaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>