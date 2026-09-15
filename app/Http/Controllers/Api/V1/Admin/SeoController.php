<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Seo\UpdateSeoRequest;
use App\Http\Resources\Admin\SeoMetadataResource;
use App\Models\AuditLog;
use App\Models\SeoMetadata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $seoList = SeoMetadata::orderBy('path', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => SeoMetadataResource::collection($seoList),
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $seo = SeoMetadata::find($id);

        if (!$seo) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new SeoMetadataResource($seo),
        ]);
    }

    public function update(UpdateSeoRequest $request, string $id): JsonResponse
    {
        $seo = SeoMetadata::find($id);
        $data = $request->validated();

        if (!$seo) {
            // Check if path exists elsewhere
            if (SeoMetadata::where('path', $data['path'])->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource already exists',
                ], 409);
            }

            $seo = SeoMetadata::create([
                'id' => $id,
                'path' => $data['path'],
                'title' => $data['title'],
                'description' => $data['description'],
                'keywords' => $data['keywords'] ?? [],
                'canonical' => $data['canonical'] ?? null,
                'og_title' => $data['og_title'] ?? null,
                'og_description' => $data['og_description'] ?? null,
                'og_image' => $data['og_image'] ?? null,
                'robots' => $data['robots'] ?? 'index, follow',
            ]);
        } else {
            if ($data['path'] !== $seo->path && SeoMetadata::where('path', $data['path'])->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource already exists',
                ], 409);
            }

            $seo->update([
                'path' => $data['path'],
                'title' => $data['title'],
                'description' => $data['description'],
                'keywords' => $data['keywords'] ?? $seo->keywords,
                'canonical' => array_key_exists('canonical', $data) ? $data['canonical'] : $seo->canonical,
                'og_title' => array_key_exists('og_title', $data) ? $data['og_title'] : $seo->og_title,
                'og_description' => array_key_exists('og_description', $data) ? $data['og_description'] : $seo->og_description,
                'og_image' => array_key_exists('og_image', $data) ? $data['og_image'] : $seo->og_image,
                'robots' => $data['robots'] ?? $seo->robots,
            ]);
        }

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'UPDATE_SEO',
            'entity_type' => 'SeoMetadata',
            'entity_id' => $seo->id,
            'resource' => '/api/v1/admin/seo/' . $id,
            'details' => "Updated SEO metadata for path: {$seo->path}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new SeoMetadataResource($seo),
        ]);
    }
}
