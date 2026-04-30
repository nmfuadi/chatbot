@extends('layouts.app') {{-- Pastikan Anda punya layout utama --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Profil Saya & Status Layanan</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Status Layanan AI</h2>
                    <div class="flex flex-col items-center py-4">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $serviceStatus['color'] }}">
                            {{ $serviceStatus['label'] }}
                        </span>
                        
                        @if($user->subscription_status != 'active')
                            <p class="text-xs text-gray-500 mt-4 text-center">Layanan AI Anda belum aktif. Silakan lakukan pembayaran untuk mulai menggunakan bot.</p>
                            <a href="{{ route('payment.index') }}" class="mt-4 w-full text-center bg-blue-600 text-white text-sm py-2 rounded-lg font-semibold hover:bg-blue-700">
                                Aktivasi Sekarang
                            </a>
                        @else
                            <p class="text-xs text-green-600 mt-4 text-center font-medium">Chatbot AI Anda siap melayani pelanggan 24/7.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-800">Detail Bisnis</h2>
                        <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">WA Terverifikasi</span>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-400">Nama Usaha</label>
                                <p class="text-gray-800 font-semibold">{{ $user->business_name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400">Kategori</label>
                                <p class="text-gray-800">{{ $user->business_category }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400">Nomor WhatsApp Bot</label>
                            <p class="text-gray-800 font-mono">{{ $user->whatsapp_number }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400">Alamat</label>
                            <p class="text-gray-800">{{ $user->business_address }}</p>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-400">Deskripsi / SOP AI</label>
                            <div class="mt-1 p-3 bg-gray-50 rounded-lg text-sm text-gray-600 italic">
                                "{{ $user->business_description }}"
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 text-right">
                        <button class="text-sm font-semibold text-blue-600 hover:text-blue-800">Edit Profil</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection