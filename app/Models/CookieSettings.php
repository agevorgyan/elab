<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CookieSettings extends Model
{
    use HasFactory;

    protected $table = 'cookie_settings';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'version',
        'banner_enabled',
        'analytics_enabled',
        'marketing_enabled',
        'ga_measurement_id',
        'meta_pixel_id',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'banner_enabled' => 'boolean',
            'analytics_enabled' => 'boolean',
            'marketing_enabled' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
