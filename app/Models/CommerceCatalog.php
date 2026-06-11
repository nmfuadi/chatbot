    protected $fillable = [
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommerceCatalog extends Model
{
    use HasFactory;

        'user_id',
        'type',
        'item_name',
        'description',
        'price',
        'weight_grams',
        'stock',
        'is_active',
    ];

    // Relasi balik ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}