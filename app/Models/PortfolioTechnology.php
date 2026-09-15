<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PortfolioTechnology extends Model
{
    use HasFactory;

    protected $table = 'portfolio_technologies';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'slug',
        'name',
        'icon',
        'url',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
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

    public function projects()
    {
        return $this->belongsToMany(
            PortfolioProject::class,
            'portfolio_project_technologies',
            'technology_id',
            'project_id'
        );
    }
}
