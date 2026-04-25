<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BankAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin Default
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@chatbot.com',
            'password' => Hash::make('password123'), // Default password
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 2. Buat Dummy Bank
        BankAccount::create([
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'PT AI Chatbot Indonesia',
        ]);
    }
}
