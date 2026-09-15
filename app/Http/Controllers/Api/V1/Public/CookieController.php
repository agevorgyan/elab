<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicCookieSettingsResource;
use App\Models\CookieSettings;
use Illuminate\Http\JsonResponse;

class CookieController extends Controller
{
    public function show(): JsonResponse
    {
        $cookies = CookieSettings::first();

        if (!$cookies) {
            return response()->json([
                'success' => true,
                'data' => [
                    'version' => '1.0',
                    'banner_enabled' => true,
                    'analytics_enabled' => false,
                    'marketing_enabled' => false,
                    'ga_measurement_id' => null,
                    'meta_pixel_id' => null,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => new PublicCookieSettingsResource($cookies),
        ]);
    }
}
