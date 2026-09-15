<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'slug',
        'title',
        'price_amd',
        'price_currency',
        'show_price',
        'price_label',
        'popular',
        'tagline',
        'description',
        'icon',
        'cta_text',
        'seo_title',
        'seo_description',
        'sort_order',
        'published',
    ];

    protected function casts(): array
    {
        return [
            'show_price' => 'boolean',
            'popular' => 'boolean',
            'published' => 'boolean',
            'sort_order' => 'integer',
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

    public function features()
    {
        return $this->hasMany(ServiceFeature::class, 'service_id');
    }
}
