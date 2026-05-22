<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    use HasFactory;

    // Menghubungkan model ini ke tabel 'ai_settings'
    protected $table = 'ai_settings';

    // Mendaftarkan kolom-kolom yang boleh diisi datanya
    protected $fillable = [
        'device_id',
        'ai_provider',
        'ai_model',
        'deepinfra_api_key',
    ];
}