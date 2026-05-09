@extends('layouts.app') {{-- Sesuaikan dengan nama layout master Anda --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        
        <div class="bg-gray-800 px-6 py-4 border-b">
            <h2 class="text-xl font-bold text-white">Setup WhatsApp Gateway</h2>
            <p class="text-sm text-gray-300">Hubungkan nomor WhatsApp Anda untuk mulai melayani pelanggan.</p>
        </div>

        <div class="p-6">
            {{-- Status Koneksi --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Status Koneksi:</h3>
                @if($deviceInfo['status'] === 'connected')
                    <div class="flex items-center text-green-600 bg-green-50 p-3 rounded">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-bold">Terhubung (Online)</span>
                    </div>
                @else
                    <div class="flex items-center text-red-600 bg-red-50 p-3 rounded">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span class="font-bold">Terputus (Offline)</span>
                    </div>
                @endif
            </div>

            {{-- Area QR Code --}}
            @if($deviceInfo['status'] !== 'connected')
                <div class="text-center border-t pt-6">
                    <h3 class="text-md font-medium text-gray-700 mb-4">Silakan Scan QR Code di bawah ini:</h3>
                    
                    <div class="flex justify-center mb-4">
                        @if(!empty($qrBase64))
                            {{-- Ini adalah keajaiban Base64, langsung render sebagai gambar --}}
                            <img src="{{ $qrBase64 }}" alt="WhatsApp QR Code" class="border p-2 rounded shadow-sm max-w-[250px]">
                        @else
                            <div class="p-4 bg-yellow-50 text-yellow-700 rounded border border-yellow-200">
                                Sedang menyiapkan QR Code... Silakan refresh halaman ini.
                            </div>
                        @endif
                    </div>

                    <div class="text-sm text-gray-600 text-left bg-gray-50 p-4 rounded-md">
                        <p class="font-bold mb-2">Cara Menghubungkan:</p>
                        <ol class="list-decimal pl-5 space-y-1">
                            <li>Buka aplikasi WhatsApp di HP Anda.</li>
                            <li>Ketuk ikon titik tiga (Menu) atau Pengaturan.</li>
                            <li>Pilih <strong>Perangkat Taut</strong> (Linked Devices).</li>
                            <li>Ketuk <strong>Tautkan Perangkat</strong> dan arahkan kamera ke layar ini.</li>
                        </ol>
                    </div>
                </div>
            @endif

            {{-- Tombol Refresh Status --}}
            <div class="mt-8 text-center">
                <a href="{{ route('member.whatsapp.setup') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition duration-200">
                    Refresh Status Koneksi
                </a>
                <p class="text-xs text-gray-400 mt-2">Nama Sesi Mesin: <code>{{ $instanceName }}</code></p>
            </div>
        </div>
        
    </div>
</div>
@endsection