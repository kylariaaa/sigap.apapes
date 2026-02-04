<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun ADMIN UTAMA
        // Login menggunakan username: admin
        User::create([
            'nik'      => '11111',
            'name'     => 'Admin Utama',
            'username' => 'admin',
            'email'    => 'admin@sigap.com',
            'password' => Hash::make('password'),
            'telp'     => '08123456789',
            'role'     => 'admin',
        ]);

        // 2. Akun WARGA CONTOH
        // Login menggunakan username: warga
        User::create([
            'nik'      => '32010001',
            'name'     => 'Warga Test',
            'username' => 'warga',
            'email'    => 'warga@sigap.com',
            'password' => Hash::make('password'),
            'telp'     => '08987654321',
            'role'     => 'masyarakat',
        ]);
    }
}
