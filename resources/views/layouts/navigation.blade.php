@php
    // Deteksi Active State untuk Sub-menu agar grup otomatis terbuka jika sedang diakses
    $isChatAutoActive = request()->routeIs('member.pk', 'catalogs.*', 'customers.index');
    $isSalesActive = request()->routeIs('sales.index'); // Sesuaikan nama route-nya
    $isMonitorActive = request()->routeIs('wa.monitor', 'server.monitor', 'traffic.monitor', 'admin.monitor.logs');
@endphp

<div x-data="{ sidebarOpen: false }" @keydown.window.escape="sidebarOpen = false">

    <div x-show="sidebarOpen" 
         x-transition.opacity
         class="fixed inset-0 z-40 bg-slate-900/70 backdrop-blur-sm lg:hidden" 
         @click="sidebarOpen = false"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-screen shadow-xl">
        
        <div class="flex items-center justify-center h-16 bg-slate-950 border-b border-slate-800 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <span class="text-white font-bold text-xl tracking-wider">🚀 AI CRM</span>
            </a>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
            
            <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 mt-2 px-3">Main Menu</div>
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.members') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.members') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Kelola Member
                </a>
                <a href="{{ route('admin.plans.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.plans.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Manajemen Paket
                </a>

                <div x-data="{ open: {{ $isMonitorActive ? 'true' : 'false' }} }" class="mt-2">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg font-medium transition-colors {{ $isMonitorActive ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Monitoring Server
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity class="mt-1 space-y-1 bg-slate-950/50 rounded-lg p-2">
                        <a href="{{ route('wa.monitor') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('wa.monitor') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Status WhatsApp</a>
                        <a href="{{ route('server.monitor') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('server.monitor') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Resource Server</a>
                        <a href="{{ route('traffic.monitor') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('traffic.monitor') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">n8n & AI Monitor</a>
                        <a href="{{ route('admin.monitor.logs') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.monitor.logs') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Log API</a>
                    </div>
                </div>

            @elseif(Auth::user()->role === 'member')
                <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil & Status
                </a>
                <a href="{{ route('user.invoice.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('user.invoice.index') ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Tagihan
                </a>

                <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-6 mb-3 px-3">Produk & AI</div>

                <a href="{{ route('member.whatsapp.setup') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('member.whatsapp.setup') ? 'bg-emerald-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Setup WhatsApp
                </a>

                <div x-data="{ open: {{ $isChatAutoActive ? 'true' : 'false' }} }" class="mt-2">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg font-medium transition-colors {{ $isChatAutoActive ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                            AI Chat Auto
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity class="mt-1 space-y-1 bg-slate-950/50 rounded-lg p-2">
                        <a href="{{ route('member.pk') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('member.pk') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">SOP & Product</a>
                        <a href="{{ route('catalogs.index') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('catalogs.*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Katalog Produk</a>
                        <a href="{{ route('customers.index') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('customers.index') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Data Customer</a>
                    </div>
                </div>

                <div x-data="{ open: {{ $isSalesActive ? 'true' : 'false' }} }" class="mt-2">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg font-medium transition-colors {{ $isSalesActive ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            AI Sales Intelligent
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity class="mt-1 space-y-1 bg-slate-950/50 rounded-lg p-2">
                        <a href="#" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('sales.index') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Sales Pipeline</a>
                    </div>
                </div>
            @endif
        </nav>
        
        <div class="p-4 bg-slate-950 border-t border-slate-800 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    <header class="fixed top-0 right-0 left-0 lg:left-64 z-30 h-16 bg-white border-b border-slate-200 shadow-sm flex items-center justify-between px-4 sm:px-6 transition-all duration-300">
        
        <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700 focus:outline-none p-2 -ml-2 rounded-md hover:bg-slate-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>

        <div class="hidden lg:block flex-1"></div>

        <div class="flex items-center ml-auto">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 px-3 py-2 border border-transparent text-sm font-medium rounded-md text-slate-600 bg-white hover:bg-slate-50 hover:text-slate-900 focus:outline-none transition">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')" class="text-slate-700">
                        {{ __('Profile Settings') }}
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 transition-colors">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </header>

</div>

<style>
    /* Styling Scrollbar Khusus untuk Navigasi Sidebar */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #334155; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #475569; }
</style>