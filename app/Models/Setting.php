<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai setting berdasarkan key.
     * Jika tidak ada, kembalikan $default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (! Schema::hasTable((new static)->getTable())) {
            return $default;
        }

        $row = static::find($key);
        return $row ? $row->value : $default;
    }

    /**
     * Simpan atau perbarui nilai setting.
     */
    public static function set(string $key, mixed $value): void
    {
        if (! Schema::hasTable((new static)->getTable())) {
            return;
        }

        static::updateOrCreate(
            ['key'   => $key],
            ['value' => $value]
        );
    }
}
