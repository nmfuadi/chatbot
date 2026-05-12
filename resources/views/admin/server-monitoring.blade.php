<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('🖥️ Kesehatan Infrastruktur (VPS & Docker)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                @foreach([$server1, $server2] as $server)
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $server['name'] }}</h3>
                            <p class="text-sm text-gray-500 font-mono">{{ $server['ip'] }}</p>
                        </div>
                        <div>
                            @if($server['status'] == 'online')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                    <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full animate-pulse"></span> ONLINE
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                    <span class="w-2 h-2 mr-1.5 bg-red-500 rounded-full"></span> OFFLINE
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        @if($server['status'] == 'online')
                            <div class="text-sm text-gray-600 mb-6 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $server['uptime'] }}
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-semibold text-gray-700">CPU Usage</span>
                                        <span class="font-bold text-indigo-600">{{ $server['cpu'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $server['cpu'] }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-semibold text-gray-700">RAM Memory</span>
                                        <span class="font-bold {{ $server['ram_percent'] > 80 ? 'text-red-600' : 'text-blue-600' }}">{{ $server['ram_used'] }}MB / {{ $server['ram_total'] }}MB ({{ $server['ram_percent'] }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="h-2.5 rounded-full transition-all duration-500 {{ $server['ram_percent'] > 80 ? 'bg-red-500' : 'bg-blue-500' }}" style="width: {{ $server['ram_percent'] }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-semibold text-gray-700">Disk Storage (/)</span>
                                        <span class="font-bold text-purple-600">{{ $server['disk_used'] }} / {{ $server['disk_total'] }} ({{ $server['disk_percent'] }}%)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-purple-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $server['disk_percent'] }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 border-t pt-5">
                                <h4 class="text-sm font-bold text-gray-800 mb-3 uppercase tracking-wider">📦 Docker Containers</h4>
                                <ul class="space-y-2">
                                    @forelse($server['docker'] as $container)
                                        <li class="flex items-center justify-between text-sm bg-gray-50 p-2 rounded border">
                                            <span class="font-medium text-gray-700">{{ $container['name'] }}</span>
                                            @if($container['state'] == 'running')
                                                <span class="text-green-600 text-xs font-bold bg-green-100 px-2 py-0.5 rounded uppercase">Running</span>
                                            @else
                                                <span class="text-red-600 text-xs font-bold bg-red-100 px-2 py-0.5 rounded uppercase">{{ $container['state'] }}</span>
                                            @endif
                                        </li>
                                    @empty
                                        <li class="text-sm text-gray-500 italic">Tidak ada Docker container.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-10">
                                <svg class="w-16 h-16 text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p class="text-gray-500 font-medium">Gagal terhubung ke server.</p>
                                <p class="text-xs text-red-500 mt-2">{{ $server['error'] ?? 'Periksa IP, Username, atau Password di .env' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>