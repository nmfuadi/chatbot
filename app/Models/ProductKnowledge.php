<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductKnowledge extends Model
{
    use HasFactory;

    // 1. Beritahu Laravel nama tabel yang benar secara eksplisit
    protected $table = 'product_knowledges';

    // 2. Izinkan kolom ini untuk diisi datanya
    protected $fillable = [
        'user_id',
        'content',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
