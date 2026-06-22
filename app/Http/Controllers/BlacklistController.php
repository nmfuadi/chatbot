<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blacklist;
use Illuminate\Support\Facades\Auth;

class BlacklistController extends Controller
{
    // 1. Tampilkan Halaman & Tabel
    public function index()
    {
        $blacklists = Blacklist::where('user_id', Auth::id())->latest()->get();
        return view('member.blacklist', compact('blacklists'));
    }

    // 2. Simpan Nomor Baru
    public function store(Request $request)
    {
        $request->validate(['phone_number' => 'required']);
        
        $phone = $this->normalizePhone($request->phone_number);

        Blacklist::updateOrCreate(
            ['user_id' => Auth::id(), 'phone_number' => $phone]
        );

        return back()->with('success', "Nomor $phone berhasil diblokir!");
    }

    // 3. Edit Nomor Lama
    public function update(Request $request, $id)
    {
        $request->validate(['phone_number' => 'required']);
        
        $blacklist = Blacklist::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $phone = $this->normalizePhone($request->phone_number);
        $blacklist->update(['phone_number' => $phone]);

        return back()->with('success', "Nomor berhasil diperbarui menjadi $phone!");
    }

    // 4. Hapus Nomor
    public function destroy($id)
    {
        $blacklist = Blacklist::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $blacklist->delete();

        return back()->with('success', 'Nomor berhasil dihapus dari daftar Blacklist.');
    }

    // ==========================================
    // FUNGSI NORMALISASI (UBAH 08 JADI 628)
    // ==========================================
    private function normalizePhone($phone)
    {
        // 1. Bersihkan semua karakter selain angka
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // 2. Jika berawalan 08, ganti 0 pertama dengan 62
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }
}