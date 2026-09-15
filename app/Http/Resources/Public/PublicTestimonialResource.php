<?php

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicTestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'company' => $this->company,
            'position' => $this->position,
            'content' => $this->content,
            'photo' => $this->photo,
            'rating' => $this->rating,
            'sort_order' => $this->sort_order,
        ];
    }
}
