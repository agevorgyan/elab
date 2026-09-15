<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'price_amd' => $this->price_amd,
            'price_currency' => $this->price_currency,
            'show_price' => (bool) $this->show_price,
            'price_label' => $this->price_label,
            'popular' => (bool) $this->popular,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'icon' => $this->icon,
            'cta_text' => $this->cta_text,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'sort_order' => $this->sort_order,
            'published' => (bool) $this->published,
            'features' => ServiceFeatureResource::collection($this->whenLoaded('features')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
