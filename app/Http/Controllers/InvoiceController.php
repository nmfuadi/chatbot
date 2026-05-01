<?php

namespace App\Http\Controllers;

use App\Models\Invoice; // <-- Ini baris yang sangat penting untuk memanggil Model
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Menampilkan detail Invoice
     */
    public function show(Invoice $invoice)
    {
        // Keamanan: Pastikan user hanya bisa melihat invoice miliknya sendiri
        if ($invoice->user_id !== auth()->id()) {
            abort(403, 'Akses Ditolak. Ini bukan invoice Anda.');
        }
        
        // Tampilkan halaman view invoice
        return view('member.invoice', compact('invoice'));
    }
}