<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model {
    protected $fillable = ['name', 'price', 'max_messages', 'is_unlimited_messages', 'max_sop_chars', 'max_wa_numbers', 'features', 'is_active'];
    protected $casts = ['features' => 'array']; // Beritahu Laravel kolom ini berisi JSON
}
