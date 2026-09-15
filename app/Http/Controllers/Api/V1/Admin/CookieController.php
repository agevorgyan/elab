<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cookie\UpdateCookieSettingsRequest;
use App\Http\Resources\Admin\CookieSettingsResource;
use App\Models\AuditLog;
use App\Models\CookieSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CookieController extends Controller
{
    public function show(): JsonResponse
    {
        $cookies = CookieSettings::first();

        if (!$cookies) {
            $cookies = CookieSettings::create([
                'id' => 'default-cookie-settings',
                'version' => 1,
                'banner_enabled' => true,
                'analytics_enabled' => true,
                'marketing_enabled' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => new CookieSettingsResource($cookies),
        ]);
    }

    public function update(UpdateCookieSettingsRequest $request): JsonResponse
    {
        $cookies = CookieSettings::first();
        $data = $request->validated();

        if (!$cookies) {
            $cookies = CookieSettings::create([
                'id' => 'default-cookie-settings',
                'version' => $data['version'] ?? 1,
                'banner_enabled' => $data['banner_enabled'],
                'analytics_enabled' => $data['analytics_enabled'],
                'marketing_enabled' => $data['marketing_enabled'],
                'ga_measurement_id' => $data['ga_measurement_id'] ?? null,
                'meta_pixel_id' => $data['meta_pixel_id'] ?? null,
            ]);
        } else {
            $cookies->update([
                'version' => $data['version'] ?? ($cookies->version + 1),
                'banner_enabled' => $data['banner_enabled'],
                'analytics_enabled' => $data['analytics_enabled'],
                'marketing_enabled' => $data['marketing_enabled'],
                'ga_measurement_id' => array_key_exists('ga_measurement_id', $data) ? $data['ga_measurement_id'] : $cookies->ga_measurement_id,
                'meta_pixel_id' => array_key_exists('meta_pixel_id', $data) ? $data['meta_pixel_id'] : $cookies->meta_pixel_id,
            ]);
        }

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_COOKIE_SETTINGS',
            'entity_type' => 'CookieSettings',
            'entity_id' => $cookies->id,
            'resource' => '/api/v1/admin/cookies',
            'details' => 'Updated cookie consent settings.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new CookieSettingsResource($cookies),
        ]);
    }
}
