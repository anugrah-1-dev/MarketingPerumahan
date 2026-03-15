<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Closing extends Model
{
    protected $fillable = [
        'client_data_id',
        'agent_id',
        'tipe_rumah_id',
        'customer_name',
        'customer_phone',
        'harga_jual',
        'komisi_persen',
        'komisi_nominal',
        'payment_status',
        'catatan',
        'tanggal_closing',
        'created_by',
    ];

    protected $casts = [
        'tanggal_closing' => 'date',
        'harga_jual'      => 'integer',
        'komisi_nominal'  => 'integer',
        'komisi_persen'   => 'float',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function tipeRumah(): BelongsTo
    {
        return $this->belongsTo(TipeRumah::class, 'tipe_rumah_id');
    }

    public function clientData(): BelongsTo
    {
        return $this->belongsTo(ClientData::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
