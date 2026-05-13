<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                👥 {{ __('Manajemen Member') }}
            </h2>
            <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                Total: {{ $members->count() }} Member
            </span>
        </div>
    </x-slot>

    <div class="py-10 px-4 max-w-7xl mx-auto">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-bold">Info Member</th>
                            <th class="px-6 py-4 font-bold">Status Akun</th>
                            <th class="px-6 py-4 font-bold">Paket Berlangganan</th>
                            <th class="px-6 py-4 font-bold">Koneksi WA (Instance)</th>
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($members as $member)
                            @php
                                // Ambil langganan terakhir
                                $latestSub = $member->subscriptions->first();
                                $isActivePlan = $latestSub && $latestSub->status === 'active' && \Carbon\Carbon::parse($latestSub->ends_at)->isFuture();
                            @endphp
                            
                            <tr class="hover:bg-gray-50/50 transition-colors" x-data="{ openEdit: false }">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-bold text-gray-900 text-base">{{ $member->name }}</div>
                                            <div class="text-gray-500 text-xs">{{ $member->email }}</div>
                                            <div class="text-indigo-600 font-mono text-xs mt-0.5 font-semibold">{{ $member->whatsapp_number ?? '-' }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-[10px] text-gray-400 font-medium">Bergabung: {{ $member->created_at->format('d M Y') }}</div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($member->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-600">
                                            Pending/Nonaktif
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if($latestSub)
                                        <div class="font-bold text-gray-800">{{ $latestSub->plan->name ?? 'Paket Unknown' }}</div>
                                        
                                        @if($isActivePlan)
                                            <div class="text-xs text-green-600 font-medium mt-1">Aktif s/d {{ \Carbon\Carbon::parse($latestSub->ends_at)->format('d M Y') }}</div>
                                            <div class="text-[10px] text-gray-500 mt-0.5">Pemakaian: {{ number_format($latestSub->usage_count) }} chat</div>
                                        @else
                                            <div class="inline-flex mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">Expired</div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 italic">Belum Pernah Langganan</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if(!empty($member->wablas_device_id))
                                        <div class="font-mono text-xs font-bold text-gray-700 bg-gray-100 px-2 py-1 rounded inline-block">{{ $member->wablas_device_id }}</div>
                                        <div class="text-[10px] text-green-600 font-bold mt-1">✓ Instance Disetup</div>
                                    @else
                                        <span class="text-xs text-rose-500 font-semibold bg-rose-50 px-2 py-1 rounded">Belum Setup Instance</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <button @click="openEdit = !openEdit" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm bg-indigo-50 px-3 py-1.5 rounded-lg transition">
                                        Edit / Setup WA
                                    </button>

                                    <div x-show="openEdit" x-transition class="absolute right-10 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 text-left p-5 overflow-hidden" style="display: none;">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="font-bold text-gray-800">Setup Evolution API</h4>
                                            <button @click="openEdit = false" class="text-gray-400 hover:text-red-500">&times;</button>
                                        </div>
                                        
                                        <form action="{{ route('admin.members.wablas', $member->id) }}" method="POST">
                                            @csrf
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">Instance Name</label>
                                                    <input type="text" name="wablas_device_id" value="{{ $member->wablas_device_id }}" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: 08123456789">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-gray-700 mb-1">API Key (Opsional)</label>
                                                    <input type="text" name="wablas_api_key" value="{{ $member->wablas_api_key }}" class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                </div>
                                                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 rounded-lg text-sm hover:bg-indigo-700 transition">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <p class="text-lg font-medium text-gray-500">Belum ada member yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>