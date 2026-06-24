<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Pengaturan Live Chat Widget') }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">Kustomisasi tampilan widget chat untuk dipasang di website Anda.</p>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-2xl flex items-center gap-3">
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-gray-900/5 overflow-hidden p-8">
                <form action="{{ route('widget.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <div>
                            <h4 class="font-bold text-gray-900">Status Widget</h4>
                            <p class="text-xs text-gray-500">Aktifkan untuk menampilkan widget di website Anda.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" class="sr-only peer" {{ $setting->is_active ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Warna Utama Widget</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="primary_color" value="{{ $setting->primary_color }}" class="h-10 w-20 rounded cursor-pointer border-gray-300">
                            <span class="text-xs text-gray-500">Pilih warna yang sesuai dengan branding website Anda.</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tata Letak Widget</label>
                        <select name="widget_position" class="w-full border-slate-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="bottom-right" {{ ($setting->widget_position ?? 'bottom-right') == 'bottom-right' ? 'selected' : '' }}>Kanan Bawah (Default)</option>
                            <option value="bottom-left" {{ ($setting->widget_position ?? '') == 'bottom-left' ? 'selected' : '' }}>Kiri Bawah</option>
                            <option value="top-right" {{ ($setting->widget_position ?? '') == 'top-right' ? 'selected' : '' }}>Kanan Atas</option>
                            <option value="top-left" {{ ($setting->widget_position ?? '') == 'top-left' ? 'selected' : '' }}>Kiri Atas</option>
                            <option value="center-right" {{ ($setting->widget_position ?? '') == 'center-right' ? 'selected' : '' }}>Kanan Tengah</option>
                            <option value="center-left" {{ ($setting->widget_position ?? '') == 'center-left' ? 'selected' : '' }}>Kiri Tengah</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pesan Sambutan (Greeting Text)</label>
                        <input type="text" name="greeting_text" value="{{ $setting->greeting_text }}" class="w-full border-gray-200 bg-gray-50 rounded-xl p-3.5 focus:ring-indigo-500 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Logo Widget (Opsional)</label>
                        @if($setting->logo_path)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="Logo" class="h-16 w-16 object-cover rounded-full border border-gray-200">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white font-black px-6 py-3 rounded-xl shadow-md hover:bg-indigo-700">
                            Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 bg-slate-900 rounded-[2.5rem] shadow-xl overflow-hidden p-8 border border-slate-800">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2.5 bg-blue-500/20 text-blue-400 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white tracking-tight">Script Instalasi Widget</h3>
                        <p class="text-xs text-slate-400">Copy kode di bawah dan paste sebelum tag <code class="text-blue-300">&lt;/body&gt;</code> di website Anda.</p>
                    </div>
                </div>

                <div class="relative bg-black rounded-xl p-4 font-mono text-sm text-green-400 overflow-x-auto border border-slate-800">
                    <pre><code id="widgetScript">&lt;script src="{{ url('/tera-widget.js') }}" data-base-url="{{ url('/') }}" data-user-id="{{ Auth::id() }}"&gt;&lt;/script&gt;</code></pre>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>