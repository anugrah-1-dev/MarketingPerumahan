<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model
{
    protected $fillable = [
        'tipe_rumah_id',
        'nomor_unit',
        'blok',
        'status',
        'harga_jual',
        'catatan',
    ];

    public function tipeRumah(): BelongsTo
    {
        return $this->belongsTo(TipeRumah::class, 'tipe_rumah_id');
    }

    public function scopeTersedia($q) { return $q->where('status', 'tersedia'); }
    public function scopeBooking($q)   { return $q->where('status', 'booking'); }
    public function scopeTerjual($q)   { return $q->where('status', 'terjual'); }

    public function getHargaJualFormatAttribute(): ?string
    {
        return $this->harga_jual
            ? 'Rp ' . number_format($this->harga_jual, 0, ',', '.')
            : null;
    }

    public static function stats(): array
    {
        return [
            'total'    => static::count(),
            'tersedia' => static::where('status', 'tersedia')->count(),
            'terjual'  => static::where('status', 'terjual')->count(),
            'booking'  => static::where('status', 'booking')->count(),
        ];
    }
}
