<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            [
                'tipe' => '36/72',
                'nama' => 'Dahlia',
                'kamar_tidur' => 2,
                'kamar_mandi' => 1,
                'luas_tanah' => 72,
                'harga' => 350000000,
                'status' => 'tersedia',
                'gambar' => 'images/dahlia.jpg',
            ],
            [
                'tipe' => '45/90',
                'nama' => 'Melati',
                'kamar_tidur' => 2,
                'kamar_mandi' => 1,
                'luas_tanah' => 90,
                'harga' => 480000000,
                'status' => 'tersedia',
                'gambar' => 'images/melati.jpg',
            ],
            [
                'tipe' => '60/120',
                'nama' => 'Anggrek',
                'kamar_tidur' => 3,
                'kamar_mandi' => 2,
                'luas_tanah' => 120,
                'harga' => 650000000,
                'status' => 'tersedia',
                'gambar' => 'images/anggrek.jpg',
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
