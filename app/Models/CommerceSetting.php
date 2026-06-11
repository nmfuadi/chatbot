<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommerceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_commerce_active',
        'catalog_mode',
        'pg_provider',
        'pg_merchant_code',
        'pg_api_key',
    ];

    // Relasi balik ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}