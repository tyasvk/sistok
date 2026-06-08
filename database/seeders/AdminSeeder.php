<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Sistok',
            'email' => 'admin@sistok.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);
    }
}