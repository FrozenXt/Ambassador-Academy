<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'icon',
        'is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value, string $group = 'general', string $type = 'text')
    {
        return static::updateOrCreate(
            ['key'   => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
    }
}
