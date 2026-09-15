<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'client' => $this->client,
            'summary' => $this->summary,
            'overview' => $this->overview,
            'challenge' => $this->challenge,
            'solution' => $this->solution,
            'services' => $this->services ?? [],
            'results' => $this->results ?? [],
            'year' => $this->year,
            'live_url' => $this->live_url,
            'hero_image' => $this->hero_image,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'featured' => (bool) $this->featured,
            'published' => (bool) $this->published,
            'sort_order' => $this->sort_order,
            'categories' => PortfolioCategoryResource::collection($this->whenLoaded('categories')),
            'technologies' => PortfolioTechnologyResource::collection($this->whenLoaded('technologies')),
            'images' => PortfolioImageResource::collection($this->whenLoaded('images')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
