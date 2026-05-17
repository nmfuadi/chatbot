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
        'objection_reasons',
        'lead_rule_baru',
        'lead_rule_prospect',
        'lead_rule_hot_prospek',
        'lead_rule_deal',
        'lead_rule_closing',
        'lead_rule_gagal'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
