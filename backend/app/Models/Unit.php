<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'tipe',
        'nama',
        'kamar_tidur',
        'kamar_mandi',
        'luas_tanah',
        'harga',
        'status',
        'gambar',
    ];
}
