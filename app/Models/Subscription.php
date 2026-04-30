<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {
    protected $fillable = ['user_id', 'plan_id', 'status', 'starts_at', 'ends_at', 'messages_used', 'payment_id', 'payment_url'];
    
    // Relasi ke User dan Plan
    public function user() { return $this->belongsTo(User::class); }
    public function plan() { return $this->belongsTo(Plan::class); }
}