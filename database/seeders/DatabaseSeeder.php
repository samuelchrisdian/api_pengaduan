<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'id' => 1,
            'nik' => 3510072012030002,
            'nama' => 'Admin',
            'telp' => '081234567890',
            'username' => 'admin@mail.com',
            'password' => bcrypt('123456'),
            'level' => 'admin'
        ]);
        $this->call(KategoriTableSeeder::class);
    }
}
