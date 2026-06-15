<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Bidan / Tenaga Kesehatan (Super User / Verifikator)
        User::factory()->create([
            'name' => 'Bidan Indah',
            'email' => 'bidan@example.com',
            'role' => 'bidan',
            'password' => Hash::make('password'),
        ]);

        // 2. Kader Posyandu (Data Entry / Operator)
        User::factory()->create([
            'name' => 'Kader Ani',
            'email' => 'kader@example.com',
            'role' => 'kader',
            'password' => Hash::make('password'),
        ]);

        // 3. Orang Tua / Ibu Balita (Viewer / Read-Only)
        User::factory()->create([
            'name' => 'Ibu Rahma',
            'email' => 'ibu@example.com',
            'role' => 'orang_tua',
            'password' => Hash::make('password'),
        ]);
    }
}
