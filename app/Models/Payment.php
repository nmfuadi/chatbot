<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'bank_account_id', 'proof_image', 'status'];
    public function user() { return $this->belongsTo(User::class); }
    public function bankAccount() { return $this->belongsTo(BankAccount::class); }
}
