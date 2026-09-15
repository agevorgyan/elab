<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceFeature extends Model
{
    use HasFactory;

    protected $table = 'service_features';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'service_id',
        'text',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
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

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
