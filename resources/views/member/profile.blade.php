<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ __('Profil Saya & Status Layanan') }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi bisnis dan status layanan AI Anda</p>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- LEFT: STATUS -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                        <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-6">
                            Status Layanan AI
                        </h2>

                        <div class="flex flex-col items-center text-center space-y-4">

                            <!-- STATUS BADGE -->
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $serviceStatus['color'] }}"></span>
                                <span class="px-4 py-1.5 rounded-full text-sm font-semibold {{ $serviceStatus['color'] }}">
                                    {{ $serviceStatus['label'] }}
                                </span>
                            </div>

                            @if($user->subscription_status != 'active')
                                <p class="text-sm text-gray-500 leading-relaxed">
                                    Layanan AI Anda belum aktif.<br>
                                    Aktifkan sekarang untuk mulai menggunakan chatbot.
                                </p>

                                <a href="route('user.invoice.index')" 
                                   class="w-full bg-blue-600 text-white text-sm py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition">
                                    Aktivasi Sekarang
                                </a>
                            @else
                                <div class="bg-green-50 text-green-700 text-sm px-4 py-3 rounded-lg">
                                    Chatbot AI Anda aktif dan siap melayani pelanggan 24/7 🚀
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- RIGHT: PROFILE -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

                        <!-- HEADER -->
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Detail Bisnis</h2>
                                <p class="text-xs text-gray-400">Informasi yang digunakan oleh AI chatbot Anda</p>
                            </div>

                            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                WA Terverifikasi
                            </span>
                        </div>

                        <!-- CONTENT -->
                        <div class="p-6 space-y-6">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-xs text-gray-400">Nama Usaha</label>
                                    <p class="mt-1 text-gray-800 font-semibold text-base">
                                        {{ $user->business_name }}
                                    </p>
                                </div>

                                <div>
                                    <label class="text-xs text-gray-400">Kategori</label>
                                    <p class="mt-1 text-gray-700">
                                        {{ $user->business_category }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Nomor WhatsApp Bot</label>
                                <p class="mt-1 text-gray-800 font-mono bg-gray-50 px-3 py-2 rounded-lg inline-block">
                                    {{ $user->whatsapp_number }}
                                </p>
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Alamat</label>
                                <p class="mt-1 text-gray-700 leading-relaxed">
                                    {{ $user->business_address }}
                                </p>
                            </div>

                            <div>
                                <label class="text-xs text-gray-400">Deskripsi / SOP AI</label>
                                <div class="mt-2 p-4 bg-gray-50 rounded-xl text-sm text-gray-600 italic leading-relaxed border border-gray-100">
                                    "{{ $user->business_description }}"
                                </div>
                            </div>

                        </div>

                        <!-- FOOTER -->
                        <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                            <p class="text-xs text-gray-400">
                                Pastikan data selalu update agar AI bekerja optimal
                            </p>

                            <a href="{{ route('profile.edit') }}" 
                               class="text-sm font-semibold text-blue-600 hover:text-blue-800">
                                Edit Profil →
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>