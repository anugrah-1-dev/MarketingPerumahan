<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaClick extends Model
{
    protected $fillable = [
        'agent_id', 'agent_slug', 'ip_address', 'device',
        'browser', 'page_url', 'status', 'notes', 'follow_up_date',
    ];

    protected $casts = [
        'follow_up_date' => 'datetime',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
