<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;

class AdminController extends Controller
{
    // A. List Member & Input API Wablas
    public function members()
    {
        $members = User::where('role', 'member')->get();
        return view('admin.members', compact('members'));
    }

    public function updateMemberWablas(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'wablas_api_key' => $request->wablas_api_key,
            'wablas_secret_key' => $request->wablas_secret_key,
            'wablas_device_id' => $request->wablas_device_id,
        ]);
        return back()->with('success', 'Data Wablas Member berhasil diupdate.');
    }

    // B. Approval Pembayaran
    public function approvePayment($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $payment->update(['status' => 'approved']);
        
        // Aktifkan User
        $payment->user->update(['status' => 'active']);

        return back()->with('success', 'Pembayaran disetujui, Member sekarang Aktif.');
    }
}
