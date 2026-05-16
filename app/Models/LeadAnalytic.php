<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadAnalytic extends Model
{
    use HasFactory;

    protected $table = 'lead_analytics';

    protected $fillable = [
        'chat_session_id', // <-- Tambahkan ini di paling atas
        'phone',
        'instance',
        'status_prospek',
        'alasan_batal',
        'sumber_iklan',
        'chat_summary', 
        'lead_score' // <-- Tambahkan ini
    ];
}