<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan Laravel mengisi data ke kolom tersebut
    protected $fillable = [
        'user_id',
        'customer_phone',
        'customer_name',
        'is_ai_active',
    ];

    // (Opsional) Relasi balik ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}