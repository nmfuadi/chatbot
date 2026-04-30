<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Invoice $invoice)
{
    // Pastikan user hanya bisa lihat invoice miliknya
    if ($invoice->user_id !== auth()->id()) { abort(403); }
    
    return view('member.invoice', compact('invoice'));
}
    //
}
