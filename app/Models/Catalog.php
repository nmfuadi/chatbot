<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'price',
        'stock',
        'description',
        'is_active'
    ];

    public function images()
{
    return $this->hasMany(CatalogImage::class);
}
}
