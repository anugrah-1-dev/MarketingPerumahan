<?php

namespace Database\Seeders;

use App\Models\TipeRumah;
use Illuminate\Database\Seeder;

class TipeRumahSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'nama_tipe'     => 'Harmony',
                'luas_bangunan' => 40,
                'luas_tanah'    => 72,
                'kamar_tidur'   => 2,
                'kamar_mandi'   => 1,
                'lantai'        => 1,
                'sertifikat'    => 'SHM',
                'fasilitas'     => ['1 Garasi', 'Listrik 2200W', 'Air PDAM'],
                'harga'         => 400_000_000,
                'harga_diskon'  => null,
                'is_diskon'     => false,
                'deskripsi'     => 'Tipe compact dengan desain nyaman untuk keluarga kecil.',
                'stok_tersedia' => 10,
            ],
            [
                'nama_tipe'     => 'Blissfull',
                'luas_bangunan' => 50,
                'luas_tanah'    => 84,
                'kamar_tidur'   => 3,
                'kamar_mandi'   => 2,
                'lantai'        => 2,
                'sertifikat'    => 'SHM',
                'fasilitas'     => ['Carport 1 Mobil', 'Listrik 2200W', 'Air PDAM'],
                'harga'         => 550_000_000,
                'harga_diskon'  => null,
                'is_diskon'     => false,
                'deskripsi'     => 'Ruang lebih lega untuk kebutuhan keluarga aktif.',
                'stok_tersedia' => 8,
            ],
            [
                'nama_tipe'     => 'Serenity',
                'luas_bangunan' => 60,
                'luas_tanah'    => 98,
                'kamar_tidur'   => 3,
                'kamar_mandi'   => 2,
                'lantai'        => 1,
                'sertifikat'    => 'SHM',
                'fasilitas'     => ['Carport 1 Mobil', 'Listrik 2200W', 'Air PDAM'],
                'harga'         => 650_000_000,
                'harga_diskon'  => null,
                'is_diskon'     => false,
                'deskripsi'     => 'Nuansa tenang dengan tata ruang modern dan asri.',
                'stok_tersedia' => 6,
            ],
        ];

        foreach ($types as $type) {
            TipeRumah::updateOrCreate(
                ['nama_tipe' => $type['nama_tipe']],
                $type
            );
        }
    }
}
