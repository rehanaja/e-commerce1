<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'role' => '1',
            'status' => 0,
            'hp' => '0812345678901',
            'password' => bcrypt('P@55word')
        ]);

        User::create([
            'name' => 'Muhamad Raihan',
            'email' => 'muhamadraihan1211@gmail.com',
            'role' => '0',
            'status' => 1,
            'hp' => '081519034676',
            'password' => bcrypt('RaihanAja')
        ]);
        Kategori::create([
        'nama_kategori' => 'Brownies',
        ]);
        Kategori::create([
        'nama_kategori' => 'Combro',
        ]);
        Kategori::create([
        'nama_kategori' => 'Dawet',
        ]);
        Kategori::create([
        'nama_kategori' => 'Mochi',
        ]);
        Kategori::create([
        'nama_kategori' => 'Wingko',
        ]);
    }
}
