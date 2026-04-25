<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('SOP & Product Knowledge') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Input Data Pengetahuan AI</h3>
                    
                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('member.pk.save') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tulis SOP / Product Knowledge disini:</label>
                            <textarea 
    name="content"     rows="15" 
    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
    placeholder="Contoh: Jam operasional toko kami dari jam 08:00 - 17:00 WIB..."
>{{ old('content', $pk->content ?? '') }}</textarea>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
                            Simpan Pengetahuan AI
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg border">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Testing Chatbot AI</h3>
                    <div class="h-64 overflow-y-auto bg-white p-4 border rounded mb-4" id="chat-box">
                        <div class="mb-2">
                            <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-lg inline-block text-sm">Halo! Ada yang bisa AI bantu?</span>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <input type="text" id="chat-input" class="w-full border-gray-300 rounded-md text-sm" placeholder="Ketik pesan untuk test AI...">
                        <button type="button" onclick="alert('Fitur ini akan dihubungkan ke Webhook n8n & Groq nanti.')" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Kirim</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>