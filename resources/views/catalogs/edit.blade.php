<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Katalog: ') }} <span class="text-indigo-600">{{ $catalog->item_name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100 p-6">
                
                <form action="{{ route('catalogs.update', $catalog->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nama Produk / Layanan</label>
                            <input type="text" name="item_name" value="{{ old('item_name', $catalog->item_name) }}" required 
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Harga (Rp)</label>
                            <input type="number" name="price" value="{{ old('price', $catalog->price) }}" required 
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Stok / Ketersediaan</label>
                        <input type="number" name="stock" value="{{ old('stock', $catalog->stock) }}" required 
                            class="w-full md:w-1/2 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Deskripsi Singkat</label>
                        <textarea name="description" rows="3" 
                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $catalog->description) }}</textarea>
                    </div>

                    @if($catalog->images->count() > 0)
                        <div class="mb-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                            <label class="block text-sm font-bold text-slate-800 mb-3">Foto Saat Ini</label>
                            <p class="text-xs text-slate-500 mb-4">Centang kotak merah muda pada foto yang ingin Anda <b>Hapus</b>.</p>
                            
                            <div class="flex flex-wrap gap-4">
                                @foreach($catalog->images as $image)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-24 h-24 object-cover rounded-lg border border-slate-300">
                                        
                                        <div class="absolute -top-2 -right-2">
                                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" 
                                                class="w-5 h-5 text-red-500 border-gray-300 rounded focus:ring-red-500 cursor-pointer shadow-sm">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tambah Foto Baru (Opsional)</label>
                        <input type="file" name="images[]" multiple accept="image/*" 
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                        <p class="text-xs text-slate-500 mt-2">*Biarkan kosong jika tidak ingin menambah foto.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                        <a href="{{ route('catalogs.index') }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold shadow-sm transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>