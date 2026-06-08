<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PimpinanSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Bapak Kepala Lapas',
            'email' => 'pimpinan@sistok.com',
            'password' => Hash::make('password123'),
            'is_admin' => false,
            'is_pimpinan' => true, // Menandakan ini akun Pimpinan
        ]);
    }
}