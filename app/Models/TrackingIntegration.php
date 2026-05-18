<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingIntegration extends Model
{
    protected $fillable = [
        'user_id', 'provider', 'pixel_id', 'access_token', 'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}