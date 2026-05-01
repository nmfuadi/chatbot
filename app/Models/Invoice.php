<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // 1. Daftarkan kolom yang boleh diisi (Mengatasi MassAssignmentException)
    protected $fillable = [
        'user_id',
        'subscription_id',
        'invoice_number',
        'amount',
        'status'
    ];

    // 2. Relasi ke tabel User (Agar bisa memanggil $invoice->user->name)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 3. Relasi ke tabel Subscription (Agar bisa memanggil $invoice->subscription->plan->name)
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}