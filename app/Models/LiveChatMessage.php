<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveChatMessage extends Model
{
    use HasFactory;

    // Hubungkan model ini secara paksa ke tabel chat_histories milik Kakak
    protected $table = 'chat_histories';

    protected $guarded = [];

    // Jika di controller mencari berdasarkan nomor WA atau user_id
    // Kita pastikan relasinya aman ke user pembawa bot
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}