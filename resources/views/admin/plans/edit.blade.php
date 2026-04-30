<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.plans.index') }}" class="text-gray-500 hover:text-gray-700">
                &larr; Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Paket: {{ $plan->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT') <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informasi Dasar</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Paket</label>
                                    <input type="text" name="name" value="{{ old('name', $plan->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                                    <input type="number" name="price" value="{{ old('price', (int)$plan->price) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <p class="text-xs text-gray-500 mt-1">Isi 0 untuk paket gratis/uji coba.</p>
                                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Limitasi Sistem</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Batas Pesan AI</label>
                                    <input type="number" name="max_messages" value="{{ old('max_messages', $plan->max_messages) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <div class="mt-2 flex items-center">
                                        <input type="checkbox" name="is_unlimited_messages" id="is_unlimited_messages" value="1" {{ old('is_unlimited_messages', $plan->is_unlimited_messages) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <label for="is_unlimited_messages" class="ml-2 text-sm text-gray-600">Jadikan Unlimited Pesan</label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Batas Karakter SOP</label>
                                    <input type="number" name="max_sop_chars" value="{{ old('max_sop_chars', $plan->max_sop_chars) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Batas Nomor WhatsApp</label>
                                    <input type="number" name="max_wa_numbers" value="{{ old('max_wa_numbers', $plan->max_wa_numbers) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Fitur Tampilan & Status</h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Daftar Fitur (Satu baris satu fitur)</label>
                                    <textarea name="features" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Masa Aktif 30 Hari&#10;Unlimited Pesan AI">{{ old('features', $featuresString) }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Tekan Enter untuk memisahkan setiap fitur.</p>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $plan->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Paket Aktif (Bisa dibeli user)</label>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md font-semibold hover:bg-blue-700 transition">Perbarui Paket</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>