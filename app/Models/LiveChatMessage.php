<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveChatMessage extends Model
{
    use HasFactory;

    // Hubungkan ke tabel khusus web widget sesuai image_c50ac6.png
    protected $table = 'live_chat_messages';

    protected $fillable = [
        'chat_session_id',
        'message',
        'sender_type',
        'is_read'
    ];

    // Relasi balik ke sesi chat
    public function chatSession()
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }
}