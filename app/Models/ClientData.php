<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TipeRumah;

class ClientData extends Model
{
    protected $table = 'client_data';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'nik',
        'no_whatsapp',
        'alamat',
        'bukti_pembayaran',
        'tipe_rumah_id',
        'status_pembayaran',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function closing(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Closing::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tipeRumah(): BelongsTo
    {
        return $this->belongsTo(TipeRumah::class, 'tipe_rumah_id');
    }
}
