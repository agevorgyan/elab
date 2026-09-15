<?php

namespace App\Http\Controllers\Api\V1\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Public\PublicSiteSettingsResource;
use App\Models\SiteSettings;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = SiteSettings::all();

        $settingsMap = [];
        foreach ($settings as $setting) {
            $settingsMap[$setting->key] = $setting->value;
        }

        return response()->json([
            'success' => true,
            'data' => $settingsMap,
        ]);
    }
}
