<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicCatalog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'raw_data'];

    // Mengubah tipe kolom json di database menjadi array di PHP secara otomatis
    protected $casts = [
        'raw_data' => 'array',
    ];
}