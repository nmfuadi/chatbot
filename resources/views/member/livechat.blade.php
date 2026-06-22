<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('Dashboard Live Chat') }}
        </h2>
        <p class="text-sm text-gray-500 mt-1">Pantau dan balas pesan dari pelanggan yang menghubungi via Widget Website.</p>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-gray-900/5 p-10 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mb-5 shadow-inner">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Tampilan Live Chat Sedang Dibangun! 🚀</h3>
                <p class="text-gray-500 max-w-md">Di sinilah nanti Anda bisa melihat daftar pelanggan yang chat dari website, membaca riwayat obrolan dengan AI, dan mengambil alih obrolan (Pause AI).</p>
            </div>
        </div>
    </div>
</x-app-layout>