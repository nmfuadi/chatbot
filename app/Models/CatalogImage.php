<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CatalogImage extends Model {
    protected $fillable = ['catalog_id', 'image_path'];
}