<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = ['nama', 'jabatan', 'slug', 'aktif', 'email', 'phone', 'commission'];

    protected $casts = ['aktif' => 'boolean'];

    /**
     * Scope untuk agent yang aktif saja.
     * Penggunaan: Agent::aktif()->get()
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
