<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Profil Bisnis') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola identitas usaha Anda dan pantau validitas layanan AI.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-[2rem] shadow-sm ring-1 ring-gray-900/5 p-8 text-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gray-50 rounded-full -mr-12 -mt-12"></div>
                        
                        <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-8 relative z-10">Status Layanan AI</h2>
                        
                        <div class="flex flex-col items-center relative z-10">
                            <div class="mb-6 px-6 py-3 rounded-2xl shadow-sm border font-black text-sm uppercase tracking-wider {{ str_contains($serviceStatus['color'], 'green') ? 'bg-green-50 border-green-100 text-green-600' : 'bg-rose-50 border-rose-100 text-rose-600' }}">
                                {{ $serviceStatus['label'] }}
                            </div>
                            
                            @if($user->subscription_status != 'active')
                                <div class="p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200 mb-6">
                                    <p class="text-xs text-gray-500 leading-relaxed">
                                        Layanan AI Anda saat ini ditangguhkan. Selesaikan pembayaran untuk mengaktifkan kembali bot cerdas Anda.
                                    </p>
                                </div>
                                <a href="{{ route('user.invoice.index') }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm py-4 rounded-2xl font-bold shadow-lg shadow-blue-500/30 transition-all duration-200">
                                    Aktivasi Sekarang
                                </a>
                            @else
                                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-4 shadow-inner">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="text-xs text-gray-500 leading-relaxed font-medium">
                                    Layanan AI Anda sedang berjalan optimal dan melayani pelanggan secara otomatis.
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-900 rounded-[2rem] p-8 shadow-xl">
                        <h3 class="text-white font-bold mb-2">Pengaturan Akun</h3>
                        <p class="text-gray-400 text-xs mb-6">Ingin mengganti password atau email login?</p>
                        <a href="{{ route('profile.edit') }}" class="flex items-center justify-between group text-white text-sm font-bold">
                            <span>Edit Data Keamanan</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2rem] shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
                        <div class="px-10 py-8 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gradient-to-r from-white to-gray-50/50">
                            <div>
                                <h2 class="text-xl font-black text-gray-900 tracking-tight">Detail Identitas Bisnis</h2>
                                <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-bold">Informasi Dasar & SOP AI</p>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-xl ring-1 ring-blue-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <span class="text-[10px] font-black uppercase tracking-tighter">WA Terverifikasi</span>
                            </div>
                        </div>
                        
                        <div class="p-10 space-y-10">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Usaha / Brand</label>
                                    <p class="text-lg font-bold text-gray-900">{{ $user->business_name }}</p>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori Bisnis</label>
                                    <div class="inline-block px-3 py-1 bg-gray-100 rounded-lg text-sm font-semibold text-gray-700">
                                        {{ $user->business_category }}
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nomor WhatsApp Integrasi</label>
                                <p class="text-xl font-mono font-black text-blue-600 tracking-tighter">{{ $user->whatsapp_number }}</p>
                            </div>

                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Lokasi Fisik / Alamat</label>
                                <p class="text-sm font-medium text-gray-700 leading-relaxed">{{ $user->business_address }}</p>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Panduan & SOP Asisten AI</label>
                                <div class="p-6 bg-gray-50 rounded-[1.5rem] ring-1 ring-gray-100 relative group">
                                    <svg class="absolute top-4 right-4 w-8 h-8 text-gray-200" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C14.9124 8 14.017 7.10457 14.017 6V3L21.017 3V15C21.017 18.3137 18.3307 21 15.017 21H14.017ZM3.017 21L3.017 18C3.017 16.8954 3.91243 16 5.017 16H8.017C8.56928 16 9.017 15.5523 9.017 15V9C9.017 8.44772 8.56928 8 8.017 8H5.017C3.91243 8 3.017 7.10457 3.017 6V3L10.017 3V15C10.017 18.3137 7.33071 21 4.017 21H3.017Z" /></svg>
                                    <p class="text-sm text-gray-600 leading-7 italic relative z-10">
                                        "{{ $user->business_description }}"
                                    </p>
                                </div>
                                <p class="text-[10px] text-gray-400 italic font-medium mt-2">*Teks di atas digunakan oleh AI sebagai panduan dalam menjawab setiap pertanyaan pelanggan.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>