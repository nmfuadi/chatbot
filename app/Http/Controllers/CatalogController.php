<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\CatalogImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CatalogController extends Controller
{
    public function index() {
        // Ambil data dengan relasi images, urutkan terbaru, batasi 10 per halaman
        $catalogs = Catalog::with('images')
                    ->where('user_id', Auth::id()) // <-- Typo diperbaiki di sini
                    ->latest()
                    ->paginate(10); 
    
        return view('catalogs.index', compact('catalogs'));
    }

    public function create() {
        return view('catalogs.create');
    }

    public function store(Request $request) {
        $request->validate([
            'item_name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048' // Validasi gambar
        ]);

        // Simpan data katalog
        $catalog = Catalog::create([
            'user_id' => Auth::id(),
            'item_name' => $request->item_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);

        // Simpan Multiple Gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('catalogs', 'public');
                CatalogImage::create([
                    'catalog_id' => $catalog->id,
                    'image_path' => $path
                ]);
            }
        }
        
        return redirect()->route('catalogs.index')->with('success', 'Katalog berhasil ditambahkan!');
    }

    public function edit($id) {
        $catalog = Catalog::with('images')->where('user_id', Auth::id())->findOrFail($id);
        return view('catalogs.edit', compact('catalog'));
    }
    
    public function update(Request $request, $id) {
        $catalog = Catalog::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'item_name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // 1. Update data teks
        $catalog->update([
            'item_name' => $request->item_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);

        // 2. Hapus gambar lama jika ada yang dicentang untuk dihapus
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = CatalogImage::where('catalog_id', $catalog->id)->find($imageId);
                if ($image) {
                    // Hapus file fisik dari storage
                    Storage::disk('public')->delete($image->image_path);
                    // Hapus data dari database
                    $image->delete();
                }
            }
        }

        // 3. Tambah gambar baru jika ada yang diupload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $upload) {
                $path = $upload->store('catalogs', 'public');
                CatalogImage::create([
                    'catalog_id' => $catalog->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('catalogs.index')->with('success', 'Katalog berhasil diperbarui!');
    }
    
    public function destroy($id) {
        $catalog = Catalog::with('images')->where('user_id', Auth::id())->findOrFail($id);

        // Hapus SEMUA file fisik gambar milik produk ini
        foreach ($catalog->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Hapus data produk (otomatis menghapus data catalog_images di DB karena cascade)
        $catalog->delete();

        return redirect()->route('catalogs.index')->with('success', 'Katalog berhasil dihapus!');
    }
}