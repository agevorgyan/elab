<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\UpdateSettingsRequest;
use App\Http\Resources\Admin\SiteSettingsResource;
use App\Models\AuditLog;
use App\Models\SiteSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = SiteSettings::all();

        return response()->json([
            'success' => true,
            'data' => SiteSettingsResource::collection($settings),
        ]);
    }

    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $settingsData = $request->validated()['settings'];

        DB::transaction(function () use ($settingsData) {
            foreach ($settingsData as $item) {
                SiteSettings::updateOrCreate(
                    ['key' => $item['key']],
                    [
                        'id' => (string) Str::uuid(),
                        'value' => $item['value'] ?? '',
                        'category' => $item['category'] ?? 'general',
                    ]
                );
            }
        });

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_SETTINGS',
            'entity_type' => 'SiteSettings',
            'resource' => '/api/v1/admin/settings',
            'details' => 'Updated site settings values.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $updatedSettings = SiteSettings::all();

        return response()->json([
            'success' => true,
            'data' => SiteSettingsResource::collection($updatedSettings),
        ]);
    }
}
