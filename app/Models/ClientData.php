<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
