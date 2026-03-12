<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeRumahFoto extends Model
{
    protected $table = 'tipe_rumah_foto';

    protected $fillable = [
        'tipe_rumah_id',
        'path',
        'keterangan',
        'urutan',
    ];

    public function tipeRumah()
    {
        return $this->belongsTo(TipeRumah::class, 'tipe_rumah_id');
    }

    /**
     * Full URL of the photo
     */
    public function getUrlAttribute(): string
    {
        if ($this->path && file_exists(storage_path('app/public/' . $this->path))) {
            return asset('storage/' . $this->path);
        }
        return 'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=600&q=80';
    }
}
