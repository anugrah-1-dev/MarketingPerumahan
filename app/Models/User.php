<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'referral_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Role helpers ────────────────────────────────
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAffiliate(): bool  { return $this->role === 'affiliate'; }

    // ── Referral Link Accessor ───────────────────────
    /**
     * Link referral lengkap: https://domain.com/ref/BSA-XXXX
     */
    public function getReferralLinkAttribute(): string
    {
        return url('/ref/' . $this->referral_code);
    }

    // ── Generate Kode Unik ───────────────────────────
    /**
     * Generate kode referral unik format BSA-XXXX
     * Dipanggil dari UserObserver saat creating.
     */
    public static function generateReferralCode(): string
    {
        do {
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // tanpa 0/O/1/I agar tidak ambigu
            $code  = 'BSA-';
            for ($i = 0; $i < 4; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    // ── Relations ────────────────────────────────────
    public function agent(): HasOne
    {
        return $this->hasOne(Agent::class);
    }
}
