<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KategoriTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Kategori::create([
            'id_kategori' => 1,
            'nama_kategori' => 'Administrasi',
        ]);
        \App\Models\Kategori::create([
            'id_kategori' => 2,
            'nama_kategori' => 'Bencana',
        ]);
    }
}
