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
                'gambar' => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&q=80&w=800',
            ],
            [
                'tipe' => '45/90',
                'nama' => 'Melati',
                'kamar_tidur' => 2,
                'kamar_mandi' => 1,
                'luas_tanah' => 90,
                'harga' => 480000000,
                'status' => 'tersedia',
                'gambar' => 'https://images.unsplash.com/photo-1580587767303-9413258d3478?auto=format&fit=crop&q=80&w=800',
            ],
            [
                'tipe' => '60/120',
                'nama' => 'Anggrek',
                'kamar_tidur' => 3,
                'kamar_mandi' => 2,
                'luas_tanah' => 120,
                'harga' => 650000000,
                'status' => 'tersedia',
                'gambar' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=800',
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
