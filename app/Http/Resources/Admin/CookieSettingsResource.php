<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CookieSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'banner_enabled' => (bool) $this->banner_enabled,
            'analytics_enabled' => (bool) $this->analytics_enabled,
            'marketing_enabled' => (bool) $this->marketing_enabled,
            'ga_measurement_id' => $this->ga_measurement_id,
            'meta_pixel_id' => $this->meta_pixel_id,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
