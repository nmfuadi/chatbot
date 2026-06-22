<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetSetting extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi secara massal
    protected $guarded = [];

    // Relasi balik ke User (Pemilik Widget)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}