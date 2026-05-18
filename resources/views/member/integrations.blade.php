<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900 leading-tight flex items-center gap-2">
                📡 Server-Side Tracking (CAPI)
            </h2>
            <p class="text-sm text-gray-500 mt-1">Hubungkan AI CRM Anda dengan platform iklan untuk melacak prospek dan closing secara akurat tanpa takut AdBlocker.</p>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('member.integrations.save') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-white p-6 rounded-[2rem] border border-blue-100 shadow-sm relative overflow-hidden group hover:border-blue-300 transition-all">
                        <div class="absolute top-0 right-0 p-4 opacity-10 text-blue-600">
                            <svg class="w-24 h-24 transform translate-x-4 -translate-y-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-black text-blue-700 text-lg flex items-center gap-2">Meta Conversions API</h3>
                                <label class="inline-flex items-center cursor-pointer shrink-0">
                                    <input type="checkbox" name="providers[meta][is_active]" class="sr-only peer" {{ isset($integrations['meta']) && $integrations['meta']->is_active ? 'checked' : '' }}>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="space-y-4 mt-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Meta Pixel ID</label>
                                    <input type="text" name="providers[meta][pixel_id]" value="{{ $integrations['meta']->pixel_id ?? '' }}" class="w-full border-gray-300 bg-gray-50 rounded-xl focus:ring-blue-500 text-sm p-3" placeholder="Contoh: 1029384756102">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Access Token (CAPI)</label>
                                    <textarea name="providers[meta][access_token]" rows="2" class="w-full border-gray-300 bg-gray-50 rounded-xl focus:ring-blue-500 text-sm p-3" placeholder="EAABwz... (Ambil dari Events Manager)">{{ $integrations['meta']->access_token ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-gray-200 shadow-sm relative overflow-hidden group hover:border-gray-400 transition-all">
                        <div class="absolute top-0 right-0 p-4 opacity-[0.03] text-black">
                            <svg class="w-24 h-24 transform translate-x-4 -translate-y-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 2.22-.69 4.46-2.15 6.08-1.58 1.76-3.9 2.75-6.26 2.68-2.61-.07-5.11-1.34-6.61-3.41-1.53-2.09-2.04-4.83-1.41-7.37.58-2.31 2.15-4.29 4.29-5.32 1.57-.75 3.39-1.02 5.14-.85.03 1.34.02 2.68.02 4.02-1.3-.22-2.67-.18-3.91.31-1.29.5-2.28 1.56-2.67 2.87-.41 1.35-.11 2.87.68 3.98 1.16 1.63 3.4 2.15 5.25 1.25 1.52-.73 2.5-2.26 2.6-3.95.03-4.71.01-9.42.02-14.13z"/></svg>
                        </div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-black text-gray-900 text-lg flex items-center gap-2">TikTok Events API</h3>
                                <label class="inline-flex items-center cursor-pointer shrink-0">
                                    <input type="checkbox" name="providers[tiktok][is_active]" class="sr-only peer" {{ isset($integrations['tiktok']) && $integrations['tiktok']->is_active ? 'checked' : '' }}>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-slate-900"></div>
                                </label>
                            </div>

                            <div class="space-y-4 mt-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">TikTok Pixel ID</label>
                                    <input type="text" name="providers[tiktok][pixel_id]" value="{{ $integrations['tiktok']->pixel_id ?? '' }}" class="w-full border-gray-300 bg-gray-50 rounded-xl focus:ring-slate-900 text-sm p-3" placeholder="Contoh: CD123456789">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Access Token</label>
                                    <textarea name="providers[tiktok][access_token]" rows="2" class="w-full border-gray-300 bg-gray-50 rounded-xl focus:ring-slate-900 text-sm p-3" placeholder="Ambil dari TikTok Ads Manager">{{ $integrations['tiktok']->access_token ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-amber-100 shadow-sm relative overflow-hidden group hover:border-amber-300 transition-all">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-black text-amber-600 text-lg flex items-center gap-2">Google Analytics 4</h3>
                                <label class="inline-flex items-center cursor-pointer shrink-0">
                                    <input type="checkbox" name="providers[ga4][is_active]" class="sr-only peer" {{ isset($integrations['ga4']) && $integrations['ga4']->is_active ? 'checked' : '' }}>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </label>
                            </div>

                            <div class="space-y-4 mt-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Measurement ID</label>
                                    <input type="text" name="providers[ga4][pixel_id]" value="{{ $integrations['ga4']->pixel_id ?? '' }}" class="w-full border-gray-300 bg-gray-50 rounded-xl focus:ring-amber-500 text-sm p-3" placeholder="G-XXXXXXXXXX">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">API Secret</label>
                                    <input type="text" name="providers[ga4][access_token]" value="{{ $integrations['ga4']->access_token ?? '' }}" class="w-full border-gray-300 bg-gray-50 rounded-xl focus:ring-amber-500 text-sm p-3" placeholder="Measurement Protocol API Secret">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-purple-100 shadow-sm relative overflow-hidden group hover:border-purple-300 transition-all">
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-black text-purple-700 text-lg flex items-center gap-2">Custom Webhook</h3>
                                <label class="inline-flex items-center cursor-pointer shrink-0">
                                    <input type="checkbox" name="providers[webhook][is_active]" class="sr-only peer" {{ isset($integrations['webhook']) && $integrations['webhook']->is_active ? 'checked' : '' }}>
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                </label>
                            </div>

                            <div class="space-y-4 mt-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Webhook URL</label>
                                    <input type="url" name="providers[webhook][access_token]" value="{{ $integrations['webhook']->access_token ?? '' }}" class="w-full border-gray-300 bg-gray-50 rounded-xl focus:ring-purple-500 text-sm p-3" placeholder="https://hooks.zapier.com/...">
                                    <p class="text-[10px] text-gray-400 mt-1">Kami akan melakukan POST data JSON setiap kali prospek berubah status.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="w-full md:w-auto flex justify-center items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-black py-4 px-10 rounded-2xl shadow-lg shadow-slate-200 transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Integrasi Tracking
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>