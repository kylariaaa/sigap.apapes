<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Admin Utama ──
        User::create([
            'nik' => '1100000000001',
            'name' => 'Administrator SIGAP',
            'username' => 'admin',
            'email' => 'admin@sigap.go.id',
            'password' => Hash::make('sigap2026'),
            'telp' => '08111000001',
            'role' => 'admin',
        ]);

        // ── 2. Warga Contoh #1 ──
        User::create([
            'nik' => '3201000000001',
            'name' => 'Budi Santoso',
            'username' => 'budi_santoso',
            'email' => 'budi@sigap.go.id',
            'password' => Hash::make('sigap2026'),
            'telp' => '08123456001',
            'role' => 'masyarakat',
        ]);

        // ── 3. Warga Contoh #2 ──
        User::create([
            'nik' => '3201000000002',
            'name' => 'Siti Rahayu',
            'username' => 'siti_rahayu',
            'email' => 'siti@sigap.go.id',
            'password' => Hash::make('sigap2026'),
            'telp' => '08123456002',
            'role' => 'masyarakat',
        ]);
    }
}
