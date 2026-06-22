<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveChatMessage extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi secara massal
    protected $guarded = [];

    // Relasi balik ke ruang obrolan (Chat Session)
    public function chatSession()
    {
        return $this->belongsTo(ChatSession::class);
    }
}