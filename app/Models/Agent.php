<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agent extends Model
{
    protected $fillable = ['user_id', 'nama', 'jabatan', 'slug', 'aktif', 'email', 'phone', 'commission'];

    protected $casts = ['aktif' => 'boolean'];

    /**
     * Scope untuk agent yang aktif saja.
     * Penggunaan: Agent::aktif()->get()
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Akun login yang terhubung ke agent ini.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
