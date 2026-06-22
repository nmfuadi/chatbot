@php
    $isChatAutoActive = request()->routeIs('member.pk', 'catalogs.*', 'customers.index', 'member.catalog', 'member.blacklist.index');
    $isSalesActive = request()->routeIs('sales.index', 'member.ai-rules', 'member.integrations'); 
    $isMonitorActive = request()->routeIs('wa.monitor', 'server.monitor', 'traffic.monitor', 'admin.monitor.logs');
    
    // --- TAMBAHAN BARU: Variabel aktif untuk menu Widget & Live Chat ---
    $isWidgetActive = request()->routeIs('widget.settings', 'livechat.*');
@endphp

<div x-show="sidebarOpen" 
     x-transition.opacity 
     @click="sidebarOpen = false"
     style="display: none;" 
     class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm lg:hidden">
</div>

<aside :class="{
        'translate-x-0': sidebarOpen, 
        '-translate-x-full': !sidebarOpen, 
        'lg:w-20': sidebarMinimized, 
        'lg:w-64': !sidebarMinimized,
        'w-64': true
    }" 
    class="fixed inset-y-0 left-0 z-50 bg-slate-900 text-slate-300 transition-all duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col h-full shadow-2xl shrink-0">
    
    <div class="flex items-center h-16 bg-slate-950 border-b border-slate-800 shrink-0 transition-all duration-300" :class="sidebarMinimized ? 'justify-center px-0' : 'justify-between px-4'">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <span class="text-2xl" x-show="sidebarMinimized" style="display: none;">🚀</span>
            <span class="text-white font-black text-xl tracking-wider" x-show="!sidebarMinimized">🚀 AI CRM</span>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white" x-show="!sidebarMinimized">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <nav class="flex-1 py-6 space-y-1.5 overflow-y-auto custom-scrollbar" :class="sidebarMinimized ? 'px-2' : 'px-4'">
        
        <a href="{{ route('dashboard') }}" title="Dashboard" class="flex items-center gap-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}" :class="sidebarMinimized ? 'justify-center px-0' : 'px-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            <span x-show="!sidebarMinimized">Dashboard</span>
        </a>

        @if(Auth::user()->role === 'admin')
            <div x-show="!sidebarMinimized" class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 mt-6 px-3 transition-opacity">Administrator</div>
            <div x-show="sidebarMinimized" class="h-px bg-slate-800 my-4 mx-2"></div>
            
            <a href="{{ route('admin.members') }}" title="Kelola Member" class="flex items-center gap-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.members') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}" :class="sidebarMinimized ? 'justify-center px-0' : 'px-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span x-show="!sidebarMinimized">Kelola Member</span>
            </a>
            <a href="{{ route('admin.plans.index') }}" title="Manajemen Paket" class="flex items-center gap-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('admin.plans.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}" :class="sidebarMinimized ? 'justify-center px-0' : 'px-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span x-show="!sidebarMinimized">Manajemen Paket</span>
            </a>

            <div x-data="{ expanded: {{ $isMonitorActive ? 'true' : 'false' }} }" class="mt-2" title="Monitoring Server">
                <button @click="sidebarMinimized ? sidebarMinimized = false : expanded = !expanded" class="flex items-center w-full py-2.5 rounded-lg font-medium transition-colors {{ $isMonitorActive ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 hover:text-white' }}" :class="sidebarMinimized ? 'justify-center px-0' : 'justify-between px-3'">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span x-show="!sidebarMinimized">Monitoring Server</span>
                    </div>
                    <svg x-show="!sidebarMinimized" :class="{'rotate-180': expanded}" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="expanded && !sidebarMinimized" x-collapse class="mt-1 space-y-1 bg-slate-950/50 rounded-lg p-2">
                    <a href="{{ route('wa.monitor') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('wa.monitor') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Status WhatsApp</a>
                    <a href="{{ route('server.monitor') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('server.monitor') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Resource Server</a>
                    <a href="{{ route('traffic.monitor') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('traffic.monitor') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">n8n & AI Monitor</a>
                    <a href="{{ route('admin.monitor.logs') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('admin.monitor.logs') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Log API</a>
                </div>
            </div>

        @elseif(Auth::user()->role === 'member')
            <div x-show="!sidebarMinimized" class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 mt-6 px-3">Menu Member</div>
            <div x-show="sidebarMinimized" class="h-px bg-slate-800 my-4 mx-2"></div>

            <a href="{{ route('profile.index') }}" title="Profil & Status" class="flex items-center gap-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}" :class="sidebarMinimized ? 'justify-center px-0' : 'px-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span x-show="!sidebarMinimized">Profil & Status</span>
            </a>
            <a href="{{ route('user.invoice.index') }}" title="Tagihan" class="flex items-center gap-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('user.invoice.index') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}" :class="sidebarMinimized ? 'justify-center px-0' : 'px-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span x-show="!sidebarMinimized">Tagihan</span>
            </a>
            <a href="{{ route('member.whatsapp.setup') }}" title="Setup WhatsApp" class="flex items-center gap-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->routeIs('member.whatsapp.setup') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}" :class="sidebarMinimized ? 'justify-center px-0' : 'px-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                <span x-show="!sidebarMinimized">Setup WhatsApp</span>
            </a>

            <div x-show="!sidebarMinimized" class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-6 mb-3 px-3">Produk & AI Auto</div>
            <div x-show="sidebarMinimized" class="h-px bg-slate-800 my-4 mx-2"></div>

            <div x-data="{ expanded: {{ $isChatAutoActive ? 'true' : 'false' }} }" class="mt-2" title="AI Chat Auto">
                <button @click="sidebarMinimized ? sidebarMinimized = false : expanded = !expanded" class="flex items-center w-full py-2.5 rounded-lg font-medium transition-colors {{ $isChatAutoActive ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 hover:text-white' }}" :class="sidebarMinimized ? 'justify-center px-0' : 'justify-between px-3'">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        <span x-show="!sidebarMinimized">AI Chat Auto</span>
                    </div>
                    <svg x-show="!sidebarMinimized" :class="{'rotate-180': expanded}" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7-7-7-7"></path></svg>
                </button>
                <div x-show="expanded && !sidebarMinimized" x-collapse class="mt-1 space-y-1 bg-slate-950/50 rounded-lg p-2">
                    <a href="{{ route('member.pk') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('member.pk') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">SOP & Product</a>
                    <a href="{{ route('catalogs.index') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('catalogs.*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Katalog Produk</a>
                    <a href="{{ route('member.catalog') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->route('member.catalog') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Katalog Produk Dinamis</a>
                    <a href="{{ route('customers.index') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('customers.index') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Data Customer</a>
                    <a href="{{ route('member.blacklist.index') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('member.blacklist.index') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Block List</a>
                </div>
            </div>

            <div x-data="{ expanded: {{ $isWidgetActive ? 'true' : 'false' }} }" class="mt-2" title="Omnichannel & Widget">
                <button @click="sidebarMinimized ? sidebarMinimized = false : expanded = !expanded" class="flex items-center w-full py-2.5 rounded-lg font-medium transition-colors {{ $isWidgetActive ? 'bg-slate-800 text-white' : 'hover:bg-slate-800 hover:text-white' }}" :class="sidebarMinimized ? 'justify-center px-0' : 'justify-between px-3'">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        <span x-show="!sidebarMinimized">Live Chat & Widget</span>
                    </div>
                    <svg x-show="!sidebarMinimized" :class="{'rotate-180': expanded}" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7-7-7-7"></path></svg>
                </button>
                <div x-show="expanded && !sidebarMinimized" x-collapse class="mt-1 space-y-1 bg-slate-950/50 rounded-lg p-2">
                    <a href="{{ route('livechat.index') }}" class="block px-10 py-2 text-sm rounded-md transition-colors {{ request()->routeIs('livechat.*') ? 'text-blue-400 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">Dashboard Live Chat</a>
                    <a href="{{ route('widget.settings') }}"