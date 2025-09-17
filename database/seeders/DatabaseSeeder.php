<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // buat user admin kalau belum ada, kalau sudah ada → update
        User::updateOrCreate(
            ['email' => 'admin@rckmanagement.com'],
            [
                'name' => 'RCK Management Admin',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // panggil seeder lain
        // $this->call([
        //     SiteSettingsSeeder::class,
        // ]);
        $this->call([
            CategorySeeder::class,
        ]);
        $this->call([
            RolesSeeder::class,
        ]);
    }
}
