<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PortfolioProject extends Model
{
    use HasFactory;

    protected $table = 'portfolio_projects';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'slug',
        'title',
        'client',
        'summary',
        'overview',
        'challenge',
        'solution',
        'services',
        'results',
        'year',
        'live_url',
        'hero_image',
        'seo_title',
        'seo_description',
        'featured',
        'published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'services' => 'array',
            'results' => 'array',
            'featured' => 'boolean',
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

    public function categories()
    {
        return $this->belongsToMany(
            PortfolioCategory::class,
            'portfolio_project_categories',
            'project_id',
            'category_id'
        );
    }

    public function technologies()
    {
        return $this->belongsToMany(
            PortfolioTechnology::class,
            'portfolio_project_technologies',
            'project_id',
            'technology_id'
        );
    }

    public function images()
    {
        return $this->hasMany(PortfolioImage::class, 'project_id');
    }
}
