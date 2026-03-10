<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeRumah extends Model
{
    protected $table = 'tipe_rumah';

    protected $fillable = [
        'nama_tipe',
        'luas_bangunan',
        'luas_tanah',
        'harga',
        'harga_diskon',
        'is_diskon',
        'deskripsi',
        'gambar',
        'stok_tersedia',
    ];

    protected $casts = [
        'is_diskon' => 'boolean',
    ];

    /**
     * Format harga ke Rupiah
     */
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getHargaDiskonFormatAttribute(): ?string
    {
        return $this->harga_diskon
            ? 'Rp ' . number_format($this->harga_diskon, 0, ',', '.')
            : null;
    }

    /**
     * URL gambar, fallback ke placeholder jika tidak ada
     */
    public function getGambarUrlAttribute(): string
    {
        if ($this->gambar && file_exists(storage_path('app/public/' . $this->gambar))) {
            return asset('storage/' . $this->gambar);
        }
        return 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80';
    }

    /**
     * Scope untuk tipe rumah yang sedang diskon
     */
    public function scopeDiskon($query)
    {
        return $query->where('is_diskon', true);
    }
}
