<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model
{
    protected const SUMMARY_KEYS = [
        'total'   => 'unit_total',
        'terjual' => 'unit_terjual',
        'booking' => 'unit_booking',
    ];

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
        $summary = static::configuredSummary();

        if ($summary !== null) {
            return $summary;
        }

        return static::databaseStats();
    }

    public static function managementSummary(): array
    {
        $summary = static::configuredSummary();

        if ($summary !== null) {
            return $summary + ['configured' => true];
        }

        return static::databaseStats() + ['configured' => false];
    }

    public static function saveSummary(array $data): void
    {
        foreach (static::SUMMARY_KEYS as $field => $key) {
            Setting::set($key, (int) $data[$field]);
        }
    }

    public static function clearSummary(): void
    {
        Setting::query()->whereIn('key', array_values(static::SUMMARY_KEYS))->delete();
    }

    protected static function configuredSummary(): ?array
    {
        $summary = [];
        $isConfigured = false;

        foreach (static::SUMMARY_KEYS as $field => $key) {
            $value = Setting::get($key);

            if ($value !== null) {
                $isConfigured = true;
            }

            $summary[$field] = max((int) $value, 0);
        }

        if (! $isConfigured) {
            return null;
        }

        return static::buildSummaryPayload(
            $summary['total'],
            $summary['terjual'],
            $summary['booking']
        );
    }

    protected static function databaseStats(): array
    {
        return [
            'total'    => static::count(),
            'tersedia' => static::where('status', 'tersedia')->count(),
            'terjual'  => static::where('status', 'terjual')->count(),
            'booking'  => static::where('status', 'booking')->count(),
        ];
    }

    protected static function buildSummaryPayload(int $total, int $terjual, int $booking): array
    {
        return [
            'total'    => $total,
            'tersedia' => max($total - $terjual - $booking, 0),
            'terjual'  => $terjual,
            'booking'  => $booking,
        ];
    }
}
