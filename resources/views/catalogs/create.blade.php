<form action="{{ route('catalogs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label>Nama Produk</label>
        <input type="text" name="item_name" class="w-full rounded-lg border-gray-300">
    </div>

    <div class="mb-4">
        <label>Foto Produk (Bisa pilih banyak)</label>
        <input type="file" name="images[]" multiple class="w-full" accept="image/*">
        <p class="text-xs text-gray-500 mt-1">*Bisa pilih lebih dari 1 foto sekaligus</p>
    </div>

    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg">Simpan Katalog</button>
</form>